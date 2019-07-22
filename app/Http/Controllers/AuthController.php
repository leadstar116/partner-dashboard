<?php
/**
 * File AuthController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers;

use App\Laravue\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource as UserResource;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(new UserResource(Auth::user()), Response::HTTP_OK)->header('Authorization', $token);
        }

        return response()->json(new JsonResponse([], 'The email or password is incorrect, please try again!'), Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(User::where('email', $credentials['email'])->count()) {
            return response()->json(new JsonResponse([], 'This email already exists!'), Response::HTTP_UNAUTHORIZED);
        }

        $user = User::create([
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);
        $role = Role::findByName('User');
        $user->syncRoles($role);

        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(new UserResource(Auth::user()), Response::HTTP_OK)->header('Authorization', $token);
        }

        return response()->json(new JsonResponse([], 'There was an error during register'), Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json((new JsonResponse())->success([]), Response::HTTP_OK);
    }

    public function user()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @return mixed
     */
    private function guard()
    {
        return Auth::guard();
    }
}
