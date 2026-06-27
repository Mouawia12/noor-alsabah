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
                @php $data = $n->data; @endphp
                <div class="d-flex align-items-start border-bottom py-4 {{ $n->read_at ? 'opacity-50' : '' }}">
                    <div class="flex-grow-1">
                        <div class="fw-bold">
                            تنبيهات الإيجارات
                            @unless ($n->read_at)<span class="badge badge-light-danger ms-2">جديد</span>@endunless
                        </div>
                        <div class="text-gray-700 mt-1">
                            مستحقة: {{ $data['summary']['upcoming'] ?? 0 }} —
                            متأخرة: {{ $data['summary']['overdue'] ?? 0 }} —
                            قاربت الانتهاء: {{ $data['summary']['expiring'] ?? 0 }}
                        </div>
                        @if (! empty($data['samples']))
                            <ul class="mt-2 text-muted fs-7">
                                @foreach (array_slice($data['samples'], 0, 5) as $s)<li>{{ $s }}</li>@endforeach
                            </ul>
                        @endif
                        <div class="text-muted fs-8 mt-1">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if (! empty($data['url']))<a href="{{ $data['url'] }}" class="btn btn-sm btn-light">فتح</a>@endif
                        @unless ($n->read_at)
                            <form action="{{ route('dashboard.notifications.read', $n->id) }}" method="POST">
                                @csrf<button class="btn btn-sm btn-light-success">مقروء</button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">لا توجد إشعارات.</div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>

@endsection
