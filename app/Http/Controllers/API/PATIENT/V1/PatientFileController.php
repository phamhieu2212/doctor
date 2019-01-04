<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Requests\APIRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\FilePatientImage;
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

    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';
        $filter = [];
        $patient = $this->userService->getUser();

        $filePatients = $this->filePatientRepository->getByFilterWithPatient($patient,$filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement
        foreach( $filePatients as $key => $filePatient ) {
            $filePatients[$key] = $filePatient->toAPIArrayList();
        }

        return Response::response(200,[$filePatients]
        );
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

    public function update($id,APIRequest $request)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $filePatient = $this->filePatientRepository->find($id);
        if( empty($filePatient) ) {
            return Response::response(20004);
        }

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
            $filePatient = $this->filePatientRepository->update($filePatient,$data);
            $filePatientImageDeletes = FilePatientImage::where('file_patient_id',$filePatient['id'])->get();
            foreach($filePatientImageDeletes as $filePatientImageDelete)
            {
                $filePatientImageDelete->delete();
            }

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
