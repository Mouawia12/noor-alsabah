@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)

@section('styles')
<style>
    .scan-doc{width:96px;height:120px;margin:0 auto;position:relative;border-radius:8px;background:#fff;
        box-shadow:0 6px 18px rgba(114,57,234,.15);border:1px solid #e6ebf2;overflow:hidden}
    .scan-doc .ln{height:6px;background:#e6ebf2;border-radius:3px;margin:12px 12px 0}
    .scan-doc .ln.s{width:55%}
    .scan-doc .beam{position:absolute;left:0;right:0;height:26px;top:-26px;
        background:linear-gradient(180deg,rgba(114,57,234,0) 0%,rgba(114,57,234,.35) 60%,rgba(114,57,234,.55) 100%);
        animation:scan 1.6s ease-in-out infinite}
    @keyframes scan{0%{top:-26px}100%{top:120px}}
</style>
@endsection

@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">معالجة الملف: {{ $batch->original_filename }}</h3>
            <div class="card-toolbar"><span id="batchStatus" class="badge badge-light-primary fs-6">{{ __('ai.status.'.$batch->status) }}</span></div>
        </div>
        <div class="card-body">
            <div class="progress h-25px mb-5">
                <div id="progressBar" class="progress-bar bg-primary fw-bold progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
            </div>
            <div id="processingMsg" class="text-center py-6 {{ in_array($batch->status, ['completed','failed']) ? 'd-none' : '' }}">
                <div class="scan-doc mb-4">
                    <div class="beam"></div>
                    <div class="ln"></div><div class="ln"></div><div class="ln s"></div><div class="ln"></div><div class="ln s"></div>
                </div>
                <div class="fs-4 fw-bold text-gray-800" id="procTitle">جارٍ قراءة العقود...</div>
                <div class="text-gray-500" id="procSub">المعالجة تتم الآن مباشرةً — أبقِ هذه الصفحة مفتوحة حتى تكتمل.</div>
            </div>

            <div class="row g-4 mb-2">
                <div class="col-4">
                    <div class="bg-light-primary rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-primary" id="totalItems">{{ $batch->total_items }}</div>
                        <div class="fs-7 text-gray-700">إجمالي العقود</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light-success rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-success" id="processedItems">{{ $batch->processed_items }}</div>
                        <div class="fs-7 text-gray-700"><i class="fas fa-check me-1"></i>مقروءة</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light-danger rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-danger" id="failedItems">{{ $batch->failed_items }}</div>
                        <div class="fs-7 text-gray-700"><i class="fas fa-times me-1"></i>فاشلة</div>
                    </div>
                </div>
            </div>

            <div id="errorBox" class="alert alert-danger mt-5 {{ $batch->error_reason ? '' : 'd-none' }}">{{ $batch->error_reason }}</div>
            {{-- بانر النجاح (يظهر فقط عند اكتمال المعالجة) --}}
            <div id="doneBox" class="mt-5 d-none">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle fs-2 me-3"></i>
                    <span><b>تمت معالجة العقود بنجاح</b> — تمّت معالجة <b id="sumTotal">0</b> عقد: <b id="sumAccepted">0</b> جاهز للمراجعة و<b id="sumRejected">0</b> فاشل.</span>
                </div>
                <a href="{{ route('dashboard.rent.ai.review', ['batch_id' => $batch->id]) }}" class="btn btn-success"><i class="fas fa-check-double me-1"></i>مراجعة واعتماد العقود</a>
            </div>

            {{-- بانر الفشل (يظهر فقط عند فشل معالجة الدفعة) مع زر إعادة المحاولة --}}
            <div id="failBox" class="mt-5 d-none">
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-circle-exclamation fs-2 me-3"></i>
                    <span><b>تعذّرت معالجة الملف.</b> <span id="failReason"></span></span>
                </div>
                <form method="POST" action="{{ route('dashboard.rent.ai.batch.reprocess', $batch->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="fas fa-rotate-right me-1"></i>إعادة المحاولة</button>
                </form>
            </div>

            {{-- بانر انقطاع الاتصال / انتهاء المهلة --}}
            <div id="connBox" class="alert alert-warning mt-5 d-none">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-wifi fs-2 me-3"></i>
                    <span id="connMsg"></span>
                </div>
                <button type="button" id="connRetry" class="btn btn-warning d-none"><i class="fas fa-rotate-right me-1"></i>إعادة المحاولة</button>
                <button type="button" id="connReload" class="btn btn-warning d-none"><i class="fas fa-arrows-rotate me-1"></i>تحديث الصفحة</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {
    // معالجة لحظية يقودها المتصفّح: كل نداء POST لـ step يعالج عقداً واحداً (أو يجهّز الصفحات أول مرّة)
    // ويُعيد التقدّم، ونكرّر حتى done=true — بلا اعتماد على عامل طابور خلفي قد يتوقّف.
    const stepUrl = "{{ route('dashboard.rent.ai.batch.step', $batch->id) }}";
    const CSRF = "{{ csrf_token() }}";
    const STATUS_AR = {!! json_encode(__('ai.status'), JSON_UNESCAPED_UNICODE) !!};
    const el = id => document.getElementById(id);
    // STEP_GAP: مهلة قصيرة بين خطوتين، ERR_MS: بعد خطأ اتصال، MAX_NET_ERRORS: سقف الأخطاء المتتالية، MAX_POLLS: سقف كلّي احترازي
    const STEP_GAP = 200, ERR_MS = 4000, MAX_NET_ERRORS = 6, MAX_POLLS = 5000;
    let stopped = false, netErrors = 0, steps = 0;
    // حالة الشريط: يزحف بسلاسة نحو «سقف» العنصر الجاري (capPct) حتى أثناء انتظار الاستخراج الطويل
    let total = 0, doneCount = 0, shownPct = 0, capPct = 8, creepTimer = null, busyTimer = null;
    // إن لم يصل أي ردّ خلال 12ث والإجمالي ما زال 0، فالخادم غالباً مشغول بملف آخر (خادم التطوير أحادي الخيط)
    busyTimer = setTimeout(function(){
        if (total === 0 && !stopped) {
            el('procSub').innerText = 'يبدو أن الخادم مشغول بمعالجة ملف آخر — ستبدأ معالجة هذا الملف تلقائياً عند توفّره. (لتسريعها: أغلق تبويبات المعالجة الأخرى.)';
        }
    }, 12000);

    function setBar(p){ p = Math.max(0, Math.min(100, p)); var b = el('progressBar'); b.style.width = p.toFixed(0) + '%'; b.innerText = p.toFixed(0) + '%'; }
    function startCreep(){
        if (creepTimer || stopped) return;
        creepTimer = setInterval(function(){
            if (stopped) return;
            // زحف أسّي مطمئن نحو سقف العنصر الجاري دون تجاوزه (حركة مستمرّة تُطمئن المستخدم أثناء القراءة)
            if (shownPct < capPct - 0.5) { shownPct += Math.max(0.15, (capPct - shownPct) * 0.02); setBar(shownPct); }
        }, 300);
    }
    function stopCreep(){ if (creepTimer) { clearInterval(creepTimer); creepTimer = null; } }

    function stop() { stopped = true; stopCreep(); }
    function showConn(msg, mode) {
        el('connMsg').innerText = msg;
        el('connRetry').classList.toggle('d-none', mode !== 'retry');
        el('connReload').classList.toggle('d-none', mode !== 'reload');
        el('connBox').classList.remove('d-none');
    }
    function hideConn() { el('connBox').classList.add('d-none'); }
    function next(ms) { if (!stopped) setTimeout(drive, ms); }

    function render(d) {
        el('batchStatus').innerText = STATUS_AR[d.status] || d.status;
        el('totalItems').innerText = d.total_items;
        el('processedItems').innerText = d.processed_items;
        el('failedItems').innerText = d.failed_items;
        total = d.total_items || 0;
        doneCount = (d.processed_items || 0) + (d.failed_items || 0);
        if (total > 0 && busyTimer) { clearTimeout(busyTimer); busyTimer = null; } // وصل التجهيز → أزل تلميح «الخادم مشغول»

        if (d.status === 'failed') {
            stopCreep();
            el('processingMsg').classList.add('d-none');
            el('doneBox').classList.add('d-none');
            el('failReason').innerText = d.error_reason || '';
            el('failBox').classList.remove('d-none');
            return true;
        }
        if (d.status === 'completed') {
            stopCreep(); setBar(100);
            el('processingMsg').classList.add('d-none');
            el('failBox').classList.add('d-none');
            el('sumTotal').innerText = total || doneCount;
            el('sumAccepted').innerText = d.processed_items || 0;
            el('sumRejected').innerText = d.failed_items || 0;
            el('doneBox').classList.remove('d-none');
            return true;
        }
        // قيد المعالجة: اضبط أرضية الشريط (ما أُنجز فعلاً) وسقف العنصر الجاري + نصّاً حيّاً
        if (total > 0) {
            var floor = doneCount / total * 100;
            if (shownPct < floor) { shownPct = floor; }
            // سقف العنصر الجاري، لكن لا نبلغ 100% إلا عند الاكتمال الفعلي (نترك هامشاً)
            capPct = Math.min(95, (Math.min(doneCount + 1, total)) / total * 100);
            var cur = Math.min(doneCount + 1, total);
            el('procTitle').innerText = 'جارٍ قراءة العقود... (' + doneCount + ' من ' + total + ')';
            el('procSub').innerText = 'يقرأ الذكاء الاصطناعي العقد رقم ' + cur + ' من ' + total + ' — قد يستغرق كل عقد لحظات، أبقِ الصفحة مفتوحة.';
        } else {
            capPct = 12; // مرحلة التحضير (تحويل صفحات الملف)
            el('procTitle').innerText = 'جارٍ تجهيز صفحات الملف...';
        }
        if (shownPct > capPct) { shownPct = capPct; }
        setBar(shownPct);
        if (d.error_reason) { const eb = el('errorBox'); eb.classList.remove('d-none'); eb.innerText = d.error_reason; }
        return false;
    }

    function drive() {
        if (stopped) return;
        startCreep();
        if (++steps > MAX_POLLS) {
            stop();
            showConn('استغرقت المعالجة وقتاً أطول من المتوقع. يمكنك تحديث الصفحة للمتابعة.', 'reload');
            return;
        }
        fetch(stepUrl, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => {
                if (res.status === 401 || res.status === 419 || res.redirected) {
                    stop();
                    showConn('انتهت جلستك. يرجى تحديث الصفحة وتسجيل الدخول من جديد لمتابعة الحالة.', 'reload');
                    return null;
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(d => {
                if (!d) return;
                netErrors = 0; hideConn();
                const finished = render(d);
                if (finished || d.done) { stop(); return; }
                next(STEP_GAP);
            })
            .catch(() => {
                netErrors++;
                if (netErrors >= MAX_NET_ERRORS) {
                    stop();
                    showConn('تعذّر الاتصال بالخادم لمتابعة المعالجة. تحقّق من الاتصال ثم أعد المحاولة.', 'retry');
                } else {
                    next(ERR_MS);
                }
            });
    }

    el('connRetry').addEventListener('click', function () {
        netErrors = 0; steps = 0; stopped = false; hideConn(); startCreep(); drive();
    });
    el('connReload').addEventListener('click', function () { location.reload(); });

    window.addEventListener('pagehide', stop);

    drive();
})();
</script>
@endsection
