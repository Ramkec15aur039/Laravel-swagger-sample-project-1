<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
    

class AuthController extends Controller
{
   
/**
 * * @SWG\Post(
 *     path="/register",
 *     tags={"Users"},
 *     operationId="register",
 *     summary="Add new user",
  *     @SWG\Parameter(
    *          name="name",
    *          in="formData",
    *          type="string",
    *          required=true,
    *          description="User name",
    *      ),
    *		@SWG\Parameter(
    *          name="email",
    *          in="formData",
    *          type="string",
    *          required=true, 
    *          format="email",
    *          description="User Email",
    *      ),
    *		@SWG\Parameter(
    *          name="password",
    *          in="formData",
    *          type="string",
    *          required=true, 
    *          format="password",
    *          description="User Password",
    *      ),
     *		@SWG\Parameter(
    *          name="password confirmation",
    *          in="formData",
    *          type="string",
    *          required=true, 
    *          format="password",
    *          description="Confirm Password",
    *      ),
     *		@SWG\Parameter(
    *          name="image",
    *          in="formData",
    *          type="file",
    *          required=true, 
    *          description="User Image",
    *      ),
 *     @SWG\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @SWG\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="Resource not found"
 *     ),  
 * )
 *     @param RegisterRequest $request
 *     @return UserResource
 */

 public function register(RegisterRequest $request)
 {
     $user = User::create($request->except('password')+[
         'password' => bcrypt($request->password), 'api_token' => str::random(80),
         'image_path' => $request->file('image')->store('users', 'public')
     ]);

     return new UserResource($user);
 }

 /**
 * * @SWG\Post(
 *     path="/login",
 *     tags={"Users"},
 *     operationId="login",
 *     summary="Login",
  *     @SWG\Parameter(
    *          name="email",
    *          in="formData",
    *          type="string",
    *          required=true,
    *          description="User email",
    *      ),
    *		@SWG\Parameter(
    *          name="password",
    *          in="formData",
    *          type="string",
    *          required=true, 
    *          format="password",
    *          description="User Password",
    *      ),
 *     @SWG\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @SWG\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="Resource not found"
 *     ),  
 * )
 *     @param LoginRequest $request
 *     @return mixed
 */

public function login(LoginRequest $request)
    {
        $user = auth()->attempt($request->only(['email','password']));
        if(!$user){
            return response()->json(['errors' => [
                'message'=> "No User with inserted data"
            ]],422);
        }
        else{
            auth()->user()->update(['api_token'=>Str::random(80)]);
            return new UserResource(auth()->user());
        }

        return new UserResource($user);
    }

     /**
 * * @SWG\Post(
 *     path="/logout",
 *     tags={"Users"},
 *     operationId="logout",
 *     summary="Logout",
 *     security={{"Bearer":{}}},
 *     @SWG\Response(
 *         response=200,
 *         description="successful operation"
 *     ),
 *     @SWG\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="Resource not found"
 *     ),  
 * )
 *     @param Request $request
 *     @return \Illuminate\Http\JsonResponse
 */

public function logout(Request $request)
{
    $token = str::after($request->header('Authorization'), 'Bearer ');
    $user = User::where('api_token', $token)->first();
    if($user){
        $user -> update(['api_token' => null]);
        return response()->json([
            'message' => "User Logged Out"
        ],200);
    }
    else{
        return response()->json([
            'message' => "User not found!"
        ],200);
    }

    return new UserResource($user);
}
}
