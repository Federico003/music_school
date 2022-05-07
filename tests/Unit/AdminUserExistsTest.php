<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

use App\Models\User;

class AdminUserExistsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $users = User::whereEncrypted('email', 'admin@example.com')->get();
        
        $this->assertTrue($users->count() > 0);
    }
}
