@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)

@section('styles')
<style>
    .ai-hero{background:linear-gradient(135deg,#009ef7 0%,#0b56a4 100%);border-radius:1rem;color:#fff;padding:1.8rem 2rem;position:relative;overflow:hidden}
    .ai-hero:after{content:"";position:absolute;bottom:-60px;left:-30px;width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%}
    .drop-zone{border:2px dashed #b9c9e6;border-radius:1rem;background:#f8fbff;padding:2.6rem 1.5rem;text-align:center;cursor:pointer;transition:all .2s ease}
    .drop-zone:hover{border-color:#009ef7;background:#eef6ff}
    .drop-zone.dragover{border-color:#009ef7;background:#e6f1fb;transform:scale(1.01)}
    .drop-ico{width:76px;height:76px;border-radius:50%;background:#e6f1fb;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;color:#0b56a4}
    .drop-zone.dragover .drop-ico{animation:pulse 1s infinite}
    @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.12)}}
    .file-chip{display:none;align-items:center;gap:.6rem;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:.6rem;padding:.6rem 1rem;margin-top:1rem}
    .step-badge{width:26px;height:26px;border-radius:50%;background:#0b56a4;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;margin-inline-end:.4rem}
</style>
@endsection

@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    @if (! $imagickOk)
        <div class="alert alert-warning d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fs-3 me-3"></i>
            <span>بيئة تحويل ملفات PDF إلى صور غير مفعّلة على الخادم بعد. يمكن رفع الملفات الآن وإعادة معالجتها بعد التفعيل.</span>
        </div>
    @endif

    {{-- الترويسة --}}
    <div class="ai-hero mb-6">
        <div class="d-flex align-items-center gap-4 position-relative">
            <i class="fas fa-robot fs-3x"></i>
            <div>
                <h1 class="text-white fw-bolder mb-1">رفع فاتورة واستخراجها بالذكاء الاصطناعي</h1>
                <div class="fs-5 opacity-75">اسحب ملف PDF وأفلته هنا، أو انقر للاختيار. تُقرأ كل فاتورة على حدة وتُستخرج بياناتها وصورتها — ثم رحّلها إلى الفرع.</div>
            </div>
        </div>
    </div>

    <div class="row g-6">
        {{-- منطقة الرفع --}}
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <form id="uploadForm" action="{{ route('dashboard.purchase.ai.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="fileInput" name="document" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required>

                        <div class="drop-zone" id="dropZone">
                            <div class="drop-ico"><i class="fas fa-cloud-arrow-up"></i></div>
                            <div class="fs-4 fw-bold text-gray-800 mb-1">أفلت ملف الفاتورة (PDF) هنا</div>
                            <div class="text-gray-500">أو انقر للاختيار — فاتورة واحدة أو عدة فواتير (حتى أكثر من 100)</div>
                            <div class="text-gray-400 fs-8 mt-2">PDF أو JPG أو PNG — بحد أقصى 50 ميغابايت</div>
                        </div>

                        <div class="file-chip" id="fileChip">
                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                            <span class="fw-bold text-gray-800" id="fileName"></span>
                            <span class="text-gray-500 fs-8" id="fileSize"></span>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger ms-auto" id="fileClear"><i class="fas fa-times"></i></button>
                        </div>

                        @error('document')<div class="text-danger mt-3">{{ $message }}</div>@enderror

                        <div class="d-flex flex-wrap gap-3 mt-6">
                            <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-wand-magic-sparkles me-1"></i> استخراج الآن</button>
                            <a href="{{ route('dashboard.purchase.ai.review') }}" class="btn btn-light-primary"><i class="fas fa-list-check me-1"></i> سجل العمليات (المراجعة)</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- كيف تعمل + آخر العمليات --}}
        <div class="col-lg-5">
            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">كيف تعمل؟</h3></div>
                <div class="card-body py-4">
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">1</span><span class="text-gray-700">ارفع ملف الفواتير (يدعم عشرات الفواتير في ملف واحد).</span></div>
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">2</span><span class="text-gray-700">يقرأ الذكاء الاصطناعي كل فاتورة ويستخرج بياناتها.</span></div>
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">3</span><span class="text-gray-700">تظهر النتائج: مقبولة / مرفوضة مع الأعداد والأسباب.</span></div>
                    <div class="d-flex align-items-start"><span class="step-badge">4</span><span class="text-gray-700">اختر الفرع ورحّل الفواتير المقبولة بنقرة.</span></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">آخر العمليات</h3></div>
                <div class="card-body py-3">
                    @forelse ($batches as $b)
                        <a href="{{ route('dashboard.purchase.ai.batch', $b->id) }}" class="d-flex align-items-center justify-content-between py-3 border-bottom text-hover-primary">
                            <span class="text-truncate" style="max-width:55%"><i class="fas fa-file-invoice text-gray-500 me-2"></i>{{ $b->original_filename }}</span>
                            <span class="d-flex align-items-center gap-3">
                                <span class="text-gray-600 fs-8">{{ $b->processed_items + $b->failed_items }}/{{ $b->total_items }}</span>
                                <span class="badge badge-light-{{ $b->status === 'completed' ? 'success' : ($b->status === 'failed' ? 'danger' : 'primary') }}">{{ __('ai.status.'.$b->status) }}</span>
                            </span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-6"><i class="fas fa-inbox fs-2x d-block mb-2 opacity-50"></i>لا توجد عمليات بعد</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {
    var dz = document.getElementById('dropZone');
    var input = document.getElementById('fileInput');
    var chip = document.getElementById('fileChip');
    var nameEl = document.getElementById('fileName');
    var sizeEl = document.getElementById('fileSize');
    var clearBtn = document.getElementById('fileClear');
    var form = document.getElementById('uploadForm');
    var submitBtn = document.getElementById('submitBtn');
    if (!dz || !input) return;

    function human(bytes){ if(bytes<1024) return bytes+' ب'; if(bytes<1048576) return (bytes/1024).toFixed(0)+' ك.ب'; return (bytes/1048576).toFixed(1)+' م.ب'; }
    function showFile(f){ if(!f){ chip.style.display='none'; dz.style.display='block'; return; }
        nameEl.textContent=f.name; sizeEl.textContent=human(f.size); chip.style.display='flex'; dz.style.display='none'; }

    dz.addEventListener('click', function(){ input.click(); });
    input.addEventListener('change', function(){ showFile(input.files[0]); });
    clearBtn.addEventListener('click', function(){ input.value=''; showFile(null); });

    ['dragenter','dragover'].forEach(function(ev){ dz.addEventListener(ev, function(e){ e.preventDefault(); e.stopPropagation(); dz.classList.add('dragover'); }); });
    ['dragleave','drop'].forEach(function(ev){ dz.addEventListener(ev, function(e){ e.preventDefault(); e.stopPropagation(); dz.classList.remove('dragover'); }); });
    dz.addEventListener('drop', function(e){ var files=e.dataTransfer.files; if(files&&files.length){ input.files=files; showFile(files[0]); } });

    form.addEventListener('submit', function(){
        if(!input.files.length){ return; }
        submitBtn.disabled=true; submitBtn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span> جارٍ الرفع...';
    });
})();
</script>
@endsection
