<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\SpecialtyRepositoryInterface;
use App\Http\Requests\Admin\SpecialtyRequest;
use App\Http\Requests\PaginationRequest;

class SpecialtyController extends Controller
{
    /** @var  \App\Repositories\SpecialtyRepositoryInterface */
    protected $specialtyRepository;

    public function __construct(
        SpecialtyRepositoryInterface $specialtyRepository
    ) {
        $this->specialtyRepository = $specialtyRepository;
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
        $paginate['baseUrl']    = action('Admin\SpecialtyController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->specialtyRepository->countByFilter($filter);
        $specialties = $this->specialtyRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.specialties.index',
            [
                'specialties'    => $specialties,
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
            'pages.admin.' . config('view.admin') . '.specialties.edit',
            [
                'isNew'     => true,
                'specialty' => $this->specialtyRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(SpecialtyRequest $request)
    {
        $input = $request->only(
            [
                            'name',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $specialty = $this->specialtyRepository->create($input);

        if( empty($specialty) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\SpecialtyController@index')
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
        $specialty = $this->specialtyRepository->find($id);
        if( empty($specialty) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.specialties.edit',
            [
                'isNew' => false,
                'specialty' => $specialty,
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
    public function update($id, SpecialtyRequest $request)
    {
        /** @var  \App\Models\Specialty $specialty */
        $specialty = $this->specialtyRepository->find($id);
        if( empty($specialty) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'name',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->specialtyRepository->update($specialty, $input);

        return redirect()->action('Admin\SpecialtyController@show', [$id])
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
        /** @var  \App\Models\Specialty $specialty */
        $specialty = $this->specialtyRepository->find($id);
        if( empty($specialty) ) {
            abort(404);
        }
        $this->specialtyRepository->delete($specialty);

        return redirect()->action('Admin\SpecialtyController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
