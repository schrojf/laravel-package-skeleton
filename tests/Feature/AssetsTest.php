<?php

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Vite;
use VendorName\PackageName\PackageName;

test('css is inlined in a style tag', function () {
    $css = PackageName::css();

    expect($css)->toBeInstanceOf(Htmlable::class)
        ->and($css->toHtml())->toStartWith('<style>')->toEndWith('</style>');
});

test('js is inlined in a module script tag with script variables', function () {
    $js = PackageName::js();

    expect($js)->toBeInstanceOf(Htmlable::class)
        ->and($js->toHtml())
        ->toStartWith('<script type="module">')
        ->toContain('window.PackageName = ')
        ->toEndWith('</script>');
});

test('inline tags use the vite csp nonce', function () {
    Vite::useCspNonce('test-nonce');

    expect(PackageName::css()->toHtml())->toStartWith('<style nonce="test-nonce">')
        ->and(PackageName::js()->toHtml())->toStartWith('<script type="module" nonce="test-nonce">');
});

test('app view renders inline tags unescaped', function () {
    $this->get('/app')
        ->assertOk()
        ->assertSee('<style>', false)
        ->assertSee('<script type="module">', false);
});
