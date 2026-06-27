@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المشتريات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    @if (! $imagickOk)
        <div class="alert alert-warning">
            ⚠️ بيئة تحويل ملفات PDF إلى صور (Imagick + Ghostscript) غير مفعّلة على الخادم.
            المعالجة لن تكتمل حتى تفعيلها. يمكن رفع الملفات الآن وإعادة معالجتها لاحقاً.
        </div>
    @endif

    <div class="row g-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">رفع فواتير للمعالجة الذكية</h3>
                </div>
                <form action="{{ route('dashboard.purchase.ai.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <p class="text-muted">
                            ارفع ملف PDF (قد يحتوي عدة فواتير) أو صورة فاتورة (JPG/PNG).
                            سيقوم النظام بفصل الفواتير واستخراج بياناتها تلقائياً.
                        </p>

                        <div class="mb-5">
                            <label class="form-label required">الملف</label>
                            <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('document')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            <div class="form-text">الحد الأقصى 50 ميغابايت — PDF أو JPG أو PNG.</div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('dashboard.purchase.ai.review') }}" class="btn btn-light">قائمة المراجعة</a>
                        <button type="submit" class="btn btn-primary">رفع وبدء المعالجة</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">آخر الدفعات</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered align-middle">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>الملف</th>
                                    <th>الحالة</th>
                                    <th>المعالَج / الكلي</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($batches as $b)
                                    <tr>
                                        <td>{{ $b->original_filename }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $b->status === 'completed' ? 'success' : ($b->status === 'failed' ? 'danger' : 'primary') }}">
                                                {{ $b->status }}
                                            </span>
                                        </td>
                                        <td>{{ $b->processed_items + $b->failed_items }} / {{ $b->total_items }}</td>
                                        <td><a href="{{ route('dashboard.purchase.ai.batch', $b->id) }}" class="btn btn-sm btn-light-primary">متابعة</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">لا توجد دفعات بعد.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
