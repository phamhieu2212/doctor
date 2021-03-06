@extends('pages.admin.metronic.layout.application',['menu' => 'point-patients'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('libs/metronic/demo/default/custom/components/base/sweetalert2.js', 'admin') !!}"></script>
    <script src="{!! \URLHelper::asset('metronic/js/delete_item.js', 'admin') !!}"></script>
@stop

@section('title')
     Quản lý điểm bệnh nhân
@stop

@section('header')
    Danh sách điểm bệnh nhân
@stop

@section('breadcrumb')
    <li class="m-nav__separator"> - </li>
    <li class="m-nav__item">
        <a href="" class="m-nav__link">
            <span class="m-nav__link-text">
                Danh sách điểm bệnh nhân
            </span>
        </a>
    </li>
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Danh sách điểm bệnh nhân
                    </h3>
                </div>
            </div>
            {{--<div class="m-portlet__head-tools">--}}
                {{--<ul class="m-portlet__nav">--}}
                    {{--<li class="m-portlet__nav-item">--}}
                        {{--<a href="{!! action('Admin\PointPatientController@create') !!}" class="btn m-btn--pill m-btn--air btn-outline-success btn-sm">--}}
                            {{--<span>--}}
                                {{--<i class="la la-plus"></i>--}}
                                {{--<span>Create New</span>--}}
                            {{--</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="m-portlet__nav-item"></li>--}}
                    {{--<li class="m-portlet__nav-item">--}}
                        {{--<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">--}}
                            {{--<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">--}}
                                {{--<i class="la la-ellipsis-h m--font-brand"></i>--}}
                            {{--</a>--}}
                            {{--<div class="m-dropdown__wrapper">--}}
                                {{--<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>--}}
                                {{--<div class="m-dropdown__inner">--}}
                                    {{--<div class="m-dropdown__body">--}}
                                        {{--<div class="m-dropdown__content">--}}
                                            {{--<ul class="m-nav">--}}
                                                {{--<li class="m-nav__section m-nav__section--first">--}}
                                                    {{--<span class="m-nav__section-text">--}}
                                                        {{--Quick Actions--}}
                                                    {{--</span>--}}
                                                {{--</li>--}}
                                                {{--<li class="m-nav__item">--}}
                                                    {{--<a href="" class="m-nav__link">--}}
                                                        {{--<i class="m-nav__link-icon flaticon-share"></i>--}}
                                                        {{--<span class="m-nav__link-text">--}}
                                                            {{--Create Post--}}
                                                        {{--</span>--}}
                                                    {{--</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        </div>

        <div class="m-portlet__body wrap-index">
            <div class="dataTables_wrapper">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        Tổng số: {{$count}} kết quả
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 wrap-index-table">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="index-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                                    <th style="width: 10px">Bệnh nhân</th>
                                                                                                                                                                                                        <th>{!! \PaginationHelper::sort('point', trans('admin.pages.point-patients.columns.point')) !!}</th>
                                    
                                    <th style="width: 40px">Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach( $pointPatients as $pointPatient )
                                    <tr>
                                        <td>{{ $pointPatient->id }}</td>
                                        <td>{{ $pointPatient->user->name }}</td>
                                                                                                                                                                                                                        <td>{{ $pointPatient->point }}</td>
                                                                                <td>
                                            <a href="{!! action('Admin\PointPatientController@show', $pointPatient->id) !!}" class="btn m--font-primary m-btn--pill m-btn--air no-padding">
                                                <i class="la la-edit"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row wrap-bottom-pagination">
                    <div class="col-sm-12">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], ['keyword' => $keyword], 5, 'pages.admin.metronic.shared.bottom-pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
