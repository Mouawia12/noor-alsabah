@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإشعارات')
@section('title', $page_title)
@section('content')

    @if (session()->has('alert.success'))
        <div class="alert alert-success">{{ session('alert.success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">الإشعارات</h3>
            <div class="card-toolbar">
                <form action="{{ route('dashboard.notifications.read_all') }}" method="POST">
                    @csrf<button class="btn btn-sm btn-light-primary">تعليم الكل كمقروء</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @forelse ($notifications as $n)
                @php $it = \App\Support\NotificationPresenter::present($n); @endphp
                <div class="d-flex align-items-start border-bottom py-4 {{ $it['is_read'] ? 'opacity-75' : '' }}">
                    <span class="symbol symbol-40px me-4">
                        <span class="symbol-label bg-light-{{ $it['color'] }}">
                            <i class="fas {{ $it['icon'] }} text-{{ $it['color'] }} fs-3"></i>
                        </span>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-gray-800">
                            {{ $it['title'] }}
                            @unless ($it['is_read'])<span class="badge badge-light-danger ms-2">جديد</span>@endunless
                        </div>
                        <div class="text-gray-700 mt-1">{{ $it['message'] }}</div>
                        @if (! empty($it['samples']))
                            <ul class="mt-2 text-muted fs-7">
                                @foreach ($it['samples'] as $s)<li>{{ $s }}</li>@endforeach
                            </ul>
                        @endif
                        <div class="text-muted fs-8 mt-1">{{ $it['time'] }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if (! empty($it['url']))<a href="{{ $it['url'] }}" class="btn btn-sm btn-light">فتح</a>@endif
                        @unless ($it['is_read'])
                            <form action="{{ route('dashboard.notifications.read', $n->id) }}" method="POST">
                                @csrf<button class="btn btn-sm btn-light-success">مقروء</button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-bell-slash fs-3x d-block mb-3 opacity-50"></i>
                    لا توجد إشعارات.
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>

@endsection
