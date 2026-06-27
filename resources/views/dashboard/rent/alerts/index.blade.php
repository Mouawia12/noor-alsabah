@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    <div class="row g-5 mb-5">
        <div class="col-md-4">
            <div class="card bg-light-warning"><div class="card-body text-center">
                <div class="fs-2 fw-bold text-warning">{{ $upcoming->count() }}</div>
                <div class="text-gray-700">دفعات مستحقة خلال {{ $dueDays }} يوم</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light-danger"><div class="card-body text-center">
                <div class="fs-2 fw-bold text-danger">{{ $overdue->count() }}</div>
                <div class="text-gray-700">دفعات متأخرة</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light-primary"><div class="card-body text-center">
                <div class="fs-2 fw-bold text-primary">{{ $expiring->count() }}</div>
                <div class="text-gray-700">عقود تنتهي خلال {{ $expiryDays }} يوم</div>
            </div></div>
        </div>
    </div>

    @php
        $payRows = function ($rows, $badge, $label) {
            return [$rows, $badge, $label];
        };
    @endphp

    {{-- المتأخرة --}}
    <div class="card mb-5">
        <div class="card-header"><h3 class="card-title text-danger">دفعات متأخرة</h3></div>
        <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-muted"><th>المحل</th><th>تاريخ الاستحقاق</th><th>المبلغ</th><th></th></tr></thead>
                <tbody>
                    @forelse ($overdue as $p)
                        <tr>
                            <td>{{ $p->shop_name ?? 'محل #' . $p->shop_id }}</td>
                            <td><span class="badge badge-light-danger">{{ \Carbon\Carbon::parse($p->rentpay_dt)->format('Y-m-d') }}</span></td>
                            <td>{{ $p->rentpay_price }}</td>
                            <td>
                                <form action="{{ route('dashboard.rent.alerts.pay', $p->rentpay_id) }}" method="POST">
                                    @csrf<button class="btn btn-sm btn-light-success">تعليم كمسدَّدة</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">لا توجد دفعات متأخرة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- المستحقة قريباً --}}
    <div class="card mb-5">
        <div class="card-header"><h3 class="card-title text-warning">دفعات مستحقة قريباً</h3></div>
        <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-muted"><th>المحل</th><th>تاريخ الاستحقاق</th><th>المبلغ</th><th></th></tr></thead>
                <tbody>
                    @forelse ($upcoming as $p)
                        <tr>
                            <td>{{ $p->shop_name ?? 'محل #' . $p->shop_id }}</td>
                            <td><span class="badge badge-light-warning">{{ \Carbon\Carbon::parse($p->rentpay_dt)->format('Y-m-d') }}</span></td>
                            <td>{{ $p->rentpay_price }}</td>
                            <td>
                                <form action="{{ route('dashboard.rent.alerts.pay', $p->rentpay_id) }}" method="POST">
                                    @csrf<button class="btn btn-sm btn-light-success">تعليم كمسدَّدة</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">لا توجد دفعات مستحقة قريباً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- عقود قاربت الانتهاء --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title text-primary">عقود قاربت الانتهاء</h3></div>
        <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-muted"><th>المحل</th><th>رقم العقد</th><th>تاريخ الانتهاء</th></tr></thead>
                <tbody>
                    @forelse ($expiring as $c)
                        <tr>
                            <td>{{ $c->shop_name ?? 'محل #' . $c->shop_id }}</td>
                            <td>{{ $c->contract_no ?? '—' }}</td>
                            <td><span class="badge badge-light-primary">{{ \Carbon\Carbon::parse($c->end_date)->format('Y-m-d') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">لا توجد عقود قاربت الانتهاء.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
