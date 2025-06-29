<?php

namespace VendorName\PackageName\Commands;

use Illuminate\Console\Command;

class PackageNameCommand extends Command
{
    public $signature = 'package-name';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
