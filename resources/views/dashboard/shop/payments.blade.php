@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المحلات')
@section('title', $page_title)
@section('content')
@php use App\Models\ShopRentpay; @endphp

<div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

<div class="card mb-5">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <h3 class="card-title">متابعة سداد العقود — {{ $shop->shop_name ?? '' }}
            @if(!empty($shop->shop_code))<span class="badge badge-light-primary ms-2">{{ $shop->shop_code }}</span>@endif
        </h3>
        <a href="{{ route('dashboard.shop.financial_report', $shop->shop_id) }}" class="btn btn-sm btn-light-info">التقرير المالي 📊</a>
    </div>
</div>

@forelse ($contracts as $c)
    @php $ct = $c['contract']; $s = $c['summary']; @endphp
    <div class="card mb-5">
        <div class="card-header flex-wrap gap-2 d-flex align-items-center justify-content-between">
            <div class="card-title flex-column">
                <h3 class="fw-bold">عقد رقم: {{ $ct->contract_no ?? $ct->rent_no ?? '—' }}
                    @if($ct->contract_type)<span class="badge badge-light-{{ $ct->contract_type === 'renewal' ? 'warning' : 'success' }} ms-2">{{ $ct->contract_type === 'renewal' ? 'مجدَّد' : 'جديد' }}</span>@endif
                </h3>
                <span class="text-muted fs-7">
                    المؤجِّر: <b class="text-gray-800">{{ $ct->landlord ?? $ct->rent_name ?? '—' }}</b>
                    · المستأجر: {{ $ct->tenant ?? '—' }} @if($ct->tenant_id_no)({{ $ct->tenant_id_no }})@endif
                    · الوحدة: {{ $ct->unit_no ?? '—' }} @if($ct->floor_no)- طابق {{ $ct->floor_no }}@endif
                    · من {{ optional($ct->start_date)->format('Y-m-d') ?? '—' }} إلى {{ optional($ct->end_date)->format('Y-m-d') ?? '—' }}
                    · الدورة: {{ ['monthly'=>'شهري','quarterly'=>'ربع سنوي','semi'=>'نصف سنوي','annual'=>'سنوي'][$ct->payment_cycle] ?? '—' }}
                </span>
            </div>
            @if(! empty($ct->ai_contract_file))
                <a href="{{ route('dashboard.rent.contract.file', $ct->shop_rent_id) }}" target="_blank" class="btn btn-sm btn-light-primary"><i class="fas fa-file-pdf text-danger me-1"></i>تحميل العقد</a>
            @endif
        </div>
        <div class="card-body">
            {{-- الملخص المالي للعقد --}}
            <div class="row g-3 mb-5">
                <div class="col"><div class="border rounded p-3 text-center"><div class="fs-4 fw-bold">{{ number_format($s['total'],2) }}</div><div class="text-muted fs-8">إجمالي العقد</div></div></div>
                <div class="col"><div class="border rounded p-3 text-center bg-light-success"><div class="fs-4 fw-bold text-success">{{ number_format($s['paid'],2) }}</div><div class="text-muted fs-8">المسدَّد</div></div></div>
                <div class="col"><div class="border rounded p-3 text-center bg-light-warning"><div class="fs-4 fw-bold text-warning">{{ number_format($s['remaining'],2) }}</div><div class="text-muted fs-8">المتبقّي</div></div></div>
                <div class="col"><div class="border rounded p-3 text-center bg-light-danger"><div class="fs-4 fw-bold text-danger">{{ number_format($s['overdue'],2) }}</div><div class="text-muted fs-8">متأخّر ({{ $s['overdue_count'] }})</div></div></div>
            </div>

            {{-- المستحقة الآن + المتأخرة --}}
            @if($c['due']->isNotEmpty())
                <h4 class="text-danger mb-3">دفعات مستحقة الآن / متأخرة</h4>
                @include('dashboard.shop._payment_rows', ['rows' => $c['due'], 'payable' => true])
            @endif

            {{-- القادمة --}}
            @if($c['upcoming']->isNotEmpty())
                <h4 class="text-primary mt-5 mb-3">دفعات قادمة</h4>
                @include('dashboard.shop._payment_rows', ['rows' => $c['upcoming'], 'payable' => true])
            @endif

            {{-- السابقة (مسددة) --}}
            @if($c['past']->isNotEmpty())
                <h4 class="text-success mt-5 mb-3">دفعات مسدَّدة</h4>
                @include('dashboard.shop._payment_rows', ['rows' => $c['past'], 'payable' => false])
            @endif

            {{-- سندات القبض --}}
            @if($c['receipts']->isNotEmpty())
                <h4 class="text-info mt-5 mb-3">سندات القبض</h4>
                @php
                    $ord = [1=>'الأولى',2=>'الثانية',3=>'الثالثة',4=>'الرابعة',5=>'الخامسة',6=>'السادسة',7=>'السابعة',8=>'الثامنة',9=>'التاسعة',10=>'العاشرة',11=>'الحادية عشرة',12=>'الثانية عشرة'];
                    $instLabel = fn($n) => $n ? ('الدفعة ' . ($ord[$n] ?? ('رقم ' . $n))) : '—';
                @endphp
                <div class="table-responsive"><table class="table table-row-bordered align-middle">
                    <thead><tr class="fw-bold text-gray-800 bg-light"><th>رقم السند</th><th>الدفعة</th><th>المبلغ</th><th>التاريخ</th><th>الطريقة</th><th>طباعة</th></tr></thead>
                    <tbody>@foreach($c['receipts'] as $r)
                        <tr><td class="fw-bold">{{ $r->receipt_no }}</td><td><span class="badge badge-light-info">{{ $instLabel(optional($r->payment)->seq_no) }}</span></td><td>{{ number_format($r->amount,2) }}</td><td>{{ optional($r->paid_at)->format('Y-m-d') }}</td><td>{{ $r->method ?? '—' }}</td>
                        <td><a href="{{ route('dashboard.shop.receipt.pdf', $r->receipt_id) }}" target="_blank" class="btn btn-sm btn-light-primary py-1"><i class="fas fa-print me-1"></i>سند PDF</a></td></tr>
                    @endforeach</tbody>
                </table></div>
            @endif
        </div>
    </div>
@empty
    <div class="card"><div class="card-body text-center text-muted py-10">لا توجد عقود مرتبطة بهذا المحل.</div></div>
@endforelse

{{-- مودال تسجيل الدفعة --}}
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h3 class="modal-title">تسجيل سداد دفعة</h3><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" id="payRentpayId">
            <div class="mb-3"><label class="form-label">المبلغ المقبوض</label><input type="number" step="0.01" id="payAmount" class="form-control"></div>
            <div class="mb-3"><label class="form-label">طريقة الدفع</label><input type="text" id="payMethod" class="form-control" placeholder="نقدي / تحويل / شبكة"></div>
            <div class="mb-3"><label class="form-label">تاريخ القبض</label><input type="date" id="payDate" class="form-control"></div>
            <div class="mb-3"><label class="form-label">ملاحظة</label><input type="text" id="payNote" class="form-control"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
            <button type="button" id="payConfirm" class="btn btn-success">تأكيد السداد وإصدار السند</button>
        </div>
    </div></div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';
    var CSRF = '{{ csrf_token() }}';
    var RECORD_URL = '{{ url('dashboard/rent/pay') }}';
    function toast(msg, ok){ var t=document.getElementById('toaster'); if(!t) return alert(msg);
        var d=document.createElement('div'); d.className='alert alert-'+(ok===false?'danger':'success')+' shadow'; d.textContent=msg; t.appendChild(d); setTimeout(function(){d.remove();},4000); }
    function post(url, body){ return fetch(url,{method:'POST',credentials:'same-origin',
        headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'}, body:JSON.stringify(body||{})})
        .then(function(r){ return r.text().then(function(t){ var j; try{j=JSON.parse(t);}catch(e){j=null;} if(!r.ok||!j) throw (j||{status:r.status}); return j; }); }); }
    function modal(){ var m=document.getElementById('payModal'); return window.bootstrap&&bootstrap.Modal?bootstrap.Modal.getOrCreateInstance(m):null; }

    document.addEventListener('click', function(e){
        var pay=e.target.closest('.js-pay');
        if(pay){ e.preventDefault();
            document.getElementById('payRentpayId').value = pay.getAttribute('data-id');
            document.getElementById('payAmount').value = pay.getAttribute('data-remaining') || '';
            document.getElementById('payMethod').value=''; document.getElementById('payNote').value=''; document.getElementById('payDate').value='';
            var m=modal(); if(m) m.show(); return; }
        var ok=e.target.closest('#payConfirm');
        if(ok){ e.preventDefault(); var id=document.getElementById('payRentpayId').value;
            if(!confirm('تأكيد تسجيل السداد؟')) return; ok.disabled=true;
            post(RECORD_URL+'/'+id+'/record', {
                amount: document.getElementById('payAmount').value,
                method: document.getElementById('payMethod').value,
                paid_at: document.getElementById('payDate').value,
                note: document.getElementById('payNote').value
            }).then(function(res){ toast(res.message); setTimeout(function(){location.reload();}, 900); })
              .catch(function(err){ ok.disabled=false; toast(err&&err.message?err.message:'تعذّر تسجيل الدفعة', false); });
            return; }
    });
})();
</script>
@endsection
