function getContacts(contact_type) {
    $.ajax({
        url: '/getContacts/' + contact_type,
        type: "GET",
        data: {
            type: contact_type
        },
        success: function (data) {
            if(contact_type==349){
            $('#cont_cooperative').html('<option value="">-- اختر الجمعية--</option>');
            $.each(data.contacts, function (key, value) {
                $("#cont_cooperative").append('<option value="' + value
                    .cont_id + '">' + value.cont_name + '</option>');
            });
        }else if(contact_type==348){
            $('#cont_member').html('<option value="">-- اختر الجمعية--</option>');
            $.each(data.contacts, function (key, value) {
                $("#cont_member").append('<option value="' + value
                    .cont_id + '">' + value.cont_name + '</option>');
            });
        }
        }
    });
}
        $('#create_contact').click(function() {
            contactModal();
                $('#cont_type_id').select2().val(349).trigger("change");
                $('#id_num_type').select2().val(375).trigger("change");
                $('#cont_type_id').attr('disabled', true);
                $('#id_num_type').attr('disabled', true);

        });
        $('#create_contact_member').click(function() {
            contactModal();
                $('#cont_type_id').select2().val(348).trigger("change");
                $('#id_num_type').select2().val(371).trigger("change");
                $('#cont_type_id').attr('disabled', true);
                $('#id_num_type').attr('disabled', true);

        });
        function contactModal(){
            $('#contact-modal').removeClass('d-none');
            $('#btn-save-contact').val("create_contact");
            $('#cont_id').val('');
            $('#id_no').val('');
            $('#birth_date').val('');
            $('#cont_type_id').select2().val('').trigger("change");
            $('#id_num_type').select2().val('').trigger("change");
            $('#governorate').select2().val('').trigger("change");
            $('#gender').select2().val('').trigger("change");
            $('#Form_').trigger("reset");
            $('#contact-modal').iziModal('open');
            $('#contact-modal').iziModal('setTitle', "إنشاء جهة اتصال");
            $('#contact-modal').iziModal('setIcon', 'fa fa-plus');

        }
        $("#btn-save-contact").click(function(e) {
            var contact_type = $("#contact_type").val();
            e.preventDefault();
            $('#btn-save-contact').html('جاري الحفظ');
            var cont_id = $("#cont_id").val();
            var cont_type_id = $("#cont_type_id").val();
            var id_num_type = $("#id_num_type").val();
            var id_no = $("#id_no").val();
            var cont_name = $("#cont_name").val();
            console.log(cont_name);
            var id_no2 = $("#id_no2").val();
            var cont_manager = $("#cont_manager").val();
            var work_id = $("#work_id").val();
            var ministry_id = $("#ministry_id").val();
            var governorate = $("#governorate").val();
            var work_place = $("#work_place").val();
            var birth_date = $("#birth_date").val();
            var gender = $("#gender").val();
            var phone = $("#phone").val();
            var mobile = $("#mobile").val();
            var fax = $("#fax").val();
            var email = $("#email").val();
            $.ajax({
                type: "POST",
                url: '/contacts',
                data: {
                    cont_id: cont_id,
                    cont_type_id: cont_type_id,
                    id_num_type: id_num_type,
                    id_no: id_no,
                    cont_name: cont_name,
                    id_no2: id_no2,
                    cont_manager: cont_manager,
                    work_id: work_id,
                    ministry_id: ministry_id,
                    governorate: governorate,
                    work_place: work_place,
                    birth_date: birth_date,
                    gender: gender,
                    phone: phone,
                    mobile: mobile,
                    fax: fax,
                    email: email,
                    // '_token': '{{ csrf_token() }}'
                },
                //dataType: 'json',
                success: function(data) {
                    $('#Form_').trigger("reset");
                    $('#cont_type_id').select2().val('').trigger("change");
                    $('#id_num_type').select2().val('').trigger("change");
                    $('#work_id').select2().val('').trigger("change");
                    $('#ministry_id').select2().val('').trigger("change");
                    $('#governorate').select2().val('').trigger("change");
                    $('#gender').select2().val('').trigger("change");
                    $('#contact-modal').iziModal('close');
                    $('#btn-save-contact').html('حفظ');
                    Swal.fire({
                        icon: "success",
                        title: "تمت العملية بنجاح!",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    getContacts(contact_type)
                    var oTable = $('#kt_datatable').DataTable();
                    oTable.destroy();
                    KTDatatablesDataSourceAjaxServer.init();

                },
                error: function(data) {
                    $('#btn-save-contact').html('حفظ');
                    Swal.fire({
                        icon: "error",
                        title: "خطأ!",
                        text: "لم يتم الحفظ",
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });
        $('#id_no').on('change', function() {
            var id = $(this).val();

            if (id) {
                $.ajax({
                    url: '/getIdName/' + id,
                    type: "GET",
                    data: {
                        // "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        //  console.log(contact_type);
                        $('#cont_name').val('');
                        $('#birth_date').val('');
                        $('#cont_name').attr('disabled', false);
                        $('#birth_date').attr('disabled', false);
                        $('#gender').attr('disabled', false);

                        if (data.length !== 0) {
                            console.log(data)


                            // $('#cont_name').attr('value',data.user_f_name_ar + ' ' + data.user_s_name_ar + ' ' + data.user_t_name_ar + ' ' + data.user_l_name_ar);
                            $('#cont_name').val(data.user_f_name_ar + ' ' + data.user_s_name_ar + ' ' +
                                data.user_t_name_ar + ' ' + data.user_l_name_ar);
                            $('#cont_name').attr('disabled', true);
                            $('#birth_date').attr('disabled', true);
                            $('#birth_date').val(data.dob);
                            $('#gender').attr('disabled', true);
                            $('#gender').select2().val(data.gender).trigger("change");



                        } else {

                            $('#cont_name').attr('value', '');
                        }
                    }
                });
            }
        });

        $('#id_no2').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: '/getIdName/' + id,
                    type: "GET",
                    data: {
                        // "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data.length !== 0) {
                            console.log(data)
                            $('#cont_manager').attr('value', data.user_f_name_ar + ' ' + data
                                .user_s_name_ar + ' ' + data.user_t_name_ar + ' ' + data
                                .user_l_name_ar);
                            $('#cont_manager').attr('disabled', true);


                        } else {

                            $('#cont_manager').attr('value', '');
                        }
                    }
                });
            }
        });

