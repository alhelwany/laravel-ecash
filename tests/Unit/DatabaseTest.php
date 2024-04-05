<?php

use Illuminate\Support\Facades\Schema;

it('sets up the database and runs migrations', function () {
    $this->assertTrue(Schema::hasTable('ecash_payments'));
});
