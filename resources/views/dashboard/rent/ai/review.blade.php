@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    @php $threshold = (float) config('ai.confidence_threshold', 0.8); @endphp

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">العقود المستخرجة بانتظار المراجعة ({{ $items->total() }})</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-row-bordered table-hover align-middle">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th>#</th>
                            <th>الملف / الصفحات</th>
                            <th>رقم العقد</th>
                            <th>المؤجر</th>
                            <th>قيمة الإيجار</th>
                            <th>الثقة</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i => $item)
                            @php
                                $d = $item->extracted_json['data'] ?? [];
                                $conf = $item->confidence;
                                $low = $conf !== null && $conf < $threshold;
                            @endphp
                            <tr>
                                <td>{{ $items->firstItem() + $i }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->batch->original_filename ?? '—', 22) }}
                                    <span class="text-muted fs-8">(ص {{ $item->page_from }}–{{ $item->page_to }})</span></td>
                                <td class="fw-bold">{{ $d['contract_no'] ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($d['landlord'] ?? '—', 20) }}</td>
                                <td>{{ $d['rent_value'] ?? '—' }}</td>
                                <td>
                                    @if ($conf !== null)<span class="badge badge-light-{{ $low ? 'danger' : 'success' }}">{{ round($conf * 100) }}%</span>@endif
                                    @if ($item->is_duplicate)<span class="badge badge-light-danger">مكرر؟</span>@endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rentModal{{ $item->id }}">
                                        مراجعة / تعديل
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-5">لا توجد عقود بانتظار المراجعة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
        </div>
    </div>

    {{-- نوافذ المراجعة/التعديل المنبثقة --}}
    @foreach ($items as $item)
        @php
            $d = $item->extracted_json['data'] ?? [];
            $fc = $item->field_confidence ?? [];
            $conf = $item->confidence;
            $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
            $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
        @endphp
        <div class="modal fade" id="rentModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">
                            مراجعة عقد — {{ $item->batch->original_filename ?? '' }}
                            @if ($conf !== null)<span class="badge badge-light-{{ $conf < $threshold ? 'danger' : 'success' }} ms-2">الثقة {{ round($conf * 100) }}%</span>@endif
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-5">
                            {{-- صورة العقد --}}
                            <div class="col-lg-5">
                                <div class="border rounded p-2 bg-light" style="max-height:60vh; overflow:auto;">
                                    @for ($p = 0; $p < max(1, $pages); $p++)
                                        <img src="{{ route('dashboard.rent.ai.image', ['item' => $item->id, 'page' => $p]) }}"
                                             loading="lazy" class="img-fluid mb-2 rounded shadow-sm w-100" alt="صفحة {{ $p + 1 }}" onerror="this.style.display='none'">
                                    @endfor
                                </div>
                            </div>
                            {{-- الحقول --}}
                            <div class="col-lg-7">
                                <form action="{{ route('dashboard.rent.ai.approve', $item->id) }}" method="POST" id="rentfrm{{ $item->id }}">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label required">المحل / العقار</label>
                                            <select name="shop_id" class="form-select" required>
                                                <option value="">— اختر —</option>
                                                @foreach ($shops as $s)<option value="{{ $s->shop_id }}">{{ $s->shop_name }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="form-label">رقم العقد</label>
                                            <input type="text" name="contract_no" class="form-control {{ $cls('contract_no') }}" value="{{ $d['contract_no'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">تاريخ البداية</label>
                                            <input type="text" name="start_date" class="form-control {{ $cls('start_date') }}" value="{{ $d['start_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                        <div class="col-md-6"><label class="form-label">تاريخ النهاية</label>
                                            <input type="text" name="end_date" class="form-control {{ $cls('end_date') }}" value="{{ $d['end_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                        <div class="col-md-4"><label class="form-label">قيمة الإيجار</label>
                                            <input type="number" step="0.01" name="rent_value" class="form-control {{ $cls('rent_value') }}" value="{{ $d['rent_value'] ?? '' }}"></div>
                                        <div class="col-md-4"><label class="form-label">عدد الدفعات</label>
                                            <input type="number" name="payments_count" class="form-control {{ $cls('payments_count') }}" value="{{ $d['payments_count'] ?? '' }}"></div>
                                        <div class="col-md-4"><label class="form-label">مبلغ الدفعة</label>
                                            <input type="number" step="0.01" name="payment_amount" class="form-control {{ $cls('payment_amount') }}" value="{{ $d['payment_amount'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">المؤجر</label>
                                            <input type="text" name="landlord" class="form-control {{ $cls('landlord') }}" value="{{ $d['landlord'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">المستأجر</label>
                                            <input type="text" name="tenant" class="form-control {{ $cls('tenant') }}" value="{{ $d['tenant'] ?? '' }}"></div>
                                        <div class="col-md-12"><label class="form-label">بيانات العقار</label>
                                            <input type="text" name="property_info" class="form-control {{ $cls('property_info') }}" value="{{ $d['property_info'] ?? '' }}"></div>
                                        @if (! empty($d['due_dates']))
                                            <div class="col-12"><div class="alert alert-light-primary mb-0">تواريخ استحقاق مكتشفة: {{ implode('، ', (array) $d['due_dates']) }}</div></div>
                                        @endif
                                    </div>
                                </form>
                                <div class="form-text mt-2">الحقول ذات الإطار الأحمر منخفضة الثقة — راجعها مقابل الصورة.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('dashboard.rent.ai.reject', $item->id) }}" method="POST" onsubmit="return confirm('تأكيد رفض هذا العقد؟');">
                            @csrf<input type="hidden" name="reason" value="رُفض يدوياً من المراجعة">
                            <button type="submit" class="btn btn-light-danger">رفض</button>
                        </form>
                        <button type="submit" form="rentfrm{{ $item->id }}" class="btn btn-success">اعتماد العقد وتوليد الدفعات</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
