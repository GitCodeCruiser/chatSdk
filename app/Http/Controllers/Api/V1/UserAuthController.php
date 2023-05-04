<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\UserAuthService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;

class UserAuthController extends Controller
{
    private $userAuthService;

    public function __construct(UserAuthService $userAuthService)
    {
        $this->userAuthService = $userAuthService;
    }

    public function signUp(UserRegisterRequest $request)
    {
        try {
            $user_phone_number = $this->userAuthService->signUp($request);

            return returnToApi('success', 'Registration completed successfully.', ['phone_number' => $user_phone_number]);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnToApi('error', 'Failed to complete registration.' . ' ' . $e->getMessage());
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            if (auth()->attempt(['number' => request('number'), 'password' => request('password')])) {
                $return_data = $this->userAuthService->login($request);
                $message = $return_data['message'];
                $return_object = $return_data['return_object'];
            } else {
                $message = 'Invalid credentials.';
                $return_object = ['user_login' => false];
            }

            return returnToApi('success', $message, $return_object);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnToApi('error', 'Failed to login user.' . ' ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = auth()->user();

            if (auth()->check()) {
                $user->token()->revoke();

                DB::commit();
                $message = 'Logout Successful.';
                $return_object = ['logout' => true];
            } else {
                $message = 'You are not logged in';
                $return_object = ['logout' => false];
            }
            return returnToApi('success', $message, $return_object);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnToApi('error', 'Failed to logout.' . ' ' . $e->getMessage());
        }
    }
}
