<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Requests\APIRequest;
use App\Http\Responses\API\V1\Response;
use App\Repositories\FilePatientImageRepositoryInterface;
use App\Repositories\FilePatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PatientFileController extends Controller
{
    protected $userService;
    protected $filePatientRepository;
    protected $filePatientImageRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        FilePatientRepositoryInterface $filePatientRepository,
        FilePatientImageRepositoryInterface $filePatientImageRepository
    )
    {
        $this->userService = $APIUserService;
        $this->filePatientRepository = $filePatientRepository;
        $this->filePatientImageRepository = $filePatientImageRepository;
    }
    public function store(APIRequest $request)
    {
        $data = array();
        $dataFileImage = array();
        $bodyRequests = $request->all();
        $data['name'] = $bodyRequests['name'];
        $data['title'] = $bodyRequests['title'];
        $data['description'] = $bodyRequests['description'];
        $data['started_at'] = $bodyRequests['started_at'];
        $data['user_id'] = $this->userService->getUser()->id;
        $imageArray = $bodyRequests['images'];
        try {
            DB::beginTransaction();
            $filePatient = $this->filePatientRepository->create($data);
            $dataFileImage['file_patient_id'] = $filePatient['id'];

            foreach($imageArray as $key=>$images)
            {
                $dataFileImage['type'] = $key;
                foreach($images as $imageId)
                {
                    $dataFileImage['image_id'] = $imageId;
                    $this->filePatientImageRepository->create($dataFileImage);
                }
            }
            DB::commit();


            return Response::response(200,['filePatient'=>$filePatient->toAPIArrayList()]);

        } catch (\Exception $ex) {
            DB::rollBack();

            return Response::response(200,['status'=>false]);
        }


    }
}
