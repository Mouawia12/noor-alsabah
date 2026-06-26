var KTFormControls = function () {

    var save_calculate_valid = function () {
        $("#save_calculate").validate({
    rules: {
        shop_id: {
            required: true
        },
        calculate_month_desc: {
            required: true
        },
        calculate_month_val: {
            required: true
        },
        calculate_month_pay: {
            required: true
        },
        calculate_month_remain: {
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
                var formData = new FormData($("#save_calculate")[0]);
        save_calculate(formData);
        return false;
    }
});
};
var upd_calculate_data_valid = function () {
$("#upd_calculate_data").validate({
    ignore: ":hidden",
    rules: {
        shop_id: {
            required: true
        },
        calculate_month_desc: {
            required: true
        },
        calculate_month_val: {
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




    var upd_statement_data_valid = function () {
        $("#upd_statement_data").validate({
            ignore: ":hidden",
            rules: {
                calculate_month_desc: {
                    required: true
                },
                calculate_month_y: {
                    required: true
                },
                calculate_month_m: {
                    required: true
                },
                calculate_month_m: {
                    required: true
                },
                calculate_month_val: {
                    required: true,
                    min:1
                },
                calculate_month_pay: {
                    required: true,
                    min:1
                },
                calculate_month_remain: {
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
            save_calculate_valid();
            upd_calculate_data_valid();
            upd_statement_data_valid();
	            upd_status_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});
function print_calculate_xlsx(id) {
    $(document).ready(function () {
        if(id==''){
            var calculate_month_desc = $('#calculate_month_desc_v').val();
            var shop_id = $('#shop_id_v').val();
            var manager_id = $('#manager_id_v').val();

        }

        var url = $('.print_calculate_xlsx').data('url');
        $.ajax({
            url:url,
            'type': 'POST',
            dataType: 'json',
            async: false,

            'data': {
                id:id,
                calculate_month_desc:calculate_month_desc,
                shop_id:shop_id,
                manager_id:manager_id,

            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'success': function(data) {
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", "calculate.xlsx");
                $a[0].click();
                $a.remove();
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


function print_calculate_pdf(id) {
    $(document).ready(function () {
        load_message();
        if(id==''){
            var calculate_month_desc = $('#calculate_month_desc_v').val();
            var shop_id = $('#shop_id_v').val();
            var manager_id = $('#manager_id_v').val();

        }

        var url = $('.print_calculate_pdf').data('url');
        $.ajax({
            url:url,
            dataType: 'binary',
            'type': 'POST',
            'data': {
                id:id,
                calculate_month_desc:calculate_month_desc,
                shop_id:shop_id,
                manager_id:manager_id,
            },
            xhrFields: {
                responseType: 'blob'
            },

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result) {
                var url = URL.createObjectURL(result);
                var $a = $('<a />', {
                    'href': url,
                    'download': 'report_calculate.pdf',
                    'text': "click"
                }).hide().appendTo("body")[0].click();
                setTimeout(function() {
                    URL.revokeObjectURL(url);
                }, 10000);
                unload_message();

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


function save_calculate(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_calculate").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_calculate").show();
$("#displayErrors_calculate").html('');
$("#errorBox_calculate").removeClass("bg-success");
$("#errorBox_calculate").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_calculate').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_calculate').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_calculate").show();
$("#displayErrors_calculate").html('');
$("#errorBox_calculate").removeClass("bg-danger");
$("#errorBox_calculate").addClass("bg-success");
$("#displayErrors_calculate").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_calculate")[0].reset();
unload_message();

$('#save_calculate').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}









function upd_calculate_det(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_calculate_det').data('url');
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
    success: function (data) {
        var container = $('#show_details');
        if (data) {
        container.html(data);
        //unload_message();
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








function upd_statement(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_statement').data('url');
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











    $("#upd_statement_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_statement_data")[0]);
    if ($('#upd_statement_data').valid()) {
    upd_statement_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_statement_data_send(formData) {
    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_statement_data").attr('action'),
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
        $("#errorBox_statement").show();
        $("#displayErrors_statement").html('');
        $("#errorBox_statement").removeClass("bg-success");
        $("#errorBox_statement").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_statement').append('<p>'+item+'</p');
        });
    $('#displayErrors_statement').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
        view_all_calculate();
    $("#displayErrors_statement").html('');
    $("#errorBox_statement").removeClass("bg-danger");
    $("#errorBox_statement").addClass("bg-success");
    $("#displayErrors_statement").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }




























function upd_calculate(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_calculate').data('url');
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





    $("#upd_calculate_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_calculate_data")[0]);
    if ($('#upd_calculate_data').valid()) {
    upd_calculate_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_calculate_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_calculate_data").attr('action'),
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
        $("#errorBox_calculate").show();
        $("#displayErrors_calculate").html('');
        $("#errorBox_calculate").removeClass("bg-success");
        $("#errorBox_calculate").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_calculate').append('<p>'+item+'</p');
        });
    $('#displayErrors_calculate').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
        view_all_calculate();
    $("#displayErrors_calculate").html('');
    $("#errorBox_calculate").removeClass("bg-danger");
    $("#errorBox_calculate").addClass("bg-success");
    $("#displayErrors_calculate").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }













function view_all_calculate() {
    var calculate_month_desc = $('#calculate_month_desc_v').val();
    var shop_id = $('#shop_id_v').val();

    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        calculate_month_desc:calculate_month_desc,
        shop_id:shop_id,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_calculate_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }




    function view_all_calculate_detail(url) {
        var calculate_id = $('#calculate_id_db').val();

        $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            calculate_id:calculate_id,
        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#result_calculate_detail_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }











function print_calculate(id) {
    $(document).ready(function () {
  // load_message();
   alert(id);
    var url = $('.print_calculate').data('url');
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

function upd_pmonth(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_pmonth').data('url');
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








function view_all_pmonth() {
    var calculate_month_desc = $('#calculate_month_desc_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        calculate_month_desc:calculate_month_desc,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_pmonth_tbl');
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

function save_calculate(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_calculate").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_calculate").show();
$("#displayErrors_calculate").html('');
$("#errorBox_calculate").removeClass("bg-success");
$("#errorBox_calculate").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_calculate').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_calculate').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_calculate").show();
$("#displayErrors_calculate").html('');
$("#errorBox_calculate").removeClass("bg-danger");
$("#errorBox_calculate").addClass("bg-success");
$("#displayErrors_calculate").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_calculate")[0].reset();
unload_message();

$('#save_calculate').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

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
