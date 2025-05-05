<?php

namespace Vendor\PackageName;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use RuntimeException;

class PackageName
{
    /**
     * Return CSS for the Package Name app.
     */
    public function css(): string
    {
        $css = __DIR__.'/../dist/package-name.css';

        if (($contents = @file_get_contents($css)) === false) {
            throw new RuntimeException("Unable to load Package Name app CSS path [{$css}].");
        }

        return "<style>{$contents}</style>".PHP_EOL;
    }

    /**
     * Return the compiled JavaScript from the vendor directory.
     */
    public function js(): string
    {
        if (($js = @file_get_contents(__DIR__.'/../dist/package-name.js')) === false) {
            throw new RuntimeException('Unable to load the Package Name app JavaScript.');
        }

        $packageName = Js::from(static::scriptVariables());

        return new HtmlString(<<<HTML
            <script type="module">
                window.PackageName = {$packageName};
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
