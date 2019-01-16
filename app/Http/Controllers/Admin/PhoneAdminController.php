<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\PhoneAdminRepositoryInterface;
use App\Http\Requests\Admin\PhoneAdminRequest;
use App\Http\Requests\PaginationRequest;

class PhoneAdminController extends Controller
{
    /** @var  \App\Repositories\PhoneAdminRepositoryInterface */
    protected $phoneAdminRepository;

    public function __construct(
        PhoneAdminRepositoryInterface $phoneAdminRepository
    ) {
        $this->phoneAdminRepository = $phoneAdminRepository;
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
        $paginate['baseUrl']    = action('Admin\PhoneAdminController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->phoneAdminRepository->countByFilter($filter);
        $phoneAdmins = $this->phoneAdminRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.phone-admins.index',
            [
                'phoneAdmins'    => $phoneAdmins,
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
            'pages.admin.' . config('view.admin') . '.phone-admins.edit',
            [
                'isNew'     => true,
                'phoneAdmin' => $this->phoneAdminRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(PhoneAdminRequest $request)
    {
        $input = $request->only(
            [
                            'phone',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $phoneAdmin = $this->phoneAdminRepository->create($input);
        $count = $this->phoneAdminRepository->count();

        if( empty($phoneAdmin) or $count > 0 ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\PhoneAdminController@index')
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
        $phoneAdmin = $this->phoneAdminRepository->find($id);
        if( empty($phoneAdmin) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.phone-admins.edit',
            [
                'isNew' => false,
                'phoneAdmin' => $phoneAdmin,
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
    public function update($id, PhoneAdminRequest $request)
    {
        /** @var  \App\Models\PhoneAdmin $phoneAdmin */
        $phoneAdmin = $this->phoneAdminRepository->find($id);
        if( empty($phoneAdmin) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'phone',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->phoneAdminRepository->update($phoneAdmin, $input);

        return redirect()->action('Admin\PhoneAdminController@show', [$id])
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
        /** @var  \App\Models\PhoneAdmin $phoneAdmin */
        $phoneAdmin = $this->phoneAdminRepository->find($id);
        if( empty($phoneAdmin) ) {
            abort(404);
        }
        $this->phoneAdminRepository->delete($phoneAdmin);

        return redirect()->action('Admin\PhoneAdminController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
