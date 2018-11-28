<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Models\Doctor;
use App\Models\Plan;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    protected $adminUserService;

    public function __construct
    (
        APIUserServiceInterface $APIUserService
    )
    {
        $this->adminUserService = $APIUserService;
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
}
