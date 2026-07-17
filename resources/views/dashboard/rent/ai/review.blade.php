@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
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
            <h3 class="card-title">العقود بانتظار المراجعة (<span id="reviewCount">{{ $items->total() }}</span>)</h3>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('dashboard.rent.ai.review.export', array_merge(request()->only('batch_id'), ['format' => 'xlsx'])) }}" class="btn btn-light-success btn-sm"><i class="fas fa-file-excel me-1"></i>Excel</a>
                <a href="{{ route('dashboard.rent.ai.review.export', array_merge(request()->only('batch_id'), ['format' => 'pdf'])) }}" class="btn btn-light-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                <button type="button" id="approveAllBtn" class="btn btn-success" data-url="{{ route('dashboard.rent.ai.approve_all') }}">اعتماد الكل ✓</button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-light-primary py-2">اختر المحل لكل عقد، ثم اضغط «اعتماد» للصف أو «اعتماد الكل». للتعديل والاطلاع على الصورة اضغط «مراجعة/تعديل».</div>
            <div id="reviewList">
                @include('dashboard.rent.ai._review_list', ['items' => $items, 'shops' => $shops])
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

    function toast(msg, ok) {
        var t = document.getElementById('toaster'); if(!t) return alert(msg);
        var d = document.createElement('div');
        d.className = 'alert alert-' + (ok === false ? 'danger' : 'success') + ' shadow';
        d.textContent = msg; t.appendChild(d);
        setTimeout(function(){ d.remove(); }, 4000);
    }
    var PER_PAGE = 20;
    function hideModal(id){
        var m = document.getElementById('rentModal'+id);
        if (m) {
            if (window.bootstrap && bootstrap.Modal) { var i = bootstrap.Modal.getInstance(m); if(i){i.hide();} }
            else { var btn = m.querySelector('[data-bs-dismiss="modal"]'); if(btn) btn.click(); }
        }
        /* إزالة أي تعتيم/قفل تمرير عالق قبل استبدال محتوى الجدول */
        document.querySelectorAll('.modal-backdrop').forEach(function(b){ b.remove(); });
        document.body.classList.remove('modal-open'); document.body.style.removeProperty('padding-right');
    }
    function currentPage(){ var p = parseInt(new URLSearchParams(location.search).get('page')); return p>0 ? p : 1; }
    /** يعيد جلب الصفحة الحالية من الخادم ويستبدل أسطر الجدول + العدّاد + الترقيم. */
    function reloadList(page){
        page = page || currentPage();
        var u = new URL(location.href); u.searchParams.set('page', page);
        return fetch(u.toString(), { credentials:'same-origin',
            headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'} })
            .then(function(r){ return r.json(); })
            .then(function(data){
                /* صفحة صارت فارغة بعد آخر اعتماد → ارجع لصفحة سابقة */
                if (page > 1 && data.count <= (page - 1) * PER_PAGE) {
                    var prev = page - 1;
                    var nu = new URL(location.href); nu.searchParams.set('page', prev);
                    history.replaceState({}, '', nu.toString());
                    return reloadList(prev);
                }
                var list = document.getElementById('reviewList'); if(list) list.innerHTML = data.html;
                var c = document.getElementById('reviewCount'); if(c) c.textContent = data.count;
                initRowSelects();
            })
            .catch(function(){ location.reload(); });
    }
    /* بحث بالكتابة (بالاسم أو الكود) على قوائم المحل في كل صف عبر Select2.
       مهم: dropdownParent=body حتى لا تُقصّ قائمة الخيارات داخل جدول ذي overflow (كانت تظهر فارغة). */
    function initRowSelects(){
        if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
            jQuery('.row-shop').each(function(){
                var $s = jQuery(this);
                if ($s.hasClass('select2-hidden-accessible')) { $s.select2('destroy'); }
                $s.select2({
                    placeholder: 'ابحث بالاسم أو الكود...',
                    dir: 'rtl',
                    width: '100%',
                    allowClear: true,
                    dropdownParent: jQuery('body')
                });
            });
        }
    }
    function rowShop(id){
        var tr = document.querySelector('tr[data-item="'+id+'"]'); if(!tr) return '';
        var s = tr.querySelector('.row-shop'); return s ? s.value : '';
    }
    function post(url, body){
        return fetch(url, { method:'POST', credentials:'same-origin',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Accept':'application/json','Content-Type':'application/json'},
            body: JSON.stringify(body||{}) })
            .then(function(r){ return r.text().then(function(t){ var j; try{j=JSON.parse(t);}catch(e){j=null;} if(!r.ok||!j) throw (j||{status:r.status}); return j; }); });
    }

    ready(function(){
        console.log('[AI-REVIEW] تم تحميل سكربت المراجعة. أزرار:', document.querySelectorAll('.js-approve').length);
        initRowSelects();

        document.addEventListener('click', function(e){
            var a = e.target.closest('.js-approve');
            if (a){ e.preventDefault();
                var tr=a.closest('tr'), id=tr.getAttribute('data-item'), shop=rowShop(id);
                if(!shop){ toast('اختر المحل/العقار أولاً', false); return; }
                a.disabled=true;
                post(a.getAttribute('data-url'), {shop_id:shop})
                    .then(function(res){ toast(res.message); hideModal(id); reloadList(); })
                    .catch(function(err){ a.disabled=false; toast(err && err.message ? err.message : 'تعذّر الاعتماد', false); });
                return;
            }
            var ma = e.target.closest('.js-modal-approve');
            if (ma){ e.preventDefault();
                var id2=ma.getAttribute('data-item'), shop2=rowShop(id2);
                if(!shop2){ toast('اختر المحل/العقار من الجدول أولاً', false); return; }
                var form=document.querySelector('.js-modal-form[data-item="'+id2+'"]'), body={shop_id:shop2};
                if(form){ new FormData(form).forEach(function(v,k){ body[k]=v; }); }
                ma.disabled=true;
                post(ma.getAttribute('data-url'), body)
                    .then(function(res){ toast(res.message); hideModal(id2); reloadList(); })
                    .catch(function(err){ ma.disabled=false; toast(err && err.message ? err.message : 'تعذّر الاعتماد', false); });
                return;
            }
            var rj = e.target.closest('.js-reject');
            if (rj){ e.preventDefault();
                if(!confirm('تأكيد رفض هذا العقد؟')) return;
                var id3=rj.closest('.modal').id.replace('rentModal','');
                post(rj.getAttribute('data-url'), {reason:'رُفض يدوياً'})
                    .then(function(res){ toast(res.message); hideModal(id3); reloadList(); })
                    .catch(function(){ toast('تعذّر الرفض', false); });
                return;
            }
            var all = e.target.closest('#approveAllBtn');
            if (all){ e.preventDefault();
                var rows=[].map.call(document.querySelectorAll('tr[data-item]'), function(tr){ return {id:tr.getAttribute('data-item'), shop_id:(tr.querySelector('.row-shop')||{}).value||''}; });
                if(!rows.length){ toast('لا توجد عقود', false); return; }
                var missing=rows.filter(function(r){return !r.shop_id;}).length;
                if(missing && !confirm('هناك '+missing+' عقد بلا محل مختار سيُتجاوز. متابعة؟')) return;
                all.disabled=true;
                post(all.getAttribute('data-url'), {items:rows})
                    .then(function(res){
                        toast('تم اعتماد '+res.approved+' عقد'+(res.errors&&res.errors.length?' (تخطّي '+res.errors.length+')':''));
                        all.disabled=false; reloadList();
                    })
                    .catch(function(){ all.disabled=false; toast('تعذّر الاعتماد الجماعي', false); });
                return;
            }
            /* اعتراض روابط الترقيم → تنقّل بـ AJAX بلا إعادة تحميل كاملة */
            var pg = e.target.closest('#reviewList .pagination a');
            if (pg){ e.preventDefault();
                var href = pg.getAttribute('href'); if(!href) return;
                var p = parseInt(new URLSearchParams(new URL(href, location.origin).search).get('page')) || 1;
                var nu = new URL(location.href); nu.searchParams.set('page', p);
                history.pushState({}, '', nu.toString());
                reloadList(p);
                return;
            }
        });
    });
})();
</script>
@endsection
