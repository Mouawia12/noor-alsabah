@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'المحلات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h3 class="card-title">إدارة أكواد المحلات</h3>
            <form method="GET" action="{{ route('dashboard.shop_codes.index') }}" class="d-flex gap-2">
                <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" style="min-width:240px"
                       placeholder="ابحث باسم المحل أو الكود...">
                <button class="btn btn-sm btn-primary">بحث</button>
                @if ($q !== '')<a href="{{ route('dashboard.shop_codes.index') }}" class="btn btn-sm btn-light">إلغاء</a>@endif
            </form>
        </div>
        <div class="card-body">
            <div class="alert alert-light-primary py-3">
                عيّن كوداً فريداً لكل محل ليسهل تمييزه والبحث عنه عند الترحيل. يظهر الكود بجانب اسم المحل في كل الشاشات.
            </div>

            <div class="table-responsive">
                <table class="table table-row-bordered table-hover align-middle gy-3">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th style="width:230px">كود المحل</th>
                            <th>اسم المحل</th>
                            <th style="width:120px">الحالة</th>
                            <th style="width:150px">تاريخ الإنشاء</th>
                            <th style="width:140px">المنشئ</th>
                            <th style="width:140px">التفعيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shops as $s)
                            <tr>
                                <td>
                                    <form method="POST" action="{{ route('dashboard.shop_codes.save') }}" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="shop_id" value="{{ $s->shop_id }}">
                                        <input type="text" name="shop_code" value="{{ $s->shop_code }}"
                                               class="form-control form-control-sm fw-bold" placeholder="بدون كود">
                                        <button type="submit" class="btn btn-sm btn-success">حفظ</button>
                                    </form>
                                </td>
                                <td class="fw-bold">{{ $s->shop_name }}</td>
                                <td>
                                    @if (! $s->shop_code)
                                        <span class="badge badge-light">لا يوجد كود</span>
                                    @elseif ((int) $s->shop_code_active === 1)
                                        <span class="badge badge-light-success">فعّال</span>
                                    @else
                                        <span class="badge badge-light-danger">غير فعّال</span>
                                    @endif
                                </td>
                                <td>{{ $s->shop_code_at ? \Illuminate\Support\Carbon::parse($s->shop_code_at)->format('Y-m-d') : '—' }}</td>
                                <td>{{ $s->code_by_name ?? '—' }}</td>
                                <td class="text-nowrap">
                                    @if ($s->shop_code)
                                        <form method="POST" action="{{ route('dashboard.shop_codes.toggle') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="shop_id" value="{{ $s->shop_id }}">
                                            <button type="submit" class="btn btn-sm {{ (int) $s->shop_code_active === 1 ? 'btn-light-danger' : 'btn-light-success' }}">
                                                {{ (int) $s->shop_code_active === 1 ? 'إلغاء التفعيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">لا توجد محلات مطابقة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $shops->links() }}</div>
        </div>
    </div>

@endsection
