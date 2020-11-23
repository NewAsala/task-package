<?php

namespace Akrad\Bridage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class userController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();

        return $users;
    }
}


