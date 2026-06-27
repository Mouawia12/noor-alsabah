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
        {{-- التدفق النقدي المتوقّع --}}
        <div class="col-lg-7">
            <div class="card mb-5">
                <div class="card-header"><h3 class="card-title">التدفق النقدي المتوقّع — 12 شهراً</h3></div>
                <div class="card-body">
                    @foreach ($cashFlow as $r)
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:80px" class="text-muted fs-7">{{ $r['month'] }}</div>
                            <div class="flex-grow-1 mx-2">
                                <div class="bg-light-success rounded" style="height:18px; width: {{ max(2, round($r['amount'] / $maxCash * 100)) }}%"></div>
                            </div>
                            <div style="width:110px" class="text-end fw-bold">{{ number_format($r['amount'], 0) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- اتجاه المشتريات --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">اتجاه المشتريات — 12 شهراً</h3></div>
                <div class="card-body">
                    @foreach ($purchaseTrend as $r)
                        <div class="d-flex align-items-center mb-3">
                            <div style="width:80px" class="text-muted fs-7">{{ $r['month'] }}</div>
                            <div class="flex-grow-1 mx-2">
                                <div class="bg-light-primary rounded" style="height:18px; width: {{ max(2, round($r['amount'] / $maxPur * 100)) }}%"></div>
                            </div>
                            <div style="width:110px" class="text-end fw-bold">{{ number_format($r['amount'], 0) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- العقود عالية المخاطر --}}
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
