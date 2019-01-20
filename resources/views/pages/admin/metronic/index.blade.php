@extends('pages.admin.metronic.layout.application',['menu' => 'dashboard'] )

@section('metadata')
@stop

@section('styles')
    <link href="{{asset('static/admin/libs/metronic/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('static/admin/libs/metronic/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('libs/metronic/app/js/dashboard.js', 'admin') !!}"></script>
    <script src="{{asset('/static/admin/libs/metronic/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
    <script src="{{asset('/static/admin/libs/metronic/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->


    <!--begin::Page Scripts -->
    <script src="{{asset('/static/admin/libs/metronic/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script>
        $("#m_reset").click(function(){
        $('#m_datepicker_5').val("").datepicker("update");
        })
    </script>
@stop

@section('title')
    {{ config('site.name') }} | Admin | Dashboard
@stop

@section('header')
    Dashboard
@stop

@section('breadcrumb')
    <li class="m-nav__separator"> - </li>
    <li class="m-nav__item">
        <a href="" class="m-nav__link">
            <span class="m-nav__link-text">
                Dashboard
            </span>
        </a>
    </li>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    <!--Begin::Section-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <form style="margin-top: 15px;" class="m-form m-form--fit m--margin-bottom-20" method="get" action="{!! action('Admin\IndexController@index') !!}">
                    <div class="row">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-lg-1 col-md-1 col-sm-12">Ngày tháng</label>
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <div class="input-daterange input-group" id="m_datepicker_5">
                                    <input type="text" class="form-control m-input" name="start" value="{{ old('start') ? old('start') : @$startDate }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="end" value="{{ old('end') ? old('end') : @$endDate }}">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12">
                                <button class="btn btn-brand m-btn m-btn--icon" id="m_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Search</span>
                                    </span>
                                </button>
                                &nbsp;&nbsp;
                                <button type="reset" class="btn btn-secondary m-btn m-btn--icon" id="m_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Reset</span>
                                    </span>
                                </button>
                            </div>

                        </div>

                    </div>

                    <div class="m-separator m-separator--md m-separator--dashed"></div>


                </form>
            </div>
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Bác sĩ
                                    </h3>
                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-brand">
															{{$countDoctor}}
														</span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Bệnh nhân
                                    </h3>

                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-danger">
															{{$countPatient}}
														</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Cuộc tư vấn
                                    </h3>

                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-brand">
															{{$countChat}}
														</span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Cuộc gọi
                                    </h3>

                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-danger">
															{{$countCall}}
														</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Tổng thu
                                    </h3>

                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-brand">
															{{$sumPayment}}
														</span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        Tổng chi
                                    </h3>
                                </div>
                                <div class="col m--align-right">
														<span class="m-widget1__number m--font-danger">
															{{$sumDoctor}}
														</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Danh sách thu nhập bác sĩ
                        </h3>
                    </div>
                </div>
            </div>

        <div class="m-portlet__body wrap-index">
            <div class="dataTables_wrapper">

                <div class="row">
                    <div class="col-sm-12 wrap-index-table">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="index-table">
                            <thead>
                            <tr>
                                <th style="width: 10px">{!! \PaginationHelper::sort('id', 'ID') !!}</th>
                                <th>{!! \PaginationHelper::sort('id', 'name') !!}</th>
                                <th>{!! \PaginationHelper::sort('id', 'Thu nhập') !!}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach( $doctors as $doctor )
                                <tr>
                                    <td>{{ $doctor->admin_user_id }}</td>
                                    <td>{{ @$doctor->adminUser->name }}</td>
                                    <td>{{ $doctor->total_amount }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                            {{ $doctors->links() }}
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>



@stop
@section('scripts')


@stop
