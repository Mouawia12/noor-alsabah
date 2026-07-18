@extends('layouts.app')
@section('module', 'إعدادات النظام')
@section('sub', 'الذكاء الاصطناعي')
@section('title', $page_title)

@section('content')
<div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

@if (session()->has('alert.success'))
    <div class="alert alert-success">{{ session('alert.success') }}</div>
@endif

<div style="max-width:960px;margin-inline:auto">
    <form method="POST" action="{{ route('dashboard.settings.update') }}">
        @csrf
        <div class="card shadow-sm mb-5">
            <div class="card-header">
                <h3 class="card-title fw-bold"><i class="fas fa-key text-primary me-2"></i>مفاتيح الـ API وإعدادات التكامل</h3>
                <div class="card-toolbar">
                    <button type="button" id="testBtn" class="btn btn-sm btn-light-primary" data-url="{{ route('dashboard.settings.test') }}"><i class="fas fa-plug me-1"></i>فحص الاتصال</button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-light-primary border border-primary border-dashed">
                    هذه الإعدادات تُخزَّن في قاعدة البيانات وتتجاوز قيم <code>.env</code>. اترك حقل المفتاح السري فارغاً للإبقاء على القيمة الحالية. مخصّص لمدير النظام فقط.
                </div>

                {{-- اختيار المزوّد النشط --}}
                <div class="row g-5 mb-8">
                    <div class="col-md-6">
                        <label class="form-label fw-bold fs-5">المزوّد النشط للاستخراج</label>
                        <select name="ai_engine" class="form-select form-select-solid fw-bold">
                            <option value="openai" @selected($engine === 'openai')>OpenAI (ChatGPT)</option>
                            <option value="gemini" @selected($engine === 'gemini')>Google Gemini</option>
                        </select>
                        <div class="form-text">المزوّد الذي يُستخدم فعليّاً في قراءة الفواتير والعقود.</div>
                    </div>
                </div>

                {{-- OpenAI --}}
                <h4 class="fw-bold text-gray-800 border-bottom pb-2 mb-5"><i class="fas fa-robot text-success me-2"></i>OpenAI (ChatGPT)</h4>
                <div class="row g-5 mb-8">
                    <div class="col-md-6">
                        <label class="form-label d-flex justify-content-between">
                            <span class="fw-bold">مفتاح OpenAI API</span>
                            @if($hasOpenaiKey)<span class="badge badge-light-success">محفوظ ✓</span>@else<span class="badge badge-light-danger">غير مضبوط</span>@endif
                        </label>
                        <input type="password" name="openai_api_key" class="form-control form-control-solid" placeholder="sk-... (اتركه فارغاً للإبقاء)" autocomplete="off">
                        <div class="form-text">OPENAI_API_KEY</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">النموذج الأساسي</label>
                        <input type="text" name="openai_model" value="{{ $s['openai_model'] ?? config('ai.openai.model') }}" class="form-control form-control-solid" placeholder="gpt-5-mini">
                        <div class="form-text">openai_model</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">نموذج التصعيد</label>
                        <input type="text" name="openai_model_heavy" value="{{ $s['openai_model_heavy'] ?? config('ai.openai.model_heavy') }}" class="form-control form-control-solid" placeholder="gpt-5.5">
                        <div class="form-text">عند انخفاض الثقة</div>
                    </div>
                </div>

                {{-- Gemini --}}
                <h4 class="fw-bold text-gray-800 border-bottom pb-2 mb-5"><i class="fas fa-star text-primary me-2"></i>Google Gemini</h4>
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="form-label d-flex justify-content-between">
                            <span class="fw-bold">مفتاح Gemini API</span>
                            @if($hasGeminiKey)<span class="badge badge-light-success">محفوظ ✓</span>@else<span class="badge badge-light-danger">غير مضبوط</span>@endif
                        </label>
                        <input type="password" name="gemini_api_key" class="form-control form-control-solid" placeholder="AIza... (اتركه فارغاً للإبقاء)" autocomplete="off">
                        <div class="form-text">GEMINI_API_KEY — من Google AI Studio</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">النموذج الأساسي</label>
                        <input type="text" name="gemini_model" value="{{ $s['gemini_model'] ?? config('ai.gemini.model') }}" class="form-control form-control-solid" placeholder="gemini-2.0-flash">
                        <div class="form-text">gemini_model</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">نموذج التصعيد</label>
                        <input type="text" name="gemini_model_heavy" value="{{ $s['gemini_model_heavy'] ?? config('ai.gemini.model_heavy') }}" class="form-control form-control-solid" placeholder="gemini-2.5-pro">
                        <div class="form-text">عند انخفاض الثقة</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">المهلة (ثانية)</label>
                        <input type="number" name="gemini_timeout" value="{{ $s['gemini_timeout'] ?? config('ai.gemini.timeout') }}" class="form-control form-control-solid" placeholder="120">
                        <div class="form-text">gemini_timeout</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>حفظ الإعدادات</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(function () {
    'use strict';
    var CSRF = '{{ csrf_token() }}';
    function toast(msg, ok){ var t=document.getElementById('toaster'); if(!t) return alert(msg);
        var d=document.createElement('div'); d.className='alert alert-'+(ok===false?'danger':'success')+' shadow'; d.textContent=msg; t.appendChild(d); setTimeout(function(){d.remove();},5000); }
    var btn = document.getElementById('testBtn');
    if (btn) btn.addEventListener('click', function(){
        btn.disabled = true;
        fetch(btn.getAttribute('data-url'), { method:'POST', credentials:'same-origin',
            headers:{ 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' } })
            .then(function(r){ return r.json(); })
            .then(function(d){ toast(d.message, d.ok); })
            .catch(function(){ toast('تعذّر إجراء الفحص', false); })
            .finally(function(){ btn.disabled = false; });
    });
})();
</script>
@endsection
