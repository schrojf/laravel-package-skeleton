<?php

use Illuminate\Support\Facades\Schema;

it('can test', function () {
    expect(true)->toBeTrue();
});

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('package is aware of routes', function () {
    $response = $this->get('/app');

    $response->assertStatus(200);
});

test('package is aware of console command', function () {
    $this->artisan('package-name')->assertExitCode(0);
});

test('package is aware of migration file', function () {
    expect(Schema::hasTable('package_name_table'))->toBeTrue();
});

test('package is aware of config file', function () {
    expect(config('package-name.ok'))->toBeTrue();
});
