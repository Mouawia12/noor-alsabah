@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)

@section('styles')
<style>
    .ai-review-tbl thead th{ background:#eef3f8 !important; color:#181c32 !important; font-weight:700 !important;
        border-bottom:2px solid #d6e0ea !important; white-space:nowrap; padding:.85rem .75rem; }
    .ai-review-tbl tbody td{ color:#2b2f42; vertical-align:middle; }
    .ai-review-tbl tbody tr:hover{ background:#f7fafd; }
</style>
@endsection

@section('content')

    <div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h3 class="card-title">الفواتير المقبولة بانتظار الترحيل (<span id="reviewCount">{{ $items->total() }}</span>)</h3>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <label class="fw-bold text-gray-700">رحّل إلى الفرع / المحل:</label>
                <select id="shopSelect" class="form-select form-select-sm fw-bold" style="min-width:280px"
                        data-control="select2" data-placeholder="ابحث بالاسم أو الكود...">
                    <option value="">— اختر الفرع —</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->shop_id }}" data-code="{{ $shop->shop_code }}">{{ ($shop->shop_code ? '('.$shop->shop_code.') ' : '').$shop->shop_name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('dashboard.purchase.ai.review.export', array_merge(request()->only('batch_id'), ['format' => 'xlsx'])) }}" class="btn btn-light-success btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
                <a href="{{ route('dashboard.purchase.ai.review.export', array_merge(request()->only('batch_id'), ['format' => 'pdf'])) }}" class="btn btn-light-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                <button type="button" id="approveAllBtn" class="btn btn-success" data-url="{{ route('dashboard.purchase.ai.approve_all') }}">ترحيل الفواتير ✓</button>
                <button type="button" id="deleteSelectedBtn" class="btn btn-light-danger" data-url="{{ route('dashboard.purchase.ai.destroy_many') }}">حذف المحدد 🗑</button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-light-primary py-3 d-flex align-items-center flex-wrap gap-2">
                <i class="fas fa-store fs-3 text-primary"></i>
                <span>الفرع المحدد للترحيل:</span>
                <span id="shopChosen" class="badge badge-danger fs-7">لم يُحدَّد فرع بعد</span>
                <span class="text-gray-600 ms-2">— اختر الفرع أولاً، ثم «ترحيل الفواتير» للكل، أو «ترحيل» لفاتورة مفردة. سيظهر اسم الفرع في رسالة التأكيد.</span>
            </div>
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
        var d=document.createElement('div'); d.className='alert alert-'+(ok===false?'danger':'success')+' shadow'; d.textContent=msg; t.appendChild(d); setTimeout(function(){d.remove();}, ok===false?7000:4000); }
    /** حالة انشغال الزر: تعطيل + سبينر أثناء المعالجة، ثم استعادته. */
    function busy(btn, on){ if(!btn) return;
        if(on){ btn.disabled=true; if(!btn.querySelector('.js-spin')){ var s=document.createElement('span'); s.className='spinner-border spinner-border-sm ms-2 align-middle js-spin'; s.setAttribute('role','status'); s.setAttribute('aria-hidden','true'); btn.appendChild(s); } }
        else { btn.disabled=false; var sp=btn.querySelector('.js-spin'); if(sp){ sp.remove(); } } }
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
    function selectedShopName(){ var s=document.getElementById('shopSelect'); return s&&s.selectedIndex>0 ? s.options[s.selectedIndex].text.trim() : ''; }
    function requireShop(){ var v=selectedShop(); if(!v){ toast('يرجى اختيار الفرع/المحل أولاً.',false); var s=document.getElementById('shopSelect'); if(s) s.focus(); } return v; }
    /** يحدّث مؤشّر الفرع المحدد المرئي. */
    function refreshChosen(){ var el=document.getElementById('shopChosen'); if(!el) return; var name=selectedShopName();
        if(name){ el.textContent=name; el.className='badge badge-success fs-7'; } else { el.textContent='لم يُحدَّد فرع بعد'; el.className='badge badge-danger fs-7'; } }

    ready(function(){
        console.log('[AI-REVIEW] سكربت مراجعة المشتريات محمّل. أزرار:', document.querySelectorAll('.js-approve').length);
        document.addEventListener('click', function(e){
            var a=e.target.closest('.js-approve');
            if(a){ e.preventDefault(); var shop=requireShop(); if(!shop) return; var id=a.closest('tr').getAttribute('data-item'); busy(a,true);
                post(a.getAttribute('data-url'),{shop_id:shop}).then(function(res){toast(res.message);hideModal(id);reloadList();}).catch(function(err){busy(a,false);toast(err&&err.message?err.message:'تعذّر الترحيل',false);}); return; }
            var ma=e.target.closest('.js-modal-approve');
            if(ma){ e.preventDefault(); var shop2=requireShop(); if(!shop2) return; var id2=ma.getAttribute('data-item'); var form=document.querySelector('.js-modal-form[data-item="'+id2+'"]'); var body={shop_id:shop2}; if(form){new FormData(form).forEach(function(v,k){body[k]=v;});} busy(ma,true);
                post(ma.getAttribute('data-url'),body).then(function(res){toast(res.message);hideModal(id2);reloadList();}).catch(function(err){busy(ma,false);toast(err&&err.message?err.message:'تعذّر الترحيل',false);}); return; }
            var rj=e.target.closest('.js-reject');
            if(rj){ e.preventDefault(); if(!confirm('تأكيد رفض هذه الفاتورة؟'))return; var id3=rj.closest('.modal').id.replace('purModal','');
                post(rj.getAttribute('data-url'),{reason:'رُفضت يدوياً'}).then(function(res){toast(res.message);hideModal(id3);reloadList();}).catch(function(){toast('تعذّر الرفض',false);}); return; }
            var all=e.target.closest('#approveAllBtn');
            if(all){ e.preventDefault(); var shopAll=requireShop(); if(!shopAll) return; var shopAllName=selectedShopName();
                var ids=[].map.call(document.querySelectorAll('tr[data-item]'),function(tr){return tr.getAttribute('data-item');});
                if(!ids.length){toast('لا توجد فواتير',false);return;} if(!confirm('ترحيل '+ids.length+' فاتورة إلى فرع «'+shopAllName+'»؟'))return; busy(all,true);
                post(all.getAttribute('data-url'),{ids:ids,shop_id:shopAll}).then(function(res){ busy(all,false); var nm=res.shop_name||shopAllName; var ok=res.approved||0; var errs=res.errors||[];
                    if(ok){ toast('تم ترحيل '+ok+' فاتورة إلى فرع «'+nm+'» بنجاح'+(errs.length?' — فشل '+errs.length:'')); }
                    if(errs.length){ var reasons=errs.map(function(x){return x.msg||'تعذّر الترحيل';}); var uniq=reasons.filter(function(v,i){return reasons.indexOf(v)===i;}); toast('لم تُرحَّل '+errs.length+' فاتورة: '+uniq.slice(0,3).join(' | ')+(uniq.length>3?' …':''), false); }
                    else if(!ok){ toast('لم تُرحَّل أي فاتورة (لا فواتير مؤهّلة للترحيل).', false); }
                    reloadList(); }).catch(function(err){busy(all,false);toast(err&&err.message?err.message:'تعذّر الترحيل الجماعي',false);}); return; }
            /* تحديد الكل / إلغاء تحديد الكل */
            var ca=e.target.closest('#checkAll');
            if(ca){ var checked=ca.checked; document.querySelectorAll('.js-row-check').forEach(function(c){c.checked=checked;}); return; }
            /* حذف فاتورة مفردة من قائمة الانتظار */
            var del=e.target.closest('.js-delete');
            if(del){ e.preventDefault(); if(!confirm('تأكيد حذف هذه الفاتورة من قائمة الانتظار؟'))return; del.disabled=true;
                post(del.getAttribute('data-url'),{}).then(function(res){toast(res.message);reloadList();}).catch(function(err){del.disabled=false;toast(err&&err.message?err.message:'تعذّر الحذف',false);}); return; }
            /* حذف الفواتير المحددة (متعدد) */
            var dsel=e.target.closest('#deleteSelectedBtn');
            if(dsel){ e.preventDefault(); var dids=[].map.call(document.querySelectorAll('.js-row-check:checked'),function(c){return c.value;});
                if(!dids.length){toast('لم تُحدَّد فواتير للحذف',false);return;} if(!confirm('حذف '+dids.length+' فاتورة من قائمة الانتظار؟'))return; dsel.disabled=true;
                post(dsel.getAttribute('data-url'),{ids:dids}).then(function(res){toast(res.message);dsel.disabled=false;reloadList();}).catch(function(err){dsel.disabled=false;toast(err&&err.message?err.message:'تعذّر الحذف',false);}); return; }
            /* اعتراض روابط الترقيم → تنقّل بـ AJAX بلا إعادة تحميل كاملة */
            var pg=e.target.closest('#reviewList .pagination a');
            if(pg){ e.preventDefault(); var href=pg.getAttribute('href'); if(!href) return;
                var p=parseInt(new URLSearchParams(new URL(href, location.origin).search).get('page'))||1;
                var nu=new URL(location.href); nu.searchParams.set('page', p); history.pushState({}, '', nu.toString()); reloadList(p); return; }
        });

        /* مؤشّر الفرع المحدد يتحدّث فور الاختيار */
        var sel=document.getElementById('shopSelect');
        if(sel){ sel.addEventListener('change', refreshChosen); refreshChosen(); }

        /* بحث بالكتابة (بالاسم أو الكود) عبر Select2 إن توفّر */
        if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
            jQuery('#shopSelect').select2({
                placeholder: 'ابحث بالاسم أو الكود...',
                dir: 'rtl', width: '280px', allowClear: true
            }).on('change', refreshChosen);
        }
    });
})();
</script>
@endsection
