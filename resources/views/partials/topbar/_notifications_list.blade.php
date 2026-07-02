{{-- قائمة الإشعارات داخل القائمة المنسدلة (تُعاد عبر AJAX أيضاً). $items = مصفوفة عناصر مُقدَّمة. --}}
@forelse ($items as $it)
    <a href="{{ $it['url'] ?? route('dashboard.notifications.index') }}"
       class="notif-item d-flex align-items-start px-5 py-3 text-hover-primary border-bottom {{ $it['is_read'] ? '' : 'bg-light-primary' }}"
       data-id="{{ $it['id'] }}"
       data-url="{{ $it['url'] ?? '' }}"
       data-read-url="{{ route('dashboard.notifications.read', $it['id']) }}">
        <span class="symbol symbol-35px me-3">
            <span class="symbol-label bg-light-{{ $it['color'] }}">
                <i class="fas {{ $it['icon'] }} text-{{ $it['color'] }}"></i>
            </span>
        </span>
        <span class="d-flex flex-column flex-grow-1">
            <span class="fw-bold text-gray-800 fs-7">
                {{ $it['title'] }}
                @unless ($it['is_read'])<span class="badge badge-light-danger fs-9 ms-1">جديد</span>@endunless
            </span>
            <span class="text-gray-600 fs-8">{{ $it['message'] }}</span>
            <span class="text-muted fs-9 mt-1">{{ $it['time'] }}</span>
        </span>
    </a>
@empty
    <div class="text-center text-muted py-8">
        <i class="fas fa-bell-slash fs-2x d-block mb-2 opacity-50"></i>
        لا توجد إشعارات
    </div>
@endforelse
