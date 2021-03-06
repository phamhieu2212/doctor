<?php

namespace App\Http\Controllers\API\PATIENT\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Repositories\FCMNotificationRepositoryInterface;
use App\Models\FCMNotification;
use App\Services\APIUserServiceInterface;
use App\Http\Responses\API\V1\Response;
use App\Http\Requests\PaginationRequest;

class NotificationController extends Controller
{
    protected $notiRepo;
    protected  $userService;

    public function __construct(
        FCMNotificationRepositoryInterface $notiRepo,
        APIUserServiceInterface $APIUserService
    ){
        $this->notiRepo = $notiRepo;
        $this->userService = $APIUserService;
    }
    
    public function index(PaginationRequest $request)
    {

        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->get('offset',$request->offset());
        $paginate['order']      = 'id';
        $paginate['direction']  = 'desc';

        $currentUser = $this->userService->getUser();
        $count = $this->notiRepo->countByUserTypeAndUserId(FCMNotification::PATIENT, $currentUser->id);

        $notifications = $this->notiRepo->getWithPaginate(
            FCMNotification::PATIENT,
            $currentUser->id,
            $paginate['order'],
            $paginate['direction'],
            $paginate['offset'],
            $paginate['limit']
        );

        $total = intval($count / $paginate['limit']) + (($count % $paginate['limit']) ? 1 : 0);

        foreach ($notifications as $key => $notification) {
            $notifications[$key] = $notification->toAPIArray();
        }


        return Response::response(200, $notifications);
    }

    public function details($id)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return response()->json(['code' => 503, 'message' => 'ID không phải số nguyên', 'data' => null]);
        }

        $currentUser = $this->userService->getUser();
        $notification = $this->notiRepo->findByIdAndUserIdAndUserType($id, $currentUser->id, FCMNotification::PATIENT);
        
        if (empty($notification)) {
            return Response::response(20004);
        }
        try {
            $this->notiRepo->update($notification, ['is_read'=>1]);
        } catch (\Exception $e) {
            return response()->json(['code' => 503, 'message' => 'Không thể cập nhật dữ liệu', 'data' => null]);
        }

        return Response::response(200, $notification->toAPIArray()); 
    }
}
