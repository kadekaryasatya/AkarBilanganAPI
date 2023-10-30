<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'validation error',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['nim'] = $user->nim;

        return response()->json([
            'success' => true,
            'message' => 'create success',
            'data' => $success
        ]);
    }

    public function login(Request $request){
        if (Auth::attempt(['nim' => $request->nim, 'password' => $request->password])){
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['nim'] = $auth->nim;

            return response()->json([
                'success' => true,
                'message' => 'login success',
                'data' => $success
            ]);
        }else{
            $this->register($request);
        }
    }

    public function getAllData() {
        $userData = User::all();
        return response()->json($userData);
    }
}
