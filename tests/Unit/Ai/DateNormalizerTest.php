<?php

use App\Services\Ai\DateNormalizer;

it('passes through ISO dates', function () {
    expect(DateNormalizer::toYmd('2026-05-13'))->toBe('2026-05-13');
    expect(DateNormalizer::toYmd('2026/05/13'))->toBe('2026-05-13');
});

it('parses day-first formats without US misreading', function () {
    // 13/05/2026 لا يمكن أن يكون شهراً 13 — يجب أن يُقرأ كـ 13 مايو
    expect(DateNormalizer::toYmd('13/05/2026'))->toBe('2026-05-13');
    expect(DateNormalizer::toYmd('13-05-2026'))->toBe('2026-05-13');
    expect(DateNormalizer::toYmd('05/13/2026'))->toBe('2026-05-13'); // صيغة US صريحة
});

it('converts arabic-indic digits', function () {
    expect(DateNormalizer::toYmd('١٣/٠٥/٢٠٢٦'))->toBe('2026-05-13');
});

it('rejects garbage that strtotime would have accepted', function () {
    expect(DateNormalizer::toYmd('next'))->toBeNull();
    expect(DateNormalizer::toYmd('غير معروف'))->toBeNull();
    expect(DateNormalizer::toYmd(''))->toBeNull();
    expect(DateNormalizer::toYmd(null))->toBeNull();
});

it('rejects impossible calendar dates', function () {
    expect(DateNormalizer::toYmd('32/01/2026'))->toBeNull();
    expect(DateNormalizer::toYmd('2026-13-01'))->toBeNull();
});

it('reports looksLikeDate consistently', function () {
    expect(DateNormalizer::looksLikeDate('2026-05-13'))->toBeTrue();
    expect(DateNormalizer::looksLikeDate('next'))->toBeFalse();
});
