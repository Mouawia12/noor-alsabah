var wait_table_data=".. انتظر قليلا من فضلك";
var required_msg='هذا الحقل مطلوب';
var sucess_txt="تم ادخال البيانات بنجاح!";

var target = document.querySelector("#kt_content_container");
var blockUI = new KTBlockUI(target, {
message: '<div class="blockui-message"><span class="spinner-border text-primary"></span><h3 style="color: #fff;">'+wait_table_data+'</h3>   </div>',
css: {
border: 'none',
padding: '15px',
backgroundColor: '#000',
'-webkit-border-radius': '10px',
'-moz-border-radius': '10px',
opacity: .5,
color: '#fff !important',
'z-index': '10100'
},
});
function load_message() {
if (blockUI.isBlocked()) {
blockUI.release();
} else {
blockUI.block();
}
}
function unload_message() {
if (blockUI.isBlocked()) {
blockUI.release();
} else {
blockUI.block();
}

}



function swal_sucess() {
    $(document).ready(function() {
        swal.fire({
            "title": "",
            "text": sucess_txt,
            "icon": "success",
            "confirmButtonClass": "btn btn-secondary"
        });
    });
}
function swal_error() {
    $(document).ready(function() {
        swal.fire({
            "title": "",
            "text": error_txt,
            "icon": "error",
            "confirmButtonClass": "btn btn-secondary"
        });
    });
}

function swal_alert() {
    $(document).ready(function() {
        swal.fire({
            "title": "",
            "text":alert_txt,
            "icon": "error",
            "confirmButtonClass": "btn btn-secondary",
            "onClose": function(e) {
                console.log('on close event fired!');
            }
        });
    });
}
function error_push(title_meg='',text_meg='',type_msg='error') {
    $(document).ready(function() {
        swal.fire({
            "title": title_meg,
            "text":text_meg,
            "icon":type_msg,
            "confirmButtonClass": "btn btn-secondary",

        });
    });
}

function create_table(table_name) {
    $(table_name).DataTable({
        "ordering": true,
        "paging": true,
        bFilter: true,
        responsive: true,

        bInfo: false,
        "iDisplayLength": 25,
        "pageLength": 25,
        language: {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ سجلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ سجل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    });
}
function DisplayToastrMessage_General(messageType, inputsObj, timeOut) {
    var messageText = '';
    var timeOutErrorMessage = 10000;
    if (!(typeof inputsObj === 'object')) {
        messageText = inputsObj;
    } else {
        messageText = inputsObj.label;
    }
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    if (messageType === 'success' || messageType === 0) {
        toastr.success(inputsObj, timeOut);
        $('.has-error').removeClass('has-error');
    }
  else  if (messageType === 'Info' || messageType === 0) {
        toastr.Info(inputsObj, timeOut);
        $('.has-error').removeClass('has-error');
    }
    else  if (messageType === 'Warning' || messageType === 0) {
        toastr.Info(inputsObj, timeOut);
        $('.has-error').removeClass('has-error');
    }
    else if (messageType == 'error' || messageType == 1) {
        toastr.error(inputsObj, timeOut);
        $('.has-error').removeClass('has-error');
        if (typeof inputsObj.field_name != 'undefined') {
            if (inputsObj.field_name.length > 0) {
                for (var i = 0; i < inputsObj.field_name.length; i++) {
                    var inpt = $('[name=' + inputsObj.field_name[i] + ']');
                    if (inpt.hasClass('select2')) {
                        inpt.next('span.select2-container').addClass('has-error');
                    }
                    if (inpt.attr('type') == 'radio' || inpt.attr('type') == 'checkbox') {
                        inpt.parent().addClass('has-error');
                    } else {
                        inpt.addClass('has-error');
                    }
                }
            }
        }
    }
    document.documentElement.scrollTop = 0;
}


function typeOf(value) {
    var s = typeof value;
    if (s === 'object') {
        if (value) {
            if (value instanceof Array) {
                s = 'array';
            }
        } else {
            s = 'null';
        }
    }
    return s;
}
  $('.input_date_').flatpickr({
           format : 'dd-mm-yyyy',
                "locale": "ar",
        });
    function darkMode() {
        var checkBox = document.getElementById("DarkMode");
        if (checkBox.checked == true){
            $("#kt_aside").removeClass('aside-light');
            $("#kt_aside").addClass('aside-dark');
            $.ajax({
                type: 'get',
                url: '{{url('/')}}' + '/darkMode',
            });
        } else {
            $("#kt_aside").removeClass('aside-dark');
            $("#kt_aside").addClass('aside-light');
            $.ajax({
                type: 'get',
                url: '{{url('/')}}' + '/lightMode',
            });
        }
    }

 setTimeout(function() {
            $('.alert-session-flash').fadeOut('low');
        }, 5000);

        function notify_num(url) {
            $.ajax({
                url: url,
                type: 'POST',
            dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'success': function (resp) {
              $('#count_moraslat').html(resp.count_notify);
            }
        });
        }
        function load_alerts(url) {
                $.ajax({
                url: url,
                type: 'POST',
                async: false,
                beforeSend: function () {
                },
                complete: function () {
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                var container = $('#load_alerts');
                if (data) {
                container.html(data);
                }
                }
                });
                }



