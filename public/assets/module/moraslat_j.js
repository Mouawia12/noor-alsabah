
var KTFormControls = function () {



    var save_moraslat_valid = function () {
                $("#save_moraslat").validate({
                    ignore: ":hidden",

            rules: {
                moraslat_type_id: {
                    required: true
                },
                shop_id: {
                    required: true
                },
                worker_id: {
                    required: true
                },

                moraslat_categoty_id: {
                    required: true
                },
                user_id: {
                    required: true
                },
                moraslat_respon: {
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
                        var formData = new FormData($("#save_moraslat")[0]);
                save_moraslat(formData);
                return false;
            }
        });
    };
    var upd_moraslat_data_valid = function () {
        $("#upd_moraslat_data").validate({
            ignore: ":hidden",
            rules: {
                moraslat_no: {
                    required: true
                },
                     moraslat_dt: {
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

    var upd_file_data_valid = function () {
        $("#upd_file_data").validate({
           // ignore: ":hidden",
            rules: {
                moraslat_id: {
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

    var open_moraslat_data_valid = function () {
        $("#open_moraslat_data").validate({
           // ignore: ":hidden",
            rules: {
                moraslat_id: {
                    required: true
                },
                moraslat_status_id: {
                    required: true
                },
                status_note: {
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


    var upd_remark_data_valid = function () {
        $("#upd_remark_data").validate({
           // ignore: ":hidden",
            rules: {
                note_type_id: {
                    required: true
                },
                moraslat_id: {
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
            save_moraslat_valid();
            upd_moraslat_data_valid();
	            upd_status_data_valid();
                upd_file_data_valid();
                open_moraslat_data_valid();
                upd_remark_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});









function load_moraslat_form(moraslat_type_id,desc) {
    var url = $('.moraslat_type_id').data('url');

    var moraslat_type_id = $('#moraslat_type_id').val();
    var moraslat_id = $('#moraslat_id_db').val();

    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            moraslat_type_id:moraslat_type_id,
            desc:desc,
            moraslat_id:moraslat_id,

        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#load_moraslat_form');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });

}










function print_moraslat(id) {
    $(document).ready(function () {
  // load_message();
   alert(id);
    var url = $('.print_moraslat').data('url');
    $.ajax({
    url:url,
    dataType: 'binary',
    'type': 'POST',
'data': {
    id: id
    },
    xhrFields: {
        responseType: 'blob'
    },

    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function(result) {
        var url = URL.createObjectURL(result);
        var $a = $('<a />', {
          'href': url,
          'download': 'rep_all_join_exp.pdf',
          'text': "click"
        }).hide().appendTo("body")[0].click();
        setTimeout(function() {
          URL.revokeObjectURL(url);
        }, 10000);
    //    unload_message();

      },
    'error': function (request, status, err) {
    if (status === "error") {
    if (err === "Not Found") {
    }
    if (err === "Internal Server Error") {
    } else {
    }
    }
    }
    });
    });
    }

    function show_history(id) {
        $(document).ready(function () {
        //load_message();
        var url = $('.show_history').data('url');
        $.ajax({
        url:url,
        'type': 'POST',
        async: false,
        'data': {
        id: id
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
         $('.modal-title').html('عرض سجلات المعاملة');
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


function upd_moraslat(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_moraslat').data('url');
    $.ajax({
    url:url,
    'type': 'POST',
    async: false,
    'data': {
    id: id
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
     $('.modal-title').html('تعديل بيانات الطلب');
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





    $("#upd_moraslat_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_moraslat_data")[0]);
    if ($('#upd_moraslat_data').valid()) {
    upd_moraslat_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_moraslat_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_moraslat_data").attr('action'),
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
        $("#errorBox_moraslat").show();
        $("#displayErrors_moraslat").html('');
        $("#errorBox_moraslat").removeClass("bg-success");
        $("#errorBox_moraslat").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_moraslat').append('<p>'+item+'</p');
        });
    $('#displayErrors_moraslat').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    view_all_moraslat();
    $("#displayErrors_moraslat").html('');
    $("#errorBox_moraslat").removeClass("bg-danger");
    $("#errorBox_moraslat").addClass("bg-success");
    $("#displayErrors_moraslat").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }
    function open_moraslat(id) {
        $(document).ready(function () {
        //load_message();
        var url = $('.open_moraslat').data('url');
        $.ajax({
        url:url,
        'type': 'POST',
        async: false,
        'data': {
        id: id
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
         $('.modal-title').html('فتح مراسلة');
        $('#view_prim_const_m').on('hidden.bs.modal', function () {
                        view_all_moraslat();
            notify_num(resp.url_notify_count);

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





        $("#open_moraslat_data").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($("#open_moraslat_data")[0]);
        if ($('#open_moraslat_data').valid()) {
        open_moraslat_data_send(formData);
        } else {
        var alert = $('#kt_form_1_msg');
        alert.removeClass('kt--hide').show();
        $("#view_prim_const_m").scrollTop(0);
        //swal_alert();
        e.preventDefault();
        }
        });
        function open_moraslat_data_send(formData) {


        formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
        $.ajax({
        url: $("#open_moraslat_data").attr('action'),
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
            $("#errorBox_moraslat").show();
            $("#displayErrors_moraslat").html('');
            $("#errorBox_moraslat").removeClass("bg-success");
            $("#errorBox_moraslat").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_moraslat').append('<p>'+item+'</p');
            });
        $('#displayErrors_moraslat').append('<p>'+resp.message_out+'</p');
        document.documentElement.scrollTop = 0;
        } else {
            $("#view_prim_const_m").modal('hide');
        $("#displayErrors_moraslat").html('');
        $("#errorBox_moraslat").removeClass("bg-danger");
        $("#errorBox_moraslat").addClass("bg-success");
        $("#displayErrors_moraslat").html(resp.message_out);

        swal_sucess();
        }
        }
        });
        }




























function view_all_moraslat() {
    var moraslat_no = $('#moraslat_no_v').val();
    var moraslat_dt_from = $('#moraslat_dt_from').val();
    var moraslat_dt_to = $('#moraslat_dt_to').val();
    var moraslat_respon = $('#moraslat_respon_v').val();
    var manager_id = $('#manager_id_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        moraslat_no:moraslat_no,
        moraslat_dt_from:moraslat_dt_from,
        moraslat_dt_to:moraslat_dt_to,
        manager_id:manager_id,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_moraslat_tbl');
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

function save_moraslat(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_moraslat").attr('action'),
dataType: 'json',
type: 'POST',
async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_moraslat").show();
$("#displayErrors_moraslat").html('');
$("#errorBox_moraslat").removeClass("bg-success");
$("#errorBox_moraslat").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_moraslat').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_moraslat').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_moraslat").show();
$("#displayErrors_moraslat").html('');
$("#errorBox_moraslat").removeClass("bg-danger");
$("#errorBox_moraslat").addClass("bg-success");
$("#displayErrors_moraslat").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_moraslat")[0].reset();
unload_message();

$('#save_moraslat').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}



