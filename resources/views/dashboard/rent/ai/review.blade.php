@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الإيجارات')
@section('title', $page_title)
@section('content')

    @php $threshold = (float) config('ai.confidence_threshold', 0.8); @endphp

    <div id="toaster" style="position:fixed;top:80px;left:20px;z-index:2000;min-width:300px"></div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title">العقود بانتظار المراجعة (<span id="reviewCount">{{ $items->total() }}</span>)</h3>
            <button type="button" id="approveAllBtn" class="btn btn-success"
                    data-url="{{ route('dashboard.rent.ai.approve_all') }}">
                اعتماد الكل ✓
            </button>
        </div>
        <div class="card-body">
            <div class="alert alert-light-primary py-2">اختر المحل لكل عقد، ثم اضغط «اعتماد» للصف أو «اعتماد الكل». للتعديل والاطلاع على الصورة اضغط «مراجعة/تعديل».</div>
            <div class="table-responsive">
                <table class="table table-row-bordered table-hover align-middle">
                    <thead><tr class="fw-bold text-muted bg-light">
                        <th>#</th><th>الملف/الصفحات</th><th>رقم العقد</th><th>المؤجر</th><th>القيمة</th><th>الثقة</th>
                        <th style="min-width:160px">المحل / العقار <span class="text-danger">*</span></th><th>الإجراء</th>
                    </tr></thead>
                    <tbody>
                        @forelse ($items as $i => $item)
                            @php $d = $item->extracted_json['data'] ?? []; $conf = $item->confidence; $low = $conf !== null && $conf < $threshold; @endphp
                            <tr data-item="{{ $item->id }}">
                                <td>{{ $items->firstItem() + $i }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->batch->original_filename ?? '—', 18) }} <span class="text-muted fs-8">(ص {{ $item->page_from }}–{{ $item->page_to }})</span></td>
                                <td class="fw-bold">{{ $d['contract_no'] ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($d['landlord'] ?? '—', 16) }}</td>
                                <td>{{ $d['rent_value'] ?? '—' }}</td>
                                <td>@if ($conf !== null)<span class="badge badge-light-{{ $low ? 'danger' : 'success' }}">{{ round($conf * 100) }}%</span>@endif @if ($item->is_duplicate)<span class="badge badge-light-danger">مكرر؟</span>@endif</td>
                                <td>
                                    <select class="form-select form-select-sm row-shop">
                                        <option value="">— اختر —</option>
                                        @foreach ($shops as $s)<option value="{{ $s->shop_id }}">{{ $s->shop_name }}</option>@endforeach
                                    </select>
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-sm btn-success js-approve" data-url="{{ route('dashboard.rent.ai.approve', $item->id) }}">اعتماد</button>
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#rentModal{{ $item->id }}">مراجعة/تعديل</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5">لا توجد عقود بانتظار المراجعة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
        </div>
    </div>

    {{-- نوافذ التعديل --}}
    @foreach ($items as $item)
        @php
            $d = $item->extracted_json['data'] ?? []; $fc = $item->field_confidence ?? []; $conf = $item->confidence;
            $pages = count(array_filter(explode(',', (string) $item->source_file_path)));
            $cls = fn ($f) => (isset($fc[$f]) && $fc[$f] < $threshold) ? 'border border-danger' : '';
        @endphp
        <div class="modal fade" id="rentModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">مراجعة عقد — {{ $item->batch->original_filename ?? '' }}
                            @if ($conf !== null)<span class="badge badge-light-{{ $conf < $threshold ? 'danger' : 'success' }} ms-2">الثقة {{ round($conf * 100) }}%</span>@endif</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-5">
                            <div class="col-lg-5"><div class="border rounded p-2 bg-light" style="max-height:60vh;overflow:auto">
                                @for ($p = 0; $p < max(1, $pages); $p++)
                                    <img src="{{ route('dashboard.rent.ai.image', ['item' => $item->id, 'page' => $p]) }}" loading="lazy" class="img-fluid mb-2 rounded shadow-sm w-100" alt="صفحة {{ $p + 1 }}" onerror="this.style.display='none'">
                                @endfor
                            </div></div>
                            <div class="col-lg-7">
                                <form class="js-modal-form" data-item="{{ $item->id }}">
                                    <div class="row g-4">
                                        <div class="col-md-6"><label class="form-label">رقم العقد</label><input type="text" name="contract_no" class="form-control {{ $cls('contract_no') }}" value="{{ $d['contract_no'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">تاريخ البداية</label><input type="text" name="start_date" class="form-control {{ $cls('start_date') }}" value="{{ $d['start_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                        <div class="col-md-6"><label class="form-label">تاريخ النهاية</label><input type="text" name="end_date" class="form-control {{ $cls('end_date') }}" value="{{ $d['end_date'] ?? '' }}" placeholder="YYYY-MM-DD"></div>
                                        <div class="col-md-3"><label class="form-label">قيمة الإيجار</label><input type="number" step="0.01" name="rent_value" class="form-control {{ $cls('rent_value') }}" value="{{ $d['rent_value'] ?? '' }}"></div>
                                        <div class="col-md-3"><label class="form-label">عدد الدفعات</label><input type="number" name="payments_count" class="form-control {{ $cls('payments_count') }}" value="{{ $d['payments_count'] ?? '' }}"></div>
                                        <div class="col-md-3"><label class="form-label">مبلغ الدفعة</label><input type="number" step="0.01" name="payment_amount" class="form-control {{ $cls('payment_amount') }}" value="{{ $d['payment_amount'] ?? '' }}"></div>
                                        <div class="col-md-3"><label class="form-label">المؤجر</label><input type="text" name="landlord" class="form-control {{ $cls('landlord') }}" value="{{ $d['landlord'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">المستأجر</label><input type="text" name="tenant" class="form-control {{ $cls('tenant') }}" value="{{ $d['tenant'] ?? '' }}"></div>
                                        <div class="col-md-6"><label class="form-label">بيانات العقار</label><input type="text" name="property_info" class="form-control {{ $cls('property_info') }}" value="{{ $d['property_info'] ?? '' }}"></div>
                                    </div>
                                </form>
                                <div class="form-text mt-2">المحل يُختار من الجدول. الحقول الحمراء منخفضة الثقة.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
                        <button type="button" class="btn btn-light-danger js-reject" data-url="{{ route('dashboard.rent.ai.reject', $item->id) }}">رفض</button>
                        <button type="button" class="btn btn-success js-modal-approve" data-item="{{ $item->id }}" data-url="{{ route('dashboard.rent.ai.approve', $item->id) }}">اعتماد العقد وتوليد الدفعات</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
    (function () {
        const CSRF = '{{ csrf_token() }}';
        function toast(msg, ok = true) {
            const d = document.createElement('div');
            d.className = 'alert alert-' + (ok ? 'success' : 'danger') + ' shadow';
            d.textContent = msg;
            document.getElementById('toaster').appendChild(d);
            setTimeout(() => d.remove(), 3500);
        }
        function post(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(body || {})
            }).then(r => r.ok ? r.json() : r.json().then(e => Promise.reject(e)));
        }
        function removeRow(id) {
            const tr = document.querySelector('tr[data-item="' + id + '"]');
            if (tr) tr.remove();
            const m = document.getElementById('rentModal' + id);
            if (m) { const inst = bootstrap.Modal.getInstance(m); if (inst) inst.hide(); }
            const c = document.getElementById('reviewCount');
            if (c) c.textContent = Math.max(0, parseInt(c.textContent) - 1);
        }
        function rowShop(id) {
            const tr = document.querySelector('tr[data-item="' + id + '"]');
            return tr ? (tr.querySelector('.row-shop')?.value || '') : '';
        }

        document.addEventListener('click', function (e) {
            // اعتماد سريع من الصف
            const a = e.target.closest('.js-approve');
            if (a) {
                const tr = a.closest('tr'); const id = tr.dataset.item; const shop = rowShop(id);
                if (!shop) return toast('اختر المحل/العقار أولاً', false);
                a.disabled = true;
                post(a.dataset.url, { shop_id: shop }).then(res => { toast(res.message); removeRow(id); })
                    .catch(() => { a.disabled = false; toast('تعذّر الاعتماد', false); });
            }
            // اعتماد من النافذة (مع التعديلات)
            const ma = e.target.closest('.js-modal-approve');
            if (ma) {
                const id = ma.dataset.item; const shop = rowShop(id);
                if (!shop) return toast('اختر المحل/العقار من الجدول أولاً', false);
                const form = document.querySelector('.js-modal-form[data-item="' + id + '"]');
                const body = { shop_id: shop };
                new FormData(form).forEach((v, k) => body[k] = v);
                ma.disabled = true;
                post(ma.dataset.url, body).then(res => { toast(res.message); removeRow(id); })
                    .catch(() => { ma.disabled = false; toast('تعذّر الاعتماد', false); });
            }
            // رفض
            const rj = e.target.closest('.js-reject');
            if (rj) {
                if (!confirm('تأكيد رفض هذا العقد؟')) return;
                const id = rj.closest('.modal').id.replace('rentModal', '');
                post(rj.dataset.url, { reason: 'رُفض يدوياً' }).then(res => { toast(res.message); removeRow(id); })
                    .catch(() => toast('تعذّر الرفض', false));
            }
            // اعتماد الكل
            const all = e.target.closest('#approveAllBtn');
            if (all) {
                const rows = [...document.querySelectorAll('tr[data-item]')].map(tr => ({ id: tr.dataset.item, shop_id: tr.querySelector('.row-shop')?.value || '' }));
                if (!rows.length) return toast('لا توجد عقود', false);
                const missing = rows.filter(r => !r.shop_id).length;
                if (missing && !confirm('هناك ' + missing + ' عقد بلا محل مختار سيُتجاوز. متابعة اعتماد الباقي؟')) return;
                all.disabled = true;
                post(all.dataset.url, { items: rows }).then(res => {
                    res.errors.forEach(er => {}); // متروكة في الجدول
                    rows.forEach(r => { if (r.shop_id && !res.errors.find(e => e.id == r.id)) removeRow(r.id); });
                    toast('تم اعتماد ' + res.approved + ' عقد' + (res.errors.length ? ' (تخطّي ' + res.errors.length + ')' : ''));
                    all.disabled = false;
                }).catch(() => { all.disabled = false; toast('تعذّر الاعتماد الجماعي', false); });
            }
        });
    })();
    </script>

@endsection
