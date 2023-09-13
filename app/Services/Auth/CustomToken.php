<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 8/26/2017 AD
 * Time: 13:18
 */

namespace App\Services\Auth;

use App\Http\interfaces\CustomTokenInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class CustomToken implements CustomTokenInterface
{
    public function attempt($username, $password): ?string
    {
        $user = User::query()->where('email', $username)->first();
//        $auth = Hash::check($password, ($user?->password));

        if($user && $password == unserialize($this->decrypt($user?->password)))
            return $this->encrypt(json_encode($user));
        else
            return null;
    }

    public function auth($token): ?string
    {
        $token = last(explode(" ", $token));
        if($token)
            try {
                $user = $this->decrypt($token);
            } catch (\Throwable $e) {
                return null;
            }
        else
            $user = null;
        return $user;
    }

    private function encrypt($plain): string
    {
        return Crypt::encryptString($plain);
    }

    private function decrypt($secret): string
    {
        return Crypt::decryptString($secret);
    }
}
