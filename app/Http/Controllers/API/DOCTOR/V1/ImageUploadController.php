<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\APIRequest;
use App\Http\Responses\API\V1\Response;
use App\Repositories\AdminUserRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Services\FileUploadServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageUploadController extends Controller
{
    protected $adminUserRepository;
    protected $adminUserService;
    protected $fileUploadService;

    public function __construct
    (
        AdminUserRepositoryInterface $adminUserRepository,
        APIUserServiceInterface $APIUserService,
        FileUploadServiceInterface $fileUploadService
    )
    {
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserService    = $APIUserService;
        $this->fileUploadService   = $fileUploadService;
    }
    public function avatar(APIRequest $request)
    {
        $idDoctor = $this->adminUserService->getUser()->id;
        if( !is_numeric($idDoctor) || ($idDoctor <= 0) ) {
            return Response::response(40001);
        }

        $adminUser = $this->adminUserRepository->find($idDoctor);
        if( empty($adminUser) ) {
            return Response::response(20004);
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            $newImage = $this->fileUploadService->upload(
                'user_profile_image',
                $file,
                [
                    'entity_type' => 'user_profile_image',
                    'entity_id'   => $adminUser->id,
                    'title'       => $request->input('name', ''),
                ]
            );

            if (!empty($newImage)) {
                $oldImage = $adminUser->coverImage;
                if (!empty($oldImage)) {
                    $this->fileUploadService->delete($oldImage);
                }

                $this->adminUserRepository->update($adminUser, ['profile_image_id' => $newImage->id]);
            }
        }
        $adminUser = $this->adminUserRepository->find($idDoctor);

        return Response::response(200, $adminUser->toAPIArrayUploadAvatar());

    }
}
