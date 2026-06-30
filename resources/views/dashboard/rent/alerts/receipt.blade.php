@php
    $date = fn ($d) => $d ? \Carbon\Carbon::parse($d)->format('Y-m-d') : '—';
    $paidAt = $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i') : now()->format('Y-m-d H:i');
@endphp
<div style="text-align:center; font-size:16px;"><b>شركة نور الصباح الاستثمارية</b></div>
<div style="text-align:center; font-size:14px;"><b>سند استلام دفعة إيجار</b></div>
<div style="text-align:center; font-size:10px; color:#555;">رقم السند: {{ $p->rentpay_id }} &nbsp;&nbsp; التاريخ: {{ $paidAt }}</div>
<br><hr><br>

<table border="1" cellpadding="6" cellspacing="0" style="width:100%; font-size:11px;">
    <tr><td style="width:35%; background-color:#eef3ff;"><b>رقم العقد</b></td><td>{{ $p->contract_no ?? '—' }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>العقار / الوحدة</b></td><td>{{ $p->shop_name ?? '—' }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>المؤجر</b></td><td>{{ $p->landlord ?? 'شركة نور الصباح الاستثمارية' }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>المستأجر</b></td><td>{{ $p->tenant ?? '—' }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>رقم الدفعة</b></td><td>الدفعة {{ $p->pay_no ?? '?' }} من {{ $p->pay_total ?? '?' }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>تاريخ الاستحقاق</b></td><td>{{ $date($p->rentpay_dt) }}</td></tr>
    <tr><td style="background-color:#eef3ff;"><b>المبلغ المستلَم</b></td><td><b>{{ number_format((float) $p->rentpay_price, 2) }} ريال</b></td></tr>
    @if ($amountWords)
        <tr><td style="background-color:#eef3ff;"><b>المبلغ كتابةً</b></td><td>{{ $amountWords }} فقط لا غير</td></tr>
    @endif
    <tr><td style="background-color:#eef3ff;"><b>تاريخ الاستلام</b></td><td>{{ $paidAt }}</td></tr>
</table>

<br><br>
<div style="font-size:11px;">أقرّ باستلام المبلغ المذكور أعلاه قيمةَ إيجار العقار المبيّن في هذا السند.</div>
<br><br>

<table cellpadding="6" style="width:100%; font-size:11px;">
    <tr>
        <td style="width:50%;">الموظف المستلِم: <b>{{ $employee }}</b><br><br>التوقيع: ............................</td>
        <td style="width:50%;">المستأجر / المُسلِّم:<br><br>التوقيع: ............................</td>
    </tr>
</table>

<br>
<div style="text-align:center; font-size:9px; color:#777;">هذا السند صادر آلياً من نظام نور الصباح — {{ $paidAt }}</div>
