<?php
/**
 * Created by PhpStorm.
 * User: mehdi
 * Date: 8/26/2017 AD
 * Time: 13:18
 */

namespace App\Http\interfaces;

interface CustomTokenInterface
{
    public function attempt($username, $password);
    public function auth($token);
}
