<?php

namespace App\Services;

use App\Models\User;
use App\Utilities\Constants;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserAuthRepository;

class UserAuthService
{
    private $userAuthRepository;
    private $uploadFileService;

    public function __construct(UserAuthRepository $userAuthRepository, UploadFileService $uploadFileService)
    {
        $this->userAuthRepository = $userAuthRepository;
        $this->uploadFileService = $uploadFileService;
    }

    public function signUp($request)
    {
        if ($request->hasFile('profile_pic')) {
            $filename = $this->uploadFileService->uploadFile($request->profile_pic, Constants::USER_PROFILE_IMAGES_PATH, Constants::USER_PROFILE_IMAGE_PREFIX);
            $compress = $this->uploadFileService->compressFile($request->profile_pic, Constants::USER_PROFILE_IMAGES_PATH, $filename);
        }

        return $this->userAuthRepository->signUp($request, $filename ?? null, $compress ?? null);
    }

    public function login($request)
    {
        $user = User::where('number', $request->number)->first();

        DB::beginTransaction();

        $token = $user->createToken('userAccessToken')->accessToken;
        $user['token'] = $token;
        $user['base_url'] = env('BASE_URL') . Constants::USER_PROFILE_IMAGES_PATH . '/';

        DB::commit();
        $message = 'User login successfully.';
        $return_object = ['user' => $user];

        return ['message' => $message, 'return_object' => $return_object];
    }
}