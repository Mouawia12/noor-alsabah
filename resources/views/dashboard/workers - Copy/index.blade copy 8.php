@extends('layouts.app')
@section('title', 'sss')
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif
    <div id="user_reg" class="alert alert-danger d-none">

    </div>


<!--begin::Form-->
<form id="kt_docs_formvalidation_text" class="form" action="#" autocomplete="off">
    <!--begin::Input group-->
    <div class="fv-row mb-10">
        <!--begin::Label-->
        <label class="required fw-bold fs-6 mb-2">Text Input</label>
        <!--end::Label-->

        <!--begin::Input-->
        <input type="text" name="text_input" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="" value="" />
        <!--end::Input-->
    </div>
    <!--end::Input group-->

    <!--begin::Actions-->
    <button id="kt_docs_formvalidation_text_submit" type="submit" class="btn btn-primary">
        <span class="indicator-label">
            Validation Form
        </span>
        <span class="indicator-progress">
            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
    <!--end::Actions-->
</form>
<!--end::Form-->


@endsection

{{-- Styles Section --}}
@section('styles')


@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/woker_j.js') }}?t={{ config('global.ver.version_all') }}">    </script>
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/invoices/create.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

















    <script>


// Define form element
const form = document.getElementById('kt_docs_formvalidation_text');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'text_input': {
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

// Submit button handler
const submitButton = document.getElementById('kt_docs_formvalidation_text_submit');
submitButton.addEventListener('click', function (e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log('validated!');

            if (status == 'Valid') {
                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click
                submitButton.disabled = true;

                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                setTimeout(function () {
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;

                    // Show popup confirmation
                    Swal.fire({
                        text: "Form has been successfully submitted!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });

                    //form.submit(); // Submit form
                }, 2000);
            }
        });
    }
});


    </script>











@endsection

