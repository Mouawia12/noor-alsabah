@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)

@section('styles')
<style>
    .scan-doc{width:96px;height:120px;margin:0 auto;position:relative;border-radius:8px;background:#fff;
        box-shadow:0 6px 18px rgba(11,86,164,.15);border:1px solid #e6ebf2;overflow:hidden}
    .scan-doc .ln{height:6px;background:#e6ebf2;border-radius:3px;margin:12px 12px 0}
    .scan-doc .ln.s{width:55%}
    .scan-doc .beam{position:absolute;left:0;right:0;height:26px;top:-26px;
        background:linear-gradient(180deg,rgba(0,158,247,0) 0%,rgba(0,158,247,.35) 60%,rgba(0,158,247,.55) 100%);
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
            <div class="card-toolbar">
                <span id="batchStatus" class="badge badge-light-primary fs-6">{{ __('ai.status.'.$batch->status) }}</span>
            </div>
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
                <div class="fs-4 fw-bold text-gray-800" id="procTitle">جارٍ قراءة الفواتير...</div>
                <div class="text-gray-500" id="procSub">المعالجة تتم الآن مباشرةً — أبقِ هذه الصفحة مفتوحة حتى تكتمل.</div>
            </div>

            {{-- عدّادات واضحة: الإجمالي / مقبولة / مرفوضة --}}
            <div class="row g-4 mb-2">
                <div class="col-4">
                    <div class="bg-light-primary rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-primary" id="totalItems">{{ $batch->total_items }}</div>
                        <div class="fs-7 text-gray-700">إجمالي الفواتير</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light-success rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-success" id="processedItems">{{ $batch->processed_items }}</div>
                        <div class="fs-7 text-gray-700"><i class="fas fa-check me-1"></i>مقبولة</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-light-danger rounded p-4 text-center">
                        <div class="fs-2 fw-bolder text-danger" id="failedItems">{{ $batch->failed_items }}</div>
                        <div class="fs-7 text-gray-700"><i class="fas fa-times me-1"></i>مرفوضة</div>
                    </div>
                </div>
            </div>

            <div id="errorBox" class="alert alert-danger mt-5 {{ $batch->error_reason ? '' : 'd-none' }}">{{ $batch->error_reason }}</div>
            {{-- بانر النجاح (يظهر فقط عند اكتمال المعالجة) --}}
            <div id="doneBox" class="mt-5 d-none">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle fs-2 me-3"></i>
                    <span><b>تمت معالجة الفواتير بنجاح</b> — تمّت معالجة <b id="sumTotal">0</b> فاتورة: <b id="sumAccepted">0</b> مقبولة و<b id="sumRejected">0</b> مرفوضة. راجِع المقبولة ورحِّلها إلى الفرع، أو اطّلع على أسباب رفض المرفوضة.</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.purchase.ai.review', ['batch_id' => $batch->id]) }}" class="btn btn-success"><i class="fas fa-check-double me-1"></i>مراجعة وترحيل المقبولة (<span id="btnAccepted">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.failed') }}" id="btnRejectedLink" class="btn btn-light-danger d-none"><i class="fas fa-times-circle me-1"></i>عرض المرفوضة (<span id="btnRejected">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.batch.report', $batch->id) }}" class="btn btn-light-primary"><i class="fas fa-list me-1"></i>تقرير حالة كل فاتورة</a>
                </div>
            </div>

            {{-- بانر الفشل (يظهر فقط عند فشل معالجة الدفعة) مع زر إعادة المحاولة --}}
            <div id="failBox" class="mt-5 d-none">
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-circle-exclamation fs-2 me-3"></i>
                    <span><b>تعذّرت معالجة الملف.</b> <span id="failReason"></span></span>
                </div>
                <form method="POST" action="{{ route('dashboard.purchase.ai.batch.reprocess', $batch->id) }}" class="d-inline">
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
    // معالجة لحظية يقودها المتصفّح: كل نداء POST لـ step يعالج فاتورة واحدة (أو يجهّز الصفحات أول مرّة)
    // ويُعيد التقدّم، ونكرّر حتى done=true — بلا اعتماد على عامل طابور خلفي قد يتوقّف.
    const stepUrl = "{{ route('dashboard.purchase.ai.batch.step', $batch->id) }}";
    const CSRF = "{{ csrf_token() }}";
    const STATUS_AR = {!! json_encode(__('ai.status'), JSON_UNESCAPED_UNICODE) !!};
    const el = id => document.getElementById(id);
    // STEP_GAP: مهلة قصيرة بين خطوتين لإراحة الواجهة، ERR_MS: بعد خطأ اتصال، MAX_NET_ERRORS: سقف الأخطاء المتتالية، MAX_POLLS: سقف كلّي للخطوات
    const STEP_GAP = 200, ERR_MS = 4000, MAX_NET_ERRORS = 6, MAX_POLLS = 5000;
    let stopped = false, netErrors = 0, steps = 0;
    // حالة الشريط: يزحف بسلاسة نحو «سقف» الفاتورة الجارية (capPct) حتى أثناء انتظار الاستخراج الطويل
    let total = 0, doneCount = 0, shownPct = 0, capPct = 8, creepTimer = null;

    function setBar(p){ p = Math.max(0, Math.min(100, p)); var b = el('progressBar'); b.style.width = p.toFixed(0) + '%'; b.innerText = p.toFixed(0) + '%'; }
    function startCreep(){
        if (creepTimer || stopped) return;
        creepTimer = setInterval(function(){
            if (stopped) return;
            // زحف أسّي مطمئن نحو سقف الفاتورة الجارية دون تجاوزه (حركة مستمرّة أثناء القراءة)
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

        // فشل الدفعة: بانر خطأ أحمر + زر إعادة المحاولة
        if (d.status === 'failed') {
            stopCreep();
            el('processingMsg').classList.add('d-none');
            el('doneBox').classList.add('d-none');
            el('failReason').innerText = d.error_reason || '';
            el('failBox').classList.remove('d-none');
            return true;
        }
        // اكتمال المعالجة: بانر نجاح واضح بالعدد + أزرار النتائج
        if (d.status === 'completed') {
            stopCreep(); setBar(100);
            el('processingMsg').classList.add('d-none');
            el('failBox').classList.add('d-none');
            var accepted = d.processed_items || 0, rejected = d.failed_items || 0;
            el('sumTotal').innerText = total || doneCount;
            el('sumAccepted').innerText = accepted;
            el('sumRejected').innerText = rejected;
            el('btnAccepted').innerText = accepted;
            el('btnRejected').innerText = rejected;
            if (rejected > 0) { el('btnRejectedLink').classList.remove('d-none'); } else { el('btnRejectedLink').classList.add('d-none'); }
            el('doneBox').classList.remove('d-none');
            return true;
        }
        // قيد المعالجة: أرضية الشريط (ما أُنجز فعلاً) + سقف الفاتورة الجارية + نصّ حيّ
        if (total > 0) {
            var floor = doneCount / total * 100;
            if (shownPct < floor) { shownPct = floor; }
            // سقف الفاتورة الجارية، لكن لا نبلغ 100% إلا عند الاكتمال الفعلي (نترك هامشاً)
            capPct = Math.min(95, (Math.min(doneCount + 1, total)) / total * 100);
            var cur = Math.min(doneCount + 1, total);
            el('procTitle').innerText = 'جارٍ قراءة الفواتير... (' + doneCount + ' من ' + total + ')';
            el('procSub').innerText = 'يقرأ الذكاء الاصطناعي الفاتورة رقم ' + cur + ' من ' + total + ' — قد تستغرق كل فاتورة لحظات، أبقِ الصفحة مفتوحة.';
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
        // سقف كلّي احترازي: بدل الدوران للأبد نوقف ونبلّغ المستخدم
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
                // انتهاء الجلسة / تحويل لتسجيل الدخول: لا نُبقي المستخدم عالقاً
                if (res.status === 401 || res.status === 419 || res.redirected) {
                    stop();
                    showConn('انتهت جلستك. يرجى تحديث الصفحة وتسجيل الدخول من جديد لمتابعة الحالة.', 'reload');
                    return null;
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(d => {
                if (!d) return; // عولجت حالة الجلسة أعلاه
                netErrors = 0; hideConn();
                const finished = render(d);
                if (finished || d.done) { stop(); return; }
                next(STEP_GAP); // تابع الفاتورة التالية فوراً
            })
            .catch(() => {
                // خطأ شبكة/خادم: نعيد المحاولة قليلاً ثم نُظهر بانراً واضحاً بدل الدوران الصامت
                netErrors++;
                if (netErrors >= MAX_NET_ERRORS) {
                    stop();
                    showConn('تعذّر الاتصال بالخادم لمتابعة المعالجة. تحقّق من الاتصال ثم أعد المحاولة.', 'retry');
                } else {
                    next(ERR_MS);
                }
            });
    }

    // زر «إعادة المحاولة» في بانر الاتصال: يصفّر ويستأنف من حيث توقّف (العناصر المُعالَجة لا تُعاد)
    el('connRetry').addEventListener('click', function () {
        netErrors = 0; steps = 0; stopped = false; hideConn(); startCreep(); drive();
    });
    el('connReload').addEventListener('click', function () { location.reload(); });

    // تنظيف عند مغادرة الصفحة، وإيقاف/استئناف عند إخفاء/إظهار التبويب
    window.addEventListener('pagehide', stop);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden && !stopped) { /* التبويب عاد للواجهة — لا شيء إضافي، الحلقة مستمرة */ }
    });

    drive();
})();
</script>
@endsection
