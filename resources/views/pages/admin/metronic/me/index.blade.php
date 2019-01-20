@extends('pages.admin.metronic.layout.application',['menu' => 'categories'] )

@section('metadata')
@stop

@section('styles')
    <style>
        .row {
            margin-bottom: 15px;
        }
    </style>
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('metronic/demo/default/custom/crud/forms/validation/form-controls.js', 'admin') !!}"></script>
    <script>
        $(document).ready(function () {
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });

            $('.datetime-picker').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                pickerPosition: 'bottom-left',
                format: 'yyyy/mm/dd hh:ii'
            });
        });
    </script>
@stop

@section('title')
    Sửa thông tin cá nhân
@stop

@section('header')
    Quản lý thông tin cá nhân
@stop

@section('breadcrumb')
    <li class="m-nav__item">
        Sửa thông tin cá nhân
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
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Sửa thông tin cá nhân
                    </h3>
                </div>
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="m-form m-form--fit" action="{!! action('Admin\MeController@update') !!}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="m-portlet__body" style="padding-top: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group row">
                                <label for="name">Tên</label>
                                <input type="text" class="form-control m-input" name="name" id="name" required readonly value="{{$adminUser->name}}">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group m-form__group row">
                                        <label for="password">Mật khẩu</label>
                                        <input type="password" class="form-control m-input" name="password" id="password" placeholder="Mật khẩu">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group m-form__group row">
                                        <label for="password">Nhập lại mật khẩu</label>
                                        <input type="password" class="form-control m-input" name="password_confirmation" id="password_confirmation" placeholder="Mật khẩu">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                                <a href="{!! action('Admin\IndexController@index') !!}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-accent" style="width: 120px;">
                                    @lang('admin.pages.common.buttons.cancel')
                                </a>
                                <button type="submit" class="btn m-btn--pill m-btn--air btn-primary m-btn m-btn--custom" style="width: 120px;">
                                    @lang('admin.pages.common.buttons.save')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
