var KTFormControls = function () {

    var save_violation_valid = function () {
        $("#save_violation").validate({
    rules: {
        shop_id: {
            required: true
        },
        violation_dt: {
            required: true
        },
        violation_val: {
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
                var formData = new FormData($("#save_violation")[0]);
        save_violation(formData);
        return false;
    }
});
};
var upd_violation_data_valid = function () {
$("#upd_violation_data").validate({
    ignore: ":hidden",
    rules: {
        shop_id: {
            required: true
        },
        violation_dt: {
            required: true
        },
        violation_val: {
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
            save_violation_valid();
            upd_violation_data_valid();
        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});






function violation_note_history(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.violation_note_history').data('url');
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
     $('.modal-title').html('عرض سجلات الملاحظة');
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


    function view_all_note_history(url) {
        var violation_id = $('#violation_id').val();

        $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            violation_id:violation_id,
        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#result_history_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }








function print_violation_xlsx(id) {
    $(document).ready(function () {
        if(id==''){
            var violation_month_desc = $('#violation_month_desc_v').val();
            var shop_id = $('#shop_id_v').val();
            var manager_id = $('#manager_id_v').val();
            var violation_no = $('#violation_no_v').val();
            var violation_ispay = $('#violation_ispay_v').val();
            var comme_no = $('#municip_no_v').val();
            var municip_no = $('#municip_no_v').val();
            var shop_respon = $('#shop_respon_v').val();
        }
        var url = $('.print_violation_xlsx').data('url');
        $.ajax({
            url:url,
            'type': 'POST',
            dataType: 'json',
            async: false,
            'data': {
                id:id,
                violation_month_desc:violation_month_desc,
                shop_id:shop_id,
                manager_id:manager_id,
                violation_no:violation_no,
                violation_ispay:violation_ispay,
                comme_no:comme_no,
                municip_no:municip_no,
                shop_respon:shop_respon,
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'success': function(data) {
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", "violation.xlsx");
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


function print_violation_pdf(id) {
    $(document).ready(function () {
        load_message();
        if(id==''){
            var violation_month_desc = $('#violation_month_desc_v').val();
            var shop_id = $('#shop_id_v').val();
            var manager_id = $('#manager_id_v').val();
            var violation_no = $('#violation_no_v').val();
            var violation_ispay = $('#violation_ispay_v').val();
            var comme_no = $('#municip_no_v').val();
            var municip_no = $('#municip_no_v').val();
            var shop_respon = $('#shop_respon_v').val();
                }
        var url = $('.print_violation_pdf').data('url');
        $.ajax({
            url:url,
            dataType: 'binary',
            'type': 'POST',
            'data': {
                id:id,
                violation_month_desc:violation_month_desc,
                shop_id:shop_id,
                manager_id:manager_id,
                violation_no:violation_no,
                violation_ispay:violation_ispay,
                comme_no:comme_no,
                municip_no:municip_no,
                shop_respon:shop_respon,            },
            xhrFields: {
                responseType: 'blob'
            },

            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(result) {
                var url = URL.createObjectURL(result);
                var $a = $('<a />', {
                    'href': url,
                    'download': 'report_violation.pdf',
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


function save_violation(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_violation").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_violation").show();
$("#displayErrors_violation").html('');
$("#errorBox_violation").removeClass("bg-success");
$("#errorBox_violation").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_violation').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_violation').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_violation").show();
$("#displayErrors_violation").html('');
$("#errorBox_violation").removeClass("bg-danger");
$("#errorBox_violation").addClass("bg-success");
$("#displayErrors_violation").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_violation")[0].reset();
unload_message();

$('#save_violation').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}









function upd_violation_det(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_violation_det').data('url');
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

































function upd_violation(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_violation').data('url');
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





    $("#upd_violation_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_violation_data")[0]);
    if ($('#upd_violation_data').valid()) {
    upd_violation_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_violation_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_violation_data").attr('action'),
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
        $("#errorBox_violation").show();
        $("#displayErrors_violation").html('');
        $("#errorBox_violation").removeClass("bg-success");
        $("#errorBox_violation").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_violation').append('<p>'+item+'</p');
        });
    $('#displayErrors_violation').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
        view_all_violation();
    $("#displayErrors_violation").html('');
    $("#errorBox_violation").removeClass("bg-danger");
    $("#errorBox_violation").addClass("bg-success");
    $("#displayErrors_violation").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }













function view_all_violation() {
    var violation_month_desc = $('#violation_month_desc_v').val();
    var shop_id = $('#shop_id_v').val();
    var manager_id = $('#manager_id_v').val();
    var violation_no = $('#violation_no_v').val();
    var violation_ispay = $('#violation_ispay_v').val();
    var comme_no = $('#municip_no_v').val();
    var municip_no = $('#municip_no_v').val();
    var shop_respon = $('#shop_respon_v').val();


    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        violation_month_desc:violation_month_desc,
        shop_id:shop_id,
        manager_id:manager_id,
        violation_no:violation_no,
        violation_ispay:violation_ispay,
        comme_no:comme_no,
        municip_no:municip_no,
        shop_respon:shop_respon,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_violation_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }





























function save_violation(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_violation").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_violation").show();
$("#displayErrors_violation").html('');
$("#errorBox_violation").removeClass("bg-success");
$("#errorBox_violation").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_violation').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_violation').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_violation").show();
$("#displayErrors_violation").html('');
$("#errorBox_violation").removeClass("bg-danger");
$("#errorBox_violation").addClass("bg-success");
$("#displayErrors_violation").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_violation")[0].reset();
unload_message();

$('#save_violation').children().find('input,select,file').each(function(){
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


