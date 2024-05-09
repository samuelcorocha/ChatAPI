<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $usermd;

    public function __construct(User $user)
    {
        $this->usermd = $user;
    }

    public function storeUser(StoreUserRequest $request)
    {
        // The incoming request is valid...

        // Retrieve the validated input data...
        $validated = $request->validated();

        dd($request, $request->username, uuid_create('4'));
        $user = $this->usermd->create([
            'name'=>$request->username,
            'password'=>bcrypt($request->password),
            'email'=>$request->email,
            'access_token'=>uuid_create('4')
        ]);
        return response()->json($user, 200);
    }
}
