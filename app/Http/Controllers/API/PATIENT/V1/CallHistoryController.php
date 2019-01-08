<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Models\Doctor;
use App\Models\PointDoctor;
use App\Models\PointPatient;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Repositories\PointDoctorRepositoryInterface;
use App\Repositories\PointPatientRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaginationRequest;

class CallHistoryController extends Controller
{
    protected $callHistoryRepository;
    protected $userService;
    protected $pointPatientRepository;
    protected $pointDoctorRepository;
    public function __construct
    (
        CallHistoryRepositoryInterface $callHistoryRepository,
        APIUserServiceInterface $APIUserService,
        PointPatientRepositoryInterface $pointPatientRepository,
        PointDoctorRepositoryInterface $pointDoctorRepository
    )
    {
        $this->callHistoryRepository = $callHistoryRepository;
        $this->userService = $APIUserService;
        $this->pointPatientRepository = $pointPatientRepository;
        $this->pointDoctorRepository  = $pointDoctorRepository;
    }

    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';
        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $callHistories = $this->callHistoryRepository->getByFilterWithPatient($this->userService->getUser()->id,$filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement

        foreach( $callHistories as $key => $callHistory ) {
            if($callHistory->caller != 'patient' or $callHistory->type != 2  )
            {
                $callHistory->is_read = 1;
                $callHistory->save();
            }
            $callHistories[$key] = $callHistory->toAPIArrayListForPatient();
        }


        return Response::response(200,$callHistories
        );
    }
    public function store(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'doctor_id'
            ]
        );

        $data = [
            'admin_user_id'=> $input['doctor_id'],
            'user_id' => $this->userService->getUser()->id,
            'caller'=>'patient',
            'type'=>3
        ];


        try {
            $callHistory = $this->callHistoryRepository->create($data);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }


        return Response::response(200,[
            'call_id'=>$callHistory->id
        ]);
    }

    public function updateEndtime(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'call_id'
            ]
        );
        $callHistory = $this->callHistoryRepository->find($input['call_id']);
        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }
        $timeNow = Carbon::now();
        if($callHistory->start_time == null)
        {
            $dataCallHistory = ['end_time'=>$timeNow,'start_time'=>$timeNow];
            $timeCall = 0;
        }
        else
        {
            $dataCallHistory = ['end_time'=>$timeNow];
            $timeCall = (int)$timeNow->timestamp - $callHistory['end_time']->timestamp;
        }


        $pointPatient = PointPatient::where('user_id',$this->userService->getUser()->id)->first();
        $pointDoctor = PointDoctor::where('admin_user_id',$callHistory['admin_user_id'])->first();
        $doctor = Doctor::where('admin_user_id',$callHistory['admin_user_id'])->first();

        $dataPointPatient = [
            'point'=>($pointPatient['point']-($doctor['price_call']/60*$timeCall) < 0)?0:$pointPatient['point']-($doctor['price_call']/60*$timeCall)
        ];
        $dataPointDoctor = [
            'point'=>($pointDoctor['point']+($doctor['price_call']/60*$timeCall) < 0)?0:$pointPatient['point']-($doctor['price_call']/60*$timeCall)
        ];
        try {
            DB::beginTransaction();

            $this->callHistoryRepository->update($callHistory,$dataCallHistory);

            $pointPatient = $this->pointPatientRepository->update($pointPatient,$dataPointPatient);
            $pointDoctor = $this->pointDoctorRepository->update($pointDoctor,$dataPointDoctor);
            DB::commit();
            return Response::response(200,['point'=>$pointPatient['point']]);

        } catch (\Exception $ex) {
            DB::rollBack();

            return Response::response(200,['status'=>false]);
        }


    }

    public function updateType(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'call_id',
                'type'
            ]
        );
        $timeNow = Carbon::now();
        $callHistory = $this->callHistoryRepository->find($input['call_id']);
        if($callHistory->type != 1 and $callHistory->type != 2)
        {
            $dataCallHistory = ['end_time'=>$timeNow,'type'=>$input['type']];
        }
        else
        {
            $dataCallHistory = [];
        }
        if($callHistory->end_time == null)
        {
            $timeCall = 0;
        }
        else
        {
            $timeCall = (int)$timeNow->timestamp - $callHistory['end_time']->timestamp;
        }
        $pointPatient = PointPatient::where('user_id',$this->userService->getUser()->id)->first();
        $pointDoctor = PointDoctor::where('admin_user_id',$callHistory['admin_user_id'])->first();
        $doctor = Doctor::where('admin_user_id',$callHistory['admin_user_id'])->first();

        $dataPointPatient = [
            'point'=>((int)floor($pointPatient['point']-($doctor['price_call']/60*$timeCall)) < 0)?0:(int)floor($pointPatient['point']-($doctor['price_call']/60*$timeCall))
        ];
        $dataPointDoctor = [
            'point'=>($pointDoctor['point']+($doctor['price_call']/60*$timeCall) < 0)?0:$pointPatient['point']-($doctor['price_call']/60*$timeCall)
        ];
        if( empty( $callHistory ) ) {
            return Response::response(50002);
        }
        try {
            DB::beginTransaction();

            $this->callHistoryRepository->update($callHistory,$dataCallHistory);

            $pointPatient = $this->pointPatientRepository->update($pointPatient,$dataPointPatient);
            $pointDoctor = $this->pointDoctorRepository->update($pointDoctor,$dataPointDoctor);
            DB::commit();
            return Response::response(200,['point'=>$pointPatient['point']]);

        } catch (\Exception $ex) {
            DB::rollBack();

            return Response::response(200,['status'=>false]);
        }
    }

    public function checkRead()
    {
        $idPatient = $this->userService->getUser()->id;

        $countRead = CallHistory::where('user_id',$idPatient)->where('caller','doctor')
            ->where('type',2)->where('is_read',0)->count();

        if($countRead >0)
        {
            return Response::response(200,[
                'status'=>true
            ]);
        }
        else
        {
            return Response::response(200,[
                'status'=>false
            ]);
        }
    }
}
