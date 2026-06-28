<?php

use App\Services\Ai\InvoiceValidationService;

beforeEach(function () {
    $this->svc = new InvoiceValidationService();
});

it('accepts invoices whose math is correct', function () {
    $res = $this->svc->validate([
        'invoice_no'        => 'INV-1',
        'amount_before_tax' => 100,
        'tax_amount'        => 15,
        'total'             => 115,
        'invoice_date'      => '2026-05-13',
        'tax_number'        => '300000000000003',
    ]);

    expect($res['math_ok'])->toBeTrue();
    expect($res['issues'])->toBe([]);
});

it('flags a broken total', function () {
    $res = $this->svc->validate([
        'invoice_no'        => 'INV-1',
        'amount_before_tax' => 100,
        'tax_amount'        => 15,
        'total'             => 120,
    ]);

    expect($res['math_ok'])->toBeFalse();
    expect($res['issues'])->not->toBeEmpty();
});

it('tolerates tiny rounding differences', function () {
    $res = $this->svc->validate([
        'amount_before_tax' => 100.00,
        'tax_amount'        => 15.00,
        'total'             => 115.03, // ضمن السماحية 0.05
    ]);

    expect($res['math_ok'])->toBeTrue();
});

it('flags a missing invoice number', function () {
    $res = $this->svc->validate(['amount_before_tax' => 1, 'tax_amount' => 0, 'total' => 1]);
    expect($res['issues'])->toContain('رقم الفاتورة مفقود.');
});

it('flags an unparseable date but accepts a day-first one', function () {
    $bad = $this->svc->validate(['invoice_no' => 'X', 'invoice_date' => 'يوم ما']);
    expect($bad['issues'])->toContain('تاريخ الفاتورة بصيغة غير واضحة.');

    $good = $this->svc->validate(['invoice_no' => 'X', 'invoice_date' => '13/05/2026']);
    expect($good['issues'])->not->toContain('تاريخ الفاتورة بصيغة غير واضحة.');
});
