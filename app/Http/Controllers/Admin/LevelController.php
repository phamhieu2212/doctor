<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\LevelRepositoryInterface;
use App\Http\Requests\Admin\LevelRequest;
use App\Http\Requests\PaginationRequest;

class LevelController extends Controller
{
    /** @var  \App\Repositories\LevelRepositoryInterface */
    protected $levelRepository;

    public function __construct(
        LevelRepositoryInterface $levelRepository
    ) {
        $this->levelRepository = $levelRepository;
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
        $paginate['baseUrl']    = action('Admin\LevelController@index');

        $filter = [];
        $keyword = $request->get('keyword');
        if (!empty($keyword)) {
            $filter['query'] = $keyword;
        }

        $count = $this->levelRepository->countByFilter($filter);
        $levels = $this->levelRepository->getByFilter($filter, $paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit']);

        return view(
            'pages.admin.' . config('view.admin') . '.levels.index',
            [
                'levels'    => $levels,
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
            'pages.admin.' . config('view.admin') . '.levels.edit',
            [
                'isNew'     => true,
                'level' => $this->levelRepository->getBlankModel(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    $request
     * @return  \Response
     */
    public function store(LevelRequest $request)
    {
        $input = $request->only(
            [
                            'name',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $level = $this->levelRepository->create($input);

        if( empty($level) ) {
            return redirect()->back()->with('message-error', trans('admin.errors.general.save_failed'));
        }

        return redirect()->action('Admin\LevelController@index')
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
        $level = $this->levelRepository->find($id);
        if( empty($level) ) {
            abort(404);
        }

        return view(
            'pages.admin.' . config('view.admin') . '.levels.edit',
            [
                'isNew' => false,
                'level' => $level,
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
    public function update($id, LevelRequest $request)
    {
        /** @var  \App\Models\Level $level */
        $level = $this->levelRepository->find($id);
        if( empty($level) ) {
            abort(404);
        }

        $input = $request->only(
            [
                            'name',
                        ]
        );

        $input['is_enabled'] = $request->get('is_enabled', 0);
        $this->levelRepository->update($level, $input);

        return redirect()->action('Admin\LevelController@show', [$id])
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
        /** @var  \App\Models\Level $level */
        $level = $this->levelRepository->find($id);
        if( empty($level) ) {
            abort(404);
        }
        $this->levelRepository->delete($level);

        return redirect()->action('Admin\LevelController@index')
                    ->with('message-success', trans('admin.messages.general.delete_success'));
    }

}
