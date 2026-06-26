@extends('layouts.app')
@section('title',"sss")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
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
                    <!--begin::Card body-->
                    <div class="card-body p-12">
                        <!--begin::Form-->
                        <!--begin::Wrapper-->
                        <div class="mb-0">
                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fw-bolder mb-10 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active text-gray-700" data-bs-toggle="tab" href="#basic_data">البيانات الأساسية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-gray-700" data-bs-toggle="tab" href="#roles">القسم والصلاحيات</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="basic_data" role="tabpanel">
                                    <!--begin::Row-->
                                    <div class="row gx-10 mb-5">
                                        <!--begin::Col-->
                                        <div class="col-lg-12">
                                            <!--begin::Input group-->
                                            <div class="row">
                                                <div class="col-12 col-md-12 mb-5">
                                                    <label for="P_DOC_ID" class="form-label fs-6 fw-bolder text-gray-700 mb-3">رقم الهوية
                                                    </label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-id-card"></i></span></div>

                                                        <input type="number" name="P_DOC_ID" id="P_DOC_ID"
                                                               class="form-control form-control-solid" required
                                                               placeholder="رقم الهوية "/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="EMP_NO" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الرقم الوظيفي
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-id-card"></i></span></div>

                                                        <input type="number" name="emp_no" id="EMP_NO"
                                                               class="form-control form-control-solid" required
                                                               placeholder="الرقم الوظيفي "/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="NAME" class="form-label fs-6 fw-bolder text-gray-700 mb-3">اسم
                                                        المستخدم</label>
                                                    <!--begin::Input group-->
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-user-edit"></i></span></div>

                                                        <input type="text" name="name" id="NAME"
                                                               class="form-control form-control-solid"
                                                               placeholder="اسم المستخدم"  autocomplete="off"/>
                                                    </div>
                                                </div>



                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="GENDER" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الجنس
                                                    </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="gender" id="GENDER"
                                                               class="form-control form-control-solid"
                                                               placeholder="الجنس "/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="SOCIAL_STATUS" class="form-label fs-6 fw-bolder text-gray-700 mb-3">الحالة الاجتماعية
                                                        </label>
                                                    <!--begin::Input group-->
                                                    <div class="">
                                                        <input type="text" name="social_status" id="SOCIAL_STATUS"
                                                               class="form-control form-control-solid"
                                                               placeholder="الحالة الاجتماعية"/>
                                                    </div>
                                                </div>


                                                <!--end::Input group-->
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="EMAIL" class="form-label fs-6 fw-bolder text-gray-700 mb-3">البريد الإلكتروني</label>
                                                    <!--begin::Input group-->
                                                    <div class="mb-5 input-group">
                                                            <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-envelope"></i></span></div>

                                                            <input type="email" name="email" id="EMAIL"
                                                               class="form-control form-control-solid" autocomplete="off"
                                                               placeholder="البريد الإلكتروني"/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="MOBILE" class="form-label fs-6 fw-bolder text-gray-700 mb-3">رقم الموبايل</label>
                                                    <!--begin::Input group-->
                                                    <div class="mb-5 input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-phone"></i></span></div>

                                                        <input type="tel" name="mobile" id="MOBILE"
                                                               class="form-control form-control-solid"
                                                               placeholder="رقم الموبايل"/>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="CURRENT_ADDRESS" class="form-label fs-6 fw-bolder text-gray-700 mb-3">العنوان</label>
                                                    <!--begin::Input group-->
                                                    <div class="mb-5 input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-location-arrow"></i></span></div>

                                                        <input type="text" name="address" id="CURRENT_ADDRESS"
                                                               class="form-control form-control-solid"
                                                               placeholder="العنوان" />
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3 mb-5">
                                                    <label for="PASSWORD" class="form-label fs-6 fw-bolder text-gray-700 mb-3">كلمة المرور</label>
                                                    <!--begin::Input group-->
                                                    <div class="mb-5 input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">  <i class="fa fa-key"></i></span></div>

                                                        <input type="password" name="password" id="PASSWORD"
                                                               class="form-control form-control-solid"
                                                               placeholder="كلمة المرور" autocomplete="off"/>
                                                    </div>
                                                </div>


                                            </div>




                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <style>
                                    .tab-pane .form-group {
                                        border-bottom-width: 1px;
                                        border-bottom-style: dashed;
                                        border-bottom-color: #eff2f5;
                                        padding-top: 1.25rem;
                                        padding-bottom: 1.25rem;
                                    }
                                </style>
                                <div class="tab-pane fade" id="roles" role="tabpanel">
                                    <!--begin::Row-->
                                    <div class="row gx-10 mb-5">
                                        <!--begin::Col-->

                                            <!--begin::Input group-->
                                            <div class="col-6 col-md-6 mb-5">
                                                <!--begin::Label-->
                                                <label class="form-label fw-bolder fs-6 text-gray-700">القسم</label>
                                                <!--end::Label-->
                                                <!--begin::Select-->
                                                <select id="department_id" name="department_id" aria-label="Select a Timezone"
                                                        data-control="select2" dir="rtl" data-hide-search="true"
                                                        data-placeholder="اختر القسم"
                                                        class="form-select form-select-solid">
                                                    <option value=""></option>
                                                </select>
                                                <!--end::Select-->
                                            </div>
                                            <!--begin::Input group-->
                                            <div class="col-6 col-md-6 mb-5">
                                                <!--begin::Label-->
                                                <label class="form-label fw-bolder fs-6 text-gray-700">المسمى الوظيفي</label>
                                                <!--end::Label-->
                                                <!--begin::Select-->
                                                <select id="role_id" name="role_id" aria-label="Select a Timezone"
                                                        data-control="select2" dir="rtl" data-hide-search="true"
                                                        data-placeholder="اختر المسمى" required
                                                        class="form-select form-select-solid">
                                                    <option value=""></option>
                                                </select>
                                                <!--end::Select-->
                                            </div>
                                            <!--end::Input group-->
                                        <div class="col-lg-12">
                                            <div class="text-gray-600 fw-bold d-none" id="roles__">
                                                <label class="form-label fw-bolder fs-6 text-gray-700">الصلاحيات:</label>

                                                <div class="form-group Section_ checkbox-list">
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                        <input type="checkbox" name="dashboard" class="form-check-input ch" id="dashboard" value="dashboard"/>
                                                        <span class="form-check-label">لوحة القيادة (Dashboard)</span>
                                                    </label>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_items" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">الأصناف</span>

                                                        </label>
                                                    </div>
                                                    <div id="Items_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="item_view" value="1"/>
                                                                <span class="form-check-label">عرض</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="item_create" value="1"/>
                                                                <span class="form-check-label">إنشاء</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="item_update" value="1"/>
                                                                <span class="form-check-label">تعديل</span>
                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="item_delete" value="1"/>
                                                                <span class="form-check-label">حذف</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_invoices" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">الفواتير</span>

                                                        </label>
                                                    </div>
                                                    <div id="Invoices_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="invoice_view" value="1"/>
                                                                <span class="form-check-label">عرض</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="invoice_create" value="1"/>
                                                                <span class="form-check-label">إنشاء</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="invoice_update" value="1"/>
                                                                <span class="form-check-label">تعديل</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="invoice_delete" value="1"/>
                                                                <span class="form-check-label">حذف</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_sides" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">الموردين</span>

                                                        </label>
                                                    </div>
                                                    <div id="Sides_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                             <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="side_view" value="1"/>
                                                                 <span class="form-check-label">عرض</span>

                                                             </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="side_create" value="1"/>
                                                                <span class="form-check-label">إنشاء</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="side_update" value="1"/>
                                                                <span class="form-check-label">تعديل</span>
                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="side_delete" value="1"/>
                                                                <span class="form-check-label">حذف</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_comities" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">اللجان</span>

                                                        </label>
                                                    </div>
                                                    <div id="Comities_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="Comity_view" value="1"/>
                                                                <span class="form-check-label">عرض</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                             <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="Comity_create" value="1"/>
                                                                 <span class="form-check-label">إنشاء</span>

                                                             </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                             <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="Comity_update" value="1"/>
                                                                 <span class="form-check-label">تعديل</span>
                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                             <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="Comity_delete" value="1"/>
                                                                 <span class="form-check-label">حذف</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_users" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">المستخدمين</span>

                                                        </label>
                                                    </div>
                                                    <div id="Users_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="user_view" value="1"/>
                                                                <span class="form-check-label">عرض</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="user_create" value="1"/>
                                                                <span class="form-check-label">إنشاء</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="user_update" value="1"/>
                                                                <span class="form-check-label">تعديل</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="user_delete" value="1"/>
                                                                <span class="form-check-label">حذف</span>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group Section_ row">
                                                    <div class="checkbox-list col-lg-3">
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input type="checkbox" id="all_constants" class="form-check-input all_ch"/>
                                                            <span class="form-check-label">الثوابت</span>

                                                        </label>
                                                    </div>
                                                    <div id="Constants_menu" class="col-lg-9 row">
                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="constant_view" value="1"/>
                                                                <span class="form-check-label">عرض</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="constant_create" value="1"/>
                                                                <span class="form-check-label">إنشاء</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="constant_update" value="1"/>
                                                                <span class="form-check-label">تعديل</span>

                                                            </label>
                                                        </div>

                                                        <div class="checkbox-list col-lg-2">
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input type="checkbox" class="form-check-input ch" name="constant_delete" value="1"/>
                                                                <span class="form-check-label">حذف</span>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>

                            </div>
                            <!--begin::Actions-->
                            <div class="mb-0 w-150px">

                                <button type="submit" class="btn btn-primary w-100" id="kt_invoice_submit_button">
                                    حفظ
                                </button>
                            </div>
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
{{--            <div class="flex-lg-auto min-w-lg-300px">--}}
{{--                <!--begin::Card-->--}}
{{--                <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"--}}
{{--                     data-kt-sticky-offset="{default: false, lg: '200px'}"--}}
{{--                     data-kt-sticky-width="{lg: '250px', lg: '300px'}" data-kt-sticky-left="auto"--}}
{{--                     data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">--}}
{{--                    <!--begin::Card body-->--}}
{{--                    <div class="card-body p-10 text-center">--}}
{{--                        <div class="mb-5">--}}
{{--                            <div>--}}
{{--                                <label class="form-label fw-bolder fs-6 text-gray-700 mb-3">صورة المستخدم</label>--}}
{{--                            </div>--}}
{{--                            <!--begin::Image input-->--}}
{{--                            <div class="image-input image-input-outline" data-kt-image-input="true"--}}
{{--                                 style="background-image: url(/assets/media/avatars/blank.png)">--}}
{{--                                <!--begin::Image preview wrapper-->--}}
{{--                                <div class="image-input-wrapper w-200px h-200px"--}}
{{--                                     style="background-image: url(/assets/media/avatars/blank.png)"></div>--}}
{{--                                <!--end::Image preview wrapper-->--}}

{{--                                <!--begin::Edit button-->--}}
{{--                                <label--}}
{{--                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"--}}
{{--                                    data-kt-image-input-action="change"--}}
{{--                                    data-bs-toggle="tooltip"--}}
{{--                                    data-bs-dismiss="click"--}}
{{--                                    title="إضافة صورة">--}}
{{--                                    <i class="bi bi-pencil-fill fs-7"></i>--}}

{{--                                    <!--begin::Inputs-->--}}
{{--                                    <input type="file" name="image" accept=".png, .jpg, .jpeg"/>--}}
{{--                                    <input type="hidden" name="avatar_remove"/>--}}
{{--                                    <!--end::Inputs-->--}}
{{--                                </label>--}}
{{--                                <!--end::Edit button-->--}}

{{--                                <!--begin::Cancel button-->--}}
{{--                                <span--}}
{{--                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"--}}
{{--                                    data-kt-image-input-action="cancel"--}}
{{--                                    data-bs-toggle="tooltip"--}}
{{--                                    data-bs-dismiss="click"--}}
{{--                                    title="إلغاء">--}}
{{--         <i class="bi bi-x fs-2"></i>--}}
{{--     </span>--}}
{{--                                <!--end::Cancel button-->--}}

{{--                                <!--begin::Remove button-->--}}
{{--                                <span--}}
{{--                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"--}}
{{--                                    data-kt-image-input-action="remove"--}}
{{--                                    data-bs-toggle="tooltip"--}}
{{--                                    data-bs-dismiss="click"--}}
{{--                                    title="حذف الصورة">--}}
{{--         <i class="bi bi-x fs-2"></i>--}}
{{--     </span>--}}
{{--                                <!--end::Remove button-->--}}
{{--                            </div>--}}
{{--                            <!--end::Image input-->--}}
{{--                        </div>--}}

{{--                        <!--begin::Separator-->--}}
{{--                        <div class="separator separator-dashed mb-8"></div>--}}
{{--                        <!--end::Separator-->--}}
{{--                        <!--begin::Actions-->--}}
{{--                        <div class="mb-0">--}}

{{--                            <button type="submit" class="btn btn-primary w-100" id="kt_invoice_submit_button">--}}
{{--                                حفظ--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <!--end::Actions-->--}}
{{--                    </div>--}}
{{--                    <!--end::Card body-->--}}
{{--                </div>--}}
{{--                <!--end::Card-->--}}
{{--            </div>--}}
            <!--end::Sidebar-->


        </div>
        <!--end::Layout-->
    </form>
@endsection

{{-- Styles Section --}}
@section('styles')


@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('assets/woker_j.js') }}?t={{ config('global.ver.version_all'); }}"></script>
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/invoices/create.js') }}"></script>
    <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
@endsection




