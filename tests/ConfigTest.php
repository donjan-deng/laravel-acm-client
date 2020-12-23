<?php

namespace Donjan\AcmClient\Test;

use File;

class ConfigTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        //File::delete(config('acm.path'));
        parent::tearDown();
    }

    /** @test */
    public function testGetConfig()
    {
        $this->artisan('acm:get-config')
                ->assertExitCode(0);
    }

    public function testSetConfig()
    {
        $this->assertEquals(config('app.name'), 'new name');
    }

}
