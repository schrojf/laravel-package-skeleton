<?php

namespace VendorName\PackageName;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use RuntimeException;

class PackageName
{
    /**
     * The directory the assets are published to, relative to the public path.
     */
    public const BUILD_DIRECTORY = 'vendor/package-name';

    /**
     * The path to the "hot" file written while the Vite development server is running.
     */
    public static string $hotFile = __DIR__.'/../vite.hot';

    /**
     * Get the CSS for the PackageName app.
     */
    public static function css(): Htmlable
    {
        if (static::shouldInlineAssets()) {
            $css = static::distContents('resources/css/package-name.css');

            $nonce = static::nonceAttribute();

            return new HtmlString(<<<HTML
                <style{$nonce}>{$css}</style>
                HTML);
        }

        return static::vite('resources/css/package-name.css');
    }

    /**
     * Get the JavaScript for the PackageName app.
     */
    public static function js(): Htmlable
    {
        $variables = Js::from(static::scriptVariables());

        $nonce = static::nonceAttribute();

        if (static::shouldInlineAssets()) {
            $js = static::distContents('resources/js/package-name.js');

            return new HtmlString(<<<HTML
                <script type="module"{$nonce}>
                    window.PackageName = {$variables};
                    {$js}
                </script>
                HTML);
        }

        $script = static::vite('resources/js/package-name.js');

        return new HtmlString(<<<HTML
            <script{$nonce}>window.PackageName = {$variables};</script>
            {$script}
            HTML);
    }

    /**
     * Get the default JavaScript variables for the PackageName app.
     */
    public static function scriptVariables(): array
    {
        return [
            //
        ];
    }

    /**
     * Determine if the published assets are up-to-date with the package's compiled assets.
     */
    public static function assetsAreCurrent(): bool
    {
        $publishedManifest = public_path(static::BUILD_DIRECTORY.'/manifest.json');

        return is_file($publishedManifest)
            && file_get_contents($publishedManifest) === static::distFile('manifest.json');
    }

    /**
     * Determine if the assets should be inlined, rather than loaded from the Vite development server or the published assets.
     *
     * Inlined assets can't load chunks or reference other assets, such as fonts or images.
     */
    protected static function shouldInlineAssets(): bool
    {
        return ! is_file(static::$hotFile) && ! static::assetsAreCurrent();
    }

    /**
     * Get the Vite tags for the given entrypoint.
     */
    protected static function vite(string $entrypoint): HtmlString
    {
        // Clone the application's Vite instance, so its configuration is kept but not changed
        $vite = (clone app(Vite::class))->useHotFile(static::$hotFile);

        return $vite($entrypoint, static::BUILD_DIRECTORY);
    }

    /**
     * Get the contents of the compiled asset for the given entrypoint.
     */
    protected static function distContents(string $entrypoint): string
    {
        $manifest = json_decode(static::distFile('manifest.json'), true);

        if (! isset($manifest[$entrypoint]['file'])) {
            throw new RuntimeException("Unable to locate the PackageName asset [{$entrypoint}] in the manifest.");
        }

        return static::distFile($manifest[$entrypoint]['file']);
    }

    /**
     * Get the contents of the given compiled file.
     */
    protected static function distFile(string $file): string
    {
        $path = __DIR__.'/../dist/'.$file;

        if (($contents = @file_get_contents($path)) === false) {
            throw new RuntimeException("Unable to load the PackageName asset [{$path}].");
        }

        return $contents;
    }

    /**
     * Get the nonce attribute for inline tags, using the application's Vite CSP nonce.
     */
    protected static function nonceAttribute(): string
    {
        if (($nonce = app(Vite::class)->cspNonce()) === null) {
            return '';
        }

        return ' nonce="'.e($nonce).'"';
    }
}
