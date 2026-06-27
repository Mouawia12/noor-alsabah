@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'التحليلات')
@section('title', $page_title)
@section('content')

    @if ($insight)
        <div class="alert alert-primary d-flex align-items-start">
            <i class="fas fa-robot fs-2 me-3 mt-1"></i>
            <div>
                <div class="fw-bold mb-1">ملخّص ذكي (AI)</div>
                <div>{{ $insight }}</div>
            </div>
        </div>
    @endif

    {{-- بطاقات المؤشرات --}}
    <div class="row g-5 mb-5">
        <div class="col-md-4"><div class="card bg-light-success"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-success">{{ number_format($expectedRev, 2) }}</div>
            <div class="text-gray-700">الإيرادات المتوقّعة (دفعات مستقبلية غير مسدَّدة)</div>
        </div></div></div>
        <div class="col-md-4"><div class="card bg-light-primary"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-primary">{{ $collection['rate'] }}%</div>
            <div class="text-gray-700">نسبة التحصيل</div>
        </div></div></div>
        <div class="col-md-4"><div class="card bg-light-danger"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-danger">{{ number_format($collection['unpaid'], 2) }}</div>
            <div class="text-gray-700">إجمالي غير المسدَّد</div>
        </div></div></div>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="card mb-5">
                <div class="card-header"><h3 class="card-title">التدفق النقدي المتوقّع — 12 شهراً</h3></div>
                <div class="card-body"><div id="cashFlowChart" style="min-height:300px"></div></div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">اتجاه المشتريات — 12 شهراً</h3></div>
                <div class="card-body"><div id="purchaseChart" style="min-height:300px"></div></div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title text-danger">محلات عالية المخاطر (تأخّر متكرّر)</h3></div>
                <div class="card-body table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead><tr class="fw-bold text-muted"><th>المحل</th><th>دفعات متأخرة</th><th>المبلغ</th></tr></thead>
                        <tbody>
                            @forelse ($highRisk as $s)
                                <tr>
                                    <td>{{ $s->name }}</td>
                                    <td><span class="badge badge-light-danger">{{ $s->overdue_count }}</span></td>
                                    <td>{{ number_format((float) $s->overdue_amount, 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">لا توجد مخاطر مسجّلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function () {
    const cash = @json($cashFlow);
    const purch = @json($purchaseTrend);

    function bar(el, data, color, name) {
        if (!window.ApexCharts || !document.querySelector(el)) return;
        new ApexCharts(document.querySelector(el), {
            chart: { type: 'bar', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
            series: [{ name: name, data: data.map(r => Math.round(r.amount)) }],
            xaxis: { categories: data.map(r => r.month) },
            colors: [color],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
            dataLabels: { enabled: false },
            grid: { strokeDashArray: 4 }
        }).render();
    }

    document.addEventListener('DOMContentLoaded', function () {
        bar('#cashFlowChart', cash, '#50cd89', 'التدفق المتوقّع');
        bar('#purchaseChart', purch, '#009ef7', 'المشتريات');
    });
})();
</script>
@endsection
