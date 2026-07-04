@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)

@section('styles')
<style>
    .ai-hero{background:linear-gradient(135deg,#7239ea 0%,#4b1fa8 100%);border-radius:1rem;color:#fff;padding:1.8rem 2rem;position:relative;overflow:hidden}
    .ai-hero:after{content:"";position:absolute;bottom:-60px;left:-30px;width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%}
    .drop-zone{border:2px dashed #cdbdf0;border-radius:1rem;background:#faf8ff;padding:2.6rem 1.5rem;text-align:center;cursor:pointer;transition:all .2s ease}
    .drop-zone:hover{border-color:#7239ea;background:#f4efff}
    .drop-zone.dragover{border-color:#7239ea;background:#efe7ff;transform:scale(1.01)}
    .drop-ico{width:76px;height:76px;border-radius:50%;background:#efe7ff;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;color:#4b1fa8}
    .drop-zone.dragover .drop-ico{animation:pulse 1s infinite}
    @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.12)}}
    .file-chip{display:none;align-items:center;gap:.6rem;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:.6rem;padding:.6rem 1rem;margin-top:1rem}
    .step-badge{width:26px;height:26px;border-radius:50%;background:#7239ea;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;margin-inline-end:.4rem}
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

    <div class="ai-hero mb-6">
        <div class="d-flex align-items-center gap-4 position-relative">
            <i class="fas fa-file-contract fs-3x"></i>
            <div>
                <h1 class="text-white fw-bolder mb-1">رفع عقود الإيجار واستخراجها بالذكاء الاصطناعي</h1>
                <div class="fs-5 opacity-75">اسحب ملف PDF وأفلته هنا، أو انقر للاختيار. يستخرج النظام بيانات كل عقد ويولّد جدول الدفعات تلقائياً بعد اعتمادك.</div>
            </div>
        </div>
    </div>

    <div class="row g-6">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <form id="uploadForm" action="{{ route('dashboard.rent.ai.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="fileInput" name="document" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required>

                        <div class="drop-zone" id="dropZone">
                            <div class="drop-ico"><i class="fas fa-cloud-arrow-up"></i></div>
                            <div class="fs-4 fw-bold text-gray-800 mb-1">أفلت ملف العقود (PDF) هنا</div>
                            <div class="text-gray-500">أو انقر للاختيار — عقد واحد أو عدة عقود في ملف واحد</div>
                            <div class="text-gray-400 fs-8 mt-2">PDF أو JPG أو PNG — بحد أقصى 50 ميغابايت</div>
                        </div>

                        <div class="file-chip" id="fileChip">
                            <i class="fas fa-file-pdf text-danger fs-3"></i>
                            <span class="fw-bold text-gray-800" id="fileName"></span>
                            <span class="text-gray-500 fs-8" id="fileSize"></span>
                            <button type="button" class="btn btn-sm btn-icon btn-light-danger ms-auto" id="fileClear"><i class="fas fa-times"></i></button>
                        </div>

                        @error('document')<div class="text-danger mt-3">{{ $message }}</div>@enderror
                        <div id="upError" class="text-danger mt-3"></div>

                        <div id="uploadProgress" class="mt-5 d-none">
                            <div class="d-flex justify-content-between mb-1"><span class="fw-bold text-gray-700"><i class="fas fa-cloud-arrow-up me-1"></i> جارٍ رفع الملف...</span><span class="fw-bold" id="upPct">0%</span></div>
                            <div class="progress h-15px"><div id="upBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%;background:#7239ea"></div></div>
                            <div class="text-gray-500 fs-8 mt-2"><i class="fas fa-circle-info me-1"></i> بعد اكتمال الرفع تُعالَج العقود <b>في الخلفية</b> — يمكنك مغادرة الصفحة والعودة لاحقاً من «سجل العمليات».</div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-6" id="uploadActions">
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="background:#7239ea;border-color:#7239ea"><i class="fas fa-wand-magic-sparkles me-1"></i> استخراج الآن</button>
                            <a href="{{ route('dashboard.rent.ai.review') }}" class="btn btn-light-primary"><i class="fas fa-list-check me-1"></i> سجل العمليات (المراجعة)</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-6">
                <div class="card-header"><h3 class="card-title">كيف تعمل؟</h3></div>
                <div class="card-body py-4">
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">1</span><span class="text-gray-700">ارفع ملف العقود (يدعم عدة عقود في ملف واحد).</span></div>
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">2</span><span class="text-gray-700">يقرأ الذكاء الاصطناعي كل عقد ويستخرج بياناته.</span></div>
                    <div class="d-flex align-items-start mb-3"><span class="step-badge">3</span><span class="text-gray-700">راجِع العقود واختر المحل المرتبط.</span></div>
                    <div class="d-flex align-items-start"><span class="step-badge">4</span><span class="text-gray-700">عند الاعتماد يُولَّد جدول الدفعات تلقائياً.</span></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">آخر العمليات</h3></div>
                <div class="card-body py-3">
                    @forelse ($batches as $b)
                        <a href="{{ route('dashboard.rent.ai.batch', $b->id) }}" class="d-flex align-items-center justify-content-between py-3 border-bottom text-hover-primary">
                            <span class="text-truncate" style="max-width:55%"><i class="fas fa-file-contract text-gray-500 me-2"></i>{{ $b->original_filename }}</span>
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

    var uploading = false;
    window.addEventListener('beforeunload', function(e){ if(uploading){ e.preventDefault(); e.returnValue=''; return ''; } });

    var progress = document.getElementById('uploadProgress');
    var bar = document.getElementById('upBar');
    var pct = document.getElementById('upPct');
    var actions = document.getElementById('uploadActions');
    var errBox = document.getElementById('upError');

    form.addEventListener('submit', function(e){
        e.preventDefault();
        errBox.textContent='';
        if(!input.files.length){ errBox.textContent='يرجى اختيار ملف أولاً.'; return; }

        uploading = true;
        progress.classList.remove('d-none');
        actions.classList.add('d-none');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = function(ev){
            if(ev.lengthComputable){ var p=Math.round(ev.loaded/ev.total*100); bar.style.width=p+'%'; pct.textContent=p+'%';
                if(p>=100){ pct.textContent='اكتمل الرفع — يبدأ التحضير...'; } }
        };
        xhr.onload = function(){
            uploading = false;
            var res=null; try{ res=JSON.parse(xhr.responseText); }catch(err){}
            if(xhr.status>=200 && xhr.status<300 && res && res.redirect){ window.location.href=res.redirect; return; }
            var msg = (res && (res.message || (res.errors && res.errors.document && res.errors.document[0]))) || 'تعذّر رفع الملف، حاول مجدداً.';
            errBox.textContent = msg;
            progress.classList.add('d-none'); actions.classList.remove('d-none');
        };
        xhr.onerror = function(){ uploading=false; errBox.textContent='انقطع الاتصال أثناء الرفع. تحقّق من الشبكة وحاول مجدداً.';
            progress.classList.add('d-none'); actions.classList.remove('d-none'); };

        xhr.send(new FormData(form));
    });
})();
</script>
@endsection
