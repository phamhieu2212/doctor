<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\AdminUserRepositoryInterface;
use App\Repositories\AdminUserRoleRepositoryInterface;
use App\Repositories\ClinicRepositoryInterface;
use App\Http\Requests\Admin\ClinicRequest;
use App\Http\Requests\PaginationRequest;
use App\Services\AdminUserServiceInterface;

class ClinicController extends Controller
{
    /** @var  \App\Repositories\ClinicRepositoryInterface */
    protected $clinicRepository;
    protected $adminUserRepository;
    protected $adminUserService;

    public function __construct(
        ClinicRepositoryInterface $clinicRepository,
        AdminUserRepositoryInterface $adminUserRepository,
        AdminUserServiceInterface $adminUserService
    ) {
        $this->clinicRepository = $clinicRepository;
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserService = $adminUserService;
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
        $paginate['baseUrl']    = action('Admin\ClinicController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }
        $adminUser = $this->adminUserService->getUser();

        $count = $this->clinicRepository->countByFilterWithAdminUser($adminUser,$filter);
        $clinics = $this->clinicRepository->getByFilterWithAdminUser($adminUser,$filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.clinics.index',
            [
                'clinics'    => $clinics,
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
            'pages.admin.' . config('view.admin') . '.clinics.edit',
            [
                'doctors'   => $this->adminUserRepository->getAllAdminUserByRole('admin'),
                'isNew'     => true,
                'clinic' => $this->clinicRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(ClinicRequest $request)
    {
        $input = $request->only(
            [
                'admin_user_id',
                'name',
                'price',
                'address',
            ]
        );
        if(!isset($input['admin_user_id']))
        {
            $input['admin_user_id'] = $this->adminUserService->getUser()->id;
        }

        $input['status'] = 1;
        $clinic = $this->clinicRepository->create($input);

        if( empty($clinic) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\ClinicController@index')
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
        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.clinics.edit',
            [
                'isNew' => false,
                'clinic' => $clinic,
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
    public function update($id, ClinicRequest $request)
    {
        /** @var  \App\Models\Clinic $clinic */
        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            abort(404);
        }

        $input = $request->only(
            [
                'admin_user_id',
                'name',
                'price',
                'address',
            ]
        );
        if($input['admin_user_id'] == '')
        {
            $input['admin_user_id'] = $this->adminUserService->getUser()->id;
        }

        $input['status'] = 1;
        $this->clinicRepository->update($clinic, $input);

        return redirect()->action('Admin\ClinicController@show', [$id])
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
        /** @var  \App\Models\Clinic $clinic */
        $clinic = $this->clinicRepository->find($id);
        if( empty($clinic) ) {
            abort(404);
        }
        $this->clinicRepository->update($clinic,['status'=>3]);

        return redirect()->action('Admin\ClinicController@index')
            ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
