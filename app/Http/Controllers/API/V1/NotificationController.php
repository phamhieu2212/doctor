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
    
    public function list(PaginationRequest $request)
    {
        $page = intval($request->get('page', 1));
        $order = $request->get('order', 'id');
        $direction = $request->get('direction', 'DESC');
        $limit = intval($request->get('limit', 100));

        $paginate['offset']     = $request->offset();
        $paginate['limit']      = $request->limit($limit);
        $paginate['order']      = $request->order($order);
        $paginate['direction']  = $request->direction($direction);

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

        $response = [
            'total_page' => $total,
            'current_page' => $page,
            'notifications' => $notifications
        ];

        return Response::response(200, $response);
    }

    public function details($id)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $currentUser = $this->userService->getUser();
        $notification = $this->notiRepo->findByIdAndUserIdAndUserType($id, $currentUser->id, FCMNotification::PATIENT);
        
        if (empty($notification)) {
            return Response::response(20004);
        }

        return Response::response(200, $notification->toAPIArray()); 
    }
}
