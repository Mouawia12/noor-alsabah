@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

<div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

{{-- نموذج إدخال دفعة واردة لتشغيل المطابقة --}}
<div class="card mb-5">
    <div class="card-header"><h3 class="card-title">إدخال دفعة واردة للمطابقة الذكية</h3></div>
    <div class="card-body">
        <form id="ingestForm" class="row g-3">
            <div class="col-md-3"><label class="form-label">رقم العقد</label><input type="text" name="contract_no" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">اسم المستأجر</label><input type="text" name="tenant_name" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">هوية المستأجر</label><input type="text" name="tenant_id_no" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">رقم الوحدة</label><input type="text" name="unit_no" class="form-control"></div>
            <div class="col-md-2"><label class="form-label">المبلغ</label><input type="number" step="0.01" name="amount" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control"></div>
            <div class="col-md-2 align-self-end"><button type="submit" class="btn btn-primary w-100">مطابقة</button></div>
        </form>
        <div class="text-muted fs-8 mt-2">يقترح النظام العقد/المحل الأنسب بنسبة ثقة وسبب — لا يُعتمد آلياً، يلزم تأكيدك.</div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h3 class="card-title">دفعات بانتظار المراجعة (<span id="pmCount">{{ $items->total() }}</span>)</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.rent.payment_match.export', ['format' => 'xlsx']) }}" class="btn btn-sm btn-light-success"><i class="fas fa-file-excel me-1"></i>Excel</a>
            <a href="{{ route('dashboard.rent.payment_match.export', ['format' => 'pdf']) }}" class="btn btn-sm btn-light-danger"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        </div>
    </div>
    <div class="card-body">
        <div id="pmList">@include('dashboard.rent.payment_match._list', ['items' => $items])</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';
    var CSRF = '{{ csrf_token() }}';
    var STORE_URL = '{{ route('dashboard.rent.payment_match.store') }}';
    var REVIEW_URL = '{{ route('dashboard.rent.payment_match.review') }}';
    function toast(msg, ok){ var t=document.getElementById('toaster'); if(!t) return alert(msg);
        var d=document.createElement('div'); d.className='alert alert-'+(ok===false?'danger':'success')+' shadow'; d.textContent=msg; t.appendChild(d); setTimeout(function(){d.remove();},4000); }
    function post(url, body){ return fetch(url,{method:'POST',credentials:'same-origin',
        headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'}, body:JSON.stringify(body||{})})
        .then(function(r){ return r.text().then(function(t){ var j; try{j=JSON.parse(t);}catch(e){j=null;} if(!r.ok||!j) throw (j||{status:r.status}); return j; }); }); }
    function reloadList(){ return fetch(REVIEW_URL, {credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
        .then(function(r){return r.json();}).then(function(d){ var l=document.getElementById('pmList'); if(l) l.innerHTML=d.html; var c=document.getElementById('pmCount'); if(c) c.textContent=d.count; })
        .catch(function(){ location.reload(); }); }

    var form=document.getElementById('ingestForm');
    if(form){ form.addEventListener('submit', function(e){ e.preventDefault(); var body={}; new FormData(form).forEach(function(v,k){ body[k]=v; });
        post(STORE_URL, body).then(function(res){ var m=res.match||{}; toast('تمّت المطابقة: '+(m.reason||'—')); form.reset(); reloadList(); })
        .catch(function(err){ toast(err&&err.message?err.message:'تعذّرت المطابقة', false); }); }); }

    document.addEventListener('click', function(e){
        var ap=e.target.closest('.js-pm-approve');
        if(ap){ e.preventDefault(); if(!confirm('اعتماد الدفعة وربطها بالعقد وتسجيل السداد؟')) return; ap.disabled=true;
            post(ap.getAttribute('data-url'), {}).then(function(res){ toast(res.message); reloadList(); }).catch(function(err){ ap.disabled=false; toast(err&&err.message?err.message:'تعذّر الاعتماد', false); }); return; }
        var rj=e.target.closest('.js-pm-reject');
        if(rj){ e.preventDefault(); if(!confirm('رفض هذه الدفعة؟')) return;
            post(rj.getAttribute('data-url'), {}).then(function(res){ toast(res.message); reloadList(); }).catch(function(){ toast('تعذّر الرفض', false); }); return; }
    });
})();
</script>
@endsection
