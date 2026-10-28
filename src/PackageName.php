<?php

namespace VendorName\PackageName;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use RuntimeException;

class PackageName
{
    /**
     * Get the CSS for the PackageName app.
     */
    public static function css(): Htmlable
    {
        $css = static::distContents('package-name.css');

        $nonce = static::nonceAttribute();

        return new HtmlString(<<<HTML
            <style{$nonce}>{$css}</style>
            HTML);
    }

    /**
     * Get the JavaScript for the PackageName app.
     */
    public static function js(): Htmlable
    {
        $js = static::distContents('package-name.js');

        $variables = Js::from(static::scriptVariables());

        $nonce = static::nonceAttribute();

        return new HtmlString(<<<HTML
            <script type="module"{$nonce}>
                window.PackageName = {$variables};
                {$js}
            </script>
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
     * Get the contents of the given compiled asset.
     */
    protected static function distContents(string $file): string
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
        if (($nonce = Vite::cspNonce()) === null) {
            return '';
        }

        return ' nonce="'.e($nonce).'"';
    }
}
