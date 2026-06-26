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
        accept-charset="utf-8" method="post" action="{{ route('dashboard.workers.tbl') }}" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column flex-lg-row">
            <div class="flex-lg-row-fluid mb-10 mb-lg-0 ">
                <div class="card">
                    <div class="card-body px-1">
                        <div class="mb-0">
                            <div class="row gx-5 mb-5">
                                <div class=" col-12 col-lg-3 col-md-12 col-sm-12  mb-5">
                                    <label for="worker_name_v" class="form-label  fs-6 fw-bold text-dark mb-3">اسم العامل</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"> <i
                                                    class="fas fa-tools fa-fw text-dark"></i></span></div>

                                        <input type="text" name="worker_name_v" id="worker_name_v" class="form-control fw-bold "
                                            placeholder="اسم العامل" value="" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="col-12 col-lg-2 col-md-12 col-sm-12   mb-5">
                                    <label for="ssn_v" class="form-label   fs-6 fw-bold text-dark mb-3">رقم الإقامة
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="far fa-id-card fa-fw text-dark"></i></span></div>
                                        <input type="number" name="ssn_v" id="ssn_v" class="form-control fw-bold  "
                                            placeholder="رقم الإقامة" />
                                    </div>
                                </div>


                                <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                    <label for="work_place_id_v" class="form-label  fs-6 fw-bold text-dark mb-3">مكان
                                        العمل</label>
                                    <div>
                                        <select class="form-select fw-bold  work_place_id_v" data-control="select2"
                                            id="work_place_id_v" name="work_place_id_v" dir="rtl">
                                            <option value="">الكل</option>
                                            @foreach ($work_place as $x)
                                                <option value="{{ $x->work_place_id }} ">{{ $x->work_place_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5"><label for="doe_v"
                                        class="form-label  fs-6 fw-bold text-dark mb-3">تاريخ إنتهاء الإقامة
                                        :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="far fa-calendar-alt fa-fw text-dark"></i></span></div><input
                                            type="text" name="doe_v" id="doe_v"
                                            class="form-control fw-bold  text-dark input_date_"
                                            placeholder="تاريخ إنتهاء الإقامة " value="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 col-md-12 col-sm-12   mb-5"
                                    style="padding-top: 2rem !important;">
                                    <a onclick="view_all_worker()" class="btn btn-primary btn-primary--icon"
                                        id="kt_search">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>بحث</span>
                                        </span>
                                    </a>
                                    &nbsp;&nbsp;
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
                        <div id="result_worker_tbl" name="result_worker_tbl">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade  " tabindex="-1" id="view_prim_const_m">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل</h5>
                    <div class="btn btn-icon btn-sm btn-danger  ms-2" data-bs-dismiss="modal" aria-label="Close">
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

    <div class="modal fade  " tabindex="-1" id="view_prim_const_sm">
        <div class="modal-dialog  modal-dialog-centered mw-550px ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-danger  ms-2" data-bs-dismiss="modal" aria-label="Close">
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
        src="{{ asset('assets/module/woker_j.js') }}?t={{ config('global.ver.version_all') }}"></script>
    <script>
        view_all_worker("{{ route('dashboard.workers.tbl') }}");
        function del_workers (id) {
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
                        url: "{{ route('dashboard.workers.del_workers') }}",
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
                                view_all_worker("{{ route('dashboard.workers.tbl') }}");
                                swal.fire('تم الحف بنجاح', resp.message);
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
