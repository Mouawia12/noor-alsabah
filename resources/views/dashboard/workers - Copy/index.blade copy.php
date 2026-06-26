@extends('layouts.app')


@section('content')


@push('styles')
<style type="text/css">
    .select2-selection__arrow b {
        display: none !important;
    }
 .custom-control-label::before,.custom-file-label,.custom-select {transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;}
    </style>

@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('assets/woker_j.js') }}?t={{ config('global.ver.version_all'); }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-inputmask]').inputmask();
        $(".sex,.city,.region,.nation").select2({
            width: 'resolve',
        });

        $('#add_file').on('click', function() {
    var newfield ='<div class="form-group row repeat"><div class="input-group mb-3"><div class="custom-file"><input type="file" class="custom-file-input" name="files[]" ><label class="custom-file-label kt-font-primary kt-font-bolder" for="customFile" data-browse="upload"></label></div><div class="input-group-append"><a class="btn btn-sm btn-danger remove"  style="padding: 0.7rem 1rem;"><span><i class="la la-minus" style="color:#fff"></i></span></a></div></div></div>';
            $('#container_file').append(newfield);
        });
        $(document).on('click', '.remove', function() {
            $(this).parent().parent().parent('div').remove();
        });
        $(document).on('change', '.custom-file-input', function() {
            var i = $(this).prev('label').clone();
            var file = this.files[0].name;
            $(this).prev('label').text(file);
            $(this).next('.custom-file-label').addClass("selected").html(file);
        });


    });
    </script>
    @endpush



     <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                     {{$main_title}} </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class=" flaticon2-back "></i></a>
                    <span class="kt-subheader__breadcrumbs"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                        {{$sub_title}}</a></i>
                    <span class="kt-subheader__breadcrumbs"></span>
                </div>
            </div>
        </div>
    </div>



    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{$sub_header}}   </h3>
                    </div>
                </div>
                <form autocomplete='off' class="kt-form kt-form--label-right" action="{{ route('dashboard.workers.store')}}" method="post"
                    id="save_workers" name="save_workers" enctype="multipart/form-data" accept-charset="utf-8">
@csrf



                    <div class="alert alert-outline-danger fade show" role="alert" id="errorBox_worker"
                        style="display: none">
                        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                        <div class="alert-text" id="displayErrors_worker"></div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>




                    <div class="kt-portlet__body">
                        <div class="form-group row">






                        <!--    <p>Date: {{ convertYmdToMdy('2022-02-12') }}</p>-->

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>اسم الموظف</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fa  fa-user kt-font-dark kt-font-bolder"></i></span></div>
                                        <input name="worker_name" type="text" class="form-control kt-font-dark kt-font-bolder"
                                            placeholder="اسم الموظف"
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                <label >الجنس</label>
                                    <select class="sex select2" id="sex" name="sex" style="width:100%">
                                        <option value="">اختر</option>
                                            @foreach ( $sexs as  $sex)

                                        <option value="{{ $sex->sex_id}} ">{{ $sex->sex_name}}</option>
@endforeach
                                    </select>
                                </div>
                            </div>




                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>رقم الجوال</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="la  la-phone kt-font-dark kt-font-bolder"></i></span></div>
                                        <input name="phone" id="phone" type="text"
                                            class="form-control kt-font-dark kt-font-bolder rtlchange"
                                            placeholder="رقم الجوال"
                                            aria-describedby="basic-addon1" data-inputmask="'alias' : 'integer'"
                                            maxlength="9" minlenght="9">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>الايميل</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text kt-font-info kt-font-bold">@</span></div>
                                        <input name="email" id="email" type="text"
                                            class="form-control kt-font-dark kt-font-bolder"
                                            placeholder="الايميل"
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>







                            <div class="col-lg-12 row" id='change_job' name='change_job'>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ملاحظات</label>
                                    <textarea class="form-control kt-font-dark kt-font-bolder"
                                        placeholder="ملاحظات" id="remarks"
                                        name="remarks" rows="1"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-9 ml-lg-auto">
                                        <button type="submit" class="btn btn-success"><i
                                                class="fa fa-check"></i>حفظ البيانات</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>



                </form>
            </div>
        </div>
    </div>




@endsection


@push('styles')
@endpush
