<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\PaginationRequest;

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
            if($callHistory->caller != 'patient' and $callHistory->type != 2  )
            {
                $callHistory->is_read = 1;
                $callHistory->save();
            }
            $callHistories[$key] = $callHistory->toAPIArrayListForDoctor();
        }


        return Response::response(200,$callHistories
        );
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
