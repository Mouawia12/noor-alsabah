@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">معالجة الملف: {{ $batch->original_filename }}</h3>
            <div class="card-toolbar"><span id="batchStatus" class="badge badge-light-primary fs-6">{{ $batch->status }}</span></div>
        </div>
        <div class="card-body">
            <div class="progress h-25px mb-5">
                <div id="progressBar" class="progress-bar bg-primary fw-bold" role="progressbar" style="width: 0%">0%</div>
            </div>
            <div class="d-flex gap-10 text-gray-700">
                <span>الكلي: <b id="totalItems">{{ $batch->total_items }}</b></span>
                <span>المعالَج: <b id="processedItems">{{ $batch->processed_items }}</b></span>
                <span>الفاشل: <b id="failedItems" class="text-danger">{{ $batch->failed_items }}</b></span>
            </div>
            <div id="errorBox" class="alert alert-danger mt-5 {{ $batch->error_reason ? '' : 'd-none' }}">{{ $batch->error_reason }}</div>
            <div id="doneBox" class="mt-5 d-none">
                <a href="{{ route('dashboard.rent.ai.review', ['batch_id' => $batch->id]) }}" class="btn btn-success">انتقل إلى المراجعة والاعتماد</a>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {
    const url = "{{ route('dashboard.rent.ai.batch.json', $batch->id) }}";
    const reviewBtn = document.getElementById('doneBox');
    function render(d) {
        document.getElementById('batchStatus').innerText = d.status;
        document.getElementById('totalItems').innerText = d.total_items;
        document.getElementById('processedItems').innerText = d.processed_items;
        document.getElementById('failedItems').innerText = d.failed_items;
        const total = d.total_items || 0;
        const done = (d.processed_items || 0) + (d.failed_items || 0);
        const pct = total > 0 ? Math.round(done / total * 100) : (d.status === 'completed' ? 100 : 0);
        const bar = document.getElementById('progressBar');
        bar.style.width = pct + '%'; bar.innerText = pct + '%';
        if (d.error_reason) { const eb = document.getElementById('errorBox'); eb.classList.remove('d-none'); eb.innerText = d.error_reason; }
        if (d.status === 'completed' || d.status === 'failed') { if (done > 0) reviewBtn.classList.remove('d-none'); return true; }
        return false;
    }
    function poll() {
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json()).then(d => { if (!render(d)) setTimeout(poll, 3000); })
            .catch(() => setTimeout(poll, 5000));
    }
    poll();
})();
</script>
@endsection
