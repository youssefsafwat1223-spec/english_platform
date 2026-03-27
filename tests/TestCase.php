<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = base_path('storage/framework/testing/views');

        File::ensureDirectoryExists($compiledPath);
        config(['view.compiled' => $compiledPath]);
    }
}
