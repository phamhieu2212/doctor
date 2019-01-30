<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\PointPatientRepositoryInterface;
use App\Http\Requests\Admin\PointPatientRequest;
use App\Http\Requests\PaginationRequest;

class PointPatientController extends Controller
{
    /** @var  \App\Repositories\PointPatientRepositoryInterface */
    protected $pointPatientRepository;

    public function __construct(
        PointPatientRepositoryInterface $pointPatientRepository
    ) {
        $this->pointPatientRepository = $pointPatientRepository;
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
        $paginate['baseUrl']    = action('Admin\PointPatientController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->pointPatientRepository->countByFilter($filter);
        $pointPatients = $this->pointPatientRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.point-patients.index',
            [
                'pointPatients'    => $pointPatients,
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
            'pages.admin.' . config('view.admin') . '.point-patients.edit',
            [
                'isNew'     => true,
                'pointPatient' => $this->pointPatientRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(PointPatientRequest $request)
    {
        $input = $request->only(
            [
                            'user_id',
                            'point',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $pointPatient = $this->pointPatientRepository->create($input);

        if( empty($pointPatient) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\PointPatientController@index')
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
        $pointPatient = $this->pointPatientRepository->find($id);
        if( empty($pointPatient) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.point-patients.edit',
            [
                'isNew' => false,
                'pointPatient' => $pointPatient,
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
    public function update($id, PointPatientRequest $request)
    {
        /** @var  \App\Models\PointPatient $pointPatient */
        $pointPatient = $this->pointPatientRepository->find($id);
        if( empty($pointPatient) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'point',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->pointPatientRepository->update($pointPatient, $input);

        return redirect()->action('Admin\PointPatientController@show', [$id])
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
        /** @var  \App\Models\PointPatient $pointPatient */
        $pointPatient = $this->pointPatientRepository->find($id);
        if( empty($pointPatient) ) {
            abort(404);
        }
        $this->pointPatientRepository->delete($pointPatient);

        return redirect()->action('Admin\PointPatientController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
