<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Http\interfaces\CustomTokenInterface;
use App\Http\Requests\ForgotPasswordCallbackRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\Author;
use App\Models\User;
use App\Services\Auth\CustomToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;

use Mockery;

class UserControllerTest extends TestCase
{
//    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function tearDown(): void
    {
        Mockery::close(); // Release the mock after each test
    }


    public function testCreateAuthor()
    {

        $requestData = [
            'shaba' => '12345',
            'slug' => 'sample-author',
            'profit' => 1000,
            'description' => 'Sample author description',
        ];

        $request = new Request($requestData);

        $controller = new UserController();

        $author = $controller->createAuthor($request);

        $this->assertInstanceOf(Author::class, $author);

        $this->assertEquals($requestData['shaba'], $author->shaba);
        $this->assertEquals($requestData['slug'], $author->slug);
        $this->assertEquals($requestData['profit'], $author->profit);
        $this->assertEquals($requestData['description'], $author->description);
    }


    public function testLoginWithValidCredentials()
    {
        $requestData = [
            'email' => 'safa@gmail.com',
            'password' => '123456',
        ];

        $request = new LoginRequest($requestData);

        $customToken = $this->createMock(CustomTokenInterface::class);
        $customToken->expects($this->once())
            ->method('attempt')
            ->with('safa@gmail.com', '123456')
            ->willReturn('eyJpdiI6IjFEMjkxeEloZVpFcWVITzVQY1p3WlE9PSIsInZhbHVlIjoiTEJLUnJJQjVuTjdxMlIwSDFySTJWVmZtVlhjWk5PeTE3TlJ0U1ZPYS9WUFR3ZWh5Um5GZU9ERHY2YXdzVHZqQjdnM204Y1VRRitmUnNteTFzRDYrOE1zbGZFWUlCZEN1UGJsV2ZpbDlBOGpMNHBraWlpSjc3Qm1kYVNMdnI1UmpsWm9OMXJRaXhQcDJkdzRCMncydmE4ZCtmNGRYaFFJdjZYUHBwMndKbVZlY0JZa3gwb2phUkppRnAzTjlzWmV4WGo1eFZndlFlQ2RHVStEVG5zUDdwWWxjb2JuUXRpelovNEpSbjAzRkpMSitsM1M5TlRpZ2V6NnZobk82OElFSlBjcktqemVScUZNaURQdmZrS2tuNTRPcjJheklxN3EzaWhEMXVLODQvVWFSaDk5SDdyNlZKeTM1aGM3b2NnSVAiLCJtYWMiOiIwMGIxMWUxZDZlZjcwNDBiZWJhN2NjNjc4ZmM1YjBmMWEwZjQ2MDQwZjBhMjg5NzU0MzFmMmJiYmI0NDA5MGIyIiwidGFnIjoiIn0=');

        $authController = new UserController();

        $response = $authController->login($request, $customToken);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertJson($response->getContent(), ['status' => 'Authorized'] );;
    }

    public function testProfile()
    {
        $user = User::query()->first();

        $request = Request::create('/profile', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        \Illuminate\Support\Facades\Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile']);

        $response = $this->json('GET', '/profile');

//        $response->assertJson(['status' => 'Accepted', 'user' => '*']);
        $response->assertStatus(200);
    }


    public function testForgotPassword()
    {
        $request = $this->getMockBuilder(ForgotPasswordRequest::class)->getMock();


        $controller = new UserController();


        $response = $controller->forgotPassword($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertJson(['url' => '/api/forgot/password/callback?token=encrypted_token'], $response->getContent());
    }

    public function testForgotPasswordCallbackWithValidData()
    {
        $user = User::query()->first();

        $requestData = [
            'token' => 'eyJpdiI6ImJaclpvUmhZblZucjlMWldQSEJGVGc9PSIsInZhbHVlIjoiZCticlhWQUVxNXoyMWtteU00R2dhc3pzUnBKQTZYcm4rdW9PYlRiM1NJZz0iLCJtYWMiOiJlYTBlM2E3ZDMyZDA4MTg1MGU1MWI5YTRkZjJkNmEyOTE5NWM0ZmZmNzZkMjMwNWY0OWMxODVjNTRiZWE2YjY4IiwidGFnIjoiIn0=',
            'password' => '123456',
            'password2' => '123456',
        ];

        $request = new ForgotPasswordCallbackRequest($requestData);

        $controller = new UserController();

        $response = $controller->forgotPasswordCallback($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertJson(['status' => 'Updated', 'user' => $user->toArray()], $response->getContent());
    }
}
