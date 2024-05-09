<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $usermd;

    public function __construct(User $user) {
        $this->usermd = $user;
    }

    public function storeUser(StoreUserRequest $request) {
        try{
            $user = $this->usermd->create([
                'name'=>$request->username,
                'password'=>bcrypt($request->password),
                'email'=>$request->email,
                'access_token'=>uuid_create('4')
            ]);
            return response()->json([
              'success' => 'Usuário criado com sucesso',
              'user' => $user
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            return response()->json([
              'error' => $e->getMessage()
            ], 400, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }

    public function login(LoginRequest $request) {
        auth()->logout();
        if(!(isset($request->username) || isset($request->email)))
            return response()->json([
                'error' => 'Informe o Usuário ou o Email para efetuar o login'
            ]);

        if(
            auth()->attempt(['name' => $request->username, 'password' => $request->password]) ||
            auth()->attempt(['email' => $request->email, 'password' => $request->password])
        ){
            if(!(auth()->id() == 1 || auth()->id() == 2)){
                dd('para');
                auth()->user()->update([
                    'access_token' => uuid_create()
                ]);
            }
            return response()->json([
                'success' => 'Bem-vindo ao ChatApi '.auth()->user()->name.', para acessar as informações utilize o seguinte token para o acesso',
                'acces_token' => auth()->user()->access_token
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['error' => 'Usuario não encontrado'], 400);
    }

    public function show($user_id) {
        try{
            $user = $this->usermd->where('id', $user_id)->first();
            if(!$user)
                return response()->json([
                    'error' => 'Usuário não encontrado, verifique se o usuário está correto.'
                ], 400);

            return response()->json([
                'success' => 'Usuário encontrado',
                'user' => $user
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
