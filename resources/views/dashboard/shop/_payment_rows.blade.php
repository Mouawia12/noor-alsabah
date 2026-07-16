@php use App\Models\ShopRentpay; @endphp
<div class="table-responsive">
    <table class="table table-row-bordered table-hover align-middle">
        <thead><tr class="fw-bold text-muted bg-light">
            <th>#</th><th>تاريخ الاستحقاق</th><th>هجري</th><th>المستحق</th><th>المدفوع</th><th>المتبقّي</th><th>الحالة</th>
            @if($payable)<th>الإجراء</th>@endif
        </tr></thead>
        <tbody>
        @foreach($rows as $p)
            @php
                $ds = $p->displayStatus();
                $badge = ['paid'=>'success','partial'=>'info','unpaid'=>'secondary','overdue'=>'danger'][$ds] ?? 'secondary';
            @endphp
            <tr>
                <td>{{ $p->seq_no ?? $loop->iteration }}</td>
                <td class="fw-bold">{{ optional($p->rentpay_dt)->format('Y-m-d') ?? '—' }}</td>
                <td class="text-muted">{{ $p->due_date_hijri ?? '—' }}</td>
                <td>{{ number_format((float)$p->rentpay_price, 2) }}</td>
                <td>{{ number_format((float)$p->paid_amount, 2) }}</td>
                <td>{{ number_format($p->remaining, 2) }}</td>
                <td><span class="badge badge-light-{{ $badge }}">{{ $p->displayStatusLabel() }}</span></td>
                @if($payable)
                    <td>
                        @if($ds !== 'paid')
                            <button type="button" class="btn btn-sm btn-success js-pay" data-id="{{ $p->rentpay_id }}" data-remaining="{{ $p->remaining }}">تسجيل سداد</button>
                        @else
                            <span class="text-muted fs-8">—</span>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
