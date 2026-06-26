


"use strict";
var KTFormValidationDemoBasic = {
    init: function () {
        !(function () {
            const demoForm = document.getElementById('save_unemployment');
            const fv = FormValidation.formValidation(demoForm, {
                fields: {
                    P_IN_PROJECT_ID: {
                        validators: {
                            notEmpty: {
                                message: required_msg,
                            },

                        },
                    },
P_IN_MOBILE: {
                        validators: {
                            notEmpty: {
                                message: required_msg,
                            },
                                  stringLength: {
                                    min: 10,
                                    max: 10,
                                    message: 'يجب ان1يكون رقم مكون من 10 ارقام',
                                },
                            numeric: {
                            message: 'يجب ان يكون رقم',
                        },

                        },
                    },

P_IN_WHATSAPP: {
                        validators: {
                                  stringLength: {
                                    min: 9,
                                    max: 9,
                                    message: 'يجب ان يكون رقم مكون من 9 ارقام',
                                },
                                  numeric: {
                            message: 'يجب ان يكون رقم',
                        },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                },
            }).on('core.form.valid', function (e) {
                var formData = new FormData($("#save_unemployment")[0]);
                save_unemployment(formData);
            }).on('core.form.invalid', function() {
            }) ;
            $(demoForm.querySelector('[name="sex"]')).on('change', function () {
                fv.revalidateField('sex');
            });
        })();
    },
};
KTUtil.onDOMContentLoaded(function () {
    KTFormValidationDemoBasic.init();
});








function show_proj_data(P_IN_PROJECT_ID) {
$(document).ready(function () {
load_message();
        var url = $('.P_IN_PROJECT_ID').data('url');
        var proj_id = $('#P_IN_PROJECT_ID').val();
$.ajax({
url:url,
'type': 'POST',
async: false,
'data': {
proj_id: proj_id
},
beforeSend: function () {
load_message();
},
complete: function () {
unload_message();
},
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
'success': function (data) {
var container = $('#show_module_sm');
if (data) {
container.html(data);
unload_message();
$("#view_prim_const_sm").modal('show');
$('#view_prim_const_sm').on('shown.bs.modal', function () {
});
$('.modal-title').html('عرض البيانات المشروع');
$('#view_prim_const_sm').on('hidden.bs.modal', function () {
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







function show_proj_data___________(proj_id) {
        var url = $('.project_id').data('url');
        var project_id = $('#project_id').val();

    $.ajax({
     'url': url,
        async: false,
        'type': 'POST',
        'data': {
            project_id:project_id,
        },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

        beforeSend: function () {
            load_message();
        },
        complete: function () {
            unload_message();
        },
        'success': function (data) {
var container = $('#show_proj_data');
            if (data) {
                container.html(data);
                unload_message();
            }
        }
    });
}
function save_unemployment(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
        url: $("#save_unemployment").attr('action'),
        dataType: 'json',
        type: 'POST',

        async: false,
        data: formData,
        contentType: false,
        processData: false,
        success: function (resp) {
            if (resp.status === false) {
                $("#errorBox_worker").show();
                $("#displayErrors_worker").html('');
                 $("#successBox_worker").hide();
                $("#displaysuccess_worker").html('');


                $.each(resp.message, function (key, item)
                {
                    $('#displayErrors_worker').append('<p>'+item+'</p');

                });

                    $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');
document.documentElement.scrollTop = 0;
                unload_message();
            } else {
                                $("#errorBox_worker").hide();
                $("#displayErrors_worker").html('');

                $("#successBox_worker").show();
                $("#displaysuccess_worker").html('');


                                     $("#displaysuccess_worker").html(resp.message_out);

//document.documentElement.scrollTop = 0;
              view_all_data(resp.url);
             //  view_all_unemployment(resp.url);
                      //     window.location.href = resp.url;

                $("#save_unemployment")[0].reset();
                $('#save_unemployment').children().find('input,select,file').not('#P_IN_MOBILE,#SSN_IN,#NAME_IN').each(function(){
                    $(this).val('').trigger('change');
                });
                unload_message();
                          // swal_sucess();
     //   DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');




            }
        }
    });
}


function view_all_data(url) {
    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
        },
        beforeSend: function () {
            load_message();
        },
        complete: function () {
            unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
            var container = $('#result_worker_tbl');
            if (data) {
                container.html(data);
            }
        }
    });
}

function view_all_unemployment(url) {
    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
        },
        beforeSend: function () {
            load_message();
        },
        complete: function () {
            unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
            var container = $('#result_worker_tbl');
            if (data) {
                container.html(data);
            }
        }
    });
}


function view_all_operator() {
//var url = "{{ route('dashboard.project.tbl') }}";
var ssn = $('#ssn').val();
var employer = $('#employer').val();
$.ajax({
// url: url,
// type: "POST",
url: $("#boew_project").attr('action'),
// dataType: 'json',
type: 'POST',
async: false,
data: {
ssn:ssn,
employer:employer,
},
beforeSend: function () {
            load_message();
},
complete: function () {
            unload_message();
},
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
success: function (data) {
var container = $('#result_operator_tbl');
if (data) {
container.html(data);
//unload_message();
}
}
});
}

