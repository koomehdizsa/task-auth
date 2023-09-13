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



}
