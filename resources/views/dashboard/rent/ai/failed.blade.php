@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
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
                    <thead><tr class="fw-bold text-gray-800"><th>الملف</th><th>السبب</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($failedBatches as $b)
                            <tr>
                                <td>{{ $b->original_filename }}</td>
                                <td class="text-danger">{{ $b->error_reason }}</td>
                                <td>
                                    <form action="{{ route('dashboard.rent.ai.batch.reprocess', $b->id) }}" method="POST">
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
        <div class="card-header"><h3 class="card-title">عقود تعذّرت معالجتها</h3></div>
        <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
                <thead><tr class="fw-bold text-gray-800"><th>الملف</th><th>الصفحات</th><th>رقم العقد</th><th>المؤجر</th><th>ملاحظات التحقق / سبب الفشل</th><th></th></tr></thead>
                <tbody>
                    @php
                        /* الحقول الأساسية المطلوبة لاعتماد العقد — نعرض الناقص منها صراحةً */
                        $required = ['contract_no' => 'رقم العقد', 'landlord' => 'المؤجر', 'tenant' => 'المستأجر',
                                     'start_date' => 'تاريخ البداية', 'end_date' => 'تاريخ النهاية', 'rent_value' => 'قيمة الإيجار'];
                    @endphp
                    @forelse ($items as $it)
                        @php
                            $d = $it->extracted_json['data'] ?? [];
                            $missing = [];
                            foreach ($required as $k => $label) {
                                $v = $d[$k] ?? null;
                                if ($v === null || $v === '' || $v === []) { $missing[] = $label; }
                            }
                        @endphp
                        <tr>
                            <td>{{ $it->batch->original_filename ?? '—' }}</td>
                            <td class="text-nowrap">{{ $it->page_from }}–{{ $it->page_to }}</td>
                            <td class="fw-bold text-nowrap">{{ $d['contract_no'] ?? '—' }}</td>
                            <td>{{ $d['landlord'] ?? '—' }}</td>
                            <td>
                                @if ($it->error_reason)<div class="text-danger fs-8 mb-1">{{ $it->error_reason }}</div>@endif
                                @if (count($missing))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($missing as $m)<span class="badge badge-light-warning fs-9">حقل مفقود: {{ $m }}</span>@endforeach
                                    </div>
                                @elseif (! $it->error_reason)
                                    <span class="text-muted fs-8">—</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <form action="{{ route('dashboard.rent.ai.reprocess', $it->id) }}" method="POST">
                                    @csrf<button class="btn btn-sm btn-light-primary">إعادة القراءة</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">لا توجد عقود فاشلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
        </div>
    </div>

@endsection
