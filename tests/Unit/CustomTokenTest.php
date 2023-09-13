<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Http\interfaces\CustomTokenInterface;
use App\Models\Author;
use App\Models\User;
use App\Services\Auth\CustomToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\LoginRequest;


class CustomTokenTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testAttemptWithValidCredentials()
    {

        $customToken = new CustomToken();

        $token = $customToken->attempt('safa@gmail.com', '123456');

        $this->assertNotNull($token);
        $this->assertIsString($token);
    }
}
