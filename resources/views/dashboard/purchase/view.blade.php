@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الاداري')
@section('title', "$page_title")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif
    <div id="user_reg" class="alert alert-danger d-none">
    </div>
    <form class="kt-form kt-form--label-right" enctype="multipart/form-data" id="boew_project" name="boew_project"
        accept-charset="utf-8" method="post" action="{{ route('dashboard.purchase.tbl') }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column flex-lg-row">
            <div class="mb-10 flex-lg-row-fluid mb-lg-0">
                <div class="card">
                    <div class="px-1 card-body">
                        <div class="mb-0">
                            <div class="mb-5 row gx-5">



                                <div class="mb-5 col-12 col-lg-2 col-md-12 col-sm-12">
                                    <label for="purchase_no_v" class="mb-3 form-label required fs-6 fw-bold text-dark">رقم
                                        الفاتورة</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="far fa-id-card fa-fw text-dark"></i></span></div><input
                                            type="text" name="purchase_no_v" id="purchase_no_v"
                                            class="form-control fw-bold text-dark text-info"
                                            minlength="1" maxlength="20"
                                            placeholder="رقم الفاتورة">
                                    </div>
                                </div>




                                <div class="mb-5 col-12 col-lg-3 col-md-12 col-sm-12">

                                    <label for="purchase_dt_from" class="mb-3 form-label fs-6 fw-bold text-dark">تاريخ
                                        الفاتورة</label>
                                    <div class="input-daterange input-group" id="kt_datepicker">
                                        <input type="text" class="form-control input_date_ flatpickr-input"
                                            name="purchase_dt_from" id="purchase_dt_from" placeholder="من"
                                            data-col-index="5" readonly="readonly">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i
                                                    class="fas fa-align-justify fa-fw text-dark"></i></span>
                                        </div>
                                        <input type="text" class="form-control input_date_ flatpickr-input"
                                            name="purchase_dt_to" id="purchase_dt_to" placeholder="إلى" data-col-index="5"
                                            readonly="readonly">
                                    </div>
                                </div>






                                <div class="mb-5 col-12 col-lg-4 col-md-12 col-sm-12"><label for="purchase_respon"
                                        class="mb-3 form-label fs-6 fw-bold text-dark">اسم المورد</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-user-tie fa-fw text-dark"></i></span></div><input
                                            type="text" name="purchase_respon_v" id="purchase_respon_v"
                                            class="form-control fw-bold text-dark" placeholder="اسم المورد "
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="mb-5 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <label for="manager_id_v" class="mb-3 form-label fs-6 fw-bold text-dark">قائد
                                        المحل</label>
                                    <div>
                                        <input type="text" id="shops" hidden value="{{ isset($_GET['shops']) ? 'on' : 'off'}}">
                                        <select class="form-select fw-bold" data-control="select2" id="manager_id_v"
                                            name="manager_id_v" dir="rtl">
                                            <option value="">الكل</option>
                                            @foreach ($manager as $x)
                                                <option value="{{ $x->manager_id }} ">{{ $x->manager_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="mb-5 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <label for="manager_id_v" class="mb-3 form-label fs-6 fw-bold text-dark">
                                    مُدخل الفاتورة </label>
                                    <div>
                                        <select class="form-select fw-bold" data-control="select2" id="create_users"
                                            name="create_users" dir="rtl">
                                            <option value="">الكل</option>
                                            @foreach ($create_users as $x)
                                                @if((auth()->user()->isAdmin) or (auth()->user()->id == $x->id) )
                                                <option value="{{$x->id}}">{{$x->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-5 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <label for="manager_id" class="mb-3 form-label fs-6 fw-bold text-dark">
                                        المحل</label>
                                    <div>
                                        <select class="form-select fw-bold" data-control="select2" id="shop_id"
                                            name="shop_id" dir="rtl" data-placeholder=" المحل">
                                            <option value="">اختر ..</option>
                                            @foreach ($shops as $x)
                                                <option value="{{ $x->shop_id }} ">{{ $x->shop_name.($x->municip->municip_no??"")}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-5 col-12 col-lg-3 col-md-12 col-sm-12">
                                    <label for="created_at_date" class="mb-3 form-label fs-6 fw-bold text-dark">
                                        تاريخ الإدخال</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt fa-fw text-dark"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control input_date_ flatpickr-input"
                                            name="created_at_date" id="created_at_date"
                                            placeholder="تاريخ الإدخال" readonly="readonly">
                                    </div>
                                </div>

                                <div class="mb-2 col-12 col-lg-3 col-md-12 col-sm-12"
                                    style="padding-top: 2rem !important;">
                                    <a onclick="view_all_purchase()" class="btn btn-primary btn-primary--icon"
                                        id="kt_search">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>بحث</span>
                                        </span>
                                    </a>
                                    <button type="button" class="btn btn-dark" data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-start"><i class="fas fa-print fa-fw"></i>
                                        تقارير
                                        <span class="rotate-180 svg-icon svg-icon-5 ms-3 me-0"><i
                                                class="fas fa-angle-down fa-fw"></i></span>
                                    </button>
                                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px"
                                        data-kt-menu="true">
                                        <div class="px-3 menu-item">
                                            <a data-url='{{ route('dashboard.report.print_purchase_pdf') }}'
                                                onclick="print_purchase_pdf('')"
                                                class="px-3 menu-link text-dark print_purchase_pdf"><span><i
                                                        class="far fa-file-pdf fa-fw text-danger"></i></span> طباعة PDF
                                            </a>
                                        </div>
                                        <div class="px-3 menu-item">
                                            <a data-url='{{ route('dashboard.report.print_purchase_xlsx') }}'
                                                onclick="print_purchase_xlsx('')"
                                                class="px-3 menu-link text-dark print_purchase_xlsx"><span><i
                                                        class="fas fa-file-excel fa-fw text-success"></i></span>طباعة
                                                EXCEL
                                            </a>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-secondary btn-secondary--icon" name="refresh"
                                        id="refresh" {{-- id="kt_reset" --}}>
                                        <span>
                                            <i class="la la-close"></i>
                                            <span>إعادة تعيين</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="result_purchase_tbl" name="result_purchase_tbl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" tabindex="-1" id="view_prim_const_m" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل</h5>
                    <div class="btn btn-icon btn-sm btn-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">X</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="show_module" name="show_module"> </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="view_prim_const_sm" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-danger ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2x">X</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="show_module_sm" name="show_module_sm"> </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Styles Section --}}
@section('styles')
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('assets/module/purchase_j.js') }}?t={{ config('global.ver.version_all') }}"></script>
    <script>
        view_all_purchase("{{ route('dashboard.purchase.tbl') }}");

        function del_purchase(id) {
            swal.fire({
                text: 'هل انت متأكد من الحذف',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'تأكيد الحذف',
                showCancelButton: true,
                cancelButtonText: 'الغاء الامر',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger'
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('dashboard.purchase.del_purchase') }}",
                        'type': 'POST',
                        'dataType': 'json',
                        'async': false,
                        'data': {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        'success': function(resp) {
                            if (resp.status == false) {
                                document.documentElement.scrollTop = 0;
                                swal.fire('خطأ', resp.message);
                            } else {
                                view_all_purchase("{{ route('dashboard.purchase.tbl') }}");
                                swal.fire('تم الحذفبنجاح', resp.message);
                            }

                        }
                    });
                } else if (result.dismiss === 'cancel') {
                    swal.fire('الغاء الامر', 'خطأ');
                }
            });
        }

        setTimeout(function() {
            view_all_purchase();
        }, 1500)

         //حذف خيار الكل من قائد المحل
         @if (auth()->user()->emp_job != 1)
            document.getElementById("manager_id_v").options[0].remove();
            setTimeout(function() {
                view_all_expense();
            }, 1500)
        @endif

    </script>
@endsection
