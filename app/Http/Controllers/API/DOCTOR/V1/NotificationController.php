<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

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

        $currentDoctor = $this->userService->getUser();
        $count = $this->notiRepo->countByUserTypeAndUserId(FCMNotification::DOCTOR, $currentDoctor->id);

        $notifications = $this->notiRepo->getWithPaginate(
            FCMNotification::DOCTOR,
            $currentDoctor->id,
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
            return Response::response(40001);
        }

        $currentDoctor = $this->userService->getUser();
        $notification = $this->notiRepo->findByIdAndUserIdAndUserType($id, $currentDoctor->id, FCMNotification::DOCTOR);
        
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
