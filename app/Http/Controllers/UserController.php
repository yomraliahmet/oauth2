<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param UserLoginRequest $loginRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $loginRequest)
    {
        if (Auth::attempt(['email' => $loginRequest->email, 'password' => $loginRequest->password])) {
            $user = Auth::user();

            $success = [
                'code'    => 'success',
                'title'   => 'Success',
                'message' => 'Login successful',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'token' => $user->createToken('OAuth2')->accessToken,
                ]
            ];
            return response()->json($success, 200);
        } else {
            $error = [
                'code'    => 'validation',
                'title'   => 'Error',
                'message' => 'The information you entered is incorrect!',
            ];
            return response()->json($error, 401);
        }
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $input = $request->all();

        $user = User::create($input);
        if($user->save()){

            if($request->has("address")){
               $user->adresses()->createMany($request->input("address"));
            }

            $user->access_token = $user->createToken('OAuth2')->accessToken;

            $success = [
                'code'    => 'success',
                'title'   => 'Success',
                'message' => 'User create successful',
                'data' => new UserResource($user)
            ];
            return response()->json($success, 200);
        } else {
            $error = [
                'code'    => 'validation',
                'title'   => 'Error',
                'message' => 'An error occurred while recording.',
            ];
            return response()->json($error, 400);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = Auth::user();
        $user->load("adresses");

        $success = [
            'code'    => 'success',
            'title'   => 'Success',
            'message' => 'User create successful',
            'data' => new UserResource($user)
        ];
        return response()->json($success, 200);
    }
}
