@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)
@section('content')

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('dashboard.purchase.ai.reports.export', ['format' => 'xlsx']) }}" class="btn btn-light-success btn-sm"><i class="fas fa-file-excel me-1"></i> تصدير Excel</a>
        <a href="{{ route('dashboard.purchase.ai.reports.export', ['format' => 'pdf']) }}" class="btn btn-light-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> تصدير PDF</a>
    </div>

    <div class="row g-5 mb-5">
        <div class="col-md-3"><div class="card bg-light-primary"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-primary">{{ $stats['items'] }}</div><div class="text-gray-700">إجمالي الفواتير المعالَجة</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-success"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-success">{{ $stats['approved'] }}</div><div class="text-gray-700">معتمدة</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-danger"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-danger">{{ $stats['rejected'] + $stats['failed'] }}</div><div class="text-gray-700">مرفوضة / فاشلة</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-warning"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-warning">{{ $stats['success_rate'] }}%</div><div class="text-gray-700">نسبة الاعتماد</div>
        </div></div></div>
    </div>

    <div class="row g-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title">ملخّص</h3></div>
                <div class="card-body">
                    <table class="table table-row-bordered">
                        <tr><td>عدد الدفعات</td><td class="fw-bold">{{ $stats['batches'] }}</td></tr>
                        <tr><td>بانتظار المراجعة</td><td class="fw-bold">{{ $stats['needs_review'] }}</td></tr>
                        <tr><td>معتمدة</td><td class="fw-bold text-success">{{ $stats['approved'] }}</td></tr>
                        <tr><td>مرفوضة</td><td class="fw-bold">{{ $stats['rejected'] }}</td></tr>
                        <tr><td>فاشلة</td><td class="fw-bold text-danger">{{ $stats['failed'] }}</td></tr>
                        <tr><td>إجمالي قيمة المشتريات المستوردة</td><td class="fw-bold">{{ number_format((float) $stats['total_amount'], 2) }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title">أكثر الموردين تكراراً</h3></div>
                <div class="card-body table-responsive">
                    <table class="table table-row-bordered align-middle">
                        <thead><tr class="fw-bold text-gray-800"><th>المورد</th><th>عدد الفواتير</th><th>الإجمالي</th></tr></thead>
                        <tbody>
                            @forelse ($topSuppliers as $s)
                                <tr><td>{{ $s->name }}</td><td>{{ $s->c }}</td><td>{{ number_format((float) $s->total, 2) }}</td></tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">لا توجد بيانات بعد.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
