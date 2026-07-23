@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)

@section('styles')
<style>
    .lb-tbl thead th{background:#eef3f8!important;color:#181c32!important;font-weight:700!important;white-space:nowrap;padding:.85rem .75rem}
    .lb-tbl tbody td{color:#2b2f42;vertical-align:middle}
    .lb-tbl tbody tr:hover{background:#f7fafd}
</style>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h3 class="card-title fw-bold mb-0">عقود الإيجار — استخراج بالذكاء الاصطناعي</h3>
            <div class="text-muted fs-7">سجل عمليات استخراج عقود الإيجار — {{ $batches->total() }} دفعة</div>
        </div>
        <a href="{{ route('dashboard.rent.ai.index') }}" class="btn btn-primary"><i class="fas fa-upload me-1"></i>رفع عقد إيجار جديد</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-bordered table-hover align-middle lb-tbl">
                <thead><tr>
                    <th>#</th><th>الملف</th><th>عدد الصفحات</th><th>العقود</th>
                    <th>تحتاج مراجعة</th><th>مُعتمدة</th><th>الحالة</th><th>التاريخ</th><th>الإجراء</th>
                </tr></thead>
                <tbody>
                @forelse ($batches as $i => $b)
                    @php
                        $done = (int) $b->processed_items + (int) $b->failed_items;
                        $pct  = $b->total_items > 0 ? round($done / $b->total_items * 100) : 0;
                    @endphp
                    <tr>
                        <td>{{ $batches->firstItem() + $i }}</td>
                        <td class="fw-bold">{{ $b->original_filename }}</td>
                        <td class="text-nowrap">{{ $done }} / {{ $b->total_items }}</td>
                        <td><span class="badge badge-light-primary">{{ $b->items_count }}</span></td>
                        <td>@if($b->needs_review_count)<span class="badge badge-light-warning">{{ $b->needs_review_count }}</span>@else — @endif</td>
                        <td>@if($b->approved_count)<span class="badge badge-light-success">{{ $b->approved_count }}</span>@else — @endif</td>
                        <td class="text-nowrap">
                            <span class="badge badge-light-{{ $b->status === 'completed' ? 'success' : ($b->status === 'failed' ? 'danger' : 'primary') }}">
                                {{ __('ai.status.'.$b->status) }}
                            </span>
                            @if($b->status !== 'completed' && $b->status !== 'failed')<span class="text-muted fs-8 ms-1">{{ $pct }}%</span>@endif
                        </td>
                        <td class="text-nowrap text-muted fs-7">{{ optional($b->created_at)->format('Y-m-d H:i') }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('dashboard.rent.ai.batch.results', $b->id) }}" class="btn btn-sm btn-light-primary">عرض</a>
                            @if($b->status !== 'completed')
                                <a href="{{ route('dashboard.rent.ai.batch', $b->id) }}" class="btn btn-sm btn-light-info">المعالجة</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-10">لا توجد عمليات استخراج بعد — ابدأ برفع عقد إيجار.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $batches->links() }}</div>
    </div>
</div>
@endsection
