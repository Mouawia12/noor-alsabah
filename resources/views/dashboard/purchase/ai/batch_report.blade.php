@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)
@section('content')

    @php
        $ok = collect($rows)->where('ok', true)->count();
        $bad = collect($rows)->where('ok', false)->count();
    @endphp

    <div class="card mb-5">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title mb-0">تقرير حالة الفواتير في الملف</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.purchase.ai.batch.report.export', ['batch' => $batch->id, 'format' => 'xlsx']) }}"
                   class="btn btn-sm btn-light-success">تصدير Excel</a>
                <a href="{{ route('dashboard.purchase.ai.batch.report.export', ['batch' => $batch->id, 'format' => 'pdf']) }}"
                   class="btn btn-sm btn-light-danger">تصدير PDF</a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <span class="badge badge-light-primary fs-6 me-2">الملف: {{ $batch->original_filename }}</span>
                <span class="badge badge-light fs-6 me-2">الإجمالي: {{ count($rows) }}</span>
                <span class="badge badge-light-success fs-6 me-2">نجحت: {{ $ok }}</span>
                <span class="badge badge-light-danger fs-6">فشلت/مرفوضة: {{ $bad }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-row-bordered table-striped align-middle gy-3">
                    <thead>
                        <tr class="fw-bold fs-7 text-muted text-uppercase">
                            <th>#</th>
                            <th>الصفحات</th>
                            <th>الحالة</th>
                            <th>رقم الفاتورة</th>
                            <th>المورد</th>
                            <th>الرقم الضريبي</th>
                            <th>الإجمالي</th>
                            <th>الثقة</th>
                            <th>السبب / ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $r)
                            <tr>
                                <td>{{ $r['seq'] }}</td>
                                <td>{{ $r['pages'] }}</td>
                                <td>
                                    <span class="badge {{ $r['ok'] ? 'badge-light-success' : 'badge-light-danger' }}">
                                        {{ $r['status_label'] }}
                                    </span>
                                </td>
                                <td>{{ $r['invoice_no'] }}</td>
                                <td>{{ $r['supplier'] }}</td>
                                <td>{{ $r['tax_number'] }}</td>
                                <td>{{ $r['total'] }}</td>
                                <td>{{ $r['confidence'] }}</td>
                                <td class="text-danger">{{ $r['reason'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted">لا توجد فواتير في هذا الملف بعد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('dashboard.purchase.ai.batch', $batch->id) }}" class="btn btn-light mt-4">رجوع لمتابعة الدفعة</a>
        </div>
    </div>

@endsection
