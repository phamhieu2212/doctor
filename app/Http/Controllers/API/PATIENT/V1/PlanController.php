<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Requests\APIRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\Clinic;
use App\Models\Plan;
use App\Repositories\PlanRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    protected $userService;
    protected $planRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        PlanRepositoryInterface $planRepository
    )
    {
        $this->userService = $APIUserService;
        $this->planRepository = $planRepository;
    }
    public function index($idDoctor,$timestamp)
    {
        $month =  date( 'Y-m', $timestamp);
        $endDateOfMonth =  date('Y-m-t 23:59:59', strtotime($month));
        $startDateOfMonth =  date('Y-m-01 00:00:00', strtotime($month));
        $clinics = Clinic::where('admin_user_id',$idDoctor)->get();
        foreach($clinics as $key=>$clinic)
        {
            $clinics[$key] = $clinic->toAPIArrayListPlanDoctor($idDoctor,$startDateOfMonth,$endDateOfMonth);
        }

        return Response::response(200,
            [
                'clinics'=>$clinics


            ]
        );
    }

    public function order()
    {
        return [
            'code'=>200,
            'status'=>'success',
            'data'=>
                [
                    "files"=> [
                        [ "id"=> 1, "name"=> "Bệnh án số 1" ],
                        ["id"=> 2, "name"=> "Bệnh án số 2" ]
                    ]
                ]

        ];
        $arr = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $dateStart =  date("Y-m-d 00:00:00", strtotime($arr[$day].' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($arr[$day].' this week'));

        $plans = Plan::where('admin_user_id',$idDoctor)->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }


        return Response::response(200,
            [
                'plans'=> $plans
            ]
        );
    }

    public function updateOrder(APIRequest $request)
    {
        return [
            'code'=>200,
            'status'=>'success',
            'data'=>
                [
                    "doctor_name" => "Cô giáo thảo",
                    "hospital_id" =>1,
                    "specialty_id"=> 1,
                    "clinic_id"=> 1,
                    "file_id"=> 1,
                    "hospital_name" =>"Bệnh viện VĐ",
                    "specialty_name"=> "Răng hàm mặt",
                    "clinic_name"=> "phòng khám số 1",
                    "file_name"=> "Bệnh án 1",
                    "date"=> "2018-12-11",
                    "day"=> 2,
                    "hour"=> 8,
                    'total_price'=>300000

                ]

        ];
        $arr = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $data = $request->only([
            'day','hour','admin_user_id'
        ]);
        $date =  date("Y-m-d", strtotime($arr[$data['day']].' this week'));
        $dateStart =  date("Y-m-d 00:00:00", strtotime($arr[$data['day']].' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($arr[$data['day']].' this week'));
        $arrayDateTimes = [];
        $hours = explode(',',$data['hour']);
        foreach($hours as $hour)
        {
            array_push($arrayDateTimes,date('Y-m-d H:i:s',strtotime($date. $hour.':00:00')));
        }
        $patient =  $this->userService->getUser();
        foreach($arrayDateTimes as $arrayDateTime)
        {
            $plan = Plan::where('admin_user_id',$data['admin_user_id'])->where('started_at',$arrayDateTime)->first();
            if(!empty($plan))
            {
                $this->planRepository->update($plan,[
                    'user_id'     => $patient->id,
                    'status'         => 1
                ]);
            }
        }



        $plans = Plan::where('admin_user_id',$data['admin_user_id'])->where('user_id',$patient->id)
            ->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }


        return Response::response(200,
            [
                'plans'=> $plans
            ]
        );
    }
}
