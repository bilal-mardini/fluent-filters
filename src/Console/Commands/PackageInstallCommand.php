<?php
namespace BilalMardini\FluentFilters\Console\Commands;

use Illuminate\Console\Command;

class PackageInstallCommand extends Command
{
    protected $signature = 'mypackage:install';
    protected $description = 'Print a message after package installation';

    public function handle()
    {
        echo "\033[32m";
        echo "\n";
        echo "
            ____  _ _       _   __  __               _ _       _ 
            | __ )(_) | __ _| | |  \/  | __ _ _ __ __| (_)_ __ (_)
            |  _ \| | |/ _` | | | |\/| |/ _` | '__/ _` | | '_ \| |
            | |_) | | | (_| | | | |  | | (_| | | | (_| | | | | | |
            |____/|_|_|\__,_|_| |_|  |_|\__,_|_|  \__,_|_|_| |_|_|
            ";
        echo "\n";
        echo "*   Thank you for installing MyPackage!  *\n";
        echo "\n";
    }
}
