@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المحلات')
@section('title', $page_title)
@section('content')

<div class="card mb-5">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <h3 class="card-title">التقرير المالي — {{ $shop->shop_name ?? '' }}</h3>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard.shop.financial_report.export', array_merge(['shop' => $shop->shop_id], request()->only(['contract_id', 'from', 'to']), ['format' => 'xlsx'])) }}" class="btn btn-sm btn-light-success"><i class="fas fa-file-excel me-1"></i>Excel</a>
            <a href="{{ route('dashboard.shop.financial_report.export', array_merge(['shop' => $shop->shop_id], request()->only(['contract_id', 'from', 'to']), ['format' => 'pdf'])) }}" class="btn btn-sm btn-light-danger"><i class="fas fa-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('dashboard.shop.payments', $shop->shop_id) }}" class="btn btn-sm btn-light-primary">متابعة السداد ↩</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-5 align-items-end">
            <div class="col-md-4">
                <label class="form-label">العقد</label>
                <select name="contract_id" class="form-select form-select-sm">
                    <option value="">— كل العقود —</option>
                    @foreach($contracts as $c)
                        <option value="{{ $c->shop_rent_id }}" @selected(($filters['contract_id'] ?? '') == $c->shop_rent_id)>{{ $c->contract_no ?? $c->rent_no ?? ('#'.$c->shop_rent_id) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">من تاريخ</label><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control form-control-sm"></div>
            <div class="col-md-3"><label class="form-label">إلى تاريخ</label><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control form-control-sm"></div>
            <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">تصفية</button></div>
        </form>

        <div class="row g-3 mb-5">
            <div class="col"><div class="border rounded p-4 text-center"><div class="fs-2 fw-bold">{{ number_format($summary['total'],2) }}</div><div class="text-muted">إجمالي المستحق</div></div></div>
            <div class="col"><div class="border rounded p-4 text-center bg-light-success"><div class="fs-2 fw-bold text-success">{{ number_format($summary['paid'],2) }}</div><div class="text-muted">المسدَّد</div></div></div>
            <div class="col"><div class="border rounded p-4 text-center bg-light-warning"><div class="fs-2 fw-bold text-warning">{{ number_format($summary['remaining'],2) }}</div><div class="text-muted">المتبقّي</div></div></div>
            <div class="col"><div class="border rounded p-4 text-center bg-light-danger"><div class="fs-2 fw-bold text-danger">{{ number_format($summary['overdue'],2) }}</div><div class="text-muted">المتأخّر ({{ $summary['overdue_count'] }})</div></div></div>
        </div>

        <h4 class="mb-3">السجل المالي (آخر 200 عملية)</h4>
        <div class="table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-muted bg-light"><th>التاريخ</th><th>النوع</th><th>الحدث</th><th>المبلغ</th><th>الوصف</th></tr></thead>
                <tbody>
                @forelse($ledger as $l)
                    <tr>
                        <td>{{ optional($l->created_at)->format('Y-m-d H:i') }}</td>
                        <td><span class="badge badge-light-{{ $l->direction === 'credit' ? 'success' : 'warning' }}">{{ $l->direction === 'credit' ? 'قبض' : 'استحقاق' }}</span></td>
                        <td>{{ ['payment'=>'سداد','due'=>'استحقاق','adjustment'=>'تعديل','reversal'=>'عكس'][$l->event] ?? $l->event }}</td>
                        <td class="fw-bold">{{ number_format((float)$l->amount,2) }}</td>
                        <td class="text-muted">{{ $l->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">لا توجد عمليات مالية.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
