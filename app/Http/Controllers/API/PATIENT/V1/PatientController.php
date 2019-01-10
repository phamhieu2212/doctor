<?php
namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Repositories\PatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Http\Requests\BaseRequest;


class PatientController extends Controller {
    protected $patientRepository;
    protected $userService;
    protected $fileUploadService;
    protected $imageRepository;

    public function __construct(
        PatientRepositoryInterface $patientRepository,
        APIUserServiceInterface $userService,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository
    ){
        $this->patientRepository = $patientRepository;
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
    }

    public function show()
    {
        $currentUser = $this->userService->getUser();
        $patient = $currentUser->patient;
        
        return Response::response(200, $patient->toAPIArray()); 
    }

    public function update(BaseRequest $request)
    {
        $currentUser = $this->userService->getUser();
        $patient = $currentUser->patient;
        $data = $request->only(
            [
                'full_name', 'birth_day', 'gender', 'identification',
                'country', 'nation', 'job', 'email',
                'province', 'district', 'ward', 'address', 'name_of_relatives',
                'relationship', 'phone_of_relatives'
            ]
        );

        try {
            $this->patientRepository->update($patient, $data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( $request->hasFile( 'cover_image' ) ) {
            $currentImage = $patient->profileImage;
            $file = $request->file( 'cover_image' );
            $newImage = $this->fileUploadService->upload(
                'user_profile_image',
                $file,
                [
                    'entityType' => 'article',
                    'entityId'   => $patient->id,
                    'title'      => $request->input( 'name', '' ),
                ]
            );

            if( !empty( $newImage ) ) {
                $patient = $this->patientRepository->update( $patient, ['profile_image_id' => $newImage->id] );

                if( !empty( $currentImage ) ) {
                    $this->fileUploadService->delete( $currentImage );
                    $this->imageRepository->delete( $currentImage );
                }
            }
        }

        return Response::response(200, $patient->toAPIArray());
    }

    public function uploadAvatar(BaseRequest $request)
    {
        $currentUser = $this->userService->getUser();
        $patient = $currentUser->patient;

        if( $request->hasFile( 'avatar' ) ) {
            $currentImage = $patient->profileImage;
            $file = $request->file( 'avatar' );
            $newImage = $this->fileUploadService->upload(
                'user_profile_image',
                $file,
                [
                    'entityType' => 'patient',
                    'entityId'   => $patient->id,
                    'title'      => $request->input( 'name', '' ),
                ]
            );

            if(empty($newImage)) {
                return Response::response(50002);
            }

            $patient = $this->patientRepository->update( $patient, ['profile_image_id' => $newImage->id] );

            if( !empty( $currentImage ) ) {
                $this->fileUploadService->delete( $currentImage );
                $this->imageRepository->delete( $currentImage );
            }

            return Response::response(200, ['url' => $newImage->present()->url]);
        }

        return Response::response(200, ['url' => null]);
    }
}
