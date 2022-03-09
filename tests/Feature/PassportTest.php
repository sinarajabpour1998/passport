<?php

namespace SRA\Passport\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SRA\Passport\Tests\TestCase;

class PassportTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_passport_list()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
