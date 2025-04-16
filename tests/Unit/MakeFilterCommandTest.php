<?php

// tests/Console/MakeFilterCommandTest.php
namespace BilalMardini\FluentFilters\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MakeFilterCommandTest extends TestCase
{
    public function test_make_filter_command_creates_filter_file()
    {
        Artisan::call('make:filter DummyClassNameFilter name=John&age=30');

        $this->assertTrue(File::exists(app_path('Filters/DummyClassNameFilter.php')));
    }

    public function test_make_filter_command_creates_correct_filter_methods()
    {
        Artisan::call('make:filter DummyClassNameFilter name=John&age=30');

        $filterFilePath = app_path('Filters/DummyClassNameFilter.php');
        $fileContents = File::get($filterFilePath);

        $this->assertStringContainsString('public function name($value)', $fileContents);
        $this->assertStringContainsString('public function age($value)', $fileContents);
    }
}
