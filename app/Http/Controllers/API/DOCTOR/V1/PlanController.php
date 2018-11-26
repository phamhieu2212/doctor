<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\APIRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\Clinic;
use App\Models\Plan;
use App\Repositories\PlanRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class PlanController extends Controller
{
    protected $adminUserService;
    protected $planRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        PlanRepositoryInterface $planRepository
    )
    {
        $this->adminUserService = $APIUserService;
        $this->planRepository = $planRepository;
    }
    public function index($day)
    {
        $doctor =  $this->adminUserService->getUser();
        $dateStart =  date("Y-m-d 00:00:00", strtotime($day.' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($day.' this week'));

        $plans = Plan::where('admin_user_id',$doctor->id)->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
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

    public function order()
    {
        $doctor =  $this->adminUserService->getUser();
        $now = Carbon::now();

        $plans = Plan::where('admin_user_id',$doctor->id)->where('started_at','>=',$now)->where('status',1)->get();
        $count = count($plans);
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }


        return Response::response(200,
            [
                'count' => $count,
                'plans'=> $plans
            ]
        );
    }

    public function create($day)
    {
        $doctor =  $this->adminUserService->getUser();
        $dateStart =  date("Y-m-d 00:00:00", strtotime($day.' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($day.' this week'));

         $plans = Plan::where('admin_user_id',$doctor->id)->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }

        $clinics = Clinic::where('admin_user_id',$doctor->id)->get();

        foreach($clinics as $key=>$clinic)
        {
            $clinics[$key] = $clinic->toAPIArray();

        }

        return Response::response(200,
            [
                'plans'=> $plans,
                'clinics' => $clinics
            ]
        );
    }

    public function store(APIRequest $request)
    {
        $data = $request->only([
            'day','hour','price','clinic_id'
        ]);
        $date =  date("Y-m-d", strtotime($data['day'].' this week'));
        $dateStart =  date("Y-m-d 00:00:00", strtotime($data['day'].' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($data['day'].' this week'));
        $arrayDateTimes = [];
        $hours = explode(',',$data['hour']);
        foreach($hours as $hour)
        {
            array_push($arrayDateTimes,date('Y-m-d H:i:s',strtotime($date. $hour.':00:00')));
        }
        $doctor =  $this->adminUserService->getUser();
        foreach($arrayDateTimes as $arrayDateTime)
        {
            $plan = Plan::where('admin_user_id',$doctor->id)->where('started_at',$arrayDateTime)->first();
            if(empty($plan))
            {
                $this->planRepository->create([
                    'admin_user_id' => $doctor->id,
                    'clinic_id'     => $data['clinic_id'],
                    'price'         => $data['price'],
                    'started_at'    => $arrayDateTime,
                    'ended_at'      => date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($arrayDateTime)))
                ]);
            }
            else
            {
                $this->planRepository->update($plan,[
                    'clinic_id'     => $data['clinic_id'],
                    'price'         => $data['price']
                ]);
            }
        }
        $planDelete = Plan::whereNotIn('started_at',$arrayDateTimes)->get();
        if(!empty($planDelete))
        {
            foreach($planDelete as $row)
            {$this->planRepository->delete($row);
            }
        }




        $plans = Plan::where('admin_user_id',$doctor->id)->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }

        $clinics = Clinic::where('admin_user_id',$doctor->id)->get();

        foreach($clinics as $key=>$clinic)
        {
            $clinics[$key] = $clinic->toAPIArray();

        }

        return Response::response(200,
            [
                'plans'=> $plans,
                'clinics' => $clinics
            ]
        );
    }
}