
var KTFormControls = function () {



    var save_shop_valid = function () {
                $("#save_shop").validate({
            rules: {
                shop_name: {
                    required: true
                },
                     manager_id: {
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
                        var formData = new FormData($("#save_shop")[0]);
                save_shop(formData);
                return false;
            }
        });
    };
    var upd_shop_data_valid = function () {
        $("#upd_shop_data").validate({
            ignore: ":hidden",
            rules: {
                shop_name: {
                    required: true
                },
                     manager_id: {
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
                shop_id: {
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

    var upd_note_data_valid = function () {
        $("#upd_note_data").validate({
           // ignore: ":hidden",
            rules: {
                note_type_id: {
                    required: true
                },
                shop_id: {
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
                shop_id: {
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


    var upd_rentpay_data_valid = function () {
        $("#upd_rentpay_data").validate({
           // ignore: ":hidden",
            rules: {
                note_type_id: {
                    required: true
                },
                shop_id: {
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
            save_shop_valid();
            upd_shop_data_valid();
	            upd_status_data_valid();
                upd_file_data_valid();
                upd_note_data_valid();
                upd_remark_data_valid();
                upd_rentpay_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});
















function upd_rentpay(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_rentpay').data('url');
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
     $('.modal-title').html('ادارة دفعات الايجار');
    $('#view_prim_const_m').on('hidden.bs.modal', function () {
      //  view_all_shop();

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





    $("#upd_rentpay_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_rentpay_data")[0]);
    if ($('#upd_rentpay_data').valid()) {
    upd_rentpay_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_rentpay_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_rentpay_data").attr('action'),
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
        $("#errorBox_shop").show();
        $("#displayErrors_shop").html('');
        $("#errorBox_shop").removeClass("bg-success");
        $("#errorBox_shop").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_shop').append('<p>'+item+'</p');
        });
    $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
      //  $("#view_prim_const_m").modal('hide');
    //  $("#upd_rentpay_data")[0].reset();

    view_all_rentpay(resp.url);
        // view_all_shop();
        // $('#show_details').css('display', 'none');
        // $("#show_details").html('');


    $("#displayErrors_shop").html('');
    $("#errorBox_shop").removeClass("bg-danger");
    $("#errorBox_shop").addClass("bg-success");
    $("#displayErrors_shop").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }



    function view_all_rentpay(url) {
        var shop_id = $('#shop_id').val();

        $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
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
        var container = $('#result_rentpay_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }


        function change_rentpay(id) {
            $(document).ready(function () {
            //load_message();
            $('#show_details').css('display', '');


            var url = $('.change_rentpay').data('url');
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
                document.documentElement.scrollTop = 0;

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
    var shop_id = $('#shop_id').val();

    $.ajax({
    url: url,
    type: 'POST',
    async: false,
    data: {
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
    var container = $('#result_history_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }




function shop_note_history(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.shop_note_history').data('url');
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




function print_shop_xlsx(id) {
    $(document).ready(function () {
        if(id==''){
            var shop_name = $('#shop_name_v').val();
            var manager_id = $('#manager_id_v').val();
            var shop_respon = $('#shop_respon_v').val();
            var shop_mobile = $('#shop_mobile_v').val();
            var city_id = $('#city_id_v').val();
            var comme_no = $('#comme_no_v').val();
            var municip_no = $('#municip_no_v').val();
            var rentpay_price = $('#rentpay_price_v').val();

                        }

        var url = $('.print_shop_xlsx').data('url');
    $.ajax({
    url:url,
    'type': 'POST',
    dataType: 'json',
    async: false,

'data': {
    id:id,
    shop_name:shop_name,
    manager_id:manager_id,
    shop_respon:shop_respon,
    shop_mobile:shop_mobile,
    city_id:city_id,
    comme_no:comme_no,
    municip_no:municip_no,
    rentpay_price:rentpay_price,

  },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    'success': function(data) {
        var $a = $("<a>");
        $a.attr("href", data.file);
        $("body").append($a);
        $a.attr("download", "shop.xlsx");
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


function print_shop_pdf(id) {
$(document).ready(function () {
load_message();
if(id==''){
    var shop_name = $('#shop_name_v').val();
    var manager_id = $('#manager_id_v').val();
    var shop_respon = $('#shop_respon_v').val();
    var shop_mobile = $('#shop_mobile_v').val();
    var city_id = $('#city_id_v').val();
    var comme_no = $('#comme_no_v').val();
    var municip_no = $('#municip_no_v').val();
    var rentpay_price = $('#rentpay_price_v').val();

}

var url = $('.print_shop_pdf').data('url');
$.ajax({
url:url,
dataType: 'binary',
'type': 'POST',
'data': {
    id:id,
    shop_name:shop_name,
    manager_id:manager_id,
    shop_respon:shop_respon,
    shop_mobile:shop_mobile,
    city_id:city_id,
    comme_no:comme_no,
    municip_no:municip_no,
    rentpay_price:rentpay_price,

},
xhrFields: {
responseType: 'blob'
},

headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
success: function(result) {
var url = URL.createObjectURL(result);
var $a = $('<a />', {
'href': url,
'download': 'report_shop.pdf',
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























function upd_shop(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_shop').data('url');
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





    $("#upd_shop_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_shop_data")[0]);
    if ($('#upd_shop_data').valid()) {
    upd_shop_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_shop_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_shop_data").attr('action'),
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
        $("#errorBox_shop").show();
        $("#displayErrors_shop").html('');
        $("#errorBox_shop").removeClass("bg-success");
        $("#errorBox_shop").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_shop').append('<p>'+item+'</p');
        });
    $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    view_all_shop();
    $("#displayErrors_shop").html('');
    $("#errorBox_shop").removeClass("bg-danger");
    $("#errorBox_shop").addClass("bg-success");
    $("#displayErrors_shop").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }


    function upd_file(id) {
        $(document).ready(function () {
        //load_message();
        var url = $('.upd_file').data('url');
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
         $('.modal-title').html('ملف محل');
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





        $("#upd_file_data").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($("#upd_file_data")[0]);
        if ($('#upd_file_data').valid()) {
        upd_file_data_send(formData);
        } else {
        var alert = $('#kt_form_1_msg');
        alert.removeClass('kt--hide').show();
        $("#view_prim_const_m").scrollTop(0);
        //swal_alert();
        e.preventDefault();
        }
        });
        function upd_file_data_send(formData) {

        formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
        $.ajax({
        url: $("#upd_file_data").attr('action'),
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
            $("#errorBox_shop").show();
            $("#displayErrors_shop").html('');
            $("#errorBox_shop").removeClass("bg-success");
            $("#errorBox_shop").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_shop').append('<p>'+item+'</p');
            });
        $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');
        document.documentElement.scrollTop = 0;
        } else {
            $("#view_prim_const_m").modal('hide');
        view_all_shop();
        $("#displayErrors_shop").html('');
        $("#errorBox_shop").removeClass("bg-danger");
        $("#errorBox_shop").addClass("bg-success");
        $("#displayErrors_shop").html(resp.message_out);

        swal_sucess();
        }
        }
        });
        }







        function upd_note(id) {
            $(document).ready(function () {
            //load_message();
            var url = $('.upd_note').data('url');
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
             $('.modal-title').html('اضافة ملاحظة');
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





            $("#upd_note_data").submit(function (e) {
            e.preventDefault();
            var formData = new FormData($("#upd_note_data")[0]);
            if ($('#upd_note_data').valid()) {
            upd_note_data_send(formData);
            } else {
            var alert = $('#kt_form_1_msg');
            alert.removeClass('kt--hide').show();
            $("#view_prim_const_m").scrollTop(0);
            //swal_alert();
            e.preventDefault();
            }
            });
            function upd_note_data_send(formData) {

            formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
            $.ajax({
            url: $("#upd_note_data").attr('action'),
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
                $("#errorBox_shop").show();
                $("#displayErrors_shop").html('');
                $("#errorBox_shop").removeClass("bg-success");
                $("#errorBox_shop").addClass( "bg-danger" );
                $.each(resp.message, function (key, item)
                {
                $('#displayErrors_shop').append('<p>'+item+'</p');
                });
            $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');
            document.documentElement.scrollTop = 0;
            } else {
                $("#view_prim_const_m").modal('hide');
            view_all_shop();
            $("#displayErrors_shop").html('');
            $("#errorBox_shop").removeClass("bg-danger");
            $("#errorBox_shop").addClass("bg-success");
            $("#displayErrors_shop").html(resp.message_out);

            swal_sucess();
            }
            }
            });
            }






            function upd_remark(id) {
                $(document).ready(function () {
                //load_message();
                var url = $('.upd_remark').data('url');
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
                 $('.modal-title').html('ادارة الملاحظات');
                $('#view_prim_const_m').on('hidden.bs.modal', function () {
                  //  view_all_shop();

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





                $("#upd_remark_data").submit(function (e) {
                e.preventDefault();
                var formData = new FormData($("#upd_remark_data")[0]);
                if ($('#upd_remark_data').valid()) {
                upd_remark_data_send(formData);
                } else {
                var alert = $('#kt_form_1_msg');
                alert.removeClass('kt--hide').show();
                $("#view_prim_const_m").scrollTop(0);
                //swal_alert();
                e.preventDefault();
                }
                });
                function upd_remark_data_send(formData) {

                formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
                $.ajax({
                url: $("#upd_remark_data").attr('action'),
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
                    $("#errorBox_shop").show();
                    $("#displayErrors_shop").html('');
                    $("#errorBox_shop").removeClass("bg-success");
                    $("#errorBox_shop").addClass( "bg-danger" );
                    $.each(resp.message, function (key, item)
                    {
                    $('#displayErrors_shop').append('<p>'+item+'</p');
                    });
                $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');
                document.documentElement.scrollTop = 0;
                } else {
                  //  $("#view_prim_const_m").modal('hide');
                //  $("#upd_remark_data")[0].reset();

                    view_all_reamrk(resp.url);
                    view_all_shop();
                    $('#show_details').css('display', 'none');
                    $("#show_details").html('');


                $("#displayErrors_shop").html('');
                $("#errorBox_shop").removeClass("bg-danger");
                $("#errorBox_shop").addClass("bg-success");
                $("#displayErrors_shop").html(resp.message_out);

                swal_sucess();
                }
                }
                });
                }



                function view_all_reamrk(url) {
                    var shop_id = $('#shop_id').val();

                    $.ajax({
                    url: url,
                    type: 'POST',
                    async: false,
                    data: {
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
                    var container = $('#result_remark_tbl');
                    if (data) {
                    container.html(data);
                    //unload_message();
                    }
                    }
                    });
                    }


                    function change_remark(id) {
                        $(document).ready(function () {
                        //load_message();
                        $('#show_details').css('display', '');


                        var url = $('.change_remark').data('url');
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
                            document.documentElement.scrollTop = 0;

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










function view_all_shop() {
    var shop_name = $('#shop_name_v').val();
    var manager_id = $('#manager_id_v').val();
    var shop_respon = $('#shop_respon_v').val();
    var shop_mobile = $('#shop_mobile_v').val();
    var city_id = $('#city_id_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        shop_name:shop_name,
        manager_id:manager_id,
        shop_respon:shop_respon,
        shop_mobile:shop_mobile,
        city_id:city_id,
    },
    beforeSend: function () {
    //  load_message();
    },
    complete: function () {
    //  unload_message();
    },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_shop_tbl');
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

function save_shop(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_shop").attr('action'),
dataType: 'json',
type: 'POST',

async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_shop").show();
$("#displayErrors_shop").html('');
$("#errorBox_shop").removeClass("bg-success");
$("#errorBox_shop").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_shop').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_shop').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_shop").show();
$("#displayErrors_shop").html('');
$("#errorBox_shop").removeClass("bg-danger");
$("#errorBox_shop").addClass("bg-success");
$("#displayErrors_shop").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_shop")[0].reset();
unload_message();

$('#save_shop').children().find('input,select,file').each(function(){
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
