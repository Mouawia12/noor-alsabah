@extends('layouts.app')
@section('module', 'وزارة العمل ')
@section('sub', ' طلبات المواطن ')
@section('title', "$page_title")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif
    <div id="user_reg" class="alert alert-danger d-none"></div>
    <form id="save_workers" name="save_workers" class="form" action="{{ route('dashboard.workers.store') }}"
        enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 ">

                <div class="card">

                    <div class="card-body px-1">
                        <div class="alert alert-dismissible bg-light-danger border border-danger d-flex flex-column flex-sm-row p-5 mb-10"
                            id="errorBox_worker" style="display: none !important">
                            <i class="ki-duotone ki-search-list fs-2hx text-success me-4 mb-5 mb-sm-0"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-column pe-0 pe-sm-10" id="displayErrors_worker">
                                <h5 class="mb-1">This is an alert</h5>
                                <span></span>
                            </div>
                            <button type="button"
                                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                                data-bs-dismiss="alert">
                                <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </button>
                        </div>
                        <div class="mb-0">
                            <div class="row gx-5 mb-5">





                                <div class=" col-12 col-lg-2 col-md-12 col-sm-12 ">


<!--begin::Image input-->
<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url(/assets/media/avatars/blank.png)">
    <!--begin::Image preview wrapper-->
    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(/assets/media/avatars/150-2.jpg)"></div>
    <!--end::Image preview wrapper-->

    <!--begin::Edit button-->
    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
       data-kt-image-input-action="change"
       data-bs-toggle="tooltip"
       data-bs-dismiss="click"
       title="Change avatar">
        <i class="bi bi-pencil-fill fs-7"></i>

        <!--begin::Inputs-->
        <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
        <input type="hidden" name="avatar_remove" />
        <!--end::Inputs-->
    </label>
    <!--end::Edit button-->

    <!--begin::Cancel button-->
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
       data-kt-image-input-action="cancel"
       data-bs-toggle="tooltip"
       data-bs-dismiss="click"
       title="Cancel avatar">
        <i class="bi bi-x fs-2"></i>
    </span>
    <!--end::Cancel button-->

    <!--begin::Remove button-->
    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
       data-kt-image-input-action="remove"
       data-bs-toggle="tooltip"
       data-bs-dismiss="click"
       title="Remove avatar">
        <i class="bi bi-x fs-2"></i>
    </span>
    <!--end::Remove button-->
</div>
<!--end::Image input-->
</div>












                                <div class=" col-12 col-lg-3 col-md-12 col-sm-12 mb-5"><label for="NAME_IN"
                                        class="form-label required fs-6 fw-bold text-dark mb-3">اسم العامل</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-tools fa-fw text-dark"></i></span></div><input
                                            type="text" readonly name="NAME_IN" id="NAME_IN"
                                            class="form-control fw-bold form-control-solid text-info"
                                            placeholder="اسم العامل" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                    <label for="SSN_IN" class="form-label required fs-6 fw-bold text-dark mb-3">رقم الإقامة  </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="far fa-id-card fa-fw text-dark"></i></span></div><input
                                            type="text" name="SSN_IN" id="SSN_IN"
                                            class="form-control fw-bold text-dark text-info"
                                             maxlength="1" minlenght="20"
                                            placeholder="رقم الإقامة ">
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 col-md-12 col-sm-12 mb-5">
                                    <label for="GOVERNORATE_IN"
                                        class="form-label required fs-6 fw-bold text-dark mb-3">مكان العمل</label>
                                    <div>
                                        <select class="form-select fw-bold  " data-control="select2" id="GOVERNORATE_IN"
                                            onchange="active_other(this.val)" name="GOVERNORATE_IN" dir="rtl"
                                            data-placeholder="مكان العمل">
                                            <option value="">اختر ..</option>
                                            @foreach ($sexs as $x)
                                                <option value="{{ $x->sex_id }} ">{{ $x->sex_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class=" col-12 col-lg-4 col-md-12 col-sm-12  mb-5">
                                    <label for="WORK_FIELD_IN"
                                        class="form-label required fs-6 fw-bold text-dark mb-3">طبيعة
                                        العمل</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"> <i
                                                    class="fas fa-hammer fa-fw text-dark"></i></span></div>

                                        <input type="text" name="WORK_FIELD_IN" id="WORK_FIELD_IN"
                                            class="form-control fw-bold " placeholder="مثال (حداد - طوبار - عامل)"
                                            value="" autocomplete="off" />
                                    </div>
                                </div>


                                <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5"><label for="NAME_ENG_IN"
                                    class="form-label required fs-6 fw-bold text-dark mb-3">تاريخ إنتهاء الإقامة :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-tools fa-fw text-dark"></i></span></div><input
                                        type="text"  name="START_DATE_IN" id="START_DATE_IN"
                                        class="form-control fw-bold  text-dark input_date_" placeholder="تاريخ إنتهاء الإقامة "
                                        value="" autocomplete="off">
                                </div>
                            </div>





                                <div class=" col-12 col-lg-5 col-md-12 col-sm-12  mb-5">
                                    <label for="PROJECT_IDEA_IN" class="  form-label fs-6 fw-bold text-dark mb-3">الملاحظة
                                    </label>
                                    <textarea name="NOTES_IN" class="form-control fw-bold" id="NOTES_IN" placeholder="الملاحظة"></textarea>
                                </div>











                                <div class="d-flex" style="margin:20px auto;width: 100%;flex-wrap: wrap;">
                                    <!--begin::Col-->
                                    <!--begin::Input group-->
                                    <div class="me-3 mb-3">
                                        <select class="d-none" name="images[]" id="images"
                                                multiple></select>

                                        <button id="uppy_images_btn" type="button"
                                                class="text-center btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                                            <i class="fas fa-upload fs-4 me-2" style="color: #009EF7;"></i>
                                            إرفاق الدورات المهنية
                                        </button>
                                        <!--end::Select-->
                                    </div>
                                    <!--begin::Input group-->
                                    <div class="me-5 mb-3">
                                        <input type="hidden" id="experience_cert" name="experience_cert">
                                        <button id="uppy_images_btn2" type="button"
                                                class="text-center btn btn-outline btn-outline-dashed btn-outline-info  btn-active-light-info">
                                            <i class="fas fa-upload fs-4 me-2" style="color: #7239EA;"></i>
                                            إرفاق شهادة الخبرة
                                        </button>
                                        <!--end::Select-->
                                    </div>
                                    <div class=" me-5 mb-3">
                                        <input type="hidden" id="good_manners_cert"
                                               name="good_manners_cert">
                                        <button id="uppy_images_btn3" type="button"
                                                class="text-center btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger">
                                            <i class="fas fa-upload fs-4 me-2" style="color: #F1416C;"></i>
                                            إرفاق حسن سير وسلوك
                                        </button>
                                        <!--end::Select-->
                                    </div>

                                </div>













                            </div>
                            <div class=" mb-2 d-flex justify-content ">
                                <button type="submit" id="kt_docs_formvalidation_text_submit"
                                    class="btn btn-primary font-weight-bold mr-2" name="submitButton">حفظ
                                    البيانات</button>
                                &nbsp;&nbsp;
                                <button type="reset" class="btn btn-light font-weight-bold mr-2">تفريغ البيانات</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
@endsection


@section('styles')
    <link href="https://releases.transloadit.com/uppy/v3.3.1/uppy.min.css" rel="stylesheet">
    <style>
        .uppy-Root {
            font-family: inherit !important;
        }

        .uppy-size--md .uppy-Dashboard-note {
            direction: rtl !important;
        }

        a.uppy-Dashboard-poweredBy {
            display: none !important;
        }

        .uppy-Dashboard-close {
            right: -15px !important;
            background-color: #2275d7;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 30px;
        }

        .uppy-Dashboard-close span {
            top: -3px;
            position: relative;
        }

    </style>


@endsection







@section('scripts')
    <script type="text/javascript"
        src="{{ asset('assets/module/woker_j.js') }}?t={{ config('global.ver.version_all') }}"></script>
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="https://releases.transloadit.com/uppy/v3.3.1/uppy.min.js"></script>
    <script src="https://releases.transloadit.com/uppy/locales/v3.0.4/ar_SA.min.js"></script>
    <script>
        let site_url = '{{URL::to('')}}';
        let csrf = "{{csrf_token()}}";


        let i_btn = "#uppy_images_btn";
        let i_route = '{{route('upload.images')}}';
        let i_path = 'mol';
        let i_note = 'يمكنك رفع صور الدورات بحد اقصى 20 صورة ولا يتجاوز حجم الصورة الواحدة 3 MB';
        let i_file_types = ['image/*', 'application/pdf'];
        let i_field_name = 'images';
        let i_max_number_of_files = 20;
        let i_max_file_size = 1048576 * 3; // 3 MB
        let i_input = document.getElementById('images');

        let i_btn2 = "#uppy_images_btn2";
        let i_route2 = '{{route('upload.images')}}';
        let i_path2 = 'mol';
        let i_note2 = 'يجب ألا يتجاوز حجم المرفق 3 MB';
        let i_file_types2 = ['image/*', 'application/pdf'];
        let i_field_name2 = 'experience_cert';
        let i_max_number_of_files2 = 1;
        let i_max_file_size2 = 1048576 * 3; // 3 MB
        let i_input2 = document.getElementById('experience_cert');

        let i_btn3 = "#uppy_images_btn3";
        let i_route3 = '{{route('upload.images')}}';
        let i_path3 = 'mol';
        let i_note3 = 'يجب ألا يتجاوز حجم المرفق 3 MB';
        let i_file_types3 = ['image/*', 'application/pdf'];
        let i_field_name3 = 'good_manners_cert';
        let i_max_number_of_files3 = 1;
        let i_max_file_size3 = 1048576 * 3; // 3 MB
        let i_input3 = document.getElementById('good_manners_cert');

    </script>
    <script src="{{asset('assets/js/custom/images.js')}}"></script>
    <script src="{{asset('assets/js/custom/images2.js')}}"></script>
    <script src="{{asset('assets/js/custom/images3.js')}}"></script>

@endsection
