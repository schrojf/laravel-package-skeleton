<?php

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite as FoundationVite;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use VendorName\PackageName\PackageName;
use VendorName\PackageName\PackageNameServiceProvider;

beforeEach(function () {
    $this->originalHotFile = PackageName::$hotFile;
    $this->publicPath = sys_get_temp_dir().'/package-name-test-'.getmypid();

    PackageName::$hotFile = $this->publicPath.'/package-name.hot';
    $this->app->usePublicPath($this->publicPath);
});

afterEach(function () {
    PackageName::$hotFile = $this->originalHotFile;

    File::deleteDirectory($this->publicPath);
});

function publishAssets(): array
{
    File::copyDirectory(__DIR__.'/../../dist', public_path(PackageName::BUILD_DIRECTORY));

    return json_decode(File::get(public_path(PackageName::BUILD_DIRECTORY.'/manifest.json')), true);
}

function runHot(): void
{
    File::ensureDirectoryExists(dirname(PackageName::$hotFile));
    File::put(PackageName::$hotFile, 'http://localhost:5173');
}

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

test('published assets are loaded from the public path', function () {
    $manifest = publishAssets();

    expect(PackageName::assetsAreCurrent())->toBeTrue()
        ->and(PackageName::css()->toHtml())
        ->toContain('<link rel="stylesheet" href="'.asset(PackageName::BUILD_DIRECTORY.'/'.$manifest['resources/css/package-name.css']['file']).'"')
        ->and(PackageName::js()->toHtml())
        ->toStartWith('<script>window.PackageName = ')
        ->toContain('<script type="module" src="'.asset(PackageName::BUILD_DIRECTORY.'/'.$manifest['resources/js/package-name.js']['file']).'"');
});

test('outdated published assets fall back to inline assets', function () {
    publishAssets();

    File::put(public_path(PackageName::BUILD_DIRECTORY.'/manifest.json'), '{}');

    expect(PackageName::assetsAreCurrent())->toBeFalse()
        ->and(PackageName::css()->toHtml())->toStartWith('<style>')
        ->and(PackageName::js()->toHtml())->toStartWith('<script type="module">');
});

test('assets are loaded from the vite development server while running hot', function () {
    runHot();

    expect(PackageName::css()->toHtml())
        ->toContain('src="http://localhost:5173/@vite/client"')
        ->toContain('href="http://localhost:5173/resources/css/package-name.css"')
        ->and(PackageName::js()->toHtml())
        ->toContain('src="http://localhost:5173/resources/js/package-name.js"');
});

test('vite tags use the vite csp nonce', function () {
    Vite::useCspNonce('test-nonce');

    publishAssets();

    expect(PackageName::css()->toHtml())->toContain('<link rel="stylesheet" href="')->toContain(' nonce="test-nonce"')
        ->and(PackageName::js()->toHtml())->toStartWith('<script nonce="test-nonce">')->toContain(' nonce="test-nonce"></script>');
});

test('application vite configuration is not changed', function () {
    runHot();

    PackageName::css();

    expect(app(FoundationVite::class)->hotFile())->toBe(public_path('/hot'));
});

test('assets are published with the laravel-assets tag', function () {
    $paths = ServiceProvider::pathsToPublish(PackageNameServiceProvider::class, 'laravel-assets');

    expect($paths)->toHaveCount(1)
        ->and(realpath(array_key_first($paths)))->toBe(realpath(__DIR__.'/../../dist'))
        ->and(reset($paths))->toEndWith('/'.PackageName::BUILD_DIRECTORY);
});

test('app view renders inline tags unescaped', function () {
    $this->get('/app')
        ->assertOk()
        ->assertSee('<style>', false)
        ->assertSee('<script type="module">', false);
});

test('workbench hello view renders the package assets', function () {
    $this->get('/hello')
        ->assertOk()
        ->assertSee('<style>', false)
        ->assertSee('<script type="module">', false);
});
