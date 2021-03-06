<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\APIRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\Clinic;
use App\Models\Plan;
use App\Repositories\PlanRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function index()
    {
        $now =  Carbon::now();
        $endDate =  date('Y-m-d 23:59:59', strtotime($now));
        $startDate =  date('Y-m-d 00:00:00', strtotime($now));
        $clinics = Clinic::where('admin_user_id',$this->adminUserService->getUser()->id)
            ->where('status',1)->get();
        foreach($clinics as $key=>$clinic)
        {
                $clinics[$key] = $clinic->toAPIArrayListPlanDoctor($this->adminUserService->getUser()->id,$startDate,$endDate);
        }

        return Response::response(200,
            $clinics
        );
    }

    public function order(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';

        $plans = $this->planRepository->getOrderByDoctor($this->adminUserService->getUser()->id,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);
        $count = $this->planRepository->countOrderByDoctor($this->adminUserService->getUser()->id);
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArrayOrder();

        }
        return Response::response(200,
            [
                'code'=>200,
                'status'=>'success',
                'data'=>
                    [
                        "count"=> $count,
                        "plans"=>$plans

                    ]
            ]
        );
    }


    public function store($idDoctor,$idClinic,$plans)
    {
        $arrayDateTimes = [];

        foreach($plans as $plan)
        {

            $day = date('Y-m-d',$plan['day']);
            foreach($plan['hours'] as $hour)
            {
                $time = date('Y-m-d H:i:s',strtotime($day. $hour.':00:00'));
                array_push($arrayDateTimes,$time);

                $this->planRepository->create([
                    'admin_user_id' => $idDoctor,
                    'clinic_id'     => $idClinic,
                    'price'         => 0,
                    'started_at'    => $time,
                    'ended_at'      => date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($time)))
                ]);
            }

        }
        return true;



    }

    public function update($idDoctor,$idClinic,$plans)
    {
        $arrayDateTimes = [];

        foreach($plans as $plan)
        {

            $day = date('Y-m-d',$plan['day']);
            $startDate =  date( 'Y-m-d 00:00:00', $plan['day']);
            $endDate =  date( 'Y-m-d 23:59:59', $plan['day']);

            foreach($plan['hours'] as $hour)
            {
                $time = date('Y-m-d H:i:s',strtotime($day. $hour.':00:00'));
                array_push($arrayDateTimes,$time);

                $plan = Plan::where('admin_user_id',$idDoctor)->where('clinic_id',$idClinic)
                    ->where('started_at',$time)->first();
                if(empty($plan))
                {
                    $this->planRepository->create([
                        'admin_user_id' => $idDoctor,
                        'clinic_id'     => $idClinic,
                        'price'         => 0,
                        'started_at'    => $time,
                        'ended_at'      => date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($time)))
                    ]);
                }

            }
            $planDelete = Plan::where('admin_user_id',$idDoctor)->where('clinic_id',$idClinic)
                ->where('started_at','>=',$startDate)->where('started_at','<=',$endDate)
                ->whereNotIn('started_at',$arrayDateTimes)->get();
            if(!empty($planDelete))
            {
                foreach($planDelete as $row)
                {
                    $this->planRepository->delete($row);
                }
            }


        }
        return true;



    }
}
