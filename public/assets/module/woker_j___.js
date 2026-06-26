

var wait_table_data=".. انتظر قليلا من فضلك";
var required_msg='هذا الحقل مطلوب';
function load_message() {
   $.blockUI({
       message: '<h3 style="color: #fff;">'+wait_table_data+'</h3>  <div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
       css: {
           border: 'none',
           padding: '15px',
           backgroundColor: '#000',
           '-webkit-border-radius': '10px',
           '-moz-border-radius': '10px',
           opacity: .5,
           color: '#fff !important',
           'z-index': '10100'
       }
   });
}
function unload_message() {
 $.unblockUI();
}
"use strict";
var KTFormValidationDemoBasic = {
    init: function () {
        !(function () {

                const demoForm = document.getElementById('save_workers');
                const fv = FormValidation.formValidation(demoForm, {
               //   locale: 'ar_MA',
                //    localization: FormValidation.locales.ar_MA,

                  //locale: 'en_US',
                 // localization: FormValidation.locales.en_US,
               /*  locale: 'en_US',
                 localization: FormValidation.locales.en_US,*/
             /*    locale: 'ar_MA',
                 localization: FormValidation.locales.ar_MA,*/
            /*   locale: 'en_US',
                 localization: en_US,*/
                    fields: {
                        SSN_IN: {
                            validators: {
                                notEmpty: {
                                    message: required_msg,
                                },
                            },
                        },
                        NAME_IN: {
                            validators: {
                                notEmpty: {
                                    message: required_msg,
                                },
                            },
                        },
                        
                        
                        MOBILE_IN: {
                            validators: {
                                notEmpty: {
                                   message: required_msg,
                                },
                            },
                        },
                        
                         /*  GOVERNORATE_IN: {
                            validators: {
                                notEmpty: {
                                   message: required_msg,
                                },
                            },
                        },*/
                        EMPLOYER_IN: {
                            validators: {
                                notEmpty: {
                                    message: required_msg,
                                },
                            },
                        },
                        WORKPLACE_IN: {
                            validators: {
                                notEmpty: {
                                   message: required_msg,
                                },
                            },
                        },
                        
                        WORK_FIELD_IN: {
                            validators: {
                                notEmpty: {
                                    message: required_msg,
                                },
                            },
                        },
                        
                        DAILY_INCOME_IN: {
                            validators: {
                                notEmpty: {
                                    message: required_msg,
                                },
                            },
                        },
                        
                
                        username: {
                            validators: {
                                notEmpty: {
                                   // message: 'The username is required',
                                },
                                stringLength: {
                                    min: 6,
                                    max: 30,
                                   // message: 'The username must be more than 6 and less than 30 characters long',
                                },
                                regexp: {
                                    regexp: /^[a-zA-Z0-9_]+$/,
                                   // message: 'The username can only consist of alphabetical, number and underscore',
                                },
                            },
                        },
                        avatar: {
                            validators: {
                                file: {
                                    extension: 'jpeg,jpg,png',
                                    type: 'image/jpeg,image/png',
                                  //  message: 'The selected file is not valid',
                                },
                            },
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                  //  message: 'The email address is required',
                                },
                                emailAddress: {
                                  //  message: 'The input is not a valid email address',
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                      //  tachyons: new FormValidation.plugins.Tachyons(),
                      bootstrap5: new FormValidation.plugins.Bootstrap5(),
                     /* bootstrap5: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.col-12',
                    }),*/


                                    submitButton: new FormValidation.plugins.SubmitButton(),
                       /* icon: new FormValidation.plugins.Icon({
                            valid: 'fa fa-check',
                            invalid: 'fa fa-times',
                            validating: 'fa fa-refresh',
                        }),*/


                    },


                }).on('core.form.valid', function (e) {

                    var formData = new FormData($("#save_workers")[0]);
                    save_workers(formData);


//alert(formData);
                }).on('core.form.invalid', function() {
                   /* Swal.fire({
                        text: "Oops! There are some error(s) detected.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });*/
                }) ;
                $(demoForm.querySelector('[name="sex"]')).on('change', function () {
                    // Revalidate the field when an option is chosen
                    fv.revalidateField('sex');
                });


        })(),



            (function () {
                const t = document.getElementById("kt_docs_formvalidation_email");
                var e = FormValidation.formValidation(t, {
                    fields: { email_input: { validators: { emailAddress: { message: "The value is not a valid email address" }, notEmpty: { message: "Email address is required" } } } },
                    plugins: { trigger: new FormValidation.plugins.Trigger(), bootstrap: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: "" }) },
                });
                const i = document.getElementById("kt_docs_formvalidation_email_submit");
                i.addEventListener("click", function (t) {
                    t.preventDefault(),
                    e &&
                    e.validate().then(function (t) {
                        console.log("validated!"),
                        "Valid" == t &&
                        (i.setAttribute("data-kt-indicator", "on"),
                            (i.disabled = !0),
                            setTimeout(function () {
                                i.removeAttribute("data-kt-indicator"),
                                    (i.disabled = !1),
                                    Swal.fire({ text: "Form has been successfully submitted!", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } });
                            }, 2e3));
                    });
                });
            })(),
            (function () {
                const t = document.getElementById("kt_docs_formvalidation_textarea");
                var e = FormValidation.formValidation(t, {
                    fields: { textarea_input: { validators: { notEmpty: { message: "Textarea input is required" } } } },
                    plugins: { trigger: new FormValidation.plugins.Trigger(), bootstrap: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: "" }) },
                });
                const i = document.getElementById("kt_docs_formvalidation_textarea_submit");
                i.addEventListener("click", function (t) {
                    t.preventDefault(),
                    e &&
                    e.validate().then(function (t) {
                        console.log("validated!"),
                        "Valid" == t &&
                        (i.setAttribute("data-kt-indicator", "on"),
                            (i.disabled = !0),
                            setTimeout(function () {
                                i.removeAttribute("data-kt-indicator"),
                                    (i.disabled = !1),
                                    Swal.fire({ text: "Form has been successfully submitted!", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } });
                            }, 2e3));
                    });
                });
            })(),
            (function () {
                const t = document.getElementById("kt_docs_formvalidation_radio");
                var e = FormValidation.formValidation(t, {
                    fields: { radio_input: { validators: { notEmpty: { message: "Radio input is required" } } } },
                    plugins: { trigger: new FormValidation.plugins.Trigger(), bootstrap: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: "" }) },
                });
                const i = document.getElementById("kt_docs_formvalidation_radio_submit");
                i.addEventListener("click", function (t) {
                    t.preventDefault(),
                    e &&
                    e.validate().then(function (t) {
                        console.log("validated!"),
                        "Valid" == t &&
                        (i.setAttribute("data-kt-indicator", "on"),
                            (i.disabled = !0),
                            setTimeout(function () {
                                i.removeAttribute("data-kt-indicator"),
                                    (i.disabled = !1),
                                    Swal.fire({ text: "Form has been successfully submitted!", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } });
                            }, 2e3));
                    });
                });
            })(),
            (function () {
                const t = document.getElementById("kt_docs_formvalidation_checkbox");
                var e = FormValidation.formValidation(t, {
                    fields: { checkbox_input: { validators: { notEmpty: { message: "Checkbox input is required" } } } },
                    plugins: { trigger: new FormValidation.plugins.Trigger(), bootstrap: new FormValidation.plugins.Bootstrap5({ rowSelector: ".fv-row", eleInvalidClass: "", eleValidClass: "" }) },
                });
                const i = document.getElementById("kt_docs_formvalidation_checkbox_submit");
                i.addEventListener("click", function (t) {
                    t.preventDefault(),
                    e &&
                    e.validate().then(function (t) {
                        console.log("validated!"),
                        "Valid" == t &&
                        (i.setAttribute("data-kt-indicator", "on"),
                            (i.disabled = !0),
                            setTimeout(function () {
                                i.removeAttribute("data-kt-indicator"),
                                    (i.disabled = !1),
                                    Swal.fire({ text: "Form has been successfully submitted!", icon: "success", buttonsStyling: !1, confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" } });
                            }, 2e3));
                    });
                });
            })();
    },
};
KTUtil.onDOMContentLoaded(function () {
    KTFormValidationDemoBasic.init();
});











function save_workers(formData) {
    // load_message();
   //  alert( $("#save_workers").attr('action'));

   //  formData.append("csrf_test_name", Cookies.get('csrf_cookie_name'));
 formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
   // headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'), '_method': 'patch'},
  /* $.ajaxSetup({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
     }
 });*/



  $.ajax({
         url: $("#save_workers").attr('action'),
         dataType: 'json',
         type: 'POST',

         async: false,
         data: formData,
         contentType: false,
         processData: false,
         success: function (resp) {

             if (resp.status === false) {
                // $("#displayErrors_worker").html(resp.message);
               /*  $("#errorBox_worker").show();
                 document.documentElement.scrollTop = 0;
                 DisplayToastrMessage_General("error", resp.message, 3000);*/
                            $("#errorBox_worker").show();

           $("#displayErrors_worker").html('');

             $.each(resp.message, function (key, item)
             {
             //  $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
            //   $("#displayErrors_worker").html(item);
                  //  $('#displayErrors_worker').append('<div class="alert alert-danger">'+item+'</div');
                    $('#displayErrors_worker').append('<p>'+item+'</p');


             });

                 unload_message();
             } else {
                 DisplayToastrMessage_General("success", resp.message, 3000);
                 $("#errorBox_worker").hide();
                 document.documentElement.scrollTop = 0;
                 $("#save_workers")[0].reset();
                 $('#save_workers').children().find('input,select,file').each(function(){
                     $(this).val('').trigger('change');
                  });
                  unload_message();

             }
         }
     });
 }



 function view_all_worker() {
    // var token = $('meta[name="csrf-token"]').attr('content');

   //var url = "{{ route('dashboard.workers.tbl') }}";
       var dt_from = $('#dt_from').val();
       var dt_to = $('#dt_to').val();
       var worker_name = $('#worker_name_v').val();
       var sex = $('#sex_v').val();
       var phone = $('#phone_v').val();
       var email = $('#email_v').val();
       $.ajax({
       // url: url,

        // type: "POST",
         url: $("#boew_workers").attr('action'),

          // dataType: 'json',
           type: 'POST',

           async: false,

         data: {
        //   "_token": "{{ csrf_token() }}",
               dt_from: dt_from,
               dt_to: dt_to,
               worker_name:worker_name,
               sex:sex,
               phone:phone,
               email:email

              // 'csrf_test_name': Cookies.get('csrf_cookie_name')
           },
           beforeSend: function () {
             //  load_message();
           },
           complete: function () {
             //  unload_message();
           },
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           success: function (data) {
               var container = $('#result_worker_tbl');
               if (data) {
                   container.html(data);
                   //unload_message();
               }
           }
       });
   }

   function upd_worker(id) {
    $(document).ready(function () {
        load_message();
        var url = $('.upd_work').data('url');

    //var url = '{{ route("dashboard.workers.upd_workers") }}';
    //   alert(url);
        $.ajax({
            url:url,

          // url: "upd_workers",
          //  url: "{{ route('dashboard.workers.upd_workers') }}",
        //  url: url,
            'type': 'POST',
            async: false,
            'data': {
                id: id
            },
            beforeSend: function () {
                load_message();
            },
            complete: function () {
                unload_message();
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'success': function (data) {
                var container = $('#show_module');
                if (data) {
                    container.html(data);
                    unload_message();
                    $("#view_prim_const_m").modal('show');

                //    $('.modal-title').html('تعديل بيانات العميل');
                    $('#view_prim_const_m').on('hidden.bs.modal', function () {
                    });
                }
            },
            'error': function (request, status, err) {
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


function show_worker(id) {
    $(document).ready(function () {
        load_message();

        $.ajax({
            url: $("#upd_worker_data").attr('action'),

            'type': 'POST',
            async: false,
            'data': {
                id: id,
                'csrf_test_name': Cookies.get('csrf_cookie_name')
            },
            'success': function (data) {
                var container = $('#show_module');
                if (data) {
                    container.html(data);
                    unload_message();
                    $("#view_prim_const_m").modal();
                //    $('.modal-title').html('تعديل بيانات العميل');
                    $('#view_prim_const_m').on('hidden.bs.modal', function () {
                    });
                }
            },
            'error': function (request, status, err) {
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


$("#upd_worker_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_worker_data")[0]);
    if ($('#upd_worker_data').valid()) {
        upd_worker_data_send(formData);
    } else {
        var alert = $('#kt_form_1_msg');
        alert.removeClass('kt--hide').show();
        $("#view_prim_const_m").scrollTop(0);
        swal_alert();
        e.preventDefault();
    }
});
function upd_worker_data_send(formData) {
    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
        url: $("#upd_worker_data").attr('action'),
        dataType: 'json',
        type: 'POST',
        async: false,
        data: formData,
        contentType: false,
        processData: false,
        success: function (resp) {
            if (resp.status == false) {
           //  alert( resp.message);
           $("#errorBox_worker").show();

           $("#displayErrors_worker").html('');

             $.each(resp.message, function (key, item)
             {
             //  $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
            //   $("#displayErrors_worker").html(item);
                  //  $('#displayErrors_worker').append('<div class="alert alert-danger">'+item+'</div');
                    $('#displayErrors_worker').append('<p>'+item+'</p');


             });

             /*  let data = resp.message;
               $.each( data, function( key, value ) {
               $("#errorBox_worker").show();
               $("#displayErrors_worker").html(value);
           });*/


               // $("#displayErrors_worker").html(resp.message);
            //    $("#errorBox_worker").show();
                document.documentElement.scrollTop = 0;
                DisplayToastrMessage_General("error", resp.message, 3000);
                unload_message();
            } else {
               DisplayToastrMessage_General("success", resp.message, 3000);
               $("#errorBox_worker").hide();
               unload_message();
                $("#view_prim_const_m").modal('hide');
                swal_sucess();
                view_all_worker();
            }
        }
    });
}
function del_worker_file(file_name, direct, val_pk,i) {
   $.ajax({
        cache: false,
        type: 'POST',
        async: false,
        'url': 'del_file',
        data: {
            file_name: file_name,
            direct: direct,
            val_pk: val_pk,
            'csrf_test_name': Cookies.get('csrf_cookie_name')
        },
        'success': function (data) {
            unload_message();
        },
        beforeSend: function () {
            load_message();
         $('#image_url_' + i).val('');
         $('.repeat_'+ i).remove();
        },
        complete: function () {
            unload_message();
            if (direct === 'files') {}
        },
        'error': function (request, status, err) {
            unload_message();
            window.location.reload();
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
}
