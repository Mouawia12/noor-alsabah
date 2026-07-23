@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)

@section('styles')
<style>
    .rs-tbl thead th{background:#eef3f8!important;color:#181c32!important;font-weight:700!important;white-space:nowrap;padding:.75rem .6rem;font-size:.85rem}
    .rs-tbl tbody td{color:#2b2f42;vertical-align:middle;font-size:.87rem}
    .rs-tbl tbody tr.need{background:#fffaf0}
    .rs-tbl tbody tr:hover{background:#f7fafd}
    .cell-edit{cursor:text;border-bottom:1px dashed #cbd5e1;min-width:70px;display:inline-block;padding:2px 4px;border-radius:4px}
    .cell-edit:hover{background:#eef6ff}
    .cell-edit:focus{outline:2px solid #0d6efd;background:#fff}
    .cell-edit.saved{background:#e8f7ee}
    .rs-tile{border:1px solid #eef0f4;border-radius:14px;padding:1rem .75rem;text-align:center;height:100%}
</style>
@endsection

@section('content')
<div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

<div class="card shadow-sm mb-5">
    <div class="card-body p-5">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-5">
            <div class="me-auto">
                <div class="text-muted fs-8 mb-1">نتائج استخراج عقود الإيجار</div>
                <h2 class="fw-bold text-gray-900 mb-0" style="word-break:break-word">{{ $batch->original_filename }}</h2>
                <div class="text-muted fs-7 mt-1">
                    {{ (int) $batch->processed_items + (int) $batch->failed_items }} / {{ $batch->total_items }} صفحة
                    · {{ optional($batch->created_at)->format('Y-m-d H:i') }}
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge badge-light-{{ $batch->status === 'completed' ? 'success' : ($batch->status === 'failed' ? 'danger' : 'primary') }} fs-6 px-4 py-2">
                    {{ __('ai.status.'.$batch->status) }}
                </span>
                <a href="{{ route('dashboard.rent.ai.batches') }}" class="btn btn-sm btn-light">← السجل</a>
            </div>
        </div>

        {{-- بطاقات إحصائية --}}
        <div class="row g-3">
            <div class="col-6 col-md-3"><div class="rs-tile bg-light-primary">
                <div class="fs-2 fw-bolder text-primary">{{ $stats['total'] }}</div>
                <div class="fs-8 fw-semibold text-gray-700">عدد العقود</div></div></div>
            <div class="col-6 col-md-3"><div class="rs-tile bg-light-warning">
                <div class="fs-2 fw-bolder text-warning">{{ $stats['needs_review'] }}</div>
                <div class="fs-8 fw-semibold text-gray-700">تحتاج مراجعة</div></div></div>
            <div class="col-6 col-md-3"><div class="rs-tile bg-light-success">
                <div class="fs-2 fw-bolder text-success">{{ $stats['approved'] }}</div>
                <div class="fs-8 fw-semibold text-gray-700">مُعتمدة</div></div></div>
            <div class="col-6 col-md-3"><div class="rs-tile bg-light-danger">
                <div class="fs-2 fw-bolder text-danger">{{ $stats['failed'] }}</div>
                <div class="fs-8 fw-semibold text-gray-700">فاشلة</div></div></div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title fw-bold">تفاصيل العقود المستخرجة</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-light-primary py-2 fs-7">
            <b>تلميح:</b> انقر على أي خلية لتعديل قيمتها ثم انقر خارجها للحفظ. اختر المحل واضغط «موافقة» لإنشاء العقد وجدول الدفعات. الصفوف الصفراء تحتاج مراجعة.
        </div>
        <div class="table-responsive">
            <table class="table table-row-bordered align-middle rs-tbl">
                <thead><tr>
                    <th>#</th><th>رقم العقد</th><th>المستأجر</th><th>المؤجر</th><th>الوحدة / العقار</th>
                    <th>البداية</th><th>النهاية</th><th>قيمة الإيجار</th><th>عدد الدفعات</th>
                    <th>الحالة</th><th>المرفق</th><th style="min-width:150px">المحل</th><th>الإجراء</th>
                </tr></thead>
                <tbody>
                @forelse ($items as $i => $item)
                    @php
                        $d = $item->extracted_json['data'] ?? [];
                        $need = $item->status === \App\Models\RentContractImportItem::STATUS_NEEDS_REVIEW;
                        $done = $item->status === \App\Models\RentContractImportItem::STATUS_APPROVED;
                        $fail = $item->status === \App\Models\RentContractImportItem::STATUS_FAILED;
                        $ed = fn ($f) => 'class="cell-edit" contenteditable="true" data-item="'.$item->id.'" data-field="'.$f.'"';
                    @endphp
                    <tr data-item="{{ $item->id }}" class="{{ $need ? 'need' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td><span {!! $ed('contract_no') !!}>{{ $d['contract_no'] ?? '' }}</span></td>
                        <td><span {!! $ed('tenant') !!}>{{ $d['tenant'] ?? '' }}</span></td>
                        <td><span {!! $ed('landlord') !!}>{{ $d['landlord'] ?? '' }}</span></td>
                        <td><span {!! $ed('property_info') !!}>{{ $d['property_info'] ?? '' }}</span></td>
                        <td class="text-nowrap"><span {!! $ed('start_date') !!}>{{ $d['start_date'] ?? '' }}</span></td>
                        <td class="text-nowrap"><span {!! $ed('end_date') !!}>{{ $d['end_date'] ?? '' }}</span></td>
                        <td class="text-nowrap"><span {!! $ed('rent_value') !!}>{{ $d['rent_value'] ?? '' }}</span></td>
                        <td><span {!! $ed('payments_count') !!}>{{ $d['payments_count'] ?? '' }}</span></td>
                        <td class="text-nowrap">
                            @if($done)<span class="badge badge-light-success">مُعتمد</span>
                            @elseif($fail)<span class="badge badge-light-danger">فشل</span>
                            @elseif($need)<span class="badge badge-light-warning">تحتاج مراجعة</span>
                            @else<span class="badge badge-light">{{ $item->status }}</span>@endif
                            @if($item->is_duplicate)<span class="badge badge-light-danger ms-1">مكرر؟</span>@endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('dashboard.rent.ai.item.pdf', $item->id) }}" target="_blank" class="btn btn-sm btn-light-danger py-1" title="تحميل العقد PDF"><i class="fas fa-file-pdf"></i></a>
                        </td>
                        <td>
                            @if($need)
                                <select class="form-select form-select-sm row-shop" style="min-width:140px">
                                    <option value="">— اختر المحل —</option>
                                    @foreach ($shops as $s)<option value="{{ $s->shop_id }}">{{ ($s->shop_code ? '('.$s->shop_code.') ' : '').$s->shop_name }}</option>@endforeach
                                </select>
                            @else — @endif
                        </td>
                        <td class="text-nowrap">
                            @if($need)
                                <button type="button" class="btn btn-sm btn-success js-approve" data-url="{{ route('dashboard.rent.ai.approve', $item->id) }}">موافقة</button>
                                <button type="button" class="btn btn-sm btn-light-danger js-reject" data-url="{{ route('dashboard.rent.ai.reject', $item->id) }}">رفض</button>
                            @else — @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="13" class="text-center text-muted py-10">لا توجد عقود مستخرجة في هذه الدفعة.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';
    var CSRF = '{{ csrf_token() }}';
    var FIELD_URL = '{{ url('dashboard/rent/ai/item') }}';

    function toast(msg, ok){
        var t = document.getElementById('toaster'); if(!t){ alert(msg); return; }
        var d = document.createElement('div');
        d.className = 'alert alert-' + (ok === false ? 'danger' : 'success') + ' shadow';
        d.textContent = msg; t.appendChild(d);
        setTimeout(function(){ d.remove(); }, 3500);
    }
    function post(url, body){
        return fetch(url, { method:'POST', credentials:'same-origin',
            headers:{ 'X-CSRF-TOKEN':CSRF, 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json', 'Content-Type':'application/json' },
            body: JSON.stringify(body || {}) })
            .then(function(r){ return r.text().then(function(t){ var j; try{ j = JSON.parse(t); }catch(e){ j = null; } if(!r.ok || !j) throw (j || {status:r.status}); return j; }); });
    }

    /* تعديل داخل الخلية: نحفظ عند مغادرة الخلية إن تغيّرت القيمة */
    var original = null;
    document.addEventListener('focusin', function(e){
        var c = e.target.closest('.cell-edit');
        if (c) { original = c.innerText.trim(); }
    });
    document.addEventListener('focusout', function(e){
        var c = e.target.closest('.cell-edit');
        if (!c) return;
        var val = c.innerText.trim();
        if (original === null || val === original) return;
        var id = c.getAttribute('data-item'), field = c.getAttribute('data-field');
        post(FIELD_URL + '/' + id + '/field', { field: field, value: val })
            .then(function(){ c.classList.add('saved'); setTimeout(function(){ c.classList.remove('saved'); }, 1200); })
            .catch(function(err){ c.innerText = original; toast(err && err.message ? err.message : 'تعذّر حفظ التعديل', false); });
        original = null;
    });
    /* Enter يحفظ ويخرج من الخلية */
    document.addEventListener('keydown', function(e){
        if (e.key === 'Enter' && e.target.closest('.cell-edit')) { e.preventDefault(); e.target.blur(); }
    });

    document.addEventListener('click', function(e){
        var ap = e.target.closest('.js-approve');
        if (ap){
            e.preventDefault();
            var tr = ap.closest('tr');
            var sel = tr.querySelector('.row-shop');
            var shop = sel ? sel.value : '';
            if (!shop){ toast('اختر المحل/العقار أولاً', false); return; }
            ap.disabled = true;
            post(ap.getAttribute('data-url'), { shop_id: shop })
                .then(function(res){ toast(res.message); setTimeout(function(){ location.reload(); }, 800); })
                .catch(function(err){ ap.disabled = false; toast(err && err.message ? err.message : 'تعذّر الاعتماد', false); });
            return;
        }
        var rj = e.target.closest('.js-reject');
        if (rj){
            e.preventDefault();
            if (!confirm('تأكيد رفض هذا العقد؟')) return;
            rj.disabled = true;
            post(rj.getAttribute('data-url'), { reason: 'رُفض يدوياً' })
                .then(function(res){ toast(res.message); setTimeout(function(){ location.reload(); }, 800); })
                .catch(function(){ rj.disabled = false; toast('تعذّر الرفض', false); });
            return;
        }
    });
})();
</script>
@endsection
