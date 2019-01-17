<?php

namespace App\Http\Controllers\API\DOCTOR\V1;

use App\Http\Responses\API\V1\Response;
use App\Models\AdminStatistic;
use App\Models\CallHistory;
use App\Models\ChatHistory;
use App\Services\APIUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    protected $adminUserService;

    public function __construct
    (
        APIUserServiceInterface $APIUserService
    )
    {
        $this->adminUserService = $APIUserService;
    }

    public function index()
    {
        $adminUserId = $this->adminUserService->getUser()->id;
        $datas = AdminStatistic::where('admin_user_id',$adminUserId)
            ->groupBy(DB::raw('DATE_FORMAT(date,"%Y-%b")'))
            ->selectRaw('*,sum(total) as total_amount')
            ->selectRaw('sum(time_call) as total_duration')
            ->selectRaw('sum(type = 2) as total_call')
            ->selectRaw('sum(is_patient_new = 1) as new_patient')->get();
        $callHistories = CallHistory::where('admin_user_id',$adminUserId)->get();
        $chatHistories = ChatHistory::where('admin_user_id',$adminUserId)->get();

        $histories = $callHistories->concat($chatHistories);
        $countTotalPatient = $histories->groupBy('user_id')->count();
        foreach($datas as $key=>$data)
        {
            $datas[$key] = $data->toAPIArrayListForDoctor($countTotalPatient);
        }
        return Response::response(200,$datas);
    }
}
