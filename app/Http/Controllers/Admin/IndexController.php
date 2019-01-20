<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminStatistic;
use App\Repositories\AdminStatisticRepositoryInterface;
use App\Repositories\AdminUserRoleRepositoryInterface;
use App\Repositories\CallHistoryRepositoryInterface;
use App\Repositories\ChatHistoryRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    protected $adminUserRoleRepository;
    protected $userRoleRepository;
    protected $callHistoryRepository;
    protected $chatHistoryRepository;
    protected $paymentRepository;
    protected $adminStatisticRepository;

    public function __construct
    (
        AdminUserRoleRepositoryInterface $adminUserRoleRepository,
        UserRepositoryInterface $userRepository,
        CallHistoryRepositoryInterface $callHistoryRepository,
        ChatHistoryRepositoryInterface $chatHistoryRepository,
        PaymentRepositoryInterface $paymentRepository,
        AdminStatisticRepositoryInterface $adminStatisticRepository
    )
    {
        $this->adminUserRoleRepository = $adminUserRoleRepository;
        $this->userRoleRepository      = $userRepository;
        $this->callHistoryRepository   = $callHistoryRepository;
        $this->chatHistoryRepository   = $chatHistoryRepository;
        $this->paymentRepository       = $paymentRepository;
        $this->adminStatisticRepository = $adminStatisticRepository;
    }
    public function index(Request $request)
    {
        if($request->has('start') and $request->has('start') )
        {
            $start = $request->get('start');
            $end = $request->get('end');
            if($start != null and $end == null)
            {
                return redirect()
                    ->back()
                    ->withErrors('Vui lòng nhập lại ngày tháng');
            }
            elseif($start == null and $end != null)
            {
                return redirect()
                    ->back()
                    ->withErrors('Vui lòng nhập lại ngày tháng');
            }
            elseif($start != null and $end != null and $start > $end)
            {
                return redirect()
                    ->back()
                    ->withErrors('Vui lòng nhập lại ngày tháng');
            }
            else
            {
                $startDate = $start;
                $endDate = $end;
            }

        }
        else
        {
            $startDate = null;
            $endDate = null;
        }
        $countDoctor = $this->adminUserRoleRepository->countAllAdminUserByRoleWithFilter('admin',$startDate,$endDate);
        $countPatient = $this->userRoleRepository->countAllWithFilter($startDate,$endDate);
        $countChat = $this->chatHistoryRepository->countAllWithFilter($startDate,$endDate);
        $countCall = $this->callHistoryRepository->countAllWithFilter($startDate,$endDate);
        $sumPayment = $this->paymentRepository->sumAllWithFilter($startDate,$endDate);
        $sumDoctor = $this->adminStatisticRepository->sumAllWithFilter($startDate,$endDate);
        if($startDate == null and $endDate == null)
        {
            $doctors = AdminStatistic::groupBy('admin_user_id')
                ->selectRaw('*,sum(total) as total_amount')->paginate(10);
        }
        else
        {
            $startDate = date('Y-m-d 00:00:00',strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59',strtotime($endDate));
            $doctors = AdminStatistic::groupBy('admin_user_id')
                ->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->selectRaw('*,sum(total) as total_amount')->paginate(10);
        }



        return view('pages.admin.' . config('view.admin') . '.index', [
            'countDoctor'=>$countDoctor,
            'countPatient'=>$countPatient,
            'startDate'=>$request->get('start'),
            'endDate'=>$request->get('end'),
            'countChat'=>$countChat,
            'countCall'=>$countCall,
            'sumPayment'=>$sumPayment,
            'sumDoctor'=>$sumDoctor,
            'doctors'=>$doctors
        ]);
    }
}
