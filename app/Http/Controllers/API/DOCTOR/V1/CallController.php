<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\APIUserServiceInterface;
use \App\Repositories\CallHistoryRepositoryInterface;
use App\Models\CallHistory;
use App\Http\Responses\API\V1\Response;
use App\Http\Requests\PaginationRequest;

class CallController extends Controller
{
    protected $adminUserService;
    protected $callHistoryRepository;
    
    public function __construct(APIUserServiceInterface $APIUserService, CallHistoryRepositoryInterface $callHistoryRepository)
    {
        $this->adminUserService = $APIUserService;
        $this->callHistoryRepository = $callHistoryRepository;
    }

    public function call(Request $request)
    {
        $currentDocter = $this->adminUserService->getUser();
    
        $patientId = $request->get('patient_id');
        $data = [
            "user_id" => $patientId,
            "admin_user_id" => $currentDocter->id,
            "caller" => CallHistory::DOCTOR,
            "start_time" => date('Y-m-d H:i:s'),
            "end_time" => date('Y-m-d H:i:s')
        ];
        $callHistory  = $this->callHistoryRepository->create($data);

        if (empty($callHistory)) {
            return Response::response(50002);
        }

        return Response::response(200, $callHistory->toAPIArray()); 
    }

    public function update(Request $request, $id)
    {
        $type = $request->get('type');
        $callHistory =  $this->callHistoryRepository->find($id);
        if (empty($callHistory)) {
            return Response::response(20004);
        }

        try {
            $callHistory = $this->callHistoryRepository->update($callHistory, ["type" => $type, "end_time" => date('Y-m-d H:i:s')]);
        } catch (\Exception $e) {
            return Response::response(50002);
        }

        return Response::response(200, $callHistory->toAPIArray());  
    }

    public function checkRead()
    {
        $currentDocter = $this->adminUserService->getUser();
        $check = $this->callHistoryRepository->checkRead($currentDocter->id);

        return Response::response(200, ["status" => $check]);
    }

    public function history(PaginationRequest $request)
    {
        $page = intval($request->get('page', 1));
        $order = $request->get('order', 'id');
        $direction = $request->get('direction', 'DESC');
        $limit = intval($request->get('limit', 100));

        $paginate['offset']     = $request->offset();
        $paginate['limit']      = $request->limit($limit);
        $paginate['order']      = $request->order($order);
        $paginate['direction']  = $request->direction($direction);

        $count = $this->callHistoryRepository->count();
        $callHistories = $this->callHistoryRepository->get($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);
        $total = intval($count / $paginate['limit']) + (($count % $paginate['limit']) ? 1 : 0);

        foreach ($callHistories as $key => $callHistory) {
            $callHistories[$key] = $callHistory->toAPIArray();
        }

        $response = [
            'total_page' => $total,
            'current_page' => $page,
            'call_histories' => $callHistories
        ];

        $this->callHistoryRepository->updateIsRead();

        return Response::response(200, $response);
    }
}
