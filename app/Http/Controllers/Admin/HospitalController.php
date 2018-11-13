<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\HospitalRepositoryInterface;
use App\Http\Requests\Admin\HospitalRequest;
use App\Http\Requests\PaginationRequest;

class HospitalController extends Controller
{
    /** @var  \App\Repositories\HospitalRepositoryInterface */
    protected $hospitalRepository;

    public function __construct(
        HospitalRepositoryInterface $hospitalRepository
    ) {
        $this->hospitalRepository = $hospitalRepository;
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
        $paginate['baseUrl']    = action('Admin\HospitalController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->hospitalRepository->countByFilter($filter);
        $hospitals = $this->hospitalRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.hospitals.index',
            [
                'hospitals'    => $hospitals,
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
            'pages.admin.' . config('view.admin') . '.hospitals.edit',
            [
                'isNew'     => true,
                'hospital' => $this->hospitalRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(HospitalRequest $request)
    {
        $input = $request->only(
            [
                            'name',
                            'address',
                            'phone',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $hospital = $this->hospitalRepository->create($input);

        if( empty($hospital) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\HospitalController@index')
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
        $hospital = $this->hospitalRepository->find($id);
        if( empty($hospital) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.hospitals.edit',
            [
                'isNew' => false,
                'hospital' => $hospital,
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
    public function update($id, HospitalRequest $request)
    {
        /** @var  \App\Models\Hospital $hospital */
        $hospital = $this->hospitalRepository->find($id);
        if( empty($hospital) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'name',
                            'address',
                            'phone',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->hospitalRepository->update($hospital, $input);

        return redirect()->action('Admin\HospitalController@show', [$id])
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
        /** @var  \App\Models\Hospital $hospital */
        $hospital = $this->hospitalRepository->find($id);
        if( empty($hospital) ) {
            abort(404);
        }
        $this->hospitalRepository->delete($hospital);

        return redirect()->action('Admin\HospitalController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
