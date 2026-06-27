@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    @if ($failedBatches->isNotEmpty())
        <div class="card mb-5">
            <div class="card-header"><h3 class="card-title text-danger">دفعات فشلت بالكامل</h3></div>
            <div class="card-body table-responsive">
                <table class="table table-row-bordered align-middle">
                    <thead><tr class="fw-bold text-muted"><th>الملف</th><th>السبب</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($failedBatches as $b)
                            <tr>
                                <td>{{ $b->original_filename }}</td>
                                <td class="text-danger">{{ $b->error_reason }}</td>
                                <td>
                                    <form action="{{ route('dashboard.purchase.ai.batch.reprocess', $b->id) }}" method="POST">
                                        @csrf<button class="btn btn-sm btn-light-primary">إعادة المعالجة</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">فواتير تعذّرت معالجتها</h3></div>
        <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-muted"><th>الملف</th><th>الصفحات</th><th>السبب</th><th></th></tr></thead>
                <tbody>
                    @forelse ($items as $it)
                        <tr>
                            <td>{{ $it->batch->original_filename ?? '—' }}</td>
                            <td>{{ $it->page_from }}–{{ $it->page_to }}</td>
                            <td class="text-danger">{{ $it->error_reason }}</td>
                            <td>
                                <form action="{{ route('dashboard.purchase.ai.reprocess', $it->id) }}" method="POST">
                                    @csrf<button class="btn btn-sm btn-light-primary">إعادة المعالجة</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">لا توجد فواتير فاشلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
        </div>
    </div>

@endsection
