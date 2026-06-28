<?php

use App\Models\Municip;
use App\Models\Purchase;
use App\Models\Shop_rent;

// تأكيد أن النماذج لم تَعُد تفتح كل الأعمدة للإسناد الجماعي ($guarded = []).

it('guards the primary key on Shop_rent', function () {
    expect((new Shop_rent())->getGuarded())->toBe(['shop_rent_id']);
});

it('guards the primary key on Municip', function () {
    expect((new Municip())->getGuarded())->toBe(['municip_id']);
});

it('guards the primary key on Purchase', function () {
    expect((new Purchase())->getGuarded())->toBe(['purchase_id']);
});

it('never leaves these models fully unguarded', function () {
    foreach ([new Shop_rent(), new Municip(), new Purchase()] as $model) {
        expect($model->getGuarded())->not->toBe([]);
    }
});
