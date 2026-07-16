@php use App\Models\RentPaymentMatchItem; @endphp
<div class="table-responsive">
    <table class="table table-row-bordered table-hover align-middle">
        <thead><tr class="fw-bold text-muted bg-light">
            <th>#</th><th>رقم العقد</th><th>المستأجر</th><th>الوحدة</th><th>المبلغ</th><th>الاستحقاق</th>
            <th>الاقتراح</th><th>الثقة</th><th>الحالة</th><th>السبب</th><th>الإجراء</th>
        </tr></thead>
        <tbody>
        @forelse($items as $it)
            @php
                $conf = $it->confidence;
                $statusBadge = [
                    'matched'=>'success','orphan'=>'secondary','underpaid'=>'warning',
                    'overpaid'=>'warning','duplicate'=>'danger',
                ][$it->match_status] ?? 'secondary';
            @endphp
            <tr data-item="{{ $it->id }}">
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td class="fw-bold">{{ $it->contract_no ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($it->tenant_name ?? '—', 18) }}</td>
                <td>{{ $it->unit_no ?? '—' }}</td>
                <td>{{ $it->amount !== null ? number_format((float)$it->amount,2) : '—' }}</td>
                <td>{{ optional($it->due_date)->format('Y-m-d') ?? '—' }}</td>
                <td>{{ $it->matched_shop_rent_id ? ('عقد #'.$it->matched_shop_rent_id) : '—' }}</td>
                <td>@if($conf !== null)<span class="badge badge-light-{{ $conf < 0.8 ? 'danger' : 'success' }}">{{ round($conf*100) }}%</span>@endif</td>
                <td><span class="badge badge-light-{{ $statusBadge }}">{{ RentPaymentMatchItem::MATCH_LABELS[$it->match_status] ?? $it->match_status }}</span></td>
                <td class="text-muted fs-8">{{ \Illuminate\Support\Str::limit($it->match_reason ?? '—', 40) }}</td>
                <td class="text-nowrap">
                    @if($it->matched_shop_rent_id)
                        <button type="button" class="btn btn-sm btn-success js-pm-approve" data-url="{{ route('dashboard.rent.payment_match.approve', $it->id) }}">اعتماد وربط</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-light-danger js-pm-reject" data-url="{{ route('dashboard.rent.payment_match.reject', $it->id) }}">رفض</button>
                </td>
            </tr>
        @empty
            <tr><td colspan="11" class="text-center text-muted py-5">لا توجد دفعات بانتظار المراجعة.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">{{ $items->links() }}</div>
