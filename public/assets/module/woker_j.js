function showLargeImage(src) {
    // إنشاء عنصر صورة جديد
    const img = document.createElement('img');
    img.src = src;
    img.style.width = '50%'; // يمكنك تعديل العرض حسب الحاجة
    img.style.height = 'auto'; // الحفاظ على نسبة العرض إلى الارتفاع

    // إنشاء عنصر نافذة منبثقة
    const modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '50%';
    modal.style.height = '50%';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '1000';

    // إضافة الصورة إلى النافذة المنبثقة
    modal.appendChild(img);

    // إضافة حدث لإغلاق النافذة عند النقر
    modal.onclick = function() {
        document.body.removeChild(modal);
    };

    // إضافة النافذة المنبثقة إلى الجسم
    document.body.appendChild(modal);
}

var KTFormControls = function () {
    var save_workers_valid = function () {
                $("#save_workers").validate({
            rules: {
                worker_name: {
                    required: true
                },
                     ssn: {
                    required: true
                },
         /*  work_place_id: {
                    required: true
                },

                 doe: {
                    required: true
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
                        var formData = new FormData($("#save_workers")[0]);
                save_workers(formData);
                return false;
            }
        });
    };
    var upd_workers_data_valid = function () {
        $("#upd_workers_data").validate({
            ignore: ":hidden",
            rules: {
                worker_name: {
                    required: true
                },
                     ssn: {
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













    var save_imp_valid = function () {
        $("#save_imp").validate({
    rules: {
        uploadFile: {
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
                var formData = new FormData($("#save_imp")[0]);
                save_imp(formData);
        return false;
    }
});
};
















    var cancal_workers_data_valid = function () {
        $("#cancal_workers_data").validate({
            ignore: ":hidden",
            rules: {
                worker_name: {
                    required: true
                },
                     ssn: {
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




    return {
        init: function () {
            save_workers_valid();
            save_imp_valid();
            upd_workers_data_valid();
            cancal_workers_data_valid();

	            upd_status_data_valid();
                upd_note_data_valid();
                upd_remark_data_valid();

        }
    };
}();
jQuery(document).ready(function () {
    KTFormControls.init();
});


function worker_note_history(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.worker_note_history').data('url');
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
        var worker_id = $('#worker_id').val();

        $.ajax({
        url: url,
        type: 'POST',
        async: false,
        data: {
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
        var container = $('#result_history_tbl');
        if (data) {
        container.html(data);
        //unload_message();
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
        $("#errorBox_worker").show();
        $("#displayErrors_worker").html('');
        $("#errorBox_worker").removeClass("bg-success");
        $("#errorBox_worker").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_worker').append('<p>'+item+'</p');
        });
    $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    view_all_worker();
    $("#displayErrors_worker").html('');
    $("#errorBox_worker").removeClass("bg-danger");
    $("#errorBox_worker").addClass("bg-success");
    $("#displayErrors_worker").html(resp.message_out);

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
            view_all_worker();
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
            $("#errorBox_worker").show();
            $("#displayErrors_worker").html('');
            $("#errorBox_worker").removeClass("bg-success");
            $("#errorBox_worker").addClass( "bg-danger" );
            $.each(resp.message, function (key, item)
            {
            $('#displayErrors_worker').append('<p>'+item+'</p');
            });
        $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');
        document.documentElement.scrollTop = 0;
        } else {
          //  $("#view_prim_const_m").modal('hide');
        //  $("#upd_remark_data")[0].reset();

            view_all_reamrk(resp.url);
            $('#show_details').css('display', 'none');
            $("#show_details").html('');


        $("#displayErrors_worker").html('');
        $("#errorBox_worker").removeClass("bg-danger");
        $("#errorBox_worker").addClass("bg-success");
        $("#displayErrors_worker").html(resp.message_out);

        swal_sucess();
        }
        }
        });
        }



        function view_all_reamrk(url) {
            var worker_id = $('#worker_id').val();

            $.ajax({
            url: url,
            type: 'POST',
            async: false,
            data: {
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




                function print_worker_xlsx(id) {
                    $(document).ready(function () {
                        if(id==''){
                            var worker_name = $('#worker_name_v').val();
                            var ssn = $('#ssn_v').val();
                            var work_place_id = $('#work_place_id_v').val();
                            var doe = $('#doe_v').val();
                            var updatedcancal_at = $('#updatedcancal_at_v').val();
                            var job_id = $('#job_id_v').val();
                            var end_dt = $('#end_dt_v').val();
                            var end_p_dt = $('#end_p_dt_v').val();
                            var manager_id = $('#manager_id_v').val();
                            var inside = $('#inside_v').val();
                            var is_imp = $('#is_imp_v').val();
                            var nation = $('#nation_v').val();

                            
    var order_date = $('#order_date').val();
    var residence_month = $('#residence_month').val();
    var residence_year = $('#residence_year').val();
    var passport_month = $('#passport_month').val();
    var passport_year = $('#passport_year').val();

                                }

                        var url = $('.print_worker_xlsx').data('url');
                    $.ajax({
                    url:url,
                    'type': 'POST',
                    dataType: 'json',
                    async: false,

                'data': {
                    id:id,
                    worker_name :worker_name,
                    ssn :ssn,
                    work_place_id:work_place_id,
                    doe: doe,
                     updatedcancal_at : updatedcancal_at,
                     job_id: job_id,
                     end_dt: end_dt,
                     end_p_dt : end_p_dt,
                     manager_id : manager_id,
                     inside : inside,
                     is_imp : is_imp,
                     nation : nation,
                     order_date : order_date,
                     residence_month : residence_month,
                     residence_year : residence_year,
                     passport_month : passport_month,
                     passport_year : passport_year
                      },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    'success': function(data) {
                        var $a = $("<a>");
                        $a.attr("href", data.file);
                        $("body").append($a);
                        $a.attr("download", "worker.xlsx");
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


function print_worker_pdf(id) {
    $(document).ready(function () {
   load_message();
  if(id==''){
  var worker_name = $('#worker_name_v').val();
  var ssn = $('#ssn_v').val();
  var work_place_id = $('#work_place_id_v').val();
  var doe = $('#doe_v').val();
  var updatedcancal_at = $('#updatedcancal_at_v').val();
  var job_id = $('#job_id_v').val();
  var end_dt = $('#end_dt_v').val();
  var end_p_dt = $('#end_p_dt_v').val();

  var manager_id = $('#manager_id_v').val();
  var inside = $('#inside_v').val();
  var is_imp = $('#is_imp_v').val();
  var nation = $('#nation_v').val();

  }

    var url = $('.print_worker_pdf').data('url');
    $.ajax({
    url:url,
    dataType: 'binary',
    'type': 'POST',
'data': {
    id: id,
    worker_name :worker_name,
    ssn :ssn,
    work_place_id:work_place_id,
    doe: doe,
     updatedcancal_at : updatedcancal_at,
     job_id: job_id,
     end_dt: end_dt,
     end_p_dt : end_p_dt,
     manager_id : manager_id,
     inside : inside,
     is_imp : is_imp,
     nation : nation,

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






function cancal_workers(id) {
    $(document).ready(function () {
    //load_message();
    var url = $('.cancal_workers').data('url');
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
     $('.modal-title').html('انهاء عامل');
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





    $("#cancal_workers_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#cancal_workers_data")[0]);
    if ($('#cancal_workers_data').valid()) {
    cancal_workers_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function cancal_workers_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#cancal_workers_data").attr('action'),
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
        $("#errorBox_worker").show();
        $("#displayErrors_worker").html('');
        $("#errorBox_worker").removeClass("bg-success");
        $("#errorBox_worker").addClass( "bg-danger" );
        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_worker').append('<p>'+item+'</p');
        });
    $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');
    document.documentElement.scrollTop = 0;
    } else {
        $("#view_prim_const_m").modal('hide');
    view_all_worker();
    $("#displayErrors_worker").html('');
    $("#errorBox_worker").removeClass("bg-danger");
    $("#errorBox_worker").addClass("bg-success");
    $("#displayErrors_worker").html(resp.message_out);

    swal_sucess();
    }
    }
    });
    }



function upd_workers(id) {
    $(document).ready(function () {
    load_message();
    var url = $('.upd_workers').data('url');
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





    $("#upd_workers_data").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upd_workers_data")[0]);
    if ($('#upd_workers_data').valid()) {
    upd_workers_data_send(formData);
    } else {
    var alert = $('#kt_form_1_msg');
    alert.removeClass('kt--hide').show();
    $("#view_prim_const_m").scrollTop(0);
    //swal_alert();
    e.preventDefault();
    }
    });
    function upd_workers_data_send(formData) {

    formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
    $.ajax({
    url: $("#upd_workers_data").attr('action'),
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
  //  unload_message();

    },
    success: function (resp) {
    if (resp.status == false) {
        unload_message();
        document.documentElement.scrollTop = 0;
        $("#errorBox_worker").show();
        $("#displayErrors_worker").html('');
        $("#errorBox_worker").removeClass("bg-success");
        $("#errorBox_worker").addClass( "bg-danger" );

        $.each(resp.message, function (key, item)
        {
        $('#displayErrors_worker').append('<p>'+item+'</p');
        });
    $('#displayErrors_worker').append('<p>'+resp.message_out+'</p');
   // inner.offsetTop - document.body.scrollTop;

   //
    } else {

        $("#view_prim_const_m").modal('hide');
    view_all_worker();
    $("#displayErrors_worker").html('');
    $("#errorBox_worker").removeClass("bg-danger");
    $("#errorBox_worker").addClass("bg-success");
    $("#displayErrors_worker").html(resp.message_out);
        unload_message();

    swal_sucess();
    }
    }
    });
    }




function view_all_worker() {
    var worker_name = $('#worker_name_v').val();
    var ssn = $('#ssn_v').val();
    var work_place_id = $('#work_place_id_v').val();
    var doe = $('#doe_v').val();
    $.ajax({
    url: $("#boew_project").attr('action'),
    type: 'POST',
    async: false,
    data: {
        worker_name:worker_name,
        ssn:ssn,
        work_place_id:work_place_id,
        doe:doe,
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
    var container = $('#result_worker_tbl');
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


function save_imp(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
$.ajax({
url: $("#save_imp").attr('action'),
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
$("#save_imp")[0].reset();
unload_message();

$('#save_imp').children().find('input,select,file').each(function(){
$(this).val('').trigger('change');
});

swal_sucess();
   //    DisplayToastrMessage_General("success", 'تمت العملية بنجاح',  '');

}
}
});
}

function save_workers(formData) {
	load_message();
formData.append('X-CSRF-TOKEN', '{{ csrf_token }}');
 /* var xxxxx= $('input[name=PROJECT_DESCRIPTION_IN]').val();
  alert(xxxxx);*/
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
$("#save_workers")[0].reset();
unload_message();

$('#save_workers').children().find('input,select,file').each(function(){
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
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////
