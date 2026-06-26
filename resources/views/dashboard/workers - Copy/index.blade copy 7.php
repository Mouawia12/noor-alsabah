@extends('layouts.dashboard')
@section('title', 'sss')
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif
    <div id="user_reg" class="alert alert-danger d-none">

    </div>


    <form action="{{ route('dashboard.workers.store') }}"   id="save_workers" name="save_workers" method="post" enctype="multipart/form-data" autocomplete="off">
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
                                <!--begin::Col-->
                                    <!--begin::Input group-->
                             <!--       <div class="row">
                                        <div class="col-12 col-md-12 mb-5">
                                            <label for="P_DOC_ID" class="form-label  fw-bolder  text-dark mb-3 ">رقم
                                                الهوية
                                            </label>
                                            <div class="input-group ">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                            class="fa fa-id-card text-dark"></i></span></div>

                                                <input type="number" name="P_DOC_ID" id="P_DOC_ID"
                                                    class="form-control form-control-solid "
                                                    placeholder="رقم الهوية " />
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class=" col-12 col-lg-4 col-md-12 col-sm-12  mb-5">
                                            <label for="worker_name" class="form-label fs-6 fw-bolder text-dark mb-3">اسم الموظف</label>
                                            <!--begin::Input group-->
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"> <i
                                                            class="fa fa-user-edit text-dark"></i></span></div>

                                                <input type="text" name="worker_name" id="worker_name"
                                                    class="form-control form-control-solid" placeholder="اسم الموظف"
                                                    autocomplete="off" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                            <label for="sex" class="form-label fs-6 fw-bolder text-dark mb-3">الجنس</label>
                                            <div>
                                                <!--<select class="form-select form-select-solid" name="filter_4" id="filter_4">-->
                                                    <select class="form-select form-select-solid" data-control="select2" id="sex" name="sex" dir="rtl" data-placeholder="اختر التصنيف" >
                                                    <option value="">اختر ..</option>
                                                    <option value="1">ذكر</option>
                                                    <option value="0">أنثى</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-12 col-lg-3 col-md-12 col-sm-12   mb-5">
                                            <label for="phone" class="form-label fs-6 fw-bolder text-dark mb-3">رقم الجوال
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i
                                                            class="fa fa-id-card text-dark"></i></span></div>

                                                <input type="number" name="phone" id="phone"
                                                    class="form-control form-control-solid"
                                                    placeholder="رقم الجوال " />
                                            </div>
                                        </div>


                                        <div class="col-12 col-lg-3 col-md-12 col-sm-12 mb-5">
                                            <label for="email"
                                                class="form-label fs-6 fw-bolder text-dark mb-3">البريد
                                                الإلكتروني</label>
                                            <!--begin::Input group-->
                                            <div class="mb-5 input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"> <i
                                                            class="fa fa-envelope text-dark"></i></span></div>

                                                <input type="email" name="email" id="email"
                                                    class="form-control form-control-solid" autocomplete="off"
                                                    placeholder="البريد الإلكتروني" />
                                            </div>
                                        </div>



                                        <div class="col-12 col-lg-12 col-md-12 col-sm-12 mb-5">
                                            <label for="remarks"
                                                class="form-label fs-6 fw-bolder text-dark mb-3">ملاحظات</label>
                                            <!--begin::Input group-->

                                                            <textarea class="form-control form-control-solid" rows="1" name="remarks" id="remarks" placeholder="اكتب ملاحظة .."></textarea>
                                        </div>






                                    </div>





                                <!--end::Col-->
                            </div>
                            <!--end::Row-->


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



        </div>
        <!--end::Layout-->
    </form>
@endsection

{{-- Styles Section --}}
@section('styles')


@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/woker_j.js') }}?t={{ config('global.ver.version_all') }}">
    </script>
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/invoices/create.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection







<script>
    document.addEventListener('DOMContentLoaded', function (e) {
        const loginButton = document.getElementById('loginButton');
        const demoForm = document.getElementById('demoForm');
        const fv = FormValidation.formValidation(demoForm, {
            fields: {
                username: {
                    validators: {
                        notEmpty: {
                            message: 'The username is required',
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The username must be more than 6 and less than 30 characters long',
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_]+$/,
                            message: 'The username can only consist of alphabetical, number and underscore',
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required',
                        },
                        stringLength: {
                            min: 8,
                            message: 'The password must have at least 8 characters',
                        },
                        different: {
                            message: 'The password cannot be the same as username',
                            compare: function () {
                                return demoForm.querySelector('[name="username"]').value;
                            },
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                tachyons: new FormValidation.plugins.Tachyons(),
                icon: new FormValidation.plugins.Icon({
                    valid: 'fa fa-check',
                    invalid: 'fa fa-times',
                    validating: 'fa fa-refresh',
                }),
            },
        }).on('core.form.validating', function () {
            loginButton.innerHTML = 'Validating ...';
        });

        loginButton.addEventListener('click', function () {
            fv.validate().then(function (status) {
                loginButton.innerHTML =
                    status === 'Valid' ? 'Form is validated. Logging in ...' : 'Please try again';
            });
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function(e) {
        FormValidation
            .formValidation(
                document.getElementById('save_workers'),
                {
                    ...
                }
            )
            .on('core.form.valid', function() {
                var formData = new FormData();

                // Append the text fields
                formData.append('worker_name', demoForm.querySelector('[name="worker_name"]').value);
                formData.append('sex', demoForm.querySelector('[name="sex"]').value);
                formData.append('phone', demoForm.querySelector('[name="phone"]').value);
                formData.append('email', demoForm.querySelector('[name="email"]').value);
                formData.append('remarks', demoForm.querySelector('[name="remarks"]').value);

                // Append the file
              /*  var avatarFiles = demoForm.querySelector('[name="avatar"]').files;
                if (avatarFiles.length > 0) {
                    formData.append('avatar', avatarFiles[0]);
                }*/

                axios.post('/path/to/your/back-end/', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function(response) {
                    ...
                });
            });
    });
</script>
