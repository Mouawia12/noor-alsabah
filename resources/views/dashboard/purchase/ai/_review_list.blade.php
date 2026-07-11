@php
    $threshold = (float) config('ai.confidence_threshold', 0.8);
    // أرقام الفواتير المكررة: موجودة مسبقاً في المشتريات، أو مكررة داخل نفس القائمة (نفس الملف)
    $nos = collect($items->items())
        ->map(fn ($it) => trim((string) (($it->extracted_json['data']['invoice_no'] ?? ''))))
        ->filter();
    $existingNos = [];
    if ($nos->isNotEmpty() && \Illuminate\Support\Facades\Schema::hasTable('purchase')) {
        $existingNos = \Illuminate\Support\Facades\DB::table('purchase')
            ->whereIn('purchase_no', $nos->unique()->values()->all())->pluck('purchase_no')->all();
    }
    $dupNos = array_values(array_unique(array_merge($nos->duplicates()->values()->all(), $existingNos)));
@endphp
<div class="table-responsive">
    <table class="table table-row-bordered table-hover align-middle">
        <thead><tr class="fw-bold text-muted bg-light">
            <th>#</th><th>الملف/الصفحات</th><th>رقم الفاتورة</th><th>المورد</th><th>الإجمالي</th><th>الثقة</th><th>الإجراء</th>
        </tr></thead>
        <tbody>
            @forelse ($items as $i => $item)
                @php $d = $item->extracted_json['data'] ?? []; $conf = $item->confidence; $low = $conf !== null && $conf < $threshold;
                    $isDup = $item->is_duplicate || in_array(trim((string) ($d['invoice_no'] ?? '')), $dupNos, true); @endphp
                <tr data-item="{{ $item->id }}">
                    <td>{{ $items->firstItem() + $i }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->batch->original_filename ?? '—', 18) }} <span class="text-muted fs-8">(ص {{ $item->page_from }}–{{ $item->page_to }})</span></td>
                    <td class="fw-bold">{{ $d['invoice_no'] ?? '—' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($d['supplier_name'] ?? '—', 16) }}</td>
                    <td>{{ $d['total'] ?? '—' }}</td>
                    <td>@if ($conf !== null)<span class="badge badge-light-{{ $low ? 'danger' : 'success' }}">{{ round($conf * 100) }}%</span>@endif @if ($isDup)<span class="badge badge-danger" title="رقم الفاتورة مكرّر — لن يُقبل ترحيله أكثر من مرة">فاتورة مكرّرة</span>@endif</td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-sm btn-success js-approve" data-url="{{ route('dashboard.purchase.ai.approve', $item->id) }}">ترحيل</button>
                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#purModal{{ $item->id }}">مراجعة/تعديل</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-5">لا توجد فواتير بانتظار المراجعة.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>

@foreach ($items as $item)
    @php
        $d = $item->extracted_json['data'] ?? []; $fc = $item->field_confidence ?? [];
        $supplier = $item->extracted_json['supplier'] ?? []; $validation = $item->extracted_json['validation'] ?? [];
        $conf = $item->confidence; $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
        $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
    @endphp
    <div class="modal fade" id="purModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">مراجعة فاتورة — {{ $item->batch->original_filename ?? '' }}
                        @if ($conf !== null)<span class="badge badge-light-{{ $conf < $threshold ? 'danger' : 'success' }} ms-2">الثقة {{ round($conf * 100) }}%</span>@endif</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if (! empty($validation['issues']))
                        <div class="alert alert-warning"><b>ملاحظات:</b><ul class="mb-0">@foreach ($validation['issues'] as $iss)<li>{{ $iss }}</li>@endforeach</ul></div>
                    @endif
                    <div class="row g-5">
                        <div class="col-lg-5"><div class="border rounded p-2 bg-light" style="max-height:60vh;overflow:auto">
                            @for ($p = 0; $p < max(1, $pages); $p++)
                                <img src="{{ route('dashboard.purchase.ai.image', ['item' => $item->id, 'page' => $p]) }}" loading="lazy" class="img-fluid mb-2 rounded shadow-sm w-100" alt="صفحة {{ $p + 1 }}" onerror="this.style.display='none'">
                            @endfor
                        </div></div>
                        <div class="col-lg-7">
                            <form class="js-modal-form" data-item="{{ $item->id }}">
                                <div class="row g-4">
                                    <div class="col-md-6"><label class="form-label">رقم الفاتورة</label><input type="text" name="invoice_no" class="form-control {{ $cls('invoice_no') }}" value="{{ $d['invoice_no'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">تاريخ الفاتورة</label><input type="text" name="invoice_date" class="form-control {{ $cls('invoice_date') }}" value="{{ $d['invoice_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                    <div class="col-md-6"><label class="form-label">الرقم الضريبي</label><input type="text" name="tax_number" class="form-control {{ $cls('tax_number') }}" value="{{ $d['tax_number'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">العملة</label><input type="text" name="currency" class="form-control {{ $cls('currency') }}" value="{{ $d['currency'] ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label">قبل الضريبة</label><input type="number" step="0.01" name="amount_before_tax" class="form-control {{ $cls('amount_before_tax') }}" value="{{ $d['amount_before_tax'] ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label">الضريبة</label><input type="number" step="0.01" name="tax_amount" class="form-control {{ $cls('tax_amount') }}" value="{{ $d['tax_amount'] ?? '' }}"></div>
                                    <div class="col-md-4"><label class="form-label">الإجمالي</label><input type="number" step="0.01" name="total" class="form-control {{ $cls('total') }}" value="{{ $d['total'] ?? '' }}"></div>
                                    <div class="col-md-6"><label class="form-label">المورد</label>
                                        @if (! empty($supplier['matched']))
                                            <input type="hidden" name="supplier_id" value="{{ $supplier['supplier_id'] }}"><input type="text" class="form-control" value="{{ $supplier['suggestion'] }} (مطابق)" disabled>
                                        @else
                                            <input type="text" name="new_supplier_name" class="form-control" value="{{ $d['supplier_name'] ?? '' }}" placeholder="اسم مورد جديد">
                                        @endif
                                    </div>
                                    <div class="col-md-6"><label class="form-label">ملاحظات</label><input type="text" name="note" class="form-control" value="{{ $d['note'] ?? '' }}"></div>
                                </div>
                            </form>
                            <div class="form-text mt-2">الحقول الحمراء منخفضة الثقة — راجعها مقابل الصورة.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-light-danger js-reject" data-url="{{ route('dashboard.purchase.ai.reject', $item->id) }}">رفض</button>
                    <button type="button" class="btn btn-success js-modal-approve" data-item="{{ $item->id }}" data-url="{{ route('dashboard.purchase.ai.approve', $item->id) }}">ترحيل إلى الفرع وإنشاء سجل مشتريات</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
