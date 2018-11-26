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
    public function index($idDoctor,$day)
    {
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

    public function order($idDoctor,$day)
    {
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
