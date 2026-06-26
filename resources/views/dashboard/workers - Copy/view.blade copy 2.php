@extends('layouts.dashboard')


@section('content')
    <style type="text/css">
        .select2-selection__arrow b {
            display: none !important;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-inputmask]').inputmask();
            $(".sex_v,.city,.region,.nation").select2({
                width: 'resolve',
            });

            $("#dt_from").datepicker({
                format: "yyyy-mm-dd",
                weekStart: 6,
                autoclose: true,
                language: "ar",
                daysOfWeekHighlighted: "6",
                todayBtn: "linked",
                todayHighlight: true,
                keyboardNavigation: true,
                clearBtn: true
            });
            $("#dt_to").datepicker({
                format: "yyyy-mm-dd",
                weekStart: 6,
                autoclose: true,
                language: "ar",
                daysOfWeekHighlighted: "6",
                todayBtn: "linked",
                todayHighlight: true,
                keyboardNavigation: true,
                clearBtn: true
            });

        });
    </script>
    <style type="text/css">
        .kt-font-info {
            color: #072a9d !important;
        }

        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border: 1px solid #232b51;
        }

        .form-control {
            border: 1px solid #232b51;
        }

        .input-group>.input-group-prepend>.btn,
        .input-group>.input-group-prepend>.input-group-text,
        .input-group>.input-group-append:not(:last-child)>.btn,
        .input-group>.input-group-append:not(:last-child)>.input-group-text,
        .input-group>.input-group-append:last-child>.btn:not(:last-child):not(.dropdown-toggle),
        .input-group>.input-group-append:last-child>.input-group-text:not(:last-child) {
            border-color: #232b51;
        }

        .kt-form.kt-form--label-right .form-group label:not(.kt-checkbox):not(.kt-radio):not(.kt-option) {
            text-align: right;
            color: #072a9d !important;
        }

        #customFile .custom-file-input:lang(en)::after {
            content: "Select file...";
        }

        #customFile .custom-file-input:lang(en)::before {
            content: "Click me";
        }

        .custom-file-input.selected:lang(en)::after {
            content: "" !important;
        }

        .custom-file {
            overflow: hidden;
        }

        .custom-file-input {
            white-space: nowrap;
        }

        .kt-form.kt-form--label-right .form-group label:not(.kt-checkbox):not(.kt-radio):not(.kt-option) {
            text-align: right;
            color: #072a9d !important;
        }

        .kt-form.kt-form--label-right .form-group label:not(.kt-checkbox):not(.kt-radio):not(.kt-option) {
            text-align: right;
        }

        .input-group>.custom-file:not(:last-child) .custom-file-label,
        .input-group>.custom-file:not(:last-child) .custom-file-label::after {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /*.form-group label {font-weight: inherit;color: #050541;}
        .form-group label {font-size: 1rem;font-weight: 400;}*/
        .btn-danger {
            color: #fff;
            background-color: #fd397a;
            border-color: #232b51;
            color: #ffffff;
        }

        .custom-control-label::before,
        .custom-file-label,
        .custom-select {
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>



    <script type="text/javascript">
        $(document).ready(function() {
            $('#add_file').on('click', function() {
                var newfield =
                    '<div class="form-group row repeat"><div class="input-group mb-3"><div class="custom-file"><input type="file" class="custom-file-input" name="files[]" ><label class="custom-file-label kt-font-primary kt-font-bolder" for="customFile" data-browse="upload"></label></div><div class="input-group-append"><a class="btn btn-sm btn-danger remove"  style="padding: 0.7rem 1rem;"><span><i class="la la-minus" style="color:#fff"></i></span></a></div></div></div>';
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

    <!--<script type="text/javascript" src="<?php echo asset('assets/woker_j.js'); ?>"></script>-->
    @push('scripts')
        <script src="{{ asset('assets/woker_j.js') }}"></script>
    @endpush

    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    <?php echo 'main_title'; ?> </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class=" flaticon2-back "></i></a>
                    <span class="kt-subheader__breadcrumbs"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                        <?php echo 'sub_title'; ?> </a></i>
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
                            <?php echo 'header'; ?> </h3>
                    </div>
                </div>
                <!--   <form autocomplete='off' class="kt-form kt-form--label-right" action="{{ route('dashboard.workers.store') }}" method="post"
                        id="save_workers" name="save_workers" enctype="multipart/form-data" accept-charset="utf-8">-->



                <form class="kt-form kt-form--label-right" enctype="multipart/form-data" id="boew_workers"
                    name="boew_workers" accept-charset="utf-8" method="post" action="{{ route('dashboard.workers.tbl') }}"
                    enctype="multipart/form-data" accept-charset="utf-8">
                    @csrf
                    <div class="kt-portlet__body">

                        <div class="form-group row">


                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label> إسم المشروع </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fa
                            fa  fa-archway
kt-font-dark kt-font-bolder"></i></span>
                                        </div>
                                        <input name="worker_name_v" id="worker_name_v" type="text"
                                            class="form-control kt-font-dark kt-font-bolder" placeholder=" إسم المشروع "
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="kt-font-info kt-font-bolder">sex</label>
                                    <select class="sex_v select2" id="sex_v" name="sex_v" style="width:100%">
                                        <option value="">اختر</option>
                                        @foreach ($sexs as $sex)
                                            <option value="{{ $sex->sex_id }} ">{{ $sex->sex_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>




                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>mobile</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="la  la-phone kt-font-dark kt-font-bolder"></i></span></div>
                                        <input name="phone_v" id="phone_v" type="text"
                                            class="form-control kt-font-dark kt-font-bolder rtlchange" placeholder="mobile"
                                            aria-describedby="basic-addon1" data-inputmask="'alias' : 'integer'"
                                            maxlength="9" minlenght="9">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span
                                                class="input-group-text kt-font-info kt-font-bold">@</span></div>
                                        <input name="email_v" id="email_v" type="text"
                                            class="form-control kt-font-dark kt-font-bolder" placeholder="email"
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="">تاريخ التعاقد من</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="flaticon2-calendar-8 kt-font-info kt-font-bold"></i></span></div>
                                        <input id="dt_from" readonly name="dt_from" type="text" class="form-control"
                                            placeholder="تاريخ التعاقد من" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="">تاريخ التعاقد الى</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="flaticon2-calendar-8 kt-font-info kt-font-bold"></i></span>
                                        </div>
                                        <input id="dt_to" readonly name="dt_to" type="text"
                                            class="form-control" placeholder=" تاريخ التعاقد الى"
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>



                            <div class="col-lg-3" style="padding-top: 27px;padding-left: 0;">
                                <a onclick="view_all_worker()" class="btn btn-success btn-brand--icon btn-pill btn-md"
                                    style="color:#fff;padding-bottom:0.9rem">
                                    <span>
                                        <span>بحث <i class="la la-search kt-font-light "></i></span>
                                    </span>
                                </a>
                                <div class="dropdown btn-group " role="group">
                                    <button class="btn btn-dark dropdown-toggle btn-pill btn-md" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" style="color:#fff;padding-bottom:0.9rem">
                                        <span>
                                            <i class="fa fa-print" style="color:#fff"></i>
                                            <span>طباعة</span>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        x-placement="bottom-start"
                                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                        <a class="dropdown-item  kt-font-danger" onclick="print_project('',1)"><i
                                                class="fa  fa-file-pdf  kt-font-danger "
                                                style="line-height: initial"></i>طباعة PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
                <div id="result_worker_tbl" name="result_worker_tbl"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="view_prim_const_m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="show_module" name="show_module"> </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function upd_worker_________(id) {
            $(document).ready(function() {
                load_message();
                $.ajax({
                    //   url: "upd_workers",
                    url: "{{ route('dashboard.workers.upd_workers') }}",

                    'type': 'POST',
                    async: false,
                    'data': {
                        id: id
                    },
                    beforeSend: function() {
                        load_message();
                    },
                    complete: function() {
                        unload_message();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    'success': function(data) {
                        var container = $('#show_module');
                        if (data) {
                            container.html(data);
                            unload_message();
                            $("#view_prim_const_m").modal();
                            //    $('.modal-title').html('تعديل بيانات العميل');
                            $('#view_prim_const_m').on('hidden.bs.modal', function() {});
                        }
                    },
                    'error': function(request, status, err) {
                        unload_message();
                        if (status === "error") {
                            if (err === "Not Found") {
                                unload_message();
                            }
                            if (err === "Internal Server Error") {
                                unload_message();
                            } else {
                                unload_message();
                            }
                        }
                    }
                });
            });
        }














        function del_workers(id) {
            swal.fire({
                title: 'حذف',
                text: 'هل انت متأكد من الحذف',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'تأكيد الحذف',
                cancelButtonText: 'الغاء الامر',
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

        function view_all_worker_________________() {
            // var token = $('meta[name="csrf-token"]').attr('content');
            alert("jjjjjjjjjjjjjjjjjj");
            //var url = "{{ route('dashboard.workers.tbl') }}";
            var dt_from = $('#dt_from').val();
            var dt_to = $('#dt_to').val();
            $.ajax({
                // url: url,

                // type: "POST",
                // url: $("#boew_workers").attr('action'),
                url: "{{ route('dashboard.workers.tbl') }}",

                // dataType: 'json',
                type: 'POST',

                async: false,

                data: {
                    //   "_token": "{{ csrf_token() }}",
                    dt_from: dt_from,
                    dt_to: dt_to,
                    // 'csrf_test_name': Cookies.get('csrf_cookie_name')
                },
                beforeSend: function() {
                    load_message();
                },
                complete: function() {
                    unload_message();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    var container = $('#result_worker_tbl');
                    if (data) {
                        container.html(data);
                        unload_message();
                    }
                }
            });
        }

        $(document).ready(function() {
            view_all_worker();
        });
    </script>
@endsection


@push('styles')
@endpush


@stack('scripts')
