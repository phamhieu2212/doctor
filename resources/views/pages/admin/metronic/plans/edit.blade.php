@extends('pages.admin.metronic.layout.application',['menu' => 'plans'] )

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
    <script src="{!! \URLHelper::asset('libs/metronic/demo/default/custom/crud/forms/validation/form-controls.js', 'admin') !!}"></script>
    <script>
        $(document).ready(function () {
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });

            $('.datetime-picker').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                pickerPosition: 'bottom-left',
                format: 'yyyy-mm-dd',
                minView: 2
            });
            $('.datetime-picker').change(function () {
                // $('#day').empty();
                var eventDate = $('.datetime-picker').val();
                var dateElement = eventDate.split("-");
                var dateFormat = dateElement[2]+'/'+dateElement[1]+'/'+dateElement[0];
                var date = new Date(eventDate+'T10:00:00Z');
                var weekday = ["Chủ nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
                var day = weekday[date.getDay()];
                $('#day').val(day);
                $('#showTime').css("display", "block");
            });
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2();
            });
        });
    </script>
@stop

@section('title')
    Plan | Admin | {{ config('site.name') }}
@stop

@section('header')
    Plan
@stop

@section('breadcrumb')
    <li class="m-nav__separator"> / </li>
    <li class="m-nav__item">
        <a href="{!! action('Admin\PlanController@index') !!}" class="m-nav__link">
            Plan
        </a>
    </li>

    @if( $isNew )
        <li class="m-nav__separator"> / </li>
        <li class="m-nav__item">
            New Record
        </li>
    @else
        <li class="m-nav__separator"> / </li>
        <li class="m-nav__item">
            {{ $plan->id }}
        </li>
    @endif
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Create New Record
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{!! action('Admin\PlanController@index') !!}" class="btn m-btn--pill m-btn--air btn-secondary btn-sm" style="width: 120px;">
                            @lang('admin.pages.common.buttons.back')
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @if(isset($errors) && count($errors))
                {{ $errs = $errors->all() }}
                <div class="m-alert m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                    <strong>
                        Error !!!
                    </strong>
                    <ul>
                        @foreach($errs as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="m-form m-form--fit" action="@if($isNew){!! action('Admin\PlanController@store') !!}@else{!! action('Admin\PlanController@update', [$plan->id]) !!}@endif" method="POST">
                @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
                {!! csrf_field() !!}
                <div class="m-portlet__body" style="padding-top: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group row @if ($errors->has('admin_user_id')) has-danger @endif">
                                <label for="admin_user_id">Bác sĩ</label>
                                <input type="text" class="form-control m-input" name="admin_user_id" id="admin_user_id" required value="{{$authUser->id}}" disabled style="display: none;">
                                <input type="text" class="form-control m-input" required value="{{$authUser->name}}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group row @if ($errors->has('clinic_id')) has-danger @endif">
                                <label for="clinic_id">Phòng khám</label>
                                <select class="form-control" name="clinic_id" id="clinic_id" style="margin-bottom: 15px;" required>
                                    <option disabled selected>Chọn phòng khám</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{$clinic->id}}">{{$clinic->name}}</option>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group m-form__group row @if ($errors->has('price')) has-danger @endif">
                                <label for="price">Giá(VND)</label>
                                <input type="number" min="0" class="form-control m-input" name="price" id="price" required placeholder="Giá" value="{{ old('price') ? old('price') : $plan->price }}">
                            </div>
                        </div>
                    </div>
                    {{--Giờ bắt đầu thiết kể kiểu app--}}
                    <div class="row">
                        <div class="col-md-6">
                            {{--<div class="form-group m-form__group row input-group date @if ($errors->has('started_at')) has-danger @endif">--}}
                                {{--<label for="started_at" class="label-datetimepicker">Ngày khám</label>--}}
                                {{--Y-m-d 00:00:00--}}
                                {{--<input type="text" class="form-control m-input datetime-picker" readonly="" placeholder="Select date &amp; time" id="started_at" name="started_at">--}}
                                {{--<div class="input-group-append">--}}
                                            {{--<span class="input-group-text">--}}
                                                {{--<i class="la la-calendar-o glyphicon-th"></i>--}}
                                            {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group m-form__group row @if ($errors->has('clinic_id')) has-danger @endif">
                                <label for="clinic_id">Ngày khám</label>
                                <select class="form-control" name="day" id="day" style="margin-bottom: 15px;" required>
                                    <option value="Mon">Thứ 2</option>
                                    <option value="Tue">Thứ 3</option>
                                    <option value="Wed">Thứ 4</option>
                                    <option value="Thu">Thứ 5</option>
                                    <option value="Fri">Thứ 6</option>
                                    <option value="Sat">Thứ 7</option>
                                    <option value="Sun">Chủ nhật</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="showTime" style="display: none;">
                            <label class="label-datetimepicker">Ngày bạn chọn</label>
                            <input type="text" class="form-control m-input datetime-picker" id="day" readonly="" disabled >
                        </div>
                    </div>
                    {{--Giờ kết thúc thì bằng giờ bắt đầu cộng thêm 1 tiếng--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div class="form-group m-form__group row input-group date @if ($errors->has('ended_at')) has-danger @endif">--}}
                                {{--<label for="ended_at" class="label-datetimepicker">@lang('admin.pages.plans.columns.ended_at')</label>--}}
                                {{--<input type="text" class="form-control m-input datetime-picker" readonly="" placeholder="Select date &amp; time" id="ended_at" name="ended_at">--}}
                                {{--<div class="input-group-append">--}}
                                            {{--<span class="input-group-text">--}}
                                                {{--<i class="la la-calendar-check-o glyphicon-th"></i>--}}
                                            {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="row">
                        <div class="col-md-12">
                            <label for="days" class="label-datetimepicker">Khung giờ</label>
                            <select class="js-example-basic-multiple form-group m-form__group row input-group" name="hour[]" id="hour[]" multiple="multiple">
                                <option value="7">7h-8h</option>
                                <option value="8">8h-9h</option>
                                <option value="9">9h-10h</option>
                                <option value="10">10h-11h</option>
                                <option value="11">11h-12h</option>
                                <option value="13">13h-14h</option>
                                <option value="14">14h-15h</option>
                                <option value="15">15h-16h</option>
                                <option value="16">16h-17h</option>
                                <option value="16">17h-18h</option>
                                <option value="18">18h-19h</option>
                                <option value="19">19h-20h</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                                <a href="{!! action('Admin\PlanController@index') !!}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-accent" style="width: 120px;">
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
