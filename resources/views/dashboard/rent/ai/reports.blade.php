@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('dashboard.rent.ai.reports.export', ['format' => 'xlsx']) }}" class="btn btn-light-success btn-sm"><i class="fas fa-file-excel me-1"></i> تصدير Excel</a>
        <a href="{{ route('dashboard.rent.ai.reports.export', ['format' => 'pdf']) }}" class="btn btn-light-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> تصدير PDF</a>
    </div>

    <div class="row g-5 mb-5">
        <div class="col-md-3"><div class="card bg-light-primary"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-primary">{{ $stats['contracts'] }}</div><div class="text-gray-700">عقود مستوردة</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-success"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-success">{{ $stats['active'] }}</div><div class="text-gray-700">عقود نشطة</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-secondary"><div class="card-body text-center">
            <div class="fs-2 fw-bold">{{ $stats['expired'] }}</div><div class="text-gray-700">عقود منتهية</div>
        </div></div></div>
        <div class="col-md-3"><div class="card bg-light-warning"><div class="card-body text-center">
            <div class="fs-2 fw-bold text-warning">{{ $stats['payments'] }}</div><div class="text-gray-700">دفعات مولّدة</div>
        </div></div></div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">ملخّص</h3></div>
        <div class="card-body">
            <table class="table table-row-bordered">
                <tr><td>عدد دفعات الاستيراد</td><td class="fw-bold">{{ $stats['batches'] }}</td></tr>
                <tr><td>عقود معالَجة</td><td class="fw-bold">{{ $stats['items'] }}</td></tr>
                <tr><td>عقود معتمدة</td><td class="fw-bold text-success">{{ $stats['approved'] }}</td></tr>
                <tr><td>بانتظار المراجعة</td><td class="fw-bold">{{ $stats['needs_review'] }}</td></tr>
                <tr><td>فاشلة</td><td class="fw-bold text-danger">{{ $stats['failed'] }}</td></tr>
                <tr><td>إجمالي الدفعات غير المسدَّدة</td><td class="fw-bold text-warning">{{ number_format((float) $stats['unpaid_amount'], 2) }}</td></tr>
            </table>
        </div>
    </div>

@endsection
