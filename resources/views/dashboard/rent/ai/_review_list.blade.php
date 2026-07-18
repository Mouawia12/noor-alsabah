@php $threshold = (float) config('ai.confidence_threshold', 0.8); @endphp
<div class="table-responsive">
    <table class="table table-row-bordered table-hover align-middle ai-review-tbl">
        <thead><tr class="fw-bold text-gray-800 bg-light">
            <th>#</th><th>الملف/الصفحات</th><th>رقم العقد</th><th>المؤجر</th><th>القيمة</th><th>الثقة</th>
            <th style="min-width:160px">المحل / العقار <span class="text-danger">*</span></th><th>الإجراء</th>
        </tr></thead>
        <tbody>
            @forelse ($items as $i => $item)
                @php $d = $item->extracted_json['data'] ?? []; $conf = $item->confidence; $low = $conf !== null && $conf < $threshold; @endphp
                <tr data-item="{{ $item->id }}">
                    <td>{{ $items->firstItem() + $i }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->batch->original_filename ?? '—', 18) }} <span class="text-muted fs-8">(ص {{ $item->page_from }}–{{ $item->page_to }})</span></td>
                    <td class="fw-bold text-nowrap">{{ $d['contract_no'] ?? '—' }}</td>
                    <td>{{ $d['landlord'] ?? '—' }}</td>
                    <td>{{ $d['rent_value'] ?? '—' }}</td>
                    <td>@if ($conf !== null)<span class="badge badge-light-{{ $low ? 'danger' : 'success' }}">{{ round($conf * 100) }}%</span>@endif @if ($item->is_duplicate)<span class="badge badge-light-danger">مكرر؟</span>@endif</td>
                    <td>
                        <select class="form-select form-select-sm row-shop" data-placeholder="ابحث بالاسم أو الكود..." style="min-width:180px">
                            <option value="">— اختر المحل —</option>
                            @foreach ($shops as $s)<option value="{{ $s->shop_id }}">{{ ($s->shop_code ?? null ? '('.$s->shop_code.') ' : '').$s->shop_name }}</option>@endforeach
                        </select>
                    </td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-success js-approve" data-url="{{ route('dashboard.rent.ai.approve', $item->id) }}">اعتماد</button>
                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#rentModal{{ $item->id }}">مراجعة/تعديل</button>
                        <a href="{{ route('dashboard.rent.ai.item.pdf', $item->id) }}" target="_blank" class="btn btn-sm btn-light-danger" title="تنزيل العقد PDF"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                        <button type="button" class="btn btn-sm btn-light-danger js-delete" data-url="{{ route('dashboard.rent.ai.destroy', $item->id) }}" title="حذف العقد"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-5">لا توجد عقود بانتظار المراجعة.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>

{{-- نوافذ التعديل --}}
@foreach ($items as $item)
    @php
        $d = $item->extracted_json['data'] ?? []; $fc = $item->field_confidence ?? []; $conf = $item->confidence;
        $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
        $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
    @endphp
    <div class="modal fade" id="rentModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">مراجعة عقد — {{ $item->batch->original_filename ?? '' }}
                        @if ($conf !== null)<span class="badge badge-light-{{ $conf < $threshold ? 'danger' : 'success' }} ms-2">الثقة {{ round($conf * 100) }}%</span>@endif</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-5">
                        <div class="col-lg-5"><div class="border rounded p-2 bg-light" style="max-height:60vh;overflow:auto">
                            @for ($p = 0; $p < max(1, $pages); $p++)
                                <img src="{{ route('dashboard.rent.ai.image', ['item' => $item->id, 'page' => $p]) }}" loading="lazy" class="img-fluid mb-2 rounded shadow-sm w-100" alt="صفحة {{ $p + 1 }}" onerror="this.style.display='none'">
                            @endfor
                        </div></div>
                        <div class="col-lg-7">
                            <form class="js-modal-form" data-item="{{ $item->id }}">
                                <div class="row g-4">
                                    <div class="col-md-6"><label class="form-label">رقم العقد</label><input type="text" name="contract_no" class="form-control {{ $cls('contract_no') }}" value="{{ $d['contract_no'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">تاريخ البداية</label><input type="text" name="start_date" class="form-control {{ $cls('start_date') }}" value="{{ $d['start_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                    <div class="col-md-6"><label class="form-label">تاريخ النهاية</label><input type="text" name="end_date" class="form-control {{ $cls('end_date') }}" value="{{ $d['end_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                    <div class="col-md-3"><label class="form-label">قيمة الإيجار</label><input type="number" step="0.01" name="rent_value" class="form-control {{ $cls('rent_value') }}" value="{{ $d['rent_value'] ?? '' }}"></div>
                                    <div class="col-md-3"><label class="form-label">عدد الدفعات</label><input type="number" name="payments_count" class="form-control {{ $cls('payments_count') }}" value="{{ $d['payments_count'] ?? '' }}"></div>
                                    <div class="col-md-3"><label class="form-label">مبلغ الدفعة</label><input type="number" step="0.01" name="payment_amount" class="form-control {{ $cls('payment_amount') }}" value="{{ $d['payment_amount'] ?? '' }}"></div>
                                    <div class="col-md-3"><label class="form-label">المؤجر</label><input type="text" name="landlord" class="form-control {{ $cls('landlord') }}" value="{{ $d['landlord'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">المستأجر</label><input type="text" name="tenant" class="form-control {{ $cls('tenant') }}" value="{{ $d['tenant'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">بيانات العقار</label><input type="text" name="property_info" class="form-control {{ $cls('property_info') }}" value="{{ $d['property_info'] ?? '' }}"></div>
                                </div>
                            </form>
                            <div class="form-text mt-2">المحل يُختار من الجدول. الحقول الحمراء منخفضة الثقة.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-light-danger js-reject" data-url="{{ route('dashboard.rent.ai.reject', $item->id) }}">رفض</button>
                    <button type="button" class="btn btn-success js-modal-approve" data-item="{{ $item->id }}" data-url="{{ route('dashboard.rent.ai.approve', $item->id) }}">اعتماد العقد وتوليد الدفعات</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
