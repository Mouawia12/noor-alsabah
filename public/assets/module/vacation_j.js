var KTFormControls = function () {

    var save_vacation_valid = function () {
        $("#save_vacation").validate({
    rules: {
        worker_id: {
            required: true
        },
        start: {
            required: true
        },
        end: {
            required: true
        },
        vacation_type_id: {
            required: true
        },
        count_day: {
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
                var formData = new FormData($("#save_vacation")[0]);
        save_vacation(formData);
        return false;
    }
});
};
var upd_vacation_data_valid = function () {
$("#upd_vacation_data").validate({
    ignore: ":hidden",
    rules: {
        worker_id: {
            required: true
        },
        start: {
            required: true
        },
        end: {
            required: true
        },
        vacation_type_id: {
            required: true
        },
        count_day: {
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
            save_vacation_valid();
            upd_vacation_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});


function save_vacation(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_vacation").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_vacation").show();
$("#displayErrors_vacation").html('');
$("#errorBox_vacation").removeClass("bg-success");
$("#errorBox_vacation").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_vacation').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_vacation').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
$("#errorBox_vacation").show();
$("#displayErrors_vacation").html('');
$("#errorBox_vacation").removeClass("bg-danger");
$("#errorBox_vacation").addClass("bg-success");
$("#displayErrors_vacation").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_vacation")[0].reset();
unload_message();
$('#save_vacation').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();

}
}
});
}























function upd_vacation(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_vacation').data('url');
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





    $("#upd_vacation_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_vacation_data")[0]);
    if ($('#upd_vacation_data').valid()) {
    upd_vacation_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_vacation_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_vacation_data").attr('action'),
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
        $("#errorBox_vacation").show();
        $("#displayErrors_vacation").html('');
        $("#errorBox_vacation").removeClass("bg-success");
        $("#errorBox_vacation").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_vacation').append('<p>'+item+'</p');
        });
    $('#displayErrors_vacation').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
        view_all_vacation();
    $("#displayErrors_vacation").html('');
    $("#errorBox_vacation").removeClass("bg-danger");
    $("#errorBox_vacation").addClass("bg-success");
    $("#displayErrors_vacation").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }













function view_all_vacation() {
    var vacation_month_desc = $('#vacation_month_desc_v').val();
    var worker_id = $('#worker_id_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        vacation_month_desc:vacation_month_desc,
        worker_id:worker_id,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_vacation_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }





    function view_all_vacation_all() {
        var vacation_month_desc = $('#vacation_month_desc_v').val();
        var worker_id = $('#worker_id_v').val();

        $.ajax({
        url: $("#search_all").attr('action'),
        type: 'POST',
        async: false,
        data: {
            vacation_month_desc:vacation_month_desc,
            worker_id:worker_id,
        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#result_vacation_tbl_all');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }









        function print_vacation_pdf(id) {
            $(document).ready(function () {
           load_message();
          if(id==''){
            var vacation_month_desc = $('#vacation_month_desc_v').val();
            var worker_id = $('#worker_id_v').val();
            var vacation_type_id = $('#vacation_type_id_v').val();
                  }

            var url = $('.print_vacation_pdf').data('url');
            $.ajax({
            url:url,
            dataType: 'binary',
            'type': 'POST',
        'data': {
            id: id,
            vacation_month_desc:vacation_month_desc,
            worker_id: worker_id,
            vacation_type_id: vacation_type_id,
                    },
            xhrFields: {
                responseType: 'blob'
            },

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result) {
                var url = URL.createObjectURL(result);
                var $a = $('<a />', {
                  'href': url,
                  'download': 'report_worker.pdf',
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



function print_vacation_xlsx(id) {
    $(document).ready(function () {
        if(id==''){
     var vacation_month_desc = $('#vacation_month_desc_v').val();
    var worker_id = $('#worker_id_v').val();
    var vacation_type_id = $('#vacation_type_id_v').val();
}

        var url = $('.print_vacation_xlsx').data('url');
    $.ajax({
    url:url,
    'type': 'POST',
    dataType: 'json',
    async: false,

'data': {
    id:id,
    vacation_month_desc:vacation_month_desc,
    worker_id: worker_id,
    vacation_type_id: vacation_type_id,
    },

    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    'success': function(data) {
        var $a = $("<a>");
        $a.attr("href", data.file);
        $("body").append($a);
        $a.attr("download", "vacation.xlsx");
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








function save_vacation(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_vacation").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_vacation").show();
$("#displayErrors_vacation").html('');
$("#errorBox_vacation").removeClass("bg-success");
$("#errorBox_vacation").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_vacation').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_vacation').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_vacation").show();
$("#displayErrors_vacation").html('');
$("#errorBox_vacation").removeClass("bg-danger");
$("#errorBox_vacation").addClass("bg-success");
$("#displayErrors_vacation").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_vacation")[0].reset();
unload_message();

$('#save_vacation').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}


