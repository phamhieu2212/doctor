<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\PlanRepositoryInterface;
use App\Http\Requests\Admin\PlanRequest;
use App\Http\Requests\PaginationRequest;
use App\Repositories\ClinicRepositoryInterface;
use App\Services\AdminUserServiceInterface;
use App\Models\Plan;
use App\Models\Clinic;

class PlanController extends Controller
{
    /** @var  \App\Repositories\PlanRepositoryInterface */
    protected $planRepository;
    protected $clinicRepository;
    protected $adminuserService;

    public function __construct(
        PlanRepositoryInterface $planRepository,
        ClinicRepositoryInterface $clinicRepository,
        AdminUserServiceInterface $adminUserService
    ) {
        $this->planRepository = $planRepository;
        $this->clinicRepository = $clinicRepository;
        $this->adminuserService = $adminUserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param    \App\Http\Requests\PaginationRequest $request
     * @return  \Response
     */
    public function index(PaginationRequest $request)
    {
        $paginate['limit']      = $request->limit();
        $paginate['offset']     = $request->offset();
        $paginate['order']      = $request->order();
        $paginate['direction']  = $request->direction();
        $paginate['baseUrl']    = action('Admin\PlanController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->planRepository->countByFilter($filter);
        $plans = $this->planRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.plans.index',
            [
                'plans'         => $plans,
                'count'         => $count,
                'paginate'      => $paginate,
                'keyword'       => $keyword
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Response
     */
    public function create()
    {
        return view(
            'pages.admin.' . config('view.admin') . '.plans.edit',
            [
                'isNew'     => true,
                'plan' => $this->planRepository->getBlankModel(),
                'clinics' => $this->clinicRepository->all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(PlanRequest $request)
    {
        $input = $request->only(
            [
                'admin_user_id',
                'user_id',
                'clinic_id',
                'price',
                'status',
                'day',
                'hour'
            ]
        );
        $input['admin_user_id'] = $this->adminuserService->getUser()->id;
        $date =  date("Y-m-d", strtotime($input['day'].' this week'));
        $dateStart =  date("Y-m-d 00:00:00", strtotime($input['day'].' this week'));
        $dateEnd =  date("Y-m-d 24:00:00", strtotime($input['day'].' this week'));
        $arrayDateTimes = [];
        $hours = $input['hour'];
        $input['is_enabled'] = $request->get('is_enabled', 0);
        $plan = $this->planRepository->create($input);

        foreach($hours as $hour)
        {
            array_push($arrayDateTimes,date('Y-m-d H:i:s',strtotime($date. $hour.':00:00')));
        }
        $doctor =  $this->adminuserService->getUser();

        foreach($arrayDateTimes as $arrayDateTime)
        {
            $plan = Plan::where('admin_user_id',$doctor->id)->where('started_at',$arrayDateTime)->first();
            if(empty($plan))
            {
                $this->planRepository->create([
                    'admin_user_id' => $doctor->id,
                    'clinic_id'     => $input['clinic_id'],
                    'price'         => $input['price'],
                    'started_at'    => $arrayDateTime,
                    'ended_at'      => date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($arrayDateTime)))
                ]);
            }
            else
            {
                $this->planRepository->update($plan,[
                    'clinic_id'     => $input['clinic_id'],
                    'price'         => $input['price']
                ]);
            }
        }
        $planDelete = Plan::whereNotIn('started_at',$arrayDateTimes)->get();
        if(!empty($planDelete))
        {
            foreach($planDelete as $row)
            {
                $this->planRepository->delete($row);
            }
        }

        $plans = Plan::where('admin_user_id',$doctor->id)->where('started_at','<=',$dateEnd)->where('started_at','>=',$dateStart)->get();
        foreach($plans as $key=>$plan)
        {
            $plans[$key] = $plan->toAPIArray();

        }

        if( empty($plan) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\PlanController@index')
            ->with('message-success', trans('admin.messages.general.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function show($id)
    {
        $plan = $this->planRepository->find($id);
        if( empty($plan) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.plans.edit',
            [
                'isNew' => false,
                'plan' => $plan,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param    int $id
     * @return  \Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    int $id
     * @param            $request
     * @return  \Response
     */
    public function update($id, PlanRequest $request)
    {
        /** @var  \App\Models\Plan $plan */
        $plan = $this->planRepository->find($id);
        if( empty($plan) ) {
            abort(404);
        }

        $input = $request->only(
            [
                'admin_user_id',
                'user_id',
                'clinic_id',
                'price',
                'status',
                'started_at',
                'ended_at',
            ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->planRepository->update($plan, $input);

        return redirect()->action('Admin\PlanController@show', [$id])
            ->with('message-success', trans('admin.messages.general.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Response
     */
    public function destroy($id)
    {
        /** @var  \App\Models\Plan $plan */
        $plan = $this->planRepository->find($id);
        if( empty($plan) ) {
            abort(404);
        }
        $this->planRepository->delete($plan);

        return redirect()->action('Admin\PlanController@index')
            ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
