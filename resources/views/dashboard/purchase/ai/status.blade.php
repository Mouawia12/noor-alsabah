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
                <div id="progressBar" class="progress-bar bg-primary fw-bold" role="progressbar" style="width: 0%">0%</div>
            </div>
            <div id="processingMsg" class="text-center py-6 {{ in_array($batch->status, ['completed','failed']) ? 'd-none' : '' }}">
                <div class="scan-doc mb-4">
                    <div class="beam"></div>
                    <div class="ln"></div><div class="ln"></div><div class="ln s"></div><div class="ln"></div><div class="ln s"></div>
                </div>
                <div class="fs-4 fw-bold text-gray-800">جارٍ قراءة الفواتير...</div>
                <div class="text-gray-500">الذكاء الاصطناعي يستخرج البيانات، يرجى الانتظار قليلاً (تُعالَج في الخلفية)</div>
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
            <div id="stuckWarn" class="alert alert-warning mt-5 d-none">
                <i class="fas fa-triangle-exclamation me-2"></i>
                لم يبدأ التقدّم منذ فترة. غالباً عامل المعالجة (queue worker) متوقّف على الخادم — يرجى إبلاغ مسؤول النظام لتشغيله. سيُستأنف تلقائياً عند تشغيله.
            </div>
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
    const url = "{{ route('dashboard.purchase.ai.batch.json', $batch->id) }}";
    const STATUS_AR = {!! json_encode(__('ai.status'), JSON_UNESCAPED_UNICODE) !!};
    // POLL_MS: فترة الاستطلاع، ERR_MS: بعد فشل مؤقت، MAX_NET_ERRORS: سقف أخطاء الاتصال المتتالية، MAX_POLLS: سقف كلّي (≈10 دقائق)
    const POLL_MS = 3000, ERR_MS = 5000, MAX_NET_ERRORS = 5, MAX_POLLS = 200;
    const el = id => document.getElementById(id);

    let timer = null, stopped = false, netErrors = 0, polls = 0;
    function schedule(ms) { if (stopped) return; clearTimeout(timer); timer = setTimeout(poll, ms); }
    function stop() { stopped = true; clearTimeout(timer); }

    // بانر الاتصال/المهلة: mode='retry' يستأنف الاستطلاع، mode='reload' يعيد تحميل الصفحة
    function showConn(msg, mode) {
        el('connMsg').innerText = msg;
        el('connRetry').classList.toggle('d-none', mode !== 'retry');
        el('connReload').classList.toggle('d-none', mode !== 'reload');
        el('connBox').classList.remove('d-none');
    }
    function hideConn() { el('connBox').classList.add('d-none'); }

    function render(d) {
        el('batchStatus').innerText = STATUS_AR[d.status] || d.status;
        el('totalItems').innerText = d.total_items;
        el('processedItems').innerText = d.processed_items;
        el('failedItems').innerText = d.failed_items;
        const total = d.total_items || 0;
        const done = (d.processed_items || 0) + (d.failed_items || 0);
        const pct = total > 0 ? Math.round(done / total * 100) : (d.status === 'completed' ? 100 : 0);
        const bar = el('progressBar');
        bar.style.width = pct + '%'; bar.innerText = pct + '%';

        // فشل الدفعة: بانر خطأ أحمر مميّز + زر إعادة المحاولة (لا رسالة نجاح)
        if (d.status === 'failed') {
            el('processingMsg').classList.add('d-none');
            el('doneBox').classList.add('d-none');
            el('failReason').innerText = d.error_reason || '';
            el('failBox').classList.remove('d-none');
            return true; // توقف
        }
        // اكتمال المعالجة: بانر نجاح واضح بالعدد + أزرار النتائج
        if (d.status === 'completed') {
            el('processingMsg').classList.add('d-none');
            el('failBox').classList.add('d-none');
            var accepted = d.processed_items || 0, rejected = d.failed_items || 0;
            el('sumTotal').innerText = total || done;
            el('sumAccepted').innerText = accepted;
            el('sumRejected').innerText = rejected;
            el('btnAccepted').innerText = accepted;
            el('btnRejected').innerText = rejected;
            // زر المرفوضة يظهر فقط عند وجود مرفوضة
            if (rejected > 0) { el('btnRejectedLink').classList.remove('d-none'); } else { el('btnRejectedLink').classList.add('d-none'); }
            el('doneBox').classList.remove('d-none');
            return true; // توقف
        }
        // ما زالت قيد المعالجة: أظهر أي سبب/تحذير جزئي إن وُجد
        if (d.error_reason) { const eb = el('errorBox'); eb.classList.remove('d-none'); eb.innerText = d.error_reason; }
        return false;
    }

    // كشف التوقّف: إن لم يتقدّم شيء لفترة (≈24ث) فغالباً عامل الطابور متوقّف
    var lastDone = -1, stalls = 0;
    function checkStall(d) {
        var done = (d.processed_items || 0) + (d.failed_items || 0);
        if (done !== lastDone) { lastDone = done; stalls = 0; el('stuckWarn').classList.add('d-none'); return; }
        if (d.status === 'pending' || d.status === 'processing') {
            stalls++;
            if (stalls >= 8) el('stuckWarn').classList.remove('d-none');
        }
    }

    function poll() {
        if (stopped) return;
        // مهلة كلّية: بدل الدوران للأبد نوقف ونبلّغ المستخدم
        if (++polls > MAX_POLLS) {
            stop();
            showConn('استغرقت المعالجة وقتاً أطول من المتوقع. قد يكون عامل المعالجة متوقّفاً — يمكنك تحديث الصفحة للمتابعة لاحقاً.', 'reload');
            return;
        }
        fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
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
                checkStall(d);
                if (!render(d)) schedule(POLL_MS);
            })
            .catch(() => {
                // فشل شبكة/خادم/JSON: نعيد المحاولة قليلاً ثم نُظهر بانراً واضحاً بدل الدوران الصامت
                netErrors++;
                if (netErrors >= MAX_NET_ERRORS) {
                    stop();
                    showConn('تعذّر الاتصال بالخادم لتحديث حالة المعالجة. تحقّق من الاتصال ثم أعد المحاولة.', 'retry');
                } else {
                    schedule(ERR_MS);
                }
            });
    }

    // زر «إعادة المحاولة» في بانر الاتصال: يصفّر العدّادات ويستأنف
    el('connRetry').addEventListener('click', function () {
        netErrors = 0; polls = 0; stopped = false; hideConn(); poll();
    });
    el('connReload').addEventListener('click', function () { location.reload(); });

    // تنظيف المؤقّت عند مغادرة الصفحة، وإيقاف/استئناف عند إخفاء التبويب
    window.addEventListener('pagehide', stop);
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) { clearTimeout(timer); }
        else if (!stopped) { schedule(0); }
    });

    poll();
})();
</script>
@endsection
