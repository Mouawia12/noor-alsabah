
var KTFormControls = function () {



    var save_expense_valid = function () {
                $("#save_expense").validate({
                    ignore: ":hidden",

            rules: {
                expense_type_id: {
                    required: true
                },
                shop_id: {
                    required: true
                },
                worker_id: {
                    required: true
                },

                expense_categoty_id: {
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
                        var formData = new FormData($("#save_expense")[0]);
                save_expense(formData);
                return false;
            }
        });
    };
    var upd_expense_data_valid = function () {
        $("#upd_expense_data").validate({
            ignore: ":hidden",
            rules: {
                expense_no: {
                    required: true
                },
                     expense_dt: {
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
                expense_id: {
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
                expense_id: {
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
                expense_id: {
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
            save_expense_valid();
            upd_expense_data_valid();
	            upd_status_data_valid();
                upd_file_data_valid();
                upd_note_data_valid();
                upd_remark_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});









function load_expense_form(expense_type_id,desc) {
    var url = $('.expense_type_id').data('url');

    var expense_type_id = $('#expense_type_id').val();
    var expense_id = $('#expense_id_db').val();

    $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            expense_type_id:expense_type_id,
            desc:desc,
            expense_id:expense_id,

        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#load_expense_form');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });

}








function print_expense_xlsx(id) {
    $(document).ready(function () {
        if(id==''){
var expense_type_id = $('#expense_type_id_v').val();
var expense_categoty_id = $('#expense_categoty_id_v').val();
var expense_dt_from = $('#expense_dt_from').val();
var expense_dt_to = $('#expense_dt_to').val();
var manager_id = $('#manager_id_v').val();
var worker_id = $('#worker_id_v').val();
var shop_id = $('#shop_id_v').val();
var expense_month_desc = $('#expense_month_desc_v').val();
var type = $('#type_v').val();
var det_calculate_month_remain = $('#det_calculate_month_remain_v').val();


                        }

        var url = $('.print_expense_xlsx').data('url');
    $.ajax({
    url:url,
    'type': 'POST',
    dataType: 'json',
    async: false,

'data': {
    id:id,
    expense_type_id:expense_type_id,
    expense_categoty_id:expense_categoty_id,
    expense_dt_from:expense_dt_from,
    expense_dt_to:expense_dt_to,
    manager_id:manager_id,
    worker_id:worker_id,
    shop_id:shop_id,
    expense_month_desc:expense_month_desc,
    type:type,
    det_calculate_month_remain:det_calculate_month_remain,

  },
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    'success': function(data) {
        var $a = $("<a>");
        $a.attr("href", data.file);
        $("body").append($a);
        $a.attr("download", "expense.xlsx");
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


function print_expense_pdf(id) {
$(document).ready(function () {
load_message();
if(id==''){

    var expense_type_id = $('#expense_type_id_v').val();
    var expense_categoty_id = $('#expense_categoty_id_v').val();
    var expense_dt_from = $('#expense_dt_from').val();
    var expense_dt_to = $('#expense_dt_to').val();
    var manager_id = $('#manager_id_v').val();
    var worker_id = $('#worker_id_v').val();
    var shop_id = $('#shop_id_v').val();
    var expense_month_desc = $('#expense_month_desc_v').val();
    var type = $('#type_v').val();
    var det_calculate_month_remain = $('#det_calculate_month_remain_v').val();

        }

var url = $('.print_expense_pdf').data('url');
$.ajax({
url:url,
dataType: 'binary',
'type': 'POST',
'data': {
    id:id,
    expense_type_id:expense_type_id,
    expense_categoty_id:expense_categoty_id,
    expense_dt_from:expense_dt_from,
    expense_dt_to:expense_dt_to,
    manager_id:manager_id,
    worker_id:worker_id,
    shop_id:shop_id,
    expense_month_desc:expense_month_desc,
    type:type,
    det_calculate_month_remain:det_calculate_month_remain,

},
xhrFields: {
responseType: 'blob'
},

headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
success: function(result) {
var url = URL.createObjectURL(result);
var $a = $('<a />', {
'href': url,
'download': 'report_expense.pdf',
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


function upd_expense(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.upd_expense').data('url');
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





    $("#upd_expense_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_expense_data")[0]);
    if ($('#upd_expense_data').valid()) {
    upd_expense_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_expense_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_expense_data").attr('action'),
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
        $("#errorBox_expense").show();
        $("#displayErrors_expense").html('');
        $("#errorBox_expense").removeClass("bg-success");
        $("#errorBox_expense").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_expense').append('<p>'+item+'</p');
        });
    $('#displayErrors_expense').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    view_all_expense();
    $("#displayErrors_expense").html('');
    $("#errorBox_expense").removeClass("bg-danger");
    $("#errorBox_expense").addClass("bg-success");
    $("#displayErrors_expense").html(resp.message_out);

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
            $("#errorBox_expense").show();
            $("#displayErrors_expense").html('');
            $("#errorBox_expense").removeClass("bg-success");
            $("#errorBox_expense").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_expense').append('<p>'+item+'</p');
            });
        $('#displayErrors_expense').append('<p>'+resp.message_out+'</p');
        document.documentElement.scrollTop = 0;
        } else {
            $("#view_prim_const_m").modal('hide');
        view_all_expense();
        $("#displayErrors_expense").html('');
        $("#errorBox_expense").removeClass("bg-danger");
        $("#errorBox_expense").addClass("bg-success");
        $("#displayErrors_expense").html(resp.message_out);

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
                $("#errorBox_expense").show();
                $("#displayErrors_expense").html('');
                $("#errorBox_expense").removeClass("bg-success");
                $("#errorBox_expense").addClass( "bg-danger" );
                $.each(resp.message, function (key, item)
                {
                $('#displayErrors_expense').append('<p>'+item+'</p');
                });
            $('#displayErrors_expense').append('<p>'+resp.message_out+'</p');
            document.documentElement.scrollTop = 0;
            } else {
                $("#view_prim_const_m").modal('hide');
            view_all_expense();
            $("#displayErrors_expense").html('');
            $("#errorBox_expense").removeClass("bg-danger");
            $("#errorBox_expense").addClass("bg-success");
            $("#displayErrors_expense").html(resp.message_out);

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
                    $("#errorBox_expense").show();
                    $("#displayErrors_expense").html('');
                    $("#errorBox_expense").removeClass("bg-success");
                    $("#errorBox_expense").addClass( "bg-danger" );
                    $.each(resp.message, function (key, item)
                    {
                    $('#displayErrors_expense').append('<p>'+item+'</p');
                    });
                $('#displayErrors_expense').append('<p>'+resp.message_out+'</p');
                document.documentElement.scrollTop = 0;
                } else {
                  //  $("#view_prim_const_m").modal('hide');
                //  $("#upd_remark_data")[0].reset();

                    view_all_reamrk(resp.url);
                    $('#show_details').css('display', 'none');
                    $("#show_details").html('');


                $("#displayErrors_expense").html('');
                $("#errorBox_expense").removeClass("bg-danger");
                $("#errorBox_expense").addClass("bg-success");
                $("#displayErrors_expense").html(resp.message_out);

                swal_sucess();
                }
                }
                });
                }



                function view_all_reamrk(url) {
                    var expense_id = $('#expense_id').val();

                    $.ajax({
                    url: url,
                    type: 'POST',
                    async: false,
                    data: {
                        expense_id:expense_id,
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










function view_all_expense() {
    var expense_no = $('#expense_no_v').val();
    var expense_dt_from = $('#expense_dt_from').val();
    var expense_dt_to = $('#expense_dt_to').val();
    var expense_respon = $('#expense_respon_v').val();
    var manager_id = $('#manager_id_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        expense_no:expense_no,
        expense_dt_from:expense_dt_from,
        expense_dt_to:expense_dt_to,
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
    var container = $('#result_expense_tbl');
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

function save_expense(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_expense").attr('action'),
dataType: 'json',
type: 'POST',
async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_expense").show();
$("#displayErrors_expense").html('');
$("#errorBox_expense").removeClass("bg-success");
$("#errorBox_expense").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_expense').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_expense').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
unload_message();
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_expense").show();
$("#displayErrors_expense").html('');
$("#errorBox_expense").removeClass("bg-danger");
$("#errorBox_expense").addClass("bg-success");
$("#displayErrors_expense").html(resp.message_out);
document.documentElement.scrollTop = 0;
$("#save_expense")[0].reset();
unload_message();

$('#save_expense').children().find('input,select,file').each(function(){
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
        view_all_expense();
    $("#displayErrors_statement").html('');
    $("#errorBox_statement").removeClass("bg-danger");
    $("#errorBox_statement").addClass("bg-success");
    $("#displayErrors_statement").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }






    function view_all_expense_detail(url) {
        var expense_id = $('#expense_id_db').val();

        $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
            expense_id:expense_id,
        },
        beforeSend: function () {
        //  load_message();
        },
        complete: function () {
        //  unload_message();
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#result_expense_detail_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }









