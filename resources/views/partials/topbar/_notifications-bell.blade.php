{{-- جرس الإشعارات في الترويسة: عدّاد حيّ + قائمة منسدلة + تعليم كمقروء --}}
@php
    $notifUser  = auth()->user();
    $notifUnread = $notifUser ? $notifUser->unreadNotifications()->count() : 0;
    $notifItems  = $notifUser
        ? $notifUser->notifications()->latest()->limit(8)->get()->map(fn ($n) => \App\Support\NotificationPresenter::present($n))
        : collect();
@endphp
<div class="d-flex align-items-center ms-1 ms-lg-3">
    <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px"
         data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="notifBell">
        <i class="fas fa-bell text-dark fs-2"></i>
        <span class="position-absolute top-0 start-0 translate-middle badge badge-circle badge-danger animation-blink {{ $notifUnread ? '' : 'd-none' }}"
              id="notifCount">{{ $notifUnread }}</span>
    </div>

    <div class="menu menu-sub menu-sub-dropdown menu-column w-300px w-lg-375px" data-kt-menu="true" id="notifMenu">
        <div class="d-flex align-items-center justify-content-between px-5 py-4 border-bottom">
            <span class="fw-bold text-gray-800">الإشعارات</span>
            <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 fs-8" id="notifMarkAll"
                    data-url="{{ route('dashboard.notifications.read_all') }}">تعليم الكل كمقروء</button>
        </div>
        <div class="scroll-y mh-350px" id="notifList">
            @include('partials.topbar._notifications_list', ['items' => $notifItems])
        </div>
        <div class="text-center border-top py-3">
            <a href="{{ route('dashboard.notifications.index') }}" class="btn btn-sm btn-light-primary fs-8">عرض كل الإشعارات</a>
        </div>
    </div>
</div>

<script>
(function () {
    var CSRF = document.querySelector('meta[name="csrf-token"]');
    CSRF = CSRF ? CSRF.getAttribute('content') : '{{ csrf_token() }}';
    var recentUrl = '{{ route('dashboard.notifications.recent') }}';

    function setCount(n) {
        var b = document.getElementById('notifCount');
        if (!b) return;
        b.textContent = n;
        if (n > 0) { b.classList.remove('d-none'); } else { b.classList.add('d-none'); }
    }

    /** يحدّث العدّاد والقائمة من الخادم (استطلاع دوري + بعد فتح القائمة). */
    function refresh() {
        fetch(recentUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                setCount(d.unread || 0);
                var list = document.getElementById('notifList');
                if (list && typeof d.html === 'string') { list.innerHTML = d.html; }
            })
            .catch(function () {});
    }

    function post(url) {
        return fetch(url, { method: 'POST', credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        /* فتح القائمة → حدّثها */
        var bell = document.getElementById('notifBell');
        if (bell) bell.addEventListener('click', function () { setTimeout(refresh, 50); });

        /* تعليم الكل كمقروء */
        var markAll = document.getElementById('notifMarkAll');
        if (markAll) markAll.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            post(markAll.getAttribute('data-url')).then(function () { setCount(0); refresh(); });
        });

        /* نقر إشعار → علّمه مقروءاً ثم انتقل لرابطه */
        var list = document.getElementById('notifList');
        if (list) list.addEventListener('click', function (e) {
            var a = e.target.closest('.notif-item');
            if (!a) return;
            e.preventDefault();
            var readUrl = a.getAttribute('data-read-url');
            var target = a.getAttribute('data-url');
            post(readUrl).then(function (res) {
                setCount(res && typeof res.unread === 'number' ? res.unread : 0);
                var dest = target || (res && res.url) || '{{ route('dashboard.notifications.index') }}';
                window.location.href = dest;
            }).catch(function () {
                window.location.href = target || '{{ route('dashboard.notifications.index') }}';
            });
        });

        /* استطلاع دوري كل 45 ثانية للعدّاد */
        setInterval(refresh, 45000);
    });
})();
</script>
