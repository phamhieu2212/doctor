<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\BaseRequest;
use App\Models\AdminUser;
use App\Models\DoctorSpecialty;
use App\Models\Specialty;
use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Responses\API\V1\Me;
use App\Http\Responses\API\V1\Response;
use App\Services\FileUploadServiceInterface;

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

    public function getMe()
    {
        $adminUser = $this->userService->getUser();

        $adminResponse = $adminUser->toAPIArray();
        $adminResponse['id'] = $adminUser->id;
        $idSpecialties = DoctorSpecialty::where('admin_user_id',$adminResponse['id'])->pluck('specialty_id');
        $specialties = Specialty::whereIn('id',$idSpecialties)->get();

        foreach( $specialties as $key => $specialty ) {
            $specialties[$key] = $specialty->toAPIArray();
        }
        return Response::response(200,
            [
                'specialties' => $specialties,
                'doctor' => $adminResponse,

            ]);
    }

}