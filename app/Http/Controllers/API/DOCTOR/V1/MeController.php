<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\API\V1\Request;
use App\Http\Requests\BaseRequest;
use App\Models\AdminUser;
use App\Models\Clinic;
use App\Models\DoctorSpecialty;
use App\Models\Specialty;
use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Responses\API\V1\Me;
use App\Http\Responses\API\V1\Response;
use App\Services\FileUploadServiceInterface;
use Illuminate\Support\Facades\Validator;

class MeController extends Controller
{
    protected $userService;

    protected $provinceRepository;

    protected $districtRepository;

    protected $adminUserRepository;

    protected $fileUploadService;

    protected $imageRepository;

    public function __construct(
        APIUserServiceInterface $userService,

        AdminUserRepositoryInterface $adminUserRepository,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository
    )
    {
        $this->userService = $userService;
        $this->adminUserRepository = $adminUserRepository;
        $this->fileUploadService   = $fileUploadService;
        $this->imageRepository     = $imageRepository;
    }

    public function getPoint()
    {
        $adminUser = $this->userService->getUser();
        return Response::response(200,
            [
                'point' => $adminUser->point->point,

            ]);
    }

    public function getMe()
    {
        $adminUser = $this->userService->getUser();

        return Response::response(200,
            [
                'user' => $adminUser->toAPIArrayLoginDoctor(),
                'accountQuick' => [
                    'username' => $adminUser->username,
                    'password' => $adminUser->username
                ]

            ]);
    }

    public function changePassword(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'new_password' => 'required|min:8',
            'old_password' => 'required',
        ]);
        if ($validate->fails()) {
            return Response::response(40001);
        }

        $data = $request->only([
            'new_password','old_password'
        ]);

        $adminUser = $this->userService->getUser();
        if (password_verify($data['old_password'], $adminUser->password)) {
            $this->adminUserRepository->update($adminUser,['password'=>$data['new_password']]);
            return Response::response(200,[
                'status'=>true
            ]);
        }
        else
        {
            return Response::response(40003);
        }
    }

}