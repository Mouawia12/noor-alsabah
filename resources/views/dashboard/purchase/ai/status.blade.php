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
            <div id="doneBox" class="mt-5 d-none">
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle fs-2 me-3"></i>
                    <span>اكتملت المعالجة — <b id="sumAccepted">0</b> فاتورة مقبولة و<b id="sumRejected">0</b> مرفوضة. راجِع المقبولة ورحِّلها إلى الفرع، أو اطّلع على أسباب رفض المرفوضة.</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.purchase.ai.review', ['batch_id' => $batch->id]) }}" class="btn btn-success"><i class="fas fa-check-double me-1"></i>مراجعة وترحيل المقبولة (<span id="btnAccepted">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.failed') }}" id="btnRejectedLink" class="btn btn-light-danger d-none"><i class="fas fa-times-circle me-1"></i>عرض المرفوضة (<span id="btnRejected">0</span>)</a>
                    <a href="{{ route('dashboard.purchase.ai.batch.report', $batch->id) }}" class="btn btn-light-primary"><i class="fas fa-list me-1"></i>تقرير حالة كل فاتورة</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {
    const url = "{{ route('dashboard.purchase.ai.batch.json', $batch->id) }}";
    const STATUS_AR = {!! json_encode(__('ai.status'), JSON_UNESCAPED_UNICODE) !!};
    const reviewBtn = document.getElementById('doneBox');
    function render(d) {
        document.getElementById('batchStatus').innerText = STATUS_AR[d.status] || d.status;
        document.getElementById('totalItems').innerText = d.total_items;
        document.getElementById('processedItems').innerText = d.processed_items;
        document.getElementById('failedItems').innerText = d.failed_items;
        const total = d.total_items || 0;
        const done = (d.processed_items || 0) + (d.failed_items || 0);
        const pct = total > 0 ? Math.round(done / total * 100) : (d.status === 'completed' ? 100 : 0);
        const bar = document.getElementById('progressBar');
        bar.style.width = pct + '%';
        bar.innerText = pct + '%';
        if (d.error_reason) {
            const eb = document.getElementById('errorBox');
            eb.classList.remove('d-none'); eb.innerText = d.error_reason;
        }
        if (d.status === 'completed' || d.status === 'failed') {
            var pm = document.getElementById('processingMsg'); if (pm) pm.classList.add('d-none');
            var accepted = d.processed_items || 0, rejected = d.failed_items || 0;
            document.getElementById('sumAccepted').innerText = accepted;
            document.getElementById('sumRejected').innerText = rejected;
            document.getElementById('btnAccepted').innerText = accepted;
            document.getElementById('btnRejected').innerText = rejected;
            // زر المرفوضة يظهر فقط عند وجود مرفوضة
            var rl = document.getElementById('btnRejectedLink');
            if (rejected > 0) { rl.classList.remove('d-none'); } else { rl.classList.add('d-none'); }
            if (done > 0) reviewBtn.classList.remove('d-none');
            return true; // توقف
        }
        return false;
    }
    function poll() {
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(d => { if (!render(d)) setTimeout(poll, 3000); })
            .catch(() => setTimeout(poll, 5000));
    }
    poll();
})();
</script>
@endsection
