





var KTFormControls = function () {

    var update_profile_valid = function () {
        $("#update_profile").validate({
    rules: {
        name: {
            required: true
        },
          email: {
            required: true
        },

    },
    highlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
            $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
        } else {
            elem.addClass(errorClass);

        }
        $('.input-group.error-class').find('.help-block.form-error').each(function() {
            $(this).closest('.form-group').addClass('error-class').append($(this));
          });
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
            $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
        }
         else {
            elem.removeClass(errorClass);
        }

    },
    errorPlacement: function (error, element) {
        var elem = $(element);
        if (elem.hasClass("select2-hidden-accessible")) {
            element = $("#select2-" + elem.attr("id") + "-container").parent();
            error.insertAfter(element);
        }
       else if (element.hasClass('multiselect')) {
    error.insertAfter(element.next('.btn-group'));

        }
       else if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
           // alert(elem);
        }
       else if (element.prop("type") === "checkbox") {
    error.insertAfter(element.parent("label"));
       }
       else {
          error.insertAfter(element);
        }
     },
    invalidHandler: function (event, validator) {},
    submitHandler: function (form) {
                var formData = new FormData($("#update_profile")[0]);
        update_profile(formData);
        return false;
    }
});
};

    var save_emps_valid = function () {
                $("#save_emps").validate({
            rules: {
                name: {
                    required: true
                },
                  email: {
                    required: true
                },

            },
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);

                }
                $('.input-group.error-class').find('.help-block.form-error').each(function() {
                    $(this).closest('.form-group').addClass('error-class').append($(this));
                  });
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                }
                 else {
                    elem.removeClass(errorClass);
                }

            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                }
               else if (element.hasClass('multiselect')) {
            error.insertAfter(element.next('.btn-group'));

                }
               else if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                   // alert(elem);
                }
               else if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
               }
               else {
                  error.insertAfter(element);
                }
             },
            invalidHandler: function (event, validator) {},
            submitHandler: function (form) {
                        var formData = new FormData($("#save_emps")[0]);
                save_emps(formData);
                return false;
            }
        });
    };
    var upd_emps_data_valid = function () {
        $("#upd_emps_data").validate({
            ignore: ":hidden",
            rules: {
                name: {
                    required: true
                },
                     email: {
                    required: true
                },

            },
             highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);
                }
                $('.input-group.error-class').find('.help-block.form-error').each(function() {
                    $(this).closest('.form-group').addClass('error-class').append($(this));
                  });
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element);
                }
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
               },
            invalidHandler: function (event, validator) {
                var alert = $('#kt_form_1_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
             //   swal_alert();
                event.preventDefault();
            },
            submitHandler: function (form, event) {
               // alert('ss');
                return false;
            }
        });
    };

    var save_role_valid = function () {
        $("#save_role").validate({

    rules: {
        role_name: {
            required: true,
        },
        role_per: {
            required: true,
        },

    },
    highlight: function (element, errorClass, validClass) {
    var elem = $(element);
    if (elem.hasClass("select2-hidden-accessible")) {
        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
    } else {
        elem.addClass(errorClass);

    }
    $('.input-group.error-class').find('.help-block.form-error').each(function() {
        $(this).closest('.form-group').addClass('error-class').append($(this));
      });


    },
    unhighlight: function (element, errorClass, validClass) {
    var elem = $(element);
    if (elem.hasClass("select2-hidden-accessible")) {
        $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
    }
     else {
        elem.removeClass(errorClass);
    }

    },
    errorPlacement: function (error, element) {
    var elem = $(element);
    if (elem.hasClass("select2-hidden-accessible")) {
        element = $("#select2-" + elem.attr("id") + "-container").parent();
        error.insertAfter(element);
    }
    else if (element.hasClass('multiselect')) {
    error.insertAfter(element.next('.btn-group'));

    }
    else if(element.parent('.input-group').length) {
        error.insertAfter(element.parent());
       // alert(elem);
    }
    else if (element.prop("type") === "checkbox") {
    error.insertAfter(element.parent("label"));
    }
    else {
      error.insertAfter(element);
    }
    },
    invalidHandler: function (event, validator) {document.documentElement.scrollTop = 0;
    },
    submitHandler: function (form) {

    var formData = new FormData($("#save_role")[0]);
    save_role(formData);
    swal_sucess();
    return false;
    }
    });
    };
    var upd_role_data_valid = function () {
        $("#upd_role_data").validate({
          //  ignore: ":hidden",
            rules: {
                role_id_val: {
                    required: true,
                },
                role_name: {
                    required: true,
                },
               /* role_per: {
                    required: true,
                },*/

            },
             highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);
                }
                $('.input-group.error-class').find('.help-block.form-error').each(function() {
                    $(this).closest('.form-group').addClass('error-class').append($(this));
                  });
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element);
                }
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
               },
            invalidHandler: function (event, validator) {
                var alert = $('#kt_form_1_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
             //   swal_alert();
                event.preventDefault();
            },
            submitHandler: function (form, event) {
               // alert('ss');
                return false;
            }
        });
    };

    var upd_status_data_valid = function () {
        $("#upd_status_data").validate({
            ignore: ":hidden",
                    rules: {
                PROJECT_NAME_IN: {
                    required: true
                },
 STATUS_IN: {
                    required: true
                },

            },
             highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                    elem.addClass(errorClass);
                }
                $('.input-group.error-class').find('.help-block.form-error').each(function() {
                    $(this).closest('.form-group').addClass('error-class').append($(this));
                  });
            },
            unhighlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                    elem.removeClass(errorClass);
                }
            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + elem.attr("id") + "-container").parent();
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element);
                }
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
               },
            invalidHandler: function (event, validator) {
                var alert = $('#kt_form_1_msg');
                alert.removeClass('kt--hide').show();
                KTUtil.scrollTop();
             //   swal_alert();
                event.preventDefault();
            },
            submitHandler: function (form, event) {
               // alert('ss');
                return false;
            }
        });
    };




    return {
        init: function () {
            save_emps_valid();
            update_profile_valid();

            upd_emps_data_valid();
            save_role_valid();
            upd_role_data_valid();
	            upd_status_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});







function upd_role(role_id) {
    $(document).ready(function () {
       // load_message();
        var url = $('.upd_role').data('url');

        $.ajax({
            url:url,
            'type': 'POST',
            async: false,
            'data': {
                role_id: role_id,
            },
            beforeSend: function () {
                //load_message();
                },
                complete: function () {
                //unload_message();
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'success': function (data) {
                var container = $('#show_module');
                if (data) {
                container.html(data);
                //unload_message();
                $("#view_prim_const_m").modal('show');
                $('#view_prim_const_m').on('shown.bs.modal', function () {
                });
                 $('.modal-title').html('تعديل بيانات المجموعة');
                $('#view_prim_const_m').on('hidden.bs.modal', function () {
                });
                }
                },
                'error': function (request, status, err) {
                //unload_message();
                if (status === "error") {
                if (err === "Not Found") {
                //unload_message();
                }
                if (err === "Internal Server Error") {
                //unload_message();
                } else {
                //unload_message();
                }
                }
                }
                });
                });
                }

function show_role(role_id) {
    $(document).ready(function () {
        load_message();
        $.ajax({
            'url':  'show_role',
            'type': 'POST',
            async: false,

            'data': {
                role_id: role_id,
                'csrf_test_name': Cookies.get('csrf_cookie_name')
            },
            'success': function (data) {
                var container = $('#show_module');
                if (data) {
                    container.html(data);
                    unload_message();
                    $("#view_prim_const_m").modal();
                    $('#view_prim_const_m').on('hidden.bs.modal', function () {});
                    $('#view_prim_const_m').on('shown.bs.modal', function () {
                        unload_message();
                    });
                }
            }
        });
    });
}






$("#upd_role_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_role_data")[0]);
    if ($('#upd_role_data').valid()) {
    uupd_role_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function uupd_role_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_role_data").attr('action'),
    dataType: 'json',
    type: 'POST',
    async: false,
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function () {
    //load_message();

    },
    complete: function () {
    //unload_message();

    },
    success: function (resp) {
    if (resp.status == false) {
        $("#errorBox_emp").show();
        $("#displayErrors_emp").html('');
        $("#errorBox_emp").removeClass("bg-success");
        $("#errorBox_emp").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_emp').append('<p>'+item+'</p');
        });
    $('#displayErrors_emp').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
        view_all_role();
        $("#displayErrors_emp").html('');
    $("#errorBox_emp").removeClass("bg-danger");
    $("#errorBox_emp").addClass("bg-success");
    $("#displayErrors_emp").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }






function view_all_role() {
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    error: function() {var location = window.location.href;window.location = location; },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_role_tbl');
    if (data) {
    container.html(data);
    }
    }
    });
    }



function save_role(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_role").attr('action'),
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
$("#errorBox_worker").removeClass("bg-success");
$("#errorBox_worker").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_worker').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_worker").show();
$("#displayErrors_worker").html('');
$("#errorBox_worker").removeClass("bg-danger");
$("#errorBox_worker").addClass("bg-success");
$("#displayErrors_worker").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_role")[0].reset();

refresh=true;
$("#jstree").jstree("refresh");
$("#jstree").jstree("deselect_all");


unload_message();

$('#save_role').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}











function show_job_cat(desc,url) {
    $(document).ready(function () {
//alert(url);
     //   load_message();
        $.ajax({
         url: url,

        //  url: "{{ route('dashboard.emps.show_job_cat') }}",

            'type': 'POST',
            async: false,

            'data': {
                desc:desc
                        },
            beforeSend: function () {
           //     load_message();
            },
            complete: function () {
              //  unload_message();
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

            'success': function (data) {
                var container = $('#show_module_role');
                if (data) {
                    container.html(data);
                 //   unload_message();












                    $("#view_role_m").modal('show');
                    $('#view_role_m').on('hidden.bs.modal', function () {
                        var id_val_desc = $('#id_val_desc').val();
                        var job_desc = $('#job_desc').val();



                        $('#job').val(id_val_desc.trim()).trigger('id_val_desc');
                        $('#job_desc').val(job_desc.trim()).trigger('job_desc');
                        if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
                            $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
                        }
                     //   load_emp_div(id_val_desc, desc);


                    });
                    $('#view_role_m').on('shown.bs.modal', function () {
                       //unload_message();
                    });
                    $('#view_role_m').on('hidden.bs.modal', function () {
                        var job = $('#job').val();
//alert(job);
if (job == 1) {
    $('#role_per_div').css('display', 'none');
    $('#manager_div').css('display', 'none');


   // $('#role_per').val('').trigger('change');

} else {
    $('#role_per_div').css('display', '');
    $('#manager_div').css('display', '');


}









                    });
                }
            }
        });
    });
}

function load_emp_div(job,desc) {
    var job = $('#job').val();
    if(job!=1){
        $.ajax({
            url: "emps/load_emp_div",
            'type': 'POST',
            async: false,
            'data': {
                job: job,
                desc:desc,
                'csrf_test_name': Cookies.get('csrf_cookie_name')
            },
            beforeSend: function () {
                load_message();
            },
            complete: function () {
                unload_message();
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'success': function (data) {
                var container = $('#change_job');
                if (data) {
                    container.html(data);
                    unload_message();
                }
            }
        });
    }
    else{
        $('#change_job').empty();
    }
}

function upd_emps(id) {
    $(document).ready(function () {
    load_message();
    var url = $('.upd_emps').data('url');
    $.ajax({
    url:url,
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
    $('#view_prim_const_m').on('shown.bs.modal', function () {
    });
     $('.modal-title').html('تعديل بيانات الطلب');
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





    $("#upd_emps_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_emps_data")[0]);
    if ($('#upd_emps_data').valid()) {
    upd_emps_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_emps_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_emps_data").attr('action'),
    dataType: 'json',
    type: 'POST',
    async: false,
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function () {
    load_message();

    },
    complete: function () {
    unload_message();

    },
    success: function (resp) {
    if (resp.status == false) {
        $("#errorBox_emp").show();
        $("#displayErrors_emp").html('');
        $("#errorBox_emp").removeClass("bg-success");
        $("#errorBox_emp").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_emp').append('<p>'+item+'</p');
        });
    $('#displayErrors_emp').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    $("#displayErrors_emp").html('');
    $("#errorBox_emp").removeClass("bg-danger");
    $("#errorBox_emp").addClass("bg-success");
    $("#displayErrors_emp").html(resp.message_out);
    view_all_emp();

    swal_sucess();
    }
    }
    });
    }




function view_all_emp() {
    var name = $('#name_v').val();
    var email = $('#email_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        name:name,
        email:email,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_emp_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }





function upd_status(id) {
$(document).ready(function () {
load_message();
var url = $('.upd_status').data('url');
$.ajax({
url:url,
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
var container = $('#show_module_sm');
if (data) {
container.html(data);
unload_message();
$("#view_prim_const_sm").modal('show');
$('#view_prim_const_sm').on('shown.bs.modal', function () {
});
 $('.modal-title').html('تعديل حالة المشروع');
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


















$("#upd_status_data").submit(function (e) {
e.preventDefault();
var formData = new FormData($("#upd_status_data")[0]);
if ($('#upd_status_data').valid()) {
upd_status_data_send(formData);
} else {
var alert = $('#kt_form_1_msg');
alert.removeClass('kt--hide').show();
$("#view_prim_const_m").scrollTop(0);
//swal_alert();
e.preventDefault();
}
});
function upd_status_data_send(formData) {
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#upd_status_data").attr('action'),
dataType: 'json',
type: 'POST',
async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status == false) {
//  alert( resp.message);
$("#errorBox_project").show();

$("#displayErrors_project").html('');

$.each(resp.message, function (key, item)
{
//  $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
//   $("#displayErrors_project").html(item);
//  $('#displayErrors_project').append('<div class="alert alert-danger">'+item+'</div');
$('#displayErrors_project').append('<p>'+item+'</p');


});

/*  let data = resp.message;
$.each( data, function( key, value ) {
$("#errorBox_project").show();
$("#displayErrors_project").html(value);
});*/


// $("#displayErrors_project").html(resp.message);
//    $("#errorBox_project").show();
document.documentElement.scrollTop = 0;
//DisplayToastrMessage_General("error", resp.message, 3000);
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_project").hide();
unload_message();
$("#view_prim_const_sm").modal('hide');
view_all_project();
swal_sucess();
}
}
});
}

function save_emps(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_emps").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {

if (resp.status === false) {
$("#errorBox_emp").show();
$("#displayErrors_emp").html('');
$("#errorBox_emp").removeClass("bg-success");
$("#errorBox_emp").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_emp').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_emp').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
$("#errorBox_emp").show();
$("#displayErrors_emp").html('');
$("#errorBox_emp").removeClass("bg-danger");
$("#errorBox_emp").addClass("bg-success");
$("#displayErrors_emp").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_emps")[0].reset();
unload_message();

$('#save_emps').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}
function update_profile(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#update_profile").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {

if (resp.status === false) {
$("#errorBox_emp").show();
$("#displayErrors_emp").html('');
$("#errorBox_emp").removeClass("bg-success");
$("#errorBox_emp").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_emp').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_emp').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
$("#errorBox_emp").show();
$("#displayErrors_emp").html('');
$("#errorBox_emp").removeClass("bg-danger");
$("#errorBox_emp").addClass("bg-success");
$("#displayErrors_emp").html(resp.message_out);
document.documentElement.scrollTop = 0;
unload_message();


swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}

function view_all_data(url) {
   // alert("ddddddd");
//var url = "{{ route('operator.project.tbl') }}";
$.ajax({
// type: "POST",
//url: $("#boew_project").attr('action'),
url: url,

// dataType: 'json',
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
var container = $('#result_project_tbl');
if (data) {
container.html(data);
//unload_message();
}
}
});
}


function view_all_project() {
//var url = "{{ route('dashboard.project.tbl') }}";
var PROJECT_NAME_IN = $('#PROJECT_NAME_IN_V').val();
var FINANCIER_IN = $('#FINANCIER_IN_V').val();
var SIDE_ID_IN = $('#SIDE_ID_IN').val();
var START_DATE_IN = $('#START_DATE_IN_V').val();
var END_DATE_IN = $('#END_DATE_IN_V').val();
$.ajax({
// url: url,
// type: "POST",
url: $("#boew_project").attr('action'),
// dataType: 'json',
type: 'POST',
async: false,
data: {
//   "_token": "{{ csrf_token() }}",
PROJECT_NAME_IN:PROJECT_NAME_IN,
FINANCIER_IN:FINANCIER_IN,
SIDE_ID_IN:SIDE_ID_IN,
START_DATE_IN:START_DATE_IN,
END_DATE_IN:END_DATE_IN,

},
beforeSend: function () {
//  load_message();
},
complete: function () {
//  unload_message();
},
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
success: function (data) {
var container = $('#result_project_tbl');
if (data) {
container.html(data);
//unload_message();
}
}
});
}

function upd_project(id) {
$(document).ready(function () {
load_message();
var url = $('.upd_project').data('url');
$.ajax({
url:url,
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
$('#view_prim_const_m').on('shown.bs.modal', function () {
});
 $('.modal-title').html('تعديل بيانات المشروع');
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


function show_project(id) {
$(document).ready(function () {
load_message();

$.ajax({
url: $("#upd_project_data").attr('action'),

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


$("#upd_project_data").submit(function (e) {
e.preventDefault();
var formData = new FormData($("#upd_project_data")[0]);
if ($('#upd_project_data').valid()) {
upd_project_data_send(formData);
} else {
var alert = $('#kt_form_1_msg');
alert.removeClass('kt--hide').show();
$("#view_prim_const_m").scrollTop(0);
//swal_alert();
e.preventDefault();
}
});
function upd_project_data_send(formData) {
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#upd_project_data").attr('action'),
dataType: 'json',
type: 'POST',
async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status == false) {
//  alert( resp.message);
$("#errorBox_project").show();

$("#displayErrors_project").html('');

$.each(resp.message, function (key, item)
{
//  $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
//   $("#displayErrors_project").html(item);
//  $('#displayErrors_project').append('<div class="alert alert-danger">'+item+'</div');
$('#displayErrors_project').append('<p>'+item+'</p');


});

/*  let data = resp.message;
$.each( data, function( key, value ) {
$("#errorBox_project").show();
$("#displayErrors_project").html(value);
});*/


// $("#displayErrors_project").html(resp.message);
//    $("#errorBox_project").show();
document.documentElement.scrollTop = 0;
//DisplayToastrMessage_General("error", resp.message, 3000);
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_project").hide();
unload_message();
$("#view_prim_const_m").modal('hide');
view_all_project();

swal_sucess();
view_all_project();
}
}
});
}
function del_project_file(file_name, direct, val_pk,i) {
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
