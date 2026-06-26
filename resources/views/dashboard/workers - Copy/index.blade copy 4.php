@extends('layouts.dashboard')
@section('module',"التدريب المهني")
@section('sub',"الإجازة المهنية")
@section('title',"ss")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-session-flash alert-success">
            {{ session('alert.success') }}
        </div>
    @endif

      @if (session()->has('alert.error'))
        <div class="alert alert-session-flash alert-danger">
            {{ session('alert.error') }}
        </div>
    @endif

    @if ($errors->any())
<div class="alert alert-session-flash alert-danger">
    <ul class="">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
    <div id="user_reg" class="alert alert-danger d-none">

    </div>


    <form action="{{route('dashboard.workers.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf

    <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Content-->
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 ">
                <!--begin::Card-->
                <div class="card">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">
                                dd
                            </h3>
                        </div>

                    </div>
                    <!--begin::Card body-->
                    <div class="card-body p-12">
                        <!--begin::Form-->
                        <!--begin::Wrapper-->
                        <div class="mb-0">

                                    <!--begin::Row-->
                                    <div class="row gx-10 mb-5">
                                        <!--begin::Col-->
                                        <div class="col-lg-12">
                                            <!--begin::Input group-->
                                            <div class="row">
                                                <div class="col-3 col-md-3 mb-5">
                                                    <label for="P_DOC_ID" class="form-label required fs-6 fw-bolder text-gray-700 mb-3">رقم الهوية
                                                    </label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-id-card"></i></span></div>

                                                        <input type="number" name="P_DOC_ID" id="P_DOC_ID"
                                                               class="form-control form-control-solid" required
                                                               placeholder="رقم الهوية "/>
                                                               <button type="button" class="btn btn-bg-success" id="ssn_search" style="padding: 0.45rem 1rem !important;border-radius: 5px 0 0 5px;color: #fff;">ابحث</button>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="mt-5 mb-5" style="border-top: 1px dashed #198754;"></div>

                                            <div id="training_form" class="d-none">
                                            <div class="row">

                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="NAME" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الاسم
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-user-edit"></i></span></div>

                                                        <input type="text" name="name" id="NAME"
                                                               class="form-control form-control-solid"
                                                               placeholder="الاسم "  autocomplete="off"/>
                                                    </div>
                                                </div>



                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="GENDER" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الجنس
                                                    </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="gender" id="GENDER"
                                                               class="form-control form-control-solid"
                                                               placeholder="الجنس "/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="SOCIAL_STATUS" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الحالة الاجتماعية
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="social_status" id="SOCIAL_STATUS"
                                                               class="form-control form-control-solid"
                                                               placeholder="الحالة الاجتماعية"/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="DATE_OF_BIRTH" class="form-label fs-6 fw-bolder text-gray-700 mb-3">تاريخ الميلاد
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="date_of_birth" id="DATE_OF_BIRTH"
                                                               class="form-control form-control-solid"
                                                               placeholder="تاريخ الميلاد"/>
                                                    </div>
                                                </div>
{{--                                                <div class="col-6 col-md-3 mb-3">--}}
{{--                                                    <label for="AGE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">العمر--}}
{{--                                                        </label>--}}
{{--                                                    <!--begin::Input group-->--}}
{{--                                                    <div class="">--}}
{{--                                                        <input type="text" name="age" id="AGE"--}}
{{--                                                               class="form-control form-control-solid"--}}
{{--                                                               placeholder="العمر"/>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}


                                                <!--end::Input group-->
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="EMAIL" class="form-label fs-6 fw-bolder text-gray-700 mb-3">البريد الإلكتروني</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-envelope"></i></span></div>

                                                            <input type="email" name="email" id="EMAIL"
                                                               class="form-control form-control-solid" autocomplete="off"
                                                               placeholder="البريد الإلكتروني"/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="MOBILE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">جوال</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-phone"></i></span></div>

                                                        <input type="tel" name="mobile" id="MOBILE"
                                                               class="form-control form-control-solid"
                                                               placeholder="جوال"/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="STATE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">المحافظة</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="state" id="STATE"
                                                               class="form-control form-control-solid"
                                                               placeholder="المحافظة" />
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="CITY" class="form-label fs-6 fw-bolder text-gray-700 mb-3">المدينة</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="city" id="CITY"
                                                               class="form-control form-control-solid"
                                                               placeholder="المدينة" />
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="DISTRICT" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الحي</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="district" id="DISTRICT"
                                                               class="form-control form-control-solid"
                                                               placeholder="الحي" />
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-3">
                                                    <label for="STREET" class="form-label fs-6 fw-bolder text-gray-700 mb-3">شارع</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="street" id="STREET"
                                                               class="form-control form-control-solid"
                                                               placeholder="الشارع" />
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-6 mb-3">
                                                    <label for="NEAREST" class="form-label fs-6 fw-bolder text-gray-700 mb-3">أقرب معلم</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="nearest" id="NEAREST"
                                                               class="form-control form-control-solid"
                                                               placeholder="أقرب معلم" />
                                                    </div>
                                                </div>





                                            </div>
                                            <div class="mt-5 mb-5" style="border-top: 1px dashed #F1416C;"></div>
                                            <div class="row">

                                                <div class="col-6 col-md-4 mb-3">
                                                    <label for="DEGREE" class="form-label required fs-6 fw-bolder text-gray-700 mb-3">المؤهل العلمي
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <select class="form-select form-select-solid" required data-control="select2" name="degree" id="DEGREE" dir="rtl" data-placeholder="اختر المؤهل"
                                                                oninvalid="this.setCustomValidity('يرجى اختيار المؤهل العلمي')"
                                                                oninput="this.setCustomValidity('')">
                                                         <option value="">اختر ..</option>

                                                    </select>
                                                    <!-- @error('degree')-->
                                                    <!--<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
                                                    <!--@enderror-->
                                                </div>
                                                <div class="col-6 col-md-4 mb-3">
                                                    <label for="PROFESSION" class="form-label required fs-6 fw-bolder text-gray-700 mb-3">المهنة المراد التسجيل لها
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <select class="form-select form-select-solid" required data-control="select2" name="profession" id="PROFESSION" dir="rtl" data-placeholder="اختر المهنة"
                                                                oninvalid="this.setCustomValidity('يرجى اختيار المهنة ')"
                                                                oninput="this.setCustomValidity('')">
                                                         <option value="">اختر ..</option>
                                                    </select>
                                                    <!-- @error('profession')-->
                                                    <!--<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
                                                    <!--@enderror-->
                                                </div>
                                                <div class="col-6 col-md-4 mb-3">
                                                    <label for="EXPERIENCE" class="form-label required fs-6 fw-bolder text-gray-700 mb-3">سنوات الخبرة
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="experience" required id="EXPERIENCE"
                                                               class="form-control form-control-solid"
                                                               oninvalid="this.setCustomValidity('يرجى إدخال عدد سنوات الخبرة')"
                                                                oninput="this.setCustomValidity('')"
                                                               placeholder="عدد سنوات الخبرة "/>
                                                    <!--            @error('experience')-->
                                                    <!--<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>-->
                                                    <!--@enderror-->
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-3">
                                                    <label for="ALTERNATIVE_MOBILE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">جوال إضافي
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="alternative_mobile" id="ALTERNATIVE_MOBILE"
                                                               class="form-control form-control-solid"
                                                               placeholder="جوال إضافي   "/>

                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-4 mb-3">
                                                    <label for="CURRENT_STATE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">السكن الحالي
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <select class="form-select form-select-solid" data-control="select2" name="current_state" id="CURRENT_STATE" dir="rtl" data-placeholder="اختر المحافظة"   >
                                                        <option value="">اختر ..</option>
                                                        <option value="غزة">غزة</option>
                                                        <option value="الشمال">الشمال</option>
                                                        <option value="الوسطى">الوسطى</option>
                                                        <option value="خانيونس">خانيونس</option>
                                                        <option value="رفح">رفح</option>
                                                    </select>

                                                </div>


                                            </div>
                                             <div class="mt-5 mb-5" style="border-top: 1px dashed #F1416C;"></div>
                                              <div class="d-flex" style="margin:50px auto;width: max-content;">
                                        <!--begin::Col-->
                                            <!--begin::Input group-->
                                            <div class="me-3">
                                                <select class="d-none" name="images[]" id="images" multiple></select>

                                                <button id="uppy_images_btn" type="button"
                                                        class="text-center btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary"><i class="fas fa-upload fs-4 me-2" style="color: #009EF7;"></i>
                                                    إرفاق الدورات المهنية
                                                </button>
                                                <!--end::Select-->
                                            </div>
                                            <!--begin::Input group-->
                                        <div class="me-5">
                                            <input type="hidden"  id="experience_cert" name="experience_cert">
                                            <button id="uppy_images_btn2"  type="button"
                                                        class="text-center btn btn-outline btn-outline-dashed btn-outline-info  btn-active-light-info"><i class="fas fa-upload fs-4 me-2" style="color: #7239EA;"></i>
                                                إرفاق شهادة الخبرة
                                            </button>
                                            <!--end::Select-->
                                        </div>
                                        <div class=" me-5">
                                            <input type="hidden"  id="good_manners_cert" name="good_manners_cert">
                                            <button id="uppy_images_btn3"  type="button"
                                                        class="text-center btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger"><i class="fas fa-upload fs-4 me-2" style="color: #F1416C;"></i>
                                                إرفاق حسن سير وسلوك
                                            </button>
                                            <!--end::Select-->
                                        </div>

                                    </div>


                                       <div class="mb-0 w-150px">

                                <button type="submit" class="btn btn-primary w-100" id="kt_invoice_submit_button">
                                    حفظ
                                </button>
                            </div>

                                            </div>



                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->

                                    <!--begin::Row-->

                                    <!--end::Row-->

                            <!--begin::Actions-->

                            <!--end::Actions-->

                        </div>
                        <!--end::Wrapper-->
                        <!--end::Form-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
            <!--begin::Sidebar-->


        </div>
        <!--end::Layout-->
    </form>
@endsection

{{-- Styles Section --}}
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
            .uppy-Dashboard-close span{
                top: -3px;
            position: relative;
        }

    </style>


@endsection
@section('scripts')

    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="https://releases.transloadit.com/uppy/v3.3.1/uppy.min.js"></script>
    <script src="https://releases.transloadit.com/uppy/locales/v3.0.4/ar_SA.min.js"></script>

    <script src="{{asset('assets/js/custom/upload/images.js')}}"></script>
    <script src="{{asset('assets/js/custom/upload/images2.js')}}"></script>
        <script src="{{asset('assets/js/custom/upload/images3.js')}}"></script>

@endsection




