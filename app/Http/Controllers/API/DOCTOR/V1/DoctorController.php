<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Models\Doctor;
use App\Models\Plan;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AdminUserRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Http\Responses\API\V1\Response;

class DoctorController extends Controller
{
    protected $adminUserService;
    protected $adminUserRepository;
    protected $fileUploadService;
    protected $imageRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        AdminUserRepositoryInterface $adminUserRepository,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository
    )
    {
        $this->adminUserService = $APIUserService;
        $this->adminUserRepository =  $adminUserRepository;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
    }
    public function detail()
    {
        $doctor = Doctor::where('admin_user_id',$this->adminUserService->getUser()->id)->first();
        $dateStart = date("Y-m-d 00:00:00", strtotime('monday this week'));
        $dateEnd = date("Y-m-d 23:59:59", strtotime('sunday this week'));

        $plans =  Plan::where('admin_user_id',$this->adminUserService->getUser()->id)->where('started_at','>=',$dateStart)
            ->where('started_at','<=',$dateEnd)
            ->get();
        foreach( $plans as $key => $plan ) {
            $plans[$key] = $plan->toAPIArrayDetail();
        }
        return [
            'code'=>200,
            'status'=>'success',
            'data'=>
                [

                    "status"=> 0|1|2,
                    "avatar"=> "abc.com",
                    "rate"=> 2,
                    "count_rate"=> 100,
                    "doctor"=> $doctor->toAPIArrayDetail(),
                    "plans"=> $plans
                ]

        ];
    }

    public function uploadAvatar(Request $request)
    {
        $currentUser = $this->adminUserService->getUser();

        if( $request->hasFile( 'avatar' ) ) {
            $currentImage = $currentUser->profileImage;
            $file = $request->file( 'avatar' );
            $newImage = $this->fileUploadService->upload(
                'user_profile_image',
                $file,
                [
                    'entityType' => 'currentUser',
                    'entityId'   => $currentUser->id,
                    'title'      => $request->input( 'name', '' ),
                ]
            );

            if(empty($newImage)) {
                return Response::response(50002);
            }

            $patient = $this->adminUserRepository->update( $currentUser, ['profile_image_id' => $newImage->id] );

            if( !empty( $currentImage ) ) {
                $this->fileUploadService->delete( $currentImage );
                $this->imageRepository->delete( $currentImage );
            }

            return Response::response(200, ['url' => $newImage->present()->url]);
        }

        return Response::response(200, ['url' => null]);
    }
}
