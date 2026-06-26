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
    <div class="col-lg-4 col-xxl-4">
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
    <div class="col-lg-8 col-xxl-8">
        <!--begin::Clients-->
        <div class="card h-100">
            <div class="card-body " style='  padding: 0 !important;'>
                <div class="card card-xxl-stretch">
                    <!--begin::Header-->
                    <div class="card-header border-0 bg-info py-5">
                        <h3 class="card-title fw-bolder text-white">خدماتنا</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body p-0" style="position: relative;" >
                        <!--begin::Chart-->
                        <div class="mixed-widget-2-chart card-rounded-bottom bg-info" data-kt-color="info" style="height: 200px; min-height: 200px;"><div id="apexcharts77qyd30x" class="apexcharts-canvas apexcharts77qyd30x apexcharts-theme-light" style="width: 381px; height: 200px;"><svg id="SvgjsSvg1257" width="381" height="200" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1259" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1258"><clipPath id="gridRectMask77qyd30x"><rect id="SvgjsRect1262" width="388" height="203" x="-3.5" y="-1.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask77qyd30x"></clipPath><clipPath id="nonForecastMask77qyd30x"></clipPath><clipPath id="gridRectMarkerMask77qyd30x"><rect id="SvgjsRect1263" width="385" height="204" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter1269" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood1270" flood-color="#cb1b46" flood-opacity="0.5" result="SvgjsFeFlood1270Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite1271" in="SvgjsFeFlood1270Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite1271Out"></feComposite><feOffset id="SvgjsFeOffset1272" dx="0" dy="5" result="SvgjsFeOffset1272Out" in="SvgjsFeComposite1271Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur1273" stdDeviation="3 " result="SvgjsFeGaussianBlur1273Out" in="SvgjsFeOffset1272Out"></feGaussianBlur><feBlend id="SvgjsFeBlend1274" in="SourceGraphic" in2="SvgjsFeGaussianBlur1273Out" mode="normal" result="SvgjsFeBlend1274Out"></feBlend></filter><filter id="SvgjsFilter1276" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood1277" flood-color="#cb1b46" flood-opacity="0.5" result="SvgjsFeFlood1277Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite1278" in="SvgjsFeFlood1277Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite1278Out"></feComposite><feOffset id="SvgjsFeOffset1279" dx="0" dy="5" result="SvgjsFeOffset1279Out" in="SvgjsFeComposite1278Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur1280" stdDeviation="3 " result="SvgjsFeGaussianBlur1280Out" in="SvgjsFeOffset1279Out"></feGaussianBlur><feBlend id="SvgjsFeBlend1281" in="SourceGraphic" in2="SvgjsFeGaussianBlur1280Out" mode="normal" result="SvgjsFeBlend1281Out"></feBlend></filter></defs><g id="SvgjsG1282" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1283" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1292" class="apexcharts-grid"><g id="SvgjsG1293" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1295" x1="0" y1="0" x2="381" y2="0" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1296" x1="0" y1="20" x2="381" y2="20" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1297" x1="0" y1="40" x2="381" y2="40" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1298" x1="0" y1="60" x2="381" y2="60" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1299" x1="0" y1="80" x2="381" y2="80" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1300" x1="0" y1="100" x2="381" y2="100" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1301" x1="0" y1="120" x2="381" y2="120" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1302" x1="0" y1="140" x2="381" y2="140" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1303" x1="0" y1="160" x2="381" y2="160" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1304" x1="0" y1="180" x2="381" y2="180" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1305" x1="0" y1="200" x2="381" y2="200" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line></g><g id="SvgjsG1294" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1307" x1="0" y1="200" x2="381" y2="200" stroke="transparent" stroke-dasharray="0"></line><line id="SvgjsLine1306" x1="0" y1="1" x2="0" y2="200" stroke="transparent" stroke-dasharray="0"></line></g><g id="SvgjsG1264" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG1265" class="apexcharts-series" seriesName="NetxProfit" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1268" d="M 0 200L 0 125C 22.224999999999998 125 41.275000000000006 87.5 63.5 87.5C 85.725 87.5 104.775 120 127 120C 149.225 120 168.275 25 190.5 25C 212.725 25 231.775 100 254 100C 276.225 100 295.275 100 317.5 100C 339.725 100 358.775 100 381 100C 381 100 381 100 381 200M 381 100z" fill="transparent" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask77qyd30x)" filter="url(#SvgjsFilter1269)" pathTo="M 0 200L 0 125C 22.224999999999998 125 41.275000000000006 87.5 63.5 87.5C 85.725 87.5 104.775 120 127 120C 149.225 120 168.275 25 190.5 25C 212.725 25 231.775 100 254 100C 276.225 100 295.275 100 317.5 100C 339.725 100 358.775 100 381 100C 381 100 381 100 381 200M 381 100z" pathFrom="M -1 200L -1 200L 63.5 200L 127 200L 190.5 200L 254 200L 317.5 200L 381 200"></path><path id="SvgjsPath1275" d="M 0 125C 22.224999999999998 125 41.275000000000006 87.5 63.5 87.5C 85.725 87.5 104.775 120 127 120C 149.225 120 168.275 25 190.5 25C 212.725 25 231.775 100 254 100C 276.225 100 295.275 100 317.5 100C 339.725 100 358.775 100 381 100" fill="none" fill-opacity="1" stroke="#cb1b46" stroke-opacity="1" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask77qyd30x)" filter="url(#SvgjsFilter1276)" pathTo="M 0 125C 22.224999999999998 125 41.275000000000006 87.5 63.5 87.5C 85.725 87.5 104.775 120 127 120C 149.225 120 168.275 25 190.5 25C 212.725 25 231.775 100 254 100C 276.225 100 295.275 100 317.5 100C 339.725 100 358.775 100 381 100" pathFrom="M -1 200L -1 200L 63.5 200L 127 200L 190.5 200L 254 200L 317.5 200L 381 200"></path><g id="SvgjsG1266" class="apexcharts-series-markers-wrap" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle1313" r="0" cx="0" cy="0" class="apexcharts-marker wql4vj3e8 no-pointer-events" stroke="#cb1b46" fill="#f1416c" fill-opacity="1" stroke-width="3" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG1267" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1308" x1="0" y1="0" x2="381" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1309" x1="0" y1="0" x2="381" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1310" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1311" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1312" class="apexcharts-point-annotations"></g></g><g id="SvgjsG1291" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1260" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 100px;"></div><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: transparent;"></span><div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
                        <!--end::Chart-->
                        <!--begin::Stats-->
                        <div class="card-p mt-n20 position-relative" style="padding: 1rem !important;">
                            <!--begin::Row-->
                            <div class="row g-0   col-12 col-lg-12 col-md-12 col-sm-12 g-0">
                                <!--begin::Col-->
                                <div class="col bg-light-warning px-1 py-8 rounded-2 me-4 mb-14 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect x="8" y="9" width="3" height="10" rx="1.5" fill="black"></rect>
                                            <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black"></rect>
                                            <rect x="18" y="11" width="3" height="8" rx="1.5" fill="black"></rect>
                                            <rect x="3" y="13" width="3" height="6" rx="1.5" fill="black"></rect>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> ادخال بيانات المشغل</a>
                                    <br>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> عرض بيانات المشغل</a>
                                    <br>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> ادخال الطلبات</a>

                                </div>
                                <div class="col bg-light-danger px-1 py-8 rounded-2 me-4 mb-14 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black"></path>
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> التشغيل المؤقت</a>
                                    <br>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> عرض بيانات التشغيل المؤقت</a>

                                </div>


                                <div class="col bg-light-info px-1 py-8 rounded-2 me-4 mb-14 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black"></path>
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> طلب اجازة مهنية</a>

                                </div>



                            </div>

                            <div class="row g-0   col-12 col-lg-12 col-md-12 col-sm-12 g-0">
                                <!--begin::Col-->
                                <div class="col bg-light-warning px-1 py-8 rounded-2 me-4 mb-14">
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect x="8" y="9" width="3" height="10" rx="1.5" fill="black"></rect>
                                            <rect opacity="0.5" x="13" y="5" width="3" height="14" rx="1.5" fill="black"></rect>
                                            <rect x="18" y="11" width="3" height="8" rx="1.5" fill="black"></rect>
                                            <rect x="3" y="13" width="3" height="6" rx="1.5" fill="black"></rect>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> طلب تأمين صحي</a>

                                </div>
                                <div class="col bg-light-danger px-1 py-8 rounded-2 me-4 mb-14">
                                    <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black"></path>
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> الحالة العملية</a>

                                </div>


                                <div class="col bg-light-dark px-1 py-8 rounded-2 me-4 mb-14">
                                    <span class="svg-icon svg-icon-3x svg-icon-dark d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="black"></path>
                                            <path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> الشهادات العملية</a>

                                </div>



                                <div class="col bg-light-danger px-1 py-8 rounded-2 me-4 mb-14">
                                    <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z" fill="black"></path>
                                            <path d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z" fill="black"></path>
                                        </svg>
                                    </span>
                                    <a href="#" class="text-primary fw-bold "><i class="fas fa-arrow-left fa-fw text-info"></i> تقديم شكوى عمالية</a>
                                </div>
                            </div>


                        </div>
                        <!--end::Stats-->
                    <div class="resize-triggers"><div class="expand-trigger"><div style="width: 382px; height: 462px;"></div></div><div class="contract-trigger"></div></div></div>
                    <!--end::Body-->
                </div>
            </div>
        </div>
    </div>
</div>









<div id="kt_billing_payment_tab_content" class="card-body tab-content">
    <!--begin::Tab panel-->
    <div id="kt_billing_creditcard" class="tab-pane fade show active" role="tabpanel">
        <!--begin::Title-->
        <h3 class="mb-5">My Cards</h3>
        <!--end::Title-->
        <!--begin::Row-->
        <div class="row gx-9 gy-6">
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card-->
                <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                    <!--begin::Info-->
                    <div class="d-flex flex-column py-2">
                        <!--begin::Owner-->
                        <div class="d-flex align-items-center fs-4 fw-bolder mb-5">Marcus Morris
                        <span class="badge badge-light-success fs-7 ms-2">Primary</span></div>
                        <!--end::Owner-->
                        <!--begin::Wrapper-->
                        <div class="d-flex align-items-center">
                            <!--begin::Icon-->
                            <img src="assets/media/svg/card-logos/visa.svg" alt="" class="me-4">
                            <!--end::Icon-->
                            <!--begin::Details-->
                            <div>
                                <div class="fs-4 fw-bolder">Visa **** 1679</div>
                                <div class="fs-6 fw-bold text-gray-400">Card expires at 09/24</div>
                            </div>
                            <!--end::Details-->
                        </div>
                        <!--end::Wrapper-->
                    </div>










                    <!--end::Info-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center py-2">
                        <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-3">Delete</button>
                        <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Edit</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card-->
                <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                    <!--begin::Info-->
                    <div class="d-flex flex-column py-2">
                        <!--begin::Owner-->
                        <div class="d-flex align-items-center fs-4 fw-bolder mb-5">Jacob Holder</div>
                        <!--end::Owner-->
                        <!--begin::Wrapper-->
                        <div class="d-flex align-items-center">
                            <!--begin::Icon-->
                            <img src="assets/media/svg/card-logos/american-express.svg" alt="" class="me-4">
                            <!--end::Icon-->
                            <!--begin::Details-->
                            <div>
                                <div class="fs-4 fw-bolder">Mastercard **** 2040</div>
                                <div class="fs-6 fw-bold text-gray-400">Card expires at 10/22</div>
                            </div>
                            <!--end::Details-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center py-2">
                        <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-3">Delete</button>
                        <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Edit</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Card-->
                <div class="card card-dashed h-xl-100 flex-row flex-stack flex-wrap p-6">
                    <!--begin::Info-->
                    <div class="d-flex flex-column py-2">
                        <!--begin::Owner-->
                        <div class="d-flex align-items-center fs-4 fw-bolder mb-5">Jhon Larson</div>
                        <!--end::Owner-->
                        <!--begin::Wrapper-->
                        <div class="d-flex align-items-center">
                            <!--begin::Icon-->
                            <img src="assets/media/svg/card-logos/mastercard.svg" alt="" class="me-4">
                            <!--end::Icon-->
                            <!--begin::Details-->
                            <div>
                                <div class="fs-4 fw-bolder">Mastercard **** 1290</div>
                                <div class="fs-6 fw-bold text-gray-400">Card expires at 03/23</div>
                            </div>
                            <!--end::Details-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center py-2">
                        <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-3">Delete</button>
                        <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Edit</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3">
                <!--begin::Notice-->
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed h-lg-100 p-6">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <!--begin::Content-->
                        <div class="mb-3 mb-md-0 fw-bold">
                            <h4 class="text-gray-900 fw-bolder">Important Note!</h4>
                            <div class="fs-6 text-gray-700 pe-7">Please carefully read
                            <a href="#" class="fw-bolder me-1">Metronic Terms</a>adding your new payment card</div>
                        </div>
                        <!--end::Content-->
                        <!--begin::Action-->
                        <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">Add Card</a>
                        <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Notice-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Tab panel-->
    <!--begin::Tab panel-->
    <div id="kt_billing_paypal" class="tab-pane fade" role="tabpanel" aria-labelledby="kt_billing_paypal_tab">
        <!--begin::Title-->
        <h3 class="mb-5">My Paypal</h3>
        <!--end::Title-->
        <!--begin::Description-->
        <div class="text-gray-600 fs-6 fw-bold mb-5">To use PayPal as your payment method, you will need to make pre-payments each month before your bill is due.</div>
        <!--end::Description-->
        <!--begin::Form-->
        <form class="form">
            <!--begin::Input group-->
            <div class="mb-7 mw-350px">
                <select name="timezone" data-control="select2" data-placeholder="Select an option" data-hide-search="true" class="form-select form-select-solid form-select-lg fw-bold fs-6 text-gray-700 select2-hidden-accessible" data-select2-id="select2-data-10-hw47" tabindex="-1" aria-hidden="true">
                    <option data-select2-id="select2-data-12-mrg7">Select an option</option>
                    <option value="25">US $25.00</option>
                    <option value="50">US $50.00</option>
                    <option value="100">US $100.00</option>
                    <option value="125">US $125.00</option>
                    <option value="150">US $150.00</option>
                </select><span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-11-kkpp" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid form-select-lg fw-bold fs-6 text-gray-700" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-timezone-c5-container" aria-controls="select2-timezone-c5-container"><span class="select2-selection__rendered" id="select2-timezone-c5-container" role="textbox" aria-readonly="true" title="Select an option">Select an option</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
            </div>
            <!--end::Input group-->
            <button type="submit" class="btn btn-primary">Pay with Paypal</button>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Tab panel-->
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
