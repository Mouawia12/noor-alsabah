
var KTFormControls = function () {
    var save_city_valid = function () {
    $("#save_city").validate({
        ignore: ":hidden",

rules: {
    city_name: {
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
            var formData = new FormData($("#save_city")[0]);
    save_city(formData);
    return false;
}
});
};
var upd_city_data_valid = function () {
$("#upd_city_data").validate({
ignore: ":hidden",
rules: {
    city_name_u: {
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


    var save_workplace_valid = function () {
                $("#save_workplace").validate({
                    ignore: ":hidden",

            rules: {
                work_place_name: {
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
                        var formData = new FormData($("#save_workplace")[0]);
                save_workplace(formData);
                return false;
            }
        });
    };
    var upd_workplace_data_valid = function () {
        $("#upd_workplace_data").validate({
            ignore: ":hidden",
            rules: {
                work_place_name_u: {
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

    var save_expensecategoty_valid = function () {
        $("#save_expensecategoty").validate({
            ignore: ":hidden",

    rules: {
        expense_categoty_name: {
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
                var formData = new FormData($("#save_expensecategoty")[0]);
        save_expensecategoty(formData);
        return false;
    }
});
};
var upd_expensecategoty_data_valid = function () {
$("#upd_expensecategoty_data").validate({
    ignore: ":hidden",
    rules: {
        expense_categoty_name_u: {
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


    var save_job_valid = function () {
        $("#save_job").validate({
            ignore: ":hidden",

    rules: {
        work_place_name: {
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
                var formData = new FormData($("#save_job")[0]);
        save_job(formData);
        return false;
    }
});
};
var upd_job_data_valid = function () {
$("#upd_job_data").validate({
    ignore: ":hidden",
    rules: {
        work_place_name_u: {
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

var save_violation_valid = function () {
    $("#save_violation").validate({
        ignore: ":hidden",

rules: {
    violation_side_name: {
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
    violation_side_name_u: {
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
            save_workplace_valid();
            upd_workplace_data_valid();
            save_job_valid();
            upd_job_data_valid();
            save_expensecategoty_valid();
            upd_expensecategoty_data_valid();
            save_violation_valid();
            upd_violation_data_valid();
                        save_city_valid();
            upd_city_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});





            function save_city(formData) {
                formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
                $.ajax({
                url: $("#save_city").attr('action'),
                dataType: 'json',
                type: 'POST',
                async: false,
                data: formData,
                contentType: false,
                processData: false,
                success: function (resp) {
                if (resp.status === false) {
                $("#errorBox_city").show();
                $("#displayErrors_city").html('');
                $("#errorBox_city").removeClass("bg-success");
                $("#errorBox_city").addClass( "bg-danger" );
                $.each(resp.message, function (key, item)
                {
                $('#displayErrors_city').append('<p>'+item+'</p');
                });
                if(resp.message_out!=''){
                    $('#displayErrors_city').append('<p>'+resp.message_out+'</p');

                }
                document.documentElement.scrollTop = 0;
                } else {
                //DisplayToastrMessage_General("success", resp.message, 3000);
                $("#errorBox_city").show();
                $("#displayErrors_city").html('');
                $("#errorBox_city").removeClass("bg-danger");
                $("#errorBox_city").addClass("bg-success");
                $("#displayErrors_city").html(resp.message_out);
                document.documentElement.scrollTop = 0;
                view_all_city(resp.url);

                $("#save_city")[0].reset();

                $('#save_city').children().find('input,select,file').each(function(){
                $(this).val('').trigger('change');
                });
                }
                }
                });
                }
                function view_all_city(url) {
                    $.ajax({
                    url: url,
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
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                    var container = $('#result_city_tbl');
                    if (data) {
                    container.html(data);
                    //unload_message();
                    }
                    }
                    });
                    }



                    function upd_city(id) {
                        $(document).ready(function () {
                        //load_message();
                        var url = $('.upd_city').data('url');
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





                        $("#upd_city_data").submit(function (e) {
                        e.preventDefault();
                        var formData = new FormData($("#upd_city_data")[0]);
                        if ($('#upd_city_data').valid()) {
                        upd_city_data_send(formData);
                        } else {
                        var alert = $('#kt_form_1_msg');
                        alert.removeClass('kt--hide').show();
                        $("#view_prim_const_m").scrollTop(0);
                        //swal_alert();
                        e.preventDefault();
                        }
                        });
                        function upd_city_data_send(formData) {

                        formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
                        $.ajax({
                        url: $("#upd_city_data").attr('action'),
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
                            $("#errorBox_city_upd").show();
                            $("#displayErrors_city_upd").html('');
                            $("#errorBox_city_upd").removeClass("bg-success");
                            $("#errorBox_city_upd").addClass( "bg-danger" );
                            $.each(resp.message, function (key, item)
                            {
                            $('#displayErrors_city_upd').append('<p>'+item+'</p');
                            });
                        $('#displayErrors_city_upd').append('<p>'+resp.message_out+'</p');
                        document.documentElement.scrollTop = 0;
                        } else {
                            $("#view_prim_const_m").modal('hide');
                        view_all_city(resp.url);
                        $("#displayErrors_city_upd").html('');
                        $("#errorBox_city_upd").removeClass("bg-danger");
                        $("#errorBox_city_upd").addClass("bg-success");
                        $("#displayErrors_city_upd").html(resp.message_out);

                        swal_sucess();
                        }
                        }
                        });
                        }










function save_violation(formData) {
    load_message();
    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#save_violation").attr('action'),
    dataType: 'json',
    type: 'POST',
  //  async: false,
    data: formData,
    contentType: false,
    processData: false,
    success: function (resp) {
        unload_message();
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
    } else {
     //   unload_message();
    $("#errorBox_violation").show();
    $("#displayErrors_violation").html('');
    $("#errorBox_violation").removeClass("bg-danger");
    $("#errorBox_violation").addClass("bg-success");
    $("#displayErrors_violation").html(resp.message_out);
    document.documentElement.scrollTop = 0;
    view_all_violation(resp.url);
    $("#save_violation")[0].reset();
    $('#save_violation').children().find('input,select,file').each(function(){
    $(this).val('').trigger('change');
    });
    }
    }
    });
    }



    function view_all_violation(url) {
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
        var container = $('#result_violation_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
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
           load_message();
            },
            complete: function () {
       //    unload_message();
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
           // async: false,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
            load_message();
            },
            complete: function () {
            //unload_message();
            },
            success: function (resp) {
            if (resp.status == false) {
                $("#errorBox_violation_upd").show();
                $("#displayErrors_violation_upd").html('');
                $("#errorBox_violation_upd").removeClass("bg-success");
                $("#errorBox_violation_upd").addClass( "bg-danger" );
                $.each(resp.message, function (key, item)
                {
                $('#displayErrors_violation_upd').append('<p>'+item+'</p');
                });
            $('#displayErrors_violation_upd').append('<p>'+resp.message_out+'</p');
            document.documentElement.scrollTop = 0;
            unload_message();
            } else {
                $("#view_prim_const_m").modal('hide');
            view_all_violation(resp.url);
            $("#displayErrors_violation_upd").html('');
            $("#errorBox_violation_upd").removeClass("bg-danger");
            $("#errorBox_violation_upd").addClass("bg-success");
            $("#displayErrors_violation_upd").html(resp.message_out);
            unload_message();
            swal_sucess();
            }
            }
            });
            }
function save_job(formData) {
    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#save_job").attr('action'),
    dataType: 'json',
    type: 'POST',
    async: false,
    data: formData,
    contentType: false,
    processData: false,
    success: function (resp) {
    if (resp.status === false) {
    $("#errorBox_job").show();
    $("#displayErrors_job").html('');
    $("#errorBox_job").removeClass("bg-success");
    $("#errorBox_job").addClass( "bg-danger" );
    $.each(resp.message, function (key, item)
    {
    $('#displayErrors_job').append('<p>'+item+'</p');
    });
    if(resp.message_out!=''){
        $('#displayErrors_job').append('<p>'+resp.message_out+'</p');

    }
    document.documentElement.scrollTop = 0;
    } else {
    //DisplayToastrMessage_General("success", resp.message, 3000);
    $("#errorBox_job").show();
    $("#displayErrors_job").html('');
    $("#errorBox_job").removeClass("bg-danger");
    $("#errorBox_job").addClass("bg-success");
    $("#displayErrors_job").html(resp.message_out);
    document.documentElement.scrollTop = 0;
    view_all_job(resp.url);

    $("#save_job")[0].reset();

    $('#save_job').children().find('input,select,file').each(function(){
    $(this).val('').trigger('change');
    });
    }
    }
    });
    }
    function view_all_job(url) {
        $.ajax({
        url: url,
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
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        var container = $('#result_job_tbl');
        if (data) {
        container.html(data);
        //unload_message();
        }
        }
        });
        }



        function upd_job(id) {
            $(document).ready(function () {
            //load_message();
            var url = $('.upd_job').data('url');
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





            $("#upd_job_data").submit(function (e) {
            e.preventDefault();
            var formData = new FormData($("#upd_job_data")[0]);
            if ($('#upd_job_data').valid()) {
            upd_job_data_send(formData);
            } else {
            var alert = $('#kt_form_1_msg');
            alert.removeClass('kt--hide').show();
            $("#view_prim_const_m").scrollTop(0);
            //swal_alert();
            e.preventDefault();
            }
            });
            function upd_job_data_send(formData) {

            formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
            $.ajax({
            url: $("#upd_job_data").attr('action'),
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
                $("#errorBox_job_upd").show();
                $("#displayErrors_job_upd").html('');
                $("#errorBox_job_upd").removeClass("bg-success");
                $("#errorBox_job_upd").addClass( "bg-danger" );
                $.each(resp.message, function (key, item)
                {
                $('#displayErrors_job_upd').append('<p>'+item+'</p');
                });
            $('#displayErrors_job_upd').append('<p>'+resp.message_out+'</p');
            document.documentElement.scrollTop = 0;
            } else {
                $("#view_prim_const_m").modal('hide');
            view_all_job(resp.url);
            $("#displayErrors_job_upd").html('');
            $("#errorBox_job_upd").removeClass("bg-danger");
            $("#errorBox_job_upd").addClass("bg-success");
            $("#displayErrors_job_upd").html(resp.message_out);

            swal_sucess();
            }
            }
            });
            }




function save_workplace(formData) {

formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
$.ajax({
url: $("#save_workplace").attr('action'),
dataType: 'json',
type: 'POST',
async: false,
data: formData,
contentType: false,
processData: false,
success: function (resp) {
if (resp.status === false) {
$("#errorBox_workplace").show();
$("#displayErrors_workplace").html('');
$("#errorBox_workplace").removeClass("bg-success");
$("#errorBox_workplace").addClass( "bg-danger" );
$.each(resp.message, function (key, item)
{
$('#displayErrors_workplace').append('<p>'+item+'</p');
});
if(resp.message_out!=''){
    $('#displayErrors_workplace').append('<p>'+resp.message_out+'</p');

}
document.documentElement.scrollTop = 0;
} else {
//DisplayToastrMessage_General("success", resp.message, 3000);
$("#errorBox_workplace").show();
$("#displayErrors_workplace").html('');
$("#errorBox_workplace").removeClass("bg-danger");
$("#errorBox_workplace").addClass("bg-success");
$("#displayErrors_workplace").html(resp.message_out);
document.documentElement.scrollTop = 0;
view_all_workplace(resp.url);

$("#save_workplace")[0].reset();

$('#save_workplace').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}



function view_all_workplace(url) {
    $.ajax({
    url: url,
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
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    success: function (data) {
    var container = $('#result_workplace_tbl');
    if (data) {
    container.html(data);
    //unload_message();
    }
    }
    });
    }



    function upd_workplace(id) {
        $(document).ready(function () {
        //load_message();
        var url = $('.upd_workplace').data('url');
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





        $("#upd_workplace_data").submit(function (e) {
        e.preventDefault();
        var formData = new FormData($("#upd_workplace_data")[0]);
        if ($('#upd_workplace_data').valid()) {
        upd_workplace_data_send(formData);
        } else {
        var alert = $('#kt_form_1_msg');
        alert.removeClass('kt--hide').show();
        $("#view_prim_const_m").scrollTop(0);
        //swal_alert();
        e.preventDefault();
        }
        });
        function upd_workplace_data_send(formData) {

        formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
        $.ajax({
        url: $("#upd_workplace_data").attr('action'),
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
            $("#errorBox_workplace_upd").show();
            $("#displayErrors_workplace_upd").html('');
            $("#errorBox_workplace_upd").removeClass("bg-success");
            $("#errorBox_workplace_upd").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_workplace_upd').append('<p>'+item+'</p');
            });
        $('#displayErrors_workplace_upd').append('<p>'+resp.message_out+'</p');
        document.documentElement.scrollTop = 0;
        } else {
            $("#view_prim_const_m").modal('hide');
        view_all_workplace(resp.url);
        $("#displayErrors_workplace_upd").html('');
        $("#errorBox_workplace_upd").removeClass("bg-danger");
        $("#errorBox_workplace_upd").addClass("bg-success");
        $("#displayErrors_workplace_upd").html(resp.message_out);

        swal_sucess();
        }
        }
        });
        }

        function save_expensecategoty(formData) {

            formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
            $.ajax({
            url: $("#save_expensecategoty").attr('action'),
            dataType: 'json',
            type: 'POST',
            async: false,
            data: formData,
            contentType: false,
            processData: false,
            success: function (resp) {
            if (resp.status === false) {
            $("#errorBox_expensecategoty").show();
            $("#displayErrors_expensecategoty").html('');
            $("#errorBox_expensecategoty").removeClass("bg-success");
            $("#errorBox_expensecategoty").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_expensecategoty').append('<p>'+item+'</p');
            });
            if(resp.message_out!=''){
                $('#displayErrors_expensecategoty').append('<p>'+resp.message_out+'</p');

            }
            document.documentElement.scrollTop = 0;
            } else {
            //DisplayToastrMessage_General("success", resp.message, 3000);
            $("#errorBox_expensecategoty").show();
            $("#displayErrors_expensecategoty").html('');
            $("#errorBox_expensecategoty").removeClass("bg-danger");
            $("#errorBox_expensecategoty").addClass("bg-success");
            $("#displayErrors_expensecategoty").html(resp.message_out);
            document.documentElement.scrollTop = 0;
            view_all_expensecategoty(resp.url);

            $("#save_expensecategoty")[0].reset();

            $('#save_expensecategoty').children().find('input,select,file').each(function(){
            $(this).val('').trigger('change');
            });

               //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

            }
            }
            });
            }



            function view_all_expensecategoty(url) {
                $.ajax({
                url: url,
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
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                var container = $('#result_expensecategoty_tbl');
                if (data) {
                container.html(data);
                //unload_message();
                }
                }
                });
                }



                function upd_expensecategoty(id) {
                    $(document).ready(function () {
                    //load_message();
                    var url = $('.upd_expensecategoty').data('url');
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





                    $("#upd_expensecategoty_data").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData($("#upd_expensecategoty_data")[0]);
                    if ($('#upd_expensecategoty_data').valid()) {
                    upd_expensecategoty_data_send(formData);
                    } else {
                    var alert = $('#kt_form_1_msg');
                    alert.removeClass('kt--hide').show();
                    $("#view_prim_const_m").scrollTop(0);
                    //swal_alert();
                    e.preventDefault();
                    }
                    });
                    function upd_expensecategoty_data_send(formData) {

                    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
                    $.ajax({
                    url: $("#upd_expensecategoty_data").attr('action'),
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
                        $("#errorBox_expensecategoty_upd").show();
                        $("#displayErrors_expensecategoty_upd").html('');
                        $("#errorBox_expensecategoty_upd").removeClass("bg-success");
                        $("#errorBox_expensecategoty_upd").addClass( "bg-danger" );
                        $.each(resp.message, function (key, item)
                        {
                        $('#displayErrors_expensecategoty_upd').append('<p>'+item+'</p');
                        });
                    $('#displayErrors_expensecategoty_upd').append('<p>'+resp.message_out+'</p');
                    document.documentElement.scrollTop = 0;
                    } else {
                        $("#view_prim_const_m").modal('hide');
                    view_all_expensecategoty(resp.url);
                    $("#displayErrors_expensecategoty_upd").html('');
                    $("#errorBox_expensecategoty_upd").removeClass("bg-danger");
                    $("#errorBox_expensecategoty_upd").addClass("bg-success");
                    $("#displayErrors_expensecategoty_upd").html(resp.message_out);

                    swal_sucess();
                    }
                    }
                    });
                    }

