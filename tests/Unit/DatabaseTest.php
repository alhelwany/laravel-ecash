<?php

use Illuminate\Support\Facades\Schema;

it('sets up the database and runs migrations', function () {
    expect(Schema::hasTable('ecash_payments'))->toBe(true);
});
