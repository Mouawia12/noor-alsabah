@extends('layouts.app')
@section('module'," التشغيل ")
@section('sub',"المشاريع ")
@section('title',"$page_title")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif
    <div id="user_reg" class="alert alert-danger d-none">

    </div>

    <form class="kt-form kt-form--label-right" enctype="multipart/form-data" id="boew_workers" name="boew_workers"
        accept-charset="utf-8" method="post" action="{{ route('dashboard.workers.tbl') }}" enctype="multipart/form-data"
        accept-charset="utf-8">

        @csrf























        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Content-->

            <div class="flex-lg-row-fluid mb-10 mb-lg-0 ">

                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body ">

                        <!--begin::Form-->
                        <!--begin::Wrapper-->
                        <div class="mb-0">

                            <!--begin::Row-->
                            <div class="row gx-10 mb-5">
                                <div class="row ">


                                    <div class=" col-12 col-lg-4 col-md-12 col-sm-12  mb-5">
                                        <label for="emps_name" class="  form-label fs-6 fw-bolder text-dark mb-3">اسم
                                            الموظف</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"> <i
                                                        class="fa fa-user-edit text-dark"></i></span></div>

                                            <input type="text" name="worker_name_v" id="worker_name_v"
                                                class="form-control form-control-solid" placeholder="اسم الموظف"
                                                value="" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                        <label class=" form-label fs-6 fw-bolder text-dark mb-3">الجنس</label>
                                        <div>
                                            <select class="form-select form-select-solid" data-control="select2"
                                                id="sex_v" name="sex_v" dir="rtl" data-placeholder="اختر الجنس"
                                                data-allow-clear="true">
                                                <option value="">اختر ..</option>
                                                @foreach ($sexs as $sex)
                                                    <option value="{{ $sex->sex_id }} ">{{ $sex->sex_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12 col-lg-3 col-md-12 col-sm-12   mb-5">
                                        <label for="phone" class="form-label fs-6 fw-bolder text-dark mb-3">رقم
                                            الجوال
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fa fa-id-card text-dark"></i></span></div>

                                            <input type="number" name="phone_v" id="phone_v"
                                                class="form-control form-control-solid" placeholder="رقم الجوال " />
                                        </div>
                                    </div>


                                    <div class="col-12 col-lg-3 col-md-12 col-sm-12 mb-5">
                                        <label for="email_v" class="form-label fs-6 fw-bolder text-dark mb-3">البريد
                                            الإلكتروني</label>
                                        <!--begin::Input group-->
                                        <div class="mb-5 input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"> <i
                                                        class="fa fa-envelope text-dark"></i></span></div>

                                            <input type="email" name="email_v" id="email_v"
                                                class="form-control form-control-solid" autocomplete="off"
                                                placeholder="البريد الإلكتروني" />
                                        </div>
                                    </div>







                                    <div class="col-12 col-lg-3 col-md-12 col-sm-12   mb-5">
                                        <label for="phone" class="form-label fs-6 fw-bolder text-dark mb-3">الرقم
                                            الوظيفي
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i
                                                        class="fa fa-id-card text-dark"></i></span></div>

                                            <input type="text" name="job_num" id="job_num"
                                                class="form-control form-control-solid" data-inputmask="'alias' : 'integer'"
                                                maxlength="20" minlenght="20" placeholder="الرقم الوظيفي " />
                                        </div>
                                    </div>




                                    <div class="col-12 col-lg-3 col-md-12 col-sm-12   mb-5" style="padding-top: 2rem !important;">
                                        <a onclick="view_all_worker()" class="btn btn-primary btn-primary--icon" id="kt_search">
                                            <span>
                                                <i class="la la-search"></i>
                                                <span>بحث</span>
                                            </span>
                                        </a>
                                        &nbsp;&nbsp;
                                        <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                            <span>
                                                <i class="la la-close"></i>
                                                <span>Reset</span>
                                            </span>
                                        </button>
                                    </div>







                                 <!--   <div class="row mt-8">
                                        <div class="col-lg-12">
                                            <a class="btn btn-primary btn-primary--icon" id="kt_search">
                                                <span>
                                                    <i class="la la-search"></i>
                                                    <span>بحث</span>
                                                </span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                <span>
                                                    <i class="la la-close"></i>
                                                    <span>Reset</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                -->




                                </div>





                                <!--end::Col-->
                            </div>
                            <!--end::Row-->


                          <!--  <div class="mb-0 w-150px">


                                <a onclick="view_all_worker()" id="kt_docs_formvalidation_text_submit"
                                    class="btn btn-primary active   font-weight-bold mr-2" name="submitButton">بحث
                                    البيانات</a>
                            </div>-->

                            <!--<div class="row mt-8">
                                <div class="col-lg-12">
                                    <a class="btn btn-primary btn-primary--icon" id="kt_search">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>بحث</span>
                                        </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                        <span>
                                            <i class="la la-close"></i>
                                            <span>Reset</span>
                                        </span>
                                    </button>
                                </div>
                            </div>-->
                            <!--end::Actions-->

                        </div>
                        <!--end::Wrapper-->
                        <!--end::Form-->
                        <div id="result_worker_tbl" name="result_worker_tbl">



                        </div>
















                    </div>
                    <!--end::Card body-->

                </div>
                <!--end::Card-->

            </div>



        </div>
        <!--end::Layout-->
    </form>







    <div class="modal fade  " tabindex="-1" id="view_prim_const_m">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered mw-950px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2x"></span>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div id="show_module" name="show_module"> </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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








    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/module/woker_j.js') }}?t={{ config('global.ver.version_all') }}"></script>














<script>
    function del_workers(id) {
        swal.fire({
       //     title: 'حذف',
            text: 'هل انت متأكد من الحذف',
            icon: 'warning',
           /* showCancelButton: true,
            confirmButtonText: 'تأكيد الحذف',
            cancelButtonText: 'الغاء الامر',*/
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
                    url: "{{ route('dashboard.workers.del_workers') }}",
                    'type': 'POST',
                    'dataType': 'json',
                    'async': false,

                    'data': {
                        id: id,
                    },
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                    'success': function(resp) {
                        // view_all_worker();

                        if (resp.status == false) {
                            document.documentElement.scrollTop = 0;
                            swal.fire('خطأ', resp
                                .message);
                        } else {
                            swal.fire('تم الحف بنجاح', resp
                                .message);
                            view_all_worker();
                        }

                    }
                });

            } else if (result.dismiss === 'cancel') {

                swal.fire('الغاء الامر', 'خطأ');
            }
        });
    }

</script>





@endsection
