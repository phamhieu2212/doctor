<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Requests\API\V1\Request;
use App\Http\Responses\API\V1\Response;
use App\Models\Doctor;
use App\Models\PointPatient;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use App\Http\Controllers\Controller;

class CallController extends Controller
{
    protected $userService;
    protected $pointPatientRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        PointPatientRepositoryInterface $pointPatientRepository

    )
    {
        $this->userService = $APIUserService;
        $this->pointPatientRepository = $pointPatientRepository;

    }
    public function getTimeCall($idDoctor)
    {
        $patient = $this->userService->getUser();
        $doctor = Doctor::where('admin_user_id',$idDoctor)->first();
        if( empty($doctor) ) {
            return Response::response(20004);
        }
        $price = $doctor['price_call']/60;
        $pointPatient = PointPatient::where('user_id',$patient->id)->first();
        if( empty($pointPatient) ) {
            $data=[
                'user_id'=>$patient->id,
                'point'=>0
            ];
            try {
                $pointPatient = $this->pointPatientRepository->create($data);
            } catch (\Exception $e) {
                return Response::response(50002);
            }

        }
        if($price == 0)
        {
            $time = -1;
        }
        else
        {
            $time = (int)$pointPatient['point']/$price;
        }
        return Response::response(200, [
            'time'=> $time
        ]);
    }


}
