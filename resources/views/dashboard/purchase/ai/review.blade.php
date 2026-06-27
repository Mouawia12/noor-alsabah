@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
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
            $supplier = $item->extracted_json['supplier'] ?? [];
            $validation = $item->extracted_json['validation'] ?? [];
            $conf = $item->confidence;
            $lowConf = $conf !== null && $conf < $threshold;
            $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
            // فئة حدّ أحمر للحقول منخفضة الثقة
            $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
        @endphp

        <div class="card mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    فاتورة من: {{ $item->batch->original_filename ?? '—' }}
                    <span class="text-muted fs-7">(صفحات {{ $item->page_from }}–{{ $item->page_to }})</span>
                </h3>
                <div class="card-toolbar gap-2">
                    @if ($conf !== null)
                        <span class="badge badge-light-{{ $lowConf ? 'danger' : 'success' }}">الثقة: {{ round($conf * 100) }}%</span>
                    @endif
                    @if ($item->is_duplicate)
                        <span class="badge badge-light-danger">فاتورة مكررة محتملة (سجل #{{ $item->duplicate_of_purchase_id }})</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-5">
                    {{-- صورة المستند الأصلي --}}
                    <div class="col-lg-5">
                        <div class="border rounded p-2 bg-light" style="max-height:520px; overflow:auto;">
                            @for ($p = 0; $p < max(1, $pages); $p++)
                                <a href="{{ route('dashboard.purchase.ai.image', ['item' => $item->id, 'page' => $p]) }}" target="_blank">
                                    <img src="{{ route('dashboard.purchase.ai.image', ['item' => $item->id, 'page' => $p]) }}"
                                         class="img-fluid mb-2 rounded shadow-sm" alt="صفحة {{ $p + 1 }}"
                                         onerror="this.style.display='none'">
                                </a>
                            @endfor
                        </div>
                        <div class="form-text">اضغط الصورة لفتحها بالحجم الكامل.</div>
                    </div>

                    {{-- الحقول --}}
                    <div class="col-lg-7">
                        @if (! empty($validation['issues']))
                            <div class="alert alert-warning">
                                <b>ملاحظات التحقق:</b>
                                <ul class="mb-0">@foreach ($validation['issues'] as $iss)<li>{{ $iss }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('dashboard.purchase.ai.approve', $item->id) }}" method="POST" id="frm-{{ $item->id }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">رقم الفاتورة</label>
                                    <input type="text" name="invoice_no" class="form-control {{ $cls('invoice_no') }}" value="{{ $d['invoice_no'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تاريخ الفاتورة</label>
                                    <input type="text" name="invoice_date" class="form-control {{ $cls('invoice_date') }}" value="{{ $d['invoice_date'] ?? '' }}" placeholder="YYYY-MM-DD">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">الرقم الضريبي</label>
                                    <input type="text" name="tax_number" class="form-control {{ $cls('tax_number') }}" value="{{ $d['tax_number'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">العملة</label>
                                    <input type="text" name="currency" class="form-control {{ $cls('currency') }}" value="{{ $d['currency'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">قبل الضريبة</label>
                                    <input type="number" step="0.01" name="amount_before_tax" class="form-control {{ $cls('amount_before_tax') }}" value="{{ $d['amount_before_tax'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">الضريبة</label>
                                    <input type="number" step="0.01" name="tax_amount" class="form-control {{ $cls('tax_amount') }}" value="{{ $d['tax_amount'] ?? '' }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">الإجمالي</label>
                                    <input type="number" step="0.01" name="total" class="form-control {{ $cls('total') }}" value="{{ $d['total'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المورد</label>
                                    @if (! empty($supplier['matched']))
                                        <input type="hidden" name="supplier_id" value="{{ $supplier['supplier_id'] }}">
                                        <input type="text" class="form-control" value="{{ $supplier['suggestion'] }} (مطابق)" disabled>
                                    @else
                                        <input type="text" name="new_supplier_name" class="form-control" value="{{ $d['supplier_name'] ?? '' }}" placeholder="اسم مورد جديد">
                                        @if (! empty($supplier['suggestion']))<div class="form-text">اقتراح قريب: {{ $supplier['suggestion'] }}</div>@endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ملاحظات</label>
                                    <input type="text" name="note" class="form-control" value="{{ $d['note'] ?? '' }}">
                                </div>
                            </div>
                        </form>
                        <div class="form-text mt-2">الحقول ذات الإطار الأحمر منخفضة الثقة — راجعها مقابل الصورة.</div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <form action="{{ route('dashboard.purchase.ai.reprocess', $item->id) }}" method="POST">
                    @csrf<button type="submit" class="btn btn-light">إعادة الاستخراج</button>
                </form>
                <form action="{{ route('dashboard.purchase.ai.reject', $item->id) }}" method="POST" onsubmit="return confirm('تأكيد رفض هذه الفاتورة؟');">
                    @csrf<input type="hidden" name="reason" value="رُفضت يدوياً من المراجعة">
                    <button type="submit" class="btn btn-light-danger">رفض</button>
                </form>
                <button type="submit" form="frm-{{ $item->id }}" class="btn btn-success">اعتماد وإنشاء سجل مشتريات</button>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center text-muted">لا توجد فواتير بانتظار المراجعة.</div></div>
    @endforelse

    <div class="d-flex justify-content-center">{{ $items->links() }}</div>

@endsection
