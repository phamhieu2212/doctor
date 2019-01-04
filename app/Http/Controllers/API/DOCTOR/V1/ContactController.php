<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\PaginationRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Models\ChatHistory;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    protected $adminUserService;
    protected $userRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        UserRepositoryInterface $userRepository
    )
    {
        $this->adminUserService = $APIUserService;
        $this->userRepository = $userRepository;
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
        $adminUser  = $this->adminUserService->getUser();
        $idListUserCall = CallHistory::where('admin_user_id',$adminUser->id)->pluck('user_id')->toArray();
        $idListUserChat = ChatHistory::where('admin_user_id',$adminUser->id)->pluck('user_id')->toArray();

        $idListUserContact = array_unique(array_merge($idListUserCall,$idListUserChat));
        $contacts = $this->userRepository->getByFilterWithListId($idListUserContact,$filter,$paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']); // change get() to geEnabled as requirement
        foreach( $contacts as $key => $contact ) {
            $contacts[$key] = $contact->toAPIArrayContact();
        }


        return Response::response(200,$contacts
        );
    }
}
