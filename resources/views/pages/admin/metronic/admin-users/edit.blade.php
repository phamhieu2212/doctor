@extends('pages.admin.metronic.layout.application',['menu' => 'admin-users'] )

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
            $("#formRole").change(function () {

                if($("#formRole").val() == 'admin')
                {
                    $("#formDoctor").css({display: "block"});
                }
                else if($("#formRole").val() == 0)
                {
                    alert('Vui lòng chọn vai trò');
                    $("#formDoctor").css({display: "none"});
                }
                else
                {
                    $("#formDoctor").css({display: "none"});
                }
            });
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });

            $('.datetime-picker').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                pickerPosition: 'bottom-left',
                format: 'yyyy-mm-dd',
                minView: 2,
            });
            $('.js-example-basic-multiple').select2(
                    {
                        dropdownAutoWidth: true

                    }
            );
        });
    </script>
@stop

@section('title')
    @if($isNew)
        {{'Thêm mới người dùng'}}
    @else
        {{'Sửa người dùng'}}
    @endif
@stop

@section('header')
    Quản lý người dùng
@stop

@section('breadcrumb')
    <li class="m-nav__separator"> / </li>
    <li class="m-nav__item">
        <a href="{!! action('Admin\AdminUserController@index') !!}" class="m-nav__link">
            Danh sách người dùng
        </a>
    </li>

    @if( $isNew )
        <li class="m-nav__separator"> / </li>
        <li class="m-nav__item">
            Thêm mới
        </li>
    @else
        <li class="m-nav__separator"> / </li>
        <li class="m-nav__item">
            {{ $adminUser->id }}
        </li>
    @endif
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Thêm mới
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{!! action('Admin\AdminUserController@index') !!}" class="btn m-btn--pill m-btn--air btn-secondary btn-sm" style="width: 120px;">
                            Quay lại
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="m-portlet__body">
            @if(isset($errors) && count($errors))
                <?php $errs = $errors->all(); ?>
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

            <form class="m-form m-form--fit m-form--state" action="@if($isNew){!! action('Admin\AdminUserController@store') !!}@else{!! action('Admin\AdminUserController@update', [$adminUser->id]) !!}@endif" method="POST" enctype="multipart/form-data">
                @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
                {!! csrf_field() !!}

                    <div class="m-portlet__body" style="padding-top: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group m-form__group row" style="max-width: 500px;">
                                    @if( !$isNew )
                                        @if( isset($adminUser) and !empty($adminUser->present()->profileImage()) )
                                            <img id="cover-image-preview" style="max-width: 100%;" src="{!! @$adminUser->present()->profileImage()->present()->url !!}" alt="" class="margin"/>
                                        @else
                                            <img id="cover-image-preview" style="max-width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                                        @endif
                                    @else
                                        <img id="cover-image-preview" style="max-width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                                    @endif
                                    <input type="file" style="display: none;" id="cover-image" name="profile_image">
                                    <p class="help-block" style="font-weight: bolder; display: block; width: 100%; text-align: center;">
                                        Ảnh Logo
                                        <label for="cover-image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">Upload</label>
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="username">Tên đăng nhập</label>
                                            <input type="text" class="form-control m-input" name="username" id="username" placeholder="Tên đăng nhập" value="{{ old('username') ? old('username') : @$adminUser->username }}">
                                        </div>
                                    </div>
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
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="exampleSelect1">Vai trò</label>
                                            <select name="role[]" class="form-control m-input" id="formRole">
                                                <option value="0">Vui lòng chọn vai trò</option>
                                                <option {{(@$adminUser->roles[0]->role == 'super_user')?'selected':''}} value="super_user">Super admin</option>
                                                <option {{(@$adminUser->roles[0]->role == 'admin')?'selected':''}} value="admin">Bác sĩ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="name">Tên đầy đủ</label>
                                            <input type="text" class="form-control m-input" name="name" id="name" placeholder="Tên đầy đủ" value="{{ old('name') ? old('name') : @$adminUser->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="username">Email</label>
                                            <input type="text" class="form-control m-input" name="email" id="email" placeholder="Email" value="{{ old('email') ? old('email') : @$adminUser->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div id="formDoctor" style="{{($isNew or @$adminUser->roles[0]->role == 'super_user')?'display:none':''}}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group m-form__group row">
                                            <label for="phone">Số điện thoại</label>
                                            <input type="text" class="form-control m-input" name="phone" id="phone" placeholder="Số điện thoại" value="{{ old('phone') ? old('phone') : @$adminUser->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group row">
                                            <label >Ngày sinh</label>
                                            <input type="text" name="birthday" class="form-control datetime-picker" id="started_date" readonly value="{{ old('birthday') ? old('birthday') : @$adminUser->doctor->birthday }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group m-form__group row">
                                            <label for="exampleSelect1">Giới tính</label>
                                            <select name="gender" class="form-control m-input" id="formRole">
                                                <option {{(@$adminUser->doctor->gender == 1)?'selected':''}} value="1">Nam</option>
                                                <option {{(@$adminUser->doctor->gender == 0)?'selected':''}} value="0">Nữ</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group m-form__group row">
                                            <label for="address">Thành phố</label>
                                            <input type="text" class="form-control m-input" name="city" id="city" placeholder="Thành phố" value="{{ old('city') ? old('city') : @$adminUser->doctor->city }}">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group m-form__group row">
                                            <label for="address">Địa chỉ</label>
                                            <input type="text" class="form-control m-input" name="address" id="address" placeholder="Địa chỉ" value="{{ old('address') ? old('address') : @$adminUser->doctor->address }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group m-form__group row">
                                            <label for="exampleSelect1">Bệnh viện</label>
                                            <select name="hospital_id" class="form-control  m-input" id="formRole">
                                                @foreach($hospitals as $hospital)
                                                    <option {{(@$adminUser->doctor->hospital_id == $hospital->id)?'selected':''}} value="{{$hospital->id}}">{{$hospital->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group m-form__group row">
                                            <label for="exampleSelect1">Chuyên khoa</label>
                                            <select class="form-control js-example-basic-multiple" name="specialty_id[]" multiple="multiple" id="formRole">
                                                @foreach($specialties as $specialty)
                                                <option  value="{{$specialty->id}}">{{$specialty->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="name">Khoa</label>
                                            <input type="text" class="form-control m-input" name="position" id="position" value="{{ old('position') ? old('position') : @$adminUser->doctor->position }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="content">Kinh nghiệm</label>
                                            <textarea name="experience" id="experience" class="form-control m-input" rows="3" >{{ old('experience') ? old('experience') : @$adminUser->doctor->experience }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-form__group row">
                                            <label for="description">Mô tả</label>
                                            <textarea name="description" id="description" class="form-control m-input" rows="3" >{{ old('description') ? old('description') : @$adminUser->doctor->description }}</textarea>
                                        </div>
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
                                <a href="{!! action('Admin\AdminUserController@index') !!}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-accent" style="width: 120px;">
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
