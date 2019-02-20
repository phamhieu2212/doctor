<?php
namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Controllers\API\V1\QuickbloxController;
use App\Http\Responses\API\V1\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Repositories\PatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Http\Requests\BaseRequest;
use Intervention\Image\Facades\Image;


class PatientController extends Controller {
    protected $patientRepository;
    protected $userService;
    protected $fileUploadService;
    protected $imageRepository;
    protected $quickBlox;

    public function __construct(
        PatientRepositoryInterface $patientRepository,
        APIUserServiceInterface $userService,
        FileUploadServiceInterface $fileUploadService,
        ImageRepositoryInterface $imageRepository,
        QuickbloxController $quickblox
    ){
        $this->patientRepository = $patientRepository;
        $this->userService = $userService;
        $this->fileUploadService = $fileUploadService;
        $this->imageRepository = $imageRepository;
        $this->quickBlox = $quickblox;
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
            return response()->json(['code' => 503, 'message' => 'Không thể cập nhật dữ liệu', 'data' => null]);
        }
        $this->quickBlox->updateUser($currentUser->quick_id,$data['full_name']);

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
                return response()->json(['code' => 503, 'message' => 'Không tồn tại ảnh', 'data' => null]);
            }

            $patient = $this->patientRepository->update( $patient, ['profile_image_id' => $newImage->id] );

            if( !empty( $currentImage ) ) {
                $this->fileUploadService->delete( $currentImage );
                $this->imageRepository->delete( $currentImage );
            }
            $this->genImage($newImage,$currentUser);

            return Response::response(200, ['url' => $newImage->present()->url]);
        }


        return Response::response(200, ['url' => null]);
    }

    public function genImage($newImage,$currentUser)
    {
        $nameImage = $newImage->url;
        Image::make(file_get_contents('static/common/img/users/'.$nameImage))->encode('jpg')->rotate(-90)->save('static/common/img/quick-ava/'.$currentUser->quick_id.'.jpg');
    }
}
