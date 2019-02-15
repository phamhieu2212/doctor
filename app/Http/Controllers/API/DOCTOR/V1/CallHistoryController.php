<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaginationRequest;
use Illuminate\Support\Facades\DB;

class CallHistoryController extends Controller
{
    protected $callHistoryRepository;
    protected $adminUserService;

    public function __construct
    (
        CallHistoryRepositoryInterface $callHistoryRepository,
        APIUserServiceInterface $APIUserService
    )
    {
        $this->callHistoryRepository = $callHistoryRepository;
        $this->adminUserService = $APIUserService;
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

        $callHistories = $this->callHistoryRepository->getByFilterWithDoctor($this->adminUserService->getUser()->id,$filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement

        foreach( $callHistories as $key => $callHistory ) {
            if($callHistory->caller != 'doctor' or $callHistory->type != 2  )
            {
                $callHistory->is_read = 1;
                $callHistory->save();
            }
            $callHistories[$key] = $callHistory->toAPIArrayListForDoctor();
        }


        return Response::response(200,$callHistories
        );
    }

    public function store(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'patient_id'
            ]
        );

        $data = [
            'user_id'=> $input['patient_id'],
            'admin_user_id' => $this->adminUserService->getUser()->id,
            'caller'=>'doctor',
            'type'=>3
        ];


        try {
            $callHistory = $this->callHistoryRepository->create($data);
        } catch (\Exception $e) {
            return response()->json(['code' => 503, 'message' => 'Không thể thêm mới dữ liệu', 'data' => null]);
        }

        if( empty( $callHistory ) ) {
            return response()->json(['code' => 503, 'message' => 'Không thể thêm mới dữ liệu', 'data' => null]);
        }


        return Response::response(200,[
            'call_id'=>$callHistory->id
        ]);
    }


    public function checkRead()
    {
        $idDoctor = $this->adminUserService->getUser()->id;

        $countRead = CallHistory::where('admin_user_id',$idDoctor)->where('caller','patient')
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

    public function updateEndtime(\App\Http\Requests\API\V1\Request $request)
    {
        $input = $request->only(
            [
                'call_id'
            ]
        );
        $callHistory = $this->callHistoryRepository->find($input['call_id']);
        if( empty( $callHistory ) ) {
            return response()->json(['code' => 503, 'message' => 'Không tồn tại dữ liệu', 'data' => null]);
        }
        $timeNow = Carbon::now();
        if($callHistory->start_time == null)
        {
            $dataCallHistory = ['end_time'=>$timeNow,'start_time'=>$timeNow];
        }
        else
        {
            $dataCallHistory = ['end_time'=>$timeNow];
        }


        try {
            DB::beginTransaction();

            $this->callHistoryRepository->update($callHistory,$dataCallHistory);

            DB::commit();
            return Response::response(200,['status'=>true]);

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

        if( empty( $callHistory ) ) {
            return response()->json(['code' => 503, 'message' => 'Không tồn tại dữ liệu', 'data' => null]);
        }
        try {
            DB::beginTransaction();

            $this->callHistoryRepository->update($callHistory,$dataCallHistory);
            DB::commit();
            return Response::response(200,['status'=>true]);

        } catch (\Exception $ex) {
            DB::rollBack();

            return Response::response(200,['status'=>false]);
        }
    }
}
