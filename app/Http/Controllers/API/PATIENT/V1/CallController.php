<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\Doctor;
use App\Models\PointPatient;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CallController extends Controller
{
    protected $userService;

    public function __construct
    (
        APIUserServiceInterface $APIUserService
    )
    {
        $this->userService = $APIUserService;
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
            return Response::response(20004);
        }
        return Response::response(200, $pointPatient['point']/$price);
    }
}
