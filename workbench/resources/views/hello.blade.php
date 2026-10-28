@use('VendorName\PackageName\Facades\PackageName')
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Hello world!</title>
        {{ PackageName::css() }}
    </head>
    <body>
        <h1 class="text-3xl font-bold underline">
            Hello world!
        </h1>
        {{ PackageName::js() }}
    </body>
</html>
