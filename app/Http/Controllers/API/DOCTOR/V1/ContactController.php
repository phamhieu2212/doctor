<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Requests\PaginationRequest;
use App\Http\Responses\API\V1\Response;
use App\Models\CallHistory;
use App\Models\ChatHistory;
use App\Models\User;
use App\Repositories\FilePatientRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    protected $adminUserService;
    protected $userRepository;
    protected $filePatientRepository;

    public function __construct
    (
        APIUserServiceInterface $APIUserService,
        UserRepositoryInterface $userRepository,
        FilePatientRepositoryInterface $filePatientRepository
    )
    {
        $this->adminUserService = $APIUserService;
        $this->userRepository = $userRepository;
        $this->filePatientRepository = $filePatientRepository;
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

    public function detail($idPatient)
    {
        if( !is_numeric($idPatient) || ($idPatient <= 0) ) {
            return Response::response(40001);
        }
        $adminUser  = $this->adminUserService->getUser();
        $callHistories = CallHistory::where('admin_user_id',$adminUser->id)->where('start_time','!=',null)
            ->where('user_id',$idPatient)->get();
        $chatHistories = ChatHistory::where('admin_user_id',$adminUser->id)
            ->where('user_id',$idPatient)->get();

        $histories = $callHistories->concat($chatHistories);
        $histories = $histories->sortByDesc('created_at')->values()->all();
        foreach($histories as $key=>$history)
        {
            $histories[$key] = $history->toAPIArrayDetailPatient();

        }

        return Response::response(200,$histories
        );
    }

    public function getFilePatient($idFilePatient)
    {
        if( !is_numeric($idFilePatient) || ($idFilePatient <= 0) ) {
            return Response::response(40001);
        }
        $filePatient = $this->filePatientRepository->find($idFilePatient);
        if( empty($filePatient) ) {
            return Response::response(20004);
        }

        return Response::response(200,$filePatient->toAPIArrayDetail()
        );
    }
}
