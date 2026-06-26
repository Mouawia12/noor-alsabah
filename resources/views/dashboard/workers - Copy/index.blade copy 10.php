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


    <form id="kt_docs_formvalidation_text" class="form" action="#" autocomplete="off">
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
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="required fw-bold fs-6 mb-2">Text Input</label>
                                        <!--end::Label-->

                                        <!--begin::Input-->
                                        <input type="text" name="text_input" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" value="" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <div class="row fv-row">
                                        <div class=" col-12 col-lg-4 col-md-12 col-sm-12  mb-5">
                                            <label  class=" required form-label fs-6 fw-bolder text-dark mb-3">اسم الموظف</label>

                                            <!--begin::Input group-->
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"> <i
                                                            class="fa fa-user-edit text-dark"></i></span></div>

                                                <input type="text" name="ccc" id="worker_name"
                                                    class="form-control form-control-solid" placeholder="اسم الموظف" value=""
                                                    autocomplete="off" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                            <label class=" form-label fs-6 fw-bolder text-dark mb-3">الجنس</label>
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
                                            <label for="noremarkste"
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

                                <!--<button id="kt_docs_formvalidation_text_submit" type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        Validation Form
                                    </span>
                                    <span class="indicator-progress">
                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>-->

                                <button type="submit" id="kt_docs_formvalidation_text_submit" class="btn btn-primary font-weight-bold mr-2" name="submitButton">Validate</button>
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
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/invoices/create.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/woker_j.js') }}?t={{ config('global.ver.version_all') }}">    </script>











    <script>

/*
        const form = document.getElementById('kt_docs_formvalidation_text');

        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'ccc': {
                        validators: {
                            notEmpty: {
                                message: 'Text input is required'
                            }
                        }
                    },

                    'sex': {
                        validators: {
                            notEmpty: {
                                message: 'Text input is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        const submitButton = document.getElementById('kt_docs_formvalidation_text_submit');
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');

                            submitButton.disabled = false;

                            Swal.fire({
                                text: "Form has been successfully submitted!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });

                        }, 2000);
                    }
                });
            }
        });
*/

            </script>














@endsection

