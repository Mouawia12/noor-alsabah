@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    @php $threshold = (float) config('ai.confidence_threshold', 0.8); @endphp

    @forelse ($items as $item)
        @php
            $d = $item->extracted_json['data'] ?? [];
            $fc = $item->field_confidence ?? [];
            $conf = $item->confidence;
            $lowConf = $conf !== null && $conf < $threshold;
            $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
            $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
        @endphp

        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    عقد من: {{ $item->batch->original_filename ?? '—' }}
                    <span class="text-muted fs-7">(صفحات {{ $item->page_from }}–{{ $item->page_to }})</span>
                </h3>
                <div class="card-toolbar gap-2">
                    @if ($conf !== null)
                        <span class="badge badge-light-{{ $lowConf ? 'danger' : 'success' }}">الثقة: {{ round($conf * 100) }}%</span>
                    @endif
                    @if ($item->is_duplicate)<span class="badge badge-light-danger">عقد مكرر محتمل</span>@endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-5">
                    {{-- صورة العقد --}}
                    <div class="col-lg-5">
                        <div class="border rounded p-2 bg-light" style="max-height:520px; overflow:auto;">
                            @for ($p = 0; $p < max(1, $pages); $p++)
                                <a href="{{ route('dashboard.rent.ai.image', ['item' => $item->id, 'page' => $p]) }}" target="_blank">
                                    <img src="{{ route('dashboard.rent.ai.image', ['item' => $item->id, 'page' => $p]) }}"
                                         class="img-fluid mb-2 rounded shadow-sm" alt="صفحة {{ $p + 1 }}"
                                         onerror="this.style.display='none'">
                                </a>
                            @endfor
                        </div>
                        <div class="form-text">اضغط الصورة لفتحها بالحجم الكامل.</div>
                    </div>

                    {{-- الحقول --}}
                    <div class="col-lg-7">
                        <form action="{{ route('dashboard.rent.ai.approve', $item->id) }}" method="POST" id="frm-{{ $item->id }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label required">المحل / العقار</label>
                                    <select name="shop_id" class="form-select" required>
                                        <option value="">— اختر —</option>
                                        @foreach ($shops as $s)<option value="{{ $s->shop_id }}">{{ $s->shop_name }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">رقم العقد</label>
                                    <input type="text" name="contract_no" class="form-control {{ $cls('contract_no') }}" value="{{ $d['contract_no'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تاريخ البداية</label>
                                    <input type="text" name="start_date" class="form-control {{ $cls('start_date') }}" value="{{ $d['start_date'] ?? '' }}" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تاريخ النهاية</label>
                                    <input type="text" name="end_date" class="form-control {{ $cls('end_date') }}" value="{{ $d['end_date'] ?? '' }}" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">قيمة الإيجار</label>
                                    <input type="number" step="0.01" name="rent_value" class="form-control {{ $cls('rent_value') }}" value="{{ $d['rent_value'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">عدد الدفعات</label>
                                    <input type="number" name="payments_count" class="form-control {{ $cls('payments_count') }}" value="{{ $d['payments_count'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">مبلغ الدفعة</label>
                                    <input type="number" step="0.01" name="payment_amount" class="form-control {{ $cls('payment_amount') }}" value="{{ $d['payment_amount'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المؤجر</label>
                                    <input type="text" name="landlord" class="form-control {{ $cls('landlord') }}" value="{{ $d['landlord'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المستأجر</label>
                                    <input type="text" name="tenant" class="form-control {{ $cls('tenant') }}" value="{{ $d['tenant'] ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">بيانات العقار</label>
                                    <input type="text" name="property_info" class="form-control {{ $cls('property_info') }}" value="{{ $d['property_info'] ?? '' }}">
                                </div>
                                @if (! empty($d['due_dates']))
                                    <div class="col-12"><div class="alert alert-light-primary mb-0">
                                        تواريخ استحقاق مكتشفة: {{ implode('، ', (array) $d['due_dates']) }} — ستُولَّد الدفعات عند الاعتماد.
                                    </div></div>
                                @endif
                            </div>
                        </form>
                        <div class="form-text mt-2">الحقول ذات الإطار الأحمر منخفضة الثقة — راجعها مقابل الصورة.</div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <form action="{{ route('dashboard.rent.ai.reprocess', $item->id) }}" method="POST">
                    @csrf<button type="submit" class="btn btn-light">إعادة الاستخراج</button>
                </form>
                <form action="{{ route('dashboard.rent.ai.reject', $item->id) }}" method="POST" onsubmit="return confirm('تأكيد رفض هذا العقد؟');">
                    @csrf<input type="hidden" name="reason" value="رُفض يدوياً من المراجعة">
                    <button type="submit" class="btn btn-light-danger">رفض</button>
                </form>
                <button type="submit" form="frm-{{ $item->id }}" class="btn btn-success">اعتماد العقد وتوليد الدفعات</button>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-muted">لا توجد عقود بانتظار المراجعة.</div></div>
    @endforelse

    <div class="d-flex justify-content-center">{{ $items->links() }}</div>

@endsection
