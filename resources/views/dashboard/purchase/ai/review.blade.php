@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)
@section('content')

    <div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h3 class="card-title">الفواتير المقبولة بانتظار الترحيل (<span id="reviewCount">{{ $items->total() }}</span>)</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <label class="fw-bold text-gray-700">الفرع / المحل:</label>
                <select id="shopSelect" class="form-select form-select-sm w-auto" style="min-width:220px">
                    <option value="">— اختر الفرع —</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->shop_id }}">{{ $shop->shop_name }}</option>
                    @endforeach
                </select>
                <button type="button" id="approveAllBtn" class="btn btn-success" data-url="{{ route('dashboard.purchase.ai.approve_all') }}">ترحيل الفواتير ✓</button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-light-primary py-2">اختر <b>الفرع/المحل</b> أولاً، ثم اضغط «ترحيل الفواتير» لترحيل الكل دفعة واحدة، أو «ترحيل» لفاتورة مفردة، أو «مراجعة/تعديل» للاطلاع على الصورة وتعديل الحقول قبل الترحيل.</div>
            <div id="reviewList">
                @include('dashboard.purchase.ai._review_list', ['items' => $items])
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {
    'use strict';
    var CSRF = '{{ csrf_token() }}';
    function ready(fn){ if(document.readyState!=='loading'){fn();} else {document.addEventListener('DOMContentLoaded',fn);} }
    function toast(msg, ok){ var t=document.getElementById('toaster'); if(!t) return alert(msg);
        var d=document.createElement('div'); d.className='alert alert-'+(ok===false?'danger':'success')+' shadow'; d.textContent=msg; t.appendChild(d); setTimeout(function(){d.remove();},4000); }
    var PER_PAGE = 20;
    function hideModal(id){ var m=document.getElementById('purModal'+id);
        if(m){ if(window.bootstrap&&bootstrap.Modal){var i=bootstrap.Modal.getInstance(m); if(i){i.hide();}}
            else { var b=m.querySelector('[data-bs-dismiss="modal"]'); if(b) b.click(); } }
        document.querySelectorAll('.modal-backdrop').forEach(function(b){ b.remove(); });
        document.body.classList.remove('modal-open'); document.body.style.removeProperty('padding-right'); }
    function currentPage(){ var p=parseInt(new URLSearchParams(location.search).get('page')); return p>0?p:1; }
    /** يعيد جلب الصفحة الحالية من الخادم ويستبدل أسطر الجدول + العدّاد + الترقيم. */
    function reloadList(page){
        page = page || currentPage();
        var u = new URL(location.href); u.searchParams.set('page', page);
        return fetch(u.toString(), { credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if (page > 1 && data.count <= (page - 1) * PER_PAGE) {
                    var prev = page - 1; var nu = new URL(location.href); nu.searchParams.set('page', prev);
                    history.replaceState({}, '', nu.toString()); return reloadList(prev);
                }
                var list=document.getElementById('reviewList'); if(list) list.innerHTML=data.html;
                var c=document.getElementById('reviewCount'); if(c) c.textContent=data.count;
            })
            .catch(function(){ location.reload(); });
    }
    function post(url, body){ return fetch(url,{method:'POST',credentials:'same-origin',
        headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'}, body:JSON.stringify(body||{})})
        .then(function(r){ return r.text().then(function(t){ var j; try{j=JSON.parse(t);}catch(e){j=null;} if(!r.ok||!j) throw (j||{status:r.status}); return j; }); }); }

    /** الفرع المختار من القائمة المنسدلة (فارغ = لم يُختر). */
    function selectedShop(){ var s=document.getElementById('shopSelect'); return s ? s.value : ''; }
    function requireShop(){ var v=selectedShop(); if(!v){ toast('يرجى اختيار الفرع/المحل أولاً.',false); var s=document.getElementById('shopSelect'); if(s) s.focus(); } return v; }

    ready(function(){
        console.log('[AI-REVIEW] سكربت مراجعة المشتريات محمّل. أزرار:', document.querySelectorAll('.js-approve').length);
        document.addEventListener('click', function(e){
            var a=e.target.closest('.js-approve');
            if(a){ e.preventDefault(); var shop=requireShop(); if(!shop) return; var id=a.closest('tr').getAttribute('data-item'); a.disabled=true;
                post(a.getAttribute('data-url'),{shop_id:shop}).then(function(res){toast(res.message);hideModal(id);reloadList();}).catch(function(err){a.disabled=false;toast(err&&err.message?err.message:'تعذّر الترحيل',false);}); return; }
            var ma=e.target.closest('.js-modal-approve');
            if(ma){ e.preventDefault(); var shop2=requireShop(); if(!shop2) return; var id2=ma.getAttribute('data-item'); var form=document.querySelector('.js-modal-form[data-item="'+id2+'"]'); var body={shop_id:shop2}; if(form){new FormData(form).forEach(function(v,k){body[k]=v;});} ma.disabled=true;
                post(ma.getAttribute('data-url'),body).then(function(res){toast(res.message);hideModal(id2);reloadList();}).catch(function(err){ma.disabled=false;toast(err&&err.message?err.message:'تعذّر الترحيل',false);}); return; }
            var rj=e.target.closest('.js-reject');
            if(rj){ e.preventDefault(); if(!confirm('تأكيد رفض هذه الفاتورة؟'))return; var id3=rj.closest('.modal').id.replace('purModal','');
                post(rj.getAttribute('data-url'),{reason:'رُفضت يدوياً'}).then(function(res){toast(res.message);hideModal(id3);reloadList();}).catch(function(){toast('تعذّر الرفض',false);}); return; }
            var all=e.target.closest('#approveAllBtn');
            if(all){ e.preventDefault(); var shopAll=requireShop(); if(!shopAll) return;
                var ids=[].map.call(document.querySelectorAll('tr[data-item]'),function(tr){return tr.getAttribute('data-item');});
                if(!ids.length){toast('لا توجد فواتير',false);return;} if(!confirm('ترحيل '+ids.length+' فاتورة إلى الفرع المحدد؟'))return; all.disabled=true;
                post(all.getAttribute('data-url'),{ids:ids,shop_id:shopAll}).then(function(res){ toast('تم ترحيل الفواتير المقبولة إلى الفرع المحدد بنجاح ('+res.approved+' فاتورة)'+(res.errors&&res.errors.length?' — تخطّي '+res.errors.length:'')); all.disabled=false; reloadList(); }).catch(function(err){all.disabled=false;toast(err&&err.message?err.message:'تعذّر الترحيل الجماعي',false);}); return; }
            /* اعتراض روابط الترقيم → تنقّل بـ AJAX بلا إعادة تحميل كاملة */
            var pg=e.target.closest('#reviewList .pagination a');
            if(pg){ e.preventDefault(); var href=pg.getAttribute('href'); if(!href) return;
                var p=parseInt(new URLSearchParams(new URL(href, location.origin).search).get('page'))||1;
                var nu=new URL(location.href); nu.searchParams.set('page', p); history.pushState({}, '', nu.toString()); reloadList(p); return; }
        });
    });
})();
</script>
@endsection
