@extends('layouts.app')
@section('module', 'نظام إدارة الشركة ')
@section('sub', 'الرئيسية')
@section('title', "$page_title")
@section('content')
    <style>
        .mol_home_ a {
            font-size: 16px !important;
        }
    </style>






<div class="row g-6 g-xl-9">
    <div class="col-lg-6 col-xxl-4">
        <!--begin::Card-->
        <div class="card h-100">
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Summary-->
                <!--begin::User Info-->
                <div class="d-flex flex-center flex-column py-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-100px symbol-circle mb-7">
                        <img src="assets/media/avatars/150-1.jpg" alt="image">
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3">مهند ابراهيم محمود السميري</a>
                    <!--end::Name-->
                    <!--begin::Position-->
                   <!-- <div class="mb-9">
                        <div class="badge badge-lg badge-light-primary d-inline">Administrator</div>
                    </div>-->
             <!--       <div class="fw-bolder mb-3">Assigned Tickets
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Number of support tickets assigned, closed and pending this week." data-bs-original-title="" title=""></i></div>
                    <div class="d-flex flex-wrap flex-center">
                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                            <div class="fs-4 fw-bolder text-gray-700">
                                <span class="w-75px">243</span>
                                <span class="svg-icon svg-icon-3 svg-icon-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black"></rect>
                                        <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="fw-bold text-muted">Total</div>
                        </div>
                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                            <div class="fs-4 fw-bolder text-gray-700">
                                <span class="w-50px">56</span>
                                <span class="svg-icon svg-icon-3 svg-icon-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="black"></rect>
                                        <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="fw-bold text-muted">Solved</div>
                        </div>
                        <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                            <div class="fs-4 fw-bolder text-gray-700">
                                <span class="w-50px">188</span>
                                <span class="svg-icon svg-icon-3 svg-icon-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black"></rect>
                                        <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="fw-bold text-muted">Open</div>
                        </div>
                    </div>-->
                    <!--end::Info-->
                </div>
                <!--end::User Info-->
                <!--end::Summary-->
                <!--begin::Details toggle-->

                <!--end::Details toggle-->
                <!--begin::Details content-->
                <div id="kt_user_view_details" class="collapse show">



                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted text-gray-800">رقم الهوية</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">413346578</span>
                        </div>
                        <!--end::Col-->
                    </div>


                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted text-gray-800">البريد الإلكتروني</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">mohannad@mol.ps</span>
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted text-gray-800">رقم الجوال</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">0599396982</span>
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted text-gray-800">المحافظة</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">شمال غزة</span>
                        </div>
                        <!--end::Col-->
                    </div>






                </div>
                <!--end::Details content-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <div class="col-lg-6 col-xxl-8">
        <!--begin::Budget-->
        <div class="card h-100">
            <div class="card-body p-9">
                <div class="fv-row fv-plugins-icon-container">
                    <!--begin::Row-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-3">
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="account_type" value="personal" checked="checked" id="kt_create_account_form_account_type_personal">
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-10" for="kt_create_account_form_account_type_personal">
                                <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                <span class="svg-icon svg-icon-3x me-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="black"></path>
                                        <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="black"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <!--begin::Info-->
                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-4 mb-2">ادخال بيانات المشغل</span>
                                </span>
                                <!--end::Info-->
                            </label>
                            <!--end::Option-->
                        <div class="fv-plugins-message-container invalid-feedback"></div></div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-lg-3">
                            <!--begin::Option-->
                            <input type="radio" class="btn-check" name="account_type" value="corporate" id="kt_create_account_form_account_type_corporate">
                            <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center" for="kt_create_account_form_account_type_corporate">
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <span class="svg-icon svg-icon-3x me-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black"></path>
                                        <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <!--begin::Info-->
                                <span class="d-block fw-bold text-start">
                                    <span class="text-dark fw-bolder d-block fs-4 mb-2">Corporate Account</span>
                                </span>
                                <!--end::Info-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
            </div>
        </div>
        <!--end::Budget-->
    </div>
   <!-- <div class="col-lg-6 col-xxl-4">
        <div class="card h-100">
            <div class="card-body p-9">
                <div class="fs-2hx fw-bolder">49</div>
                <div class="fs-4 fw-bold text-gray-400 mb-7">Metronic Clients</div>
                <div class="symbol-group symbol-hover mb-9">
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Alan Warden">
                        <span class="symbol-label bg-warning text-inverse-warning fw-bolder">A</span>
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Michael Eberon">
                        <img alt="Pic" src="assets/media/avatars/150-12.jpg">
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Michelle Swanston">
                        <img alt="Pic" src="assets/media/avatars/150-13.jpg">
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Francis Mitcham">
                        <img alt="Pic" src="assets/media/avatars/150-5.jpg">
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Susan Redwood">
                        <span class="symbol-label bg-primary text-inverse-primary fw-bolder">S</span>
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Melody Macy">
                        <img alt="Pic" src="assets/media/avatars/150-3.jpg">
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Perry Matthew">
                        <span class="symbol-label bg-info text-inverse-info fw-bolder">P</span>
                    </div>
                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="" data-bs-original-title="Barry Walter">
                        <img alt="Pic" src="assets/media/avatars/150-7.jpg">
                    </div>
                    <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                        <span class="symbol-label bg-dark text-gray-300 fs-8 fw-bolder">+42</span>
                    </a>
                </div>
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">All Clients</a>
                    <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_users_search">Invite New</a>
                </div>
            </div>
        </div>
    </div>-->
</div>









    <div class="row g-5 g-xl-8">














        <div class="card-body border-top p-9">
            <div class="notice  bg-light-success rounded border-success border border-dashed mb-9 p-6">
                <div class=" flex-stack flex-grow-1 text-center ">
                    <!--begin::Content-->
                    <div class="fw-bold ">
                        <h4 class="text-gray-900 fw-bolder ">خدمة الدفع الالكتروني</h4>
                    </div>
                </div>
            </div>
        </div>


<div class="overflow-hidden position-relative card-rounded ">
														<!--begin::Ribbon-->
														<div class="ribbon ribbon-triangle ribbon-top-end border-success ">
															<!--begin::Ribbon icon-->
															<div class="ribbon-icon mt-n5 me-n6">
																<!--begin::Svg Icon | path: icons/duotune/electronics/elc006.svg-->
																<span class="svg-icon svg-icon-2 svg-icon-white">
																	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																		<path opacity="0.3" d="M11.5 22.004C11.4 22.004 11.2 22.004 11.1 21.904C10.7 21.704 10.5 21.404 10.5 21.004V12.404L6.3 8.20393C5.9 7.80393 5.9 7.20403 6.3 6.80403C6.7 6.40403 7.30002 6.40403 7.70002 6.80403L16.7 15.804C17.1 16.204 17.1 16.8039 16.7 17.2039L12.2 21.7039C12 21.9039 11.8 22.004 11.5 22.004ZM12.5 14.404V18.604L14.6 16.504L12.5 14.404Z" fill="black"></path>
																		<path d="M7.00001 17.5041C6.70001 17.5041 6.5 17.404 6.3 17.204C5.9 16.804 5.9 16.2041 6.3 15.8041L10.5 11.604V3.00406C10.5 2.60406 10.7 2.20403 11.1 2.10403C11.5 1.90403 11.9 2.0041 12.2 2.3041L16.7 6.8041C17.1 7.2041 17.1 7.80401 16.7 8.20401L7.70002 17.204C7.50002 17.404 7.30001 17.5041 7.00001 17.5041ZM12.5 5.40408V9.60403L14.6 7.50406L12.5 5.40408Z" fill="black"></path>
																	</svg>
																</span>
																<!--end::Svg Icon-->
															</div>
															<!--end::Ribbon icon-->
														</div>
														<!--end::Ribbon-->
														<!--begin::Card-->
														<div class="card card-bordered">
															<!--
															<div class="card-header ribbon ribbon-top ribbon-vertical">
																<div class="card-title">Ribbon Example</div>
															</div>-->
															<!--end::Header-->
															<!--begin::Body-->
															<div class="card-body">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
															<!--end::Body-->
														</div>
														<!--end::Card-->
													</div>











        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            ادخال بيانات الموظفين</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary"> <i
                                class="fas fa-arrow-left fa-fw text-info"></i>
                                إدارة الموظفين </a>

                    </div>
                    <img src="{{ url('assets/media/illustrations/unitedpalms-1/4.png') }}" alt="" class=" h-100px">
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            ادخال بيانات العامل</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            إدارة العمال </a>

                    </div>
                    <img src="{{ url('assets/media/svg/avatars/job-svgrepo-com.svg') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            حسابات العمال</a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            استعراض الحسابات </a>

                    </div>
                    <img src="{{ url('assets/media/svg/avatars/job-svgrepo-com.svg') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card ">
                <div class="card-body d-flex align-items-center pt-3 pb-0 px-5">
                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-8 me-2">
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            حسابات المحلات </a>
                        <a href="" class="fw-bold text-info fs-4 mb-2 text-hover-primary">
                            <i class="fas fa-arrow-left fa-fw text-info"></i>
                            إدارة حسابات المحلات  </a>

                    </div>
                    <img src="{{ url('assets/media/svg/avatars/job-svgrepo-com.svg') }}" alt="" class=" h-80px">
                </div>
            </div>
        </div>


    </div>
@endsection
@section('styles')
    <style>
        .card {
            border: 1px solid #0abb87 !important;
        }

        body {
            background-color: #fff;
        }

        .pt-3 {
            padding-top: 0rem !important;
        }
    </style>
@endsection
