<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class InspiringCommandTest extends TestCase
{
    /**
     * A basic test example.
     * @return void
     * @throws \Throwable
     */
    public function testCanCreateStagingSnapshot()
    {
        $env = 'staging';
        $hash = Str::random(16);
        $arguments = [
            'env'  => $env,
            'hash' => $hash,
        ];
        $this->artisan('snapshots:run', $arguments);
        $this->assertCommandCalled('snapshots:run', $arguments);
        $this->assertTrue(Storage::disk($env)->exists("$hash.sql"));
        $this->assertNotEmpty(Storage::disk($env)->get("$hash.sql"));
    }


    /**
     * A basic test example.
     * @return void
     * @throws \Throwable
     */
    public function testCanCreateProductionSnapshot()
    {
        $env = 'production';
        $hash = Str::random(16);
        $arguments = [
            'env'  => $env,
            'hash' => $hash,
        ];
        $this->artisan('snapshots:run', $arguments);
        $this->assertCommandCalled('snapshots:run', $arguments);
        $this->assertTrue(Storage::disk($env)->exists("$hash.sql"));
        $this->assertNotEmpty(Storage::disk($env)->get("$hash.sql"));
    }
}
