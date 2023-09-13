<?php

namespace App\Http\Controllers;

use App\Http\interfaces\CustomTokenInterface;
use App\Http\Requests\ForgotPasswordCallbackRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
use App\Models\Author;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\password;

class UserController extends Controller
{

    public function register(RegisterRequest $request, string $role,  CustomTokenInterface $customToken): \Illuminate\Http\JsonResponse
    {
        $user = User::firstOrNew(
            ['email' =>  $request->email],
            ['name' => $request->name,
                'role' => $role,
                'phone' => $request->phone,
                'password' => $request->password]
        );

        if($role == 'author')
            $temp = $this->createAuthor($request);
        elseif($role == 'account')
            $temp = $this->createAccount($request);
        else
            $temp = null;

        $user->accountable()->associate($temp);
        $user->save();

        return response()->json(['status' => 'Created', 'token' => $customToken->attempt($request->email, $request->password), 'user' => $user], 201);
    }

    //it should be private
    public function createAuthor(Request $request)
    {
        $author = new Author($request->only(['shaba', 'slug', 'profit', 'description']));
        $author->save();
        return $author;
    }

    private function createAccount(Request $request)
    {
        $account = new Account($request->only(['gender', 'city']));
        $account->save();
        return $account;
    }


    public function login(LoginRequest $request, CustomTokenInterface $customToken): \Illuminate\Http\JsonResponse
    {
        if($token = $customToken->attempt($request->email, $request->password))
        {
            return response()->json(['status' => 'Authorized', 'token' => $token ], 200);
        }
        else {
            return response()->json(['status'=>'Unauthorized'], 401);
        }
    }

    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => 'Accepted', 'user' => $request->user ], 200);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $users =  User::latest()->paginate(10);
        return response()->json(['status' => 'Accepted', 'users' => $users ], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $email = $request->email;
        $token = Crypt::encrypt($email);
        return response()->json(['url' => '/api/forgot/password/callback?token='.$token], 200);
    }

    public function forgotPasswordCallback(ForgotPasswordCallbackRequest $request): \Illuminate\Http\JsonResponse
    {
        $token = $request->token;
        try {
            $email = Crypt::decrypt($token);
        } catch (\Throwable $e) {
            return response()->json(['status'=>'Bad Request'], 400);
        }
        $user = User::query()->where('email', $email)->first();
        if($user && $request->password == $request->password2) {
            $user->password = $request->password;
            $user->save();
            return response()->json(['status' => 'Updated', 'user' => $user ], 200);
        }
        else
        {
            return response()->json(['status'=>'Bad Request'], 400);
        }
    }


}
