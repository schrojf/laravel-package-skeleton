<?php

namespace VendorName\PackageName;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use RuntimeException;

class PackageName
{
    /**
     * Return CSS for the PackageName app.
     */
    public static function css(): string
    {
        $css = __DIR__.'/../dist/package-name.css';

        if (($contents = @file_get_contents($css)) === false) {
            throw new RuntimeException("Unable to load PackageName app CSS path [{$css}].");
        }

        return "<style>{$contents}</style>".PHP_EOL;
    }

    /**
     * Return the compiled JavaScript from the vendor directory.
     */
    public static function js(): string
    {
        if (($js = @file_get_contents(__DIR__.'/../dist/package-name.js')) === false) {
            throw new RuntimeException('Unable to load the PackageName app JavaScript.');
        }

        $variables = Js::from(static::scriptVariables());

        return new HtmlString(<<<HTML
            <script type="module">
                window.PackageName = {$variables};
                {$js}
            </script>
            HTML);
    }

    /**
     * Get the default JavaScript variables for Package.
     */
    public static function scriptVariables(): array
    {
        return [
            //
        ];
    }
}
