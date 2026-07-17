@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)

@section('styles')
<style>
    .proc-wrap{max-width:880px;margin-inline:auto}
    .proc-doc{width:78px;height:98px;margin-inline:auto;position:relative;border-radius:10px;background:#fff;
        box-shadow:0 10px 26px rgba(11,86,164,.18);border:1px solid #eef0f4;overflow:hidden}
    .proc-doc .ln{height:6px;background:#eceef4;border-radius:3px;margin:11px 11px 0}
    .proc-doc .ln.s{width:52%}
    .proc-doc .beam{position:absolute;left:0;right:0;height:22px;top:-22px;
        background:linear-gradient(180deg,rgba(0,158,247,0) 0%,rgba(0,158,247,.4) 60%,rgba(0,158,247,.55) 100%);
        animation:scan 1.5s ease-in-out infinite}
    @keyframes scan{0%{top:-22px}100%{top:98px}}
    .proc-pct{font-size:2.6rem;line-height:1;font-weight:800}
    .proc-tile{border:1px solid #eef0f4;border-radius:14px;padding:1.15rem .75rem;text-align:center;height:100%}
    @media (max-width:576px){ .proc-pct{font-size:2rem} }
    @keyframes pop{0%{transform:scale(.6);opacity:0}60%{transform:scale(1.08)}100%{transform:scale(1);opacity:1}}
    .proc-pop{animation:pop .45s ease-out}
</style>
@endsection

@section('content')
<div class="proc-wrap">

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-5 p-lg-8">
            {{-- الترويسة --}}
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-7">
                <div class="me-auto">
                    <div class="text-muted fs-8 text-uppercase mb-1">معالجة ملف الفواتير</div>
                    <h2 class="fw-bold text-gray-900 mb-0" style="word-break:break-word">{{ $batch->original_filename }}</h2>
                </div>
                <span id="batchStatus" class="badge badge-light-primary fs-6 px-4 py-2 flex-shrink-0">{{ __('ai.status.'.$batch->status) }}</span>
            </div>

            {{-- منطقة التقدّم (تختفي عند الاكتمال/الفشل) --}}
            <div id="processingMsg" class="{{ in_array($batch->status, ['completed','failed']) ? 'd-none' : '' }}">
                <div class="d-flex align-items-end justify-content-between mb-2">
                    <div id="procTitle" class="fw-bold fs-4 text-gray-800">جارٍ التحضير...</div>
                    <div id="pctLabel" class="proc-pct text-primary">0%</div>
                </div>
                <div class="progress h-15px rounded-pill mb-2" style="background:#eef1f8">
                    <div id="progressBar" class="progress-bar bg-primary progress-bar-striped progress-bar-animated rounded-pill" role="progressbar" style="width: 0%"></div>
                </div>
                <div id="procSub" class="text-muted fs-7 mb-7">المعالجة تتم الآن مباشرةً — أبقِ هذه الصفحة مفتوحة حتى تكتمل.</div>

                <div class="proc-doc mb-2">
                    <div class="beam"></div>
                    <div class="ln"></div><div class="ln"></div><div class="ln s"></div><div class="ln"></div><div class="ln s"></div>
                </div>
            </div>

            {{-- عدّادات (ريسبونسيف: تتراص عمودياً على الجوال) --}}
            <div class="row g-3 mt-2">
                <div class="col-12 col-sm-4">
                    <div class="proc-tile bg-light-primary">
                        <div class="fs-2 fw-bolder text-primary" id="totalItems">{{ $batch->total_items }}</div>
                        <div class="fs-8 fw-semibold text-gray-700">إجمالي الفواتير</div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="proc-tile bg-light-success">
                        <div class="fs-2 fw-bolder text-success" id="processedItems">{{ $batch->processed_items }}</div>
                        <div class="fs-8 fw-semibold text-gray-700"><i class="fas fa-check me-1"></i>مقبولة</div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="proc-tile bg-light-danger">
                        <div class="fs-2 fw-bolder text-danger" id="failedItems">{{ $batch->failed_items }}</div>
                        <div class="fs-8 fw-semibold text-gray-700"><i class="fas fa-times me-1"></i>مرفوضة</div>
                    </div>
                </div>
            </div>

            <div id="errorBox" class="alert alert-danger mt-5 {{ $batch->error_reason ? '' : 'd-none' }}">{{ $batch->error_reason }}</div>

            {{-- حالة الاكتمال: رسالة نجاح + أزرار الترحيل والمراجعة --}}
            <div id="doneBox" class="d-none mt-7 text-center">
                <div class="proc-pop mb-3"><i class="fas fa-circle-check text-success" style="font-size:3.6rem"></i></div>
                <h2 class="fw-bold text-gray-900 mb-2">تمت معالجة الفواتير بنجاح ✓</h2>
                <div class="text-muted fs-5 mb-6">
                    تمّت معالجة <b class="text-gray-900" id="sumTotal">0</b> فاتورة —
                    <b class="text-success" id="sumAccepted">0</b> مقبولة
                    و<b class="text-danger" id="sumRejected">0</b> مرفوضة.
                </div>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center flex-wrap">
                    <a href="{{ route('dashboard.purchase.ai.review', ['batch_id' => $batch->id]) }}" class="btn btn-success btn-lg"><i class="fas fa-check-double me-2"></i>مراجعة وترحيل المقبولة (<span id="btnAccepted">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.failed') }}" id="btnRejectedLink" class="btn btn-light-danger btn-lg d-none"><i class="fas fa-times-circle me-2"></i>عرض المرفوضة (<span id="btnRejected">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.batch.report', $batch->id) }}" class="btn btn-light-primary btn-lg"><i class="fas fa-list me-2"></i>تقرير حالة كل فاتورة</a>
                </div>
            </div>

            {{-- حالة الفشل: رسالة + إعادة المحاولة --}}
            <div id="failBox" class="d-none mt-7 text-center">
                <div class="mb-3"><i class="fas fa-circle-exclamation text-danger" style="font-size:3.2rem"></i></div>
                <h3 class="fw-bold text-gray-900 mb-2">تعذّرت معالجة الملف</h3>
                <div class="text-muted fs-6 mb-5" id="failReason"></div>
                <div class="d-grid d-sm-flex justify-content-sm-center">
                    <form method="POST" action="{{ route('dashboard.purchase.ai.batch.reprocess', $batch->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-rotate-right me-2"></i>إعادة المحاولة</button>
                    </form>
                </div>
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
    let total = 0, doneCount = 0, shownPct = 0, capPct = 8, creepTimer = null, busyTimer = null;
    // إن لم يصل أي ردّ خلال 12ث والإجمالي ما زال 0، فالخادم غالباً مشغول بملف آخر (خادم التطوير أحادي الخيط)
    busyTimer = setTimeout(function(){
        if (total === 0 && !stopped) {
            el('procSub').innerText = 'يبدو أن الخادم مشغول بمعالجة ملف آخر — ستبدأ معالجة هذا الملف تلقائياً عند توفّره. (لتسريعها: أغلق تبويبات المعالجة الأخرى.)';
        }
    }, 12000);

    function setBar(p){ p = Math.max(0, Math.min(100, p)); el('progressBar').style.width = p.toFixed(0) + '%'; var l = el('pctLabel'); if (l) { l.innerText = p.toFixed(0) + '%'; } }
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
        if (total > 0 && busyTimer) { clearTimeout(busyTimer); busyTimer = null; } // وصل التجهيز → أزل تلميح «الخادم مشغول»

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
