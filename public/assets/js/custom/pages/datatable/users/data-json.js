//
// "use strict";
// // Class definition
//
// var KTDatatableJsonRemoteDemo = function() {
//     // Private functions
//
//     // basic demo
//     var demo = function() {
//         var datatable = $('#kt_datatable').KTDatatable({
//             translate: {
//                 records: {
//                     processing: 'جارٍ التحميل...',
//                     noRecords: 'لا يوجد سجلات'
//                 },
//                 toolbar: {
//                     pagination: {
//                         items: {
//                             info: 'إظهار {{start}} - {{end}} من {{total}}'
//                         }
//                     }
//                 }
//
//             },
//
//             // datasource definition
//             data: {
//                 type: 'remote',
//                 // source: HOST_URL + '/api/?file=datatables/datasource/default.json',
//                 source: DATA_URL,
//                 pageSize: 10,
//             },
//
//             // layout definition
//             layout: {
//                 scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
//                 footer: false // display/hide footer
//             },
//
//             // column sorting
//             sortable: true,
//
//             pagination: true,
//
//             search: {
//                 input: $('#kt_datatable_search_query'),
//                 key: 'generalSearch'
//             },
//
//             // columns definition
//             columns: [{
//                 field: 'id',
//                 title: 'رقم المكلف',
//                 width: 90,
//                 // sortable: false,
//             },
//                 {
//                     field: 'identity_number',
//                     title: 'رقم الهوية',
//                     width: 90,
//                 },{
//                     field: 'name',
//                     title: 'الاسم',
//                 },
//                 {
//                     field: 'address',
//                     title: 'عنوان المكلف',
//                 },
//                 //     {
//                 //     field: 'first_name',
//                 //     title: 'الاسم',
//                 //     template: function(row) {
//                 //         return row.first_name + ' ' + row.second_name+ ' ' + row.third_name+ ' ' + row.last_name;
//                 //     },
//                 // },
//                 //     {
//                 //         field: 'section_id',
//                 //         title: 'القسم',
//                 //     },
//                 {
//                     field: 'created_at',
//                     title: 'تاريخ إضافة المكلف',
//                     type: 'date',
//                     format: 'MM/DD/YYYY',
//                 }, {
//                     field: 'actions',
//                     title: 'الإجراءات',
//                     sortable: false,
//                     width: 125,
//                     autoHide: false,
//                     overflow: 'visible',
//                     template: function(row) {
//                         var edit = SITEURL + '/mokalafs/'+row.id+'/edit';
//                         var destroy = SITEURL + '/mokalafs/'+row.id+'/destroy';
//                         return '\
//                         <div class="dropdown dropdown-inline">\
//                             <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
//                                 <span class="svg-icon svg-icon-md">\
//                                     <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
//                                         <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
//                                             <rect x="0" y="0" width="24" height="24"/>\
//                                             <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
//                                         </g>\
//                                     </svg>\
//                                 </span>\
//                             </a>\
//                             <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
//                                 <ul class="navi flex-column navi-hover py-2">\
//                                     <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
//                                         Choose an action:\
//                                     </li>\
//                                     <li class="navi-item">\
//                                         <a href="#" class="navi-link">\
//                                             <span class="navi-icon"><i class="la la-print"></i></span>\
//                                             <span class="navi-text">Print</span>\
//                                         </a>\
//                                     </li>\
//                                     <li class="navi-item">\
//                                         <a href="#" class="navi-link">\
//                                             <span class="navi-icon"><i class="la la-copy"></i></span>\
//                                             <span class="navi-text">Copy</span>\
//                                         </a>\
//                                     </li>\
//                                     <li class="navi-item">\
//                                         <a href="#" class="navi-link">\
//                                             <span class="navi-icon"><i class="la la-file-excel-o"></i></span>\
//                                             <span class="navi-text">Excel</span>\
//                                         </a>\
//                                     </li>\
//                                     <li class="navi-item">\
//                                         <a href="#" class="navi-link">\
//                                             <span class="navi-icon"><i class="la la-file-text-o"></i></span>\
//                                             <span class="navi-text">CSV</span>\
//                                         </a>\
//                                     </li>\
//                                     <li class="navi-item">\
//                                         <a href="#" class="navi-link">\
//                                             <span class="navi-icon"><i class="la la-file-pdf-o"></i></span>\
//                                             <span class="navi-text">PDF</span>\
//                                         </a>\
//                                     </li>\
//                                 </ul>\
//                             </div>\
//                         </div>\
//                         <a href="'+edit+'" class="btn btn-sm btn-clean btn-icon mr-2" title="تعديل">\
//                             <span class="svg-icon svg-icon-md">\
//                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
//                                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
//                                         <rect x="0" y="0" width="24" height="24"/>\
//                                         <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
//                                         <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
//                                     </g>\
//                                 </svg>\
//                             </span>\
//                         </a>\
//                         <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" onclick="return deleted_('+row.id+')"; title="حذف">\
//                             <span class="svg-icon svg-icon-md">\
//                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
//                                     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
//                                         <rect x="0" y="0" width="24" height="24"/>\
//                                         <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
//                                         <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
//                                     </g>\
//                                 </svg>\
//                             </span>\
//                         </a>\
//                     ';
//                     },
//                 }],
//
//         });
//
//         $('#kt_datatable_search_status').on('change', function() {
//             datatable.search($(this).val().toLowerCase(), 'Status');
//         });
//
//         $('#kt_datatable_search_type').on('change', function() {
//             datatable.search($(this).val().toLowerCase(), 'Type');
//         });
//
//         $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
//     };
//
//     return {
//         // public functions
//         init: function() {
//             demo();
//         }
//     };
// }();
//
// jQuery(document).ready(function() {
//     KTDatatableJsonRemoteDemo.init();
// });
//
//
//
//



"use strict";
var KTDatatablesDataSourceAjaxServer = function() {

    var initTable1 = function() {
        var table = $('#kt_datatable');
        // begin first table
        table.DataTable({
            language: {
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "أظهر _MENU_ مدخلات",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sSearch": "بحث عام:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "الأول",
                    "sPrevious": "السابق",
                    "sNext": "التالي",
                    "sLast": "الأخير"
                }
            },
            lengthMenu: [[10,25, 50, 100, 500,1000,1500,2000,10000], ['10','25', '50', '100', "500", "1000", "1500", "2000", "الكل"]],
            dom: 'Blfrtip',
            buttons: [
                // {
                //     "extend": "colvis",
                //     "text": "إخفاء/إظهار أعمدة",
                //     "bom": "true",
                //     exportOptions: {
                //         columns: ':visible'
                //     },
                // },
                // {
                //     "extend": "copy",
                //     "text": "نسخ",
                //     "bom": "true",
                //     exportOptions: {
                //         // columns: ':visible'
                //         columns: [ 0,1,2,3,4 ]
                //     },
                // },
                {
                    "extend": "excel",
                    "text": "إكسل",
                    "bom": "true",
                    "attr": { class: 'btn btn-success buttons-excel buttons-html5'},
                    exportOptions: {
                        // columns: ':visible'
                        columns: [ 0,1,2,3,4,5,6 ]
                    },
                    customize: function (doc) {
                    }
                },
                {
                    "extend": "print",
                    "text": "طباعة",
                    "title": '',
                    "bom": "true",
                    "attr": { class: 'btn btn-info buttons-excel buttons-html5'},
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6]
                    },

                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'direction', 'rtl' )
                            .prepend(
                                '<div style="display: flex;border-bottom: 1px solid #000;margin-bottom: 20px"><div style="width: 33%;margin-top: auto;margin-bottom: auto;padding-right: 20px"><img src="'+SITEURL+'/assets/media/reports/header-right.png" width="150" /></div><div style="width: 33%;text-align: center;margin: auto"><img style="text-align: center" src="'+SITEURL+'/assets/media/reports/header-center.png" width="50" /></div><div style="margin-right:auto;margin-top:auto;margin-bottom:auto;text-align: center;width: 33%;font-size: 14px;line-height: 25px"><div>الإدارة العامة للشؤون المالية والإدارية</div><div>دائرة اللوازم العامة - المستودعات العامة</div></div></div>'
                            );

                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'direction', 'rtl' );
                    },
                },

            ],
            select: true,
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,

            ajax: {
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                // url: HOST_URL + '/api/datatables/demos/server.php',
                url: DATA_URL,
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'ssn','emp_no','name','email','mobile','department', 'created_at','actions'],
                    from_date:from_date,
                    to_date:to_date,
                    filter_1:filter_1,
                    filter_2:filter_2,
                    filter_3:filter_3,
                    filter_4:filter_4,
                    filter_5:filter_5,
                },
            },
            columns: [
                {data: 'ssn',width: 100},
                {data: 'emp_no',width: 100},
                {data: 'name',width: 180},
                {data: 'email',width: 140},
                {data: 'mobile',width: 120},
                {data: 'department',width: 120},
                {data: 'created_at', class:'dir_ltr',width: 200},
                {data: 'actions',width: 160},

            ],
            columnDefs: [
                {
                    targets: -1,
                    title: 'الإجراءات',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return '\
                            <a href="'+SITEURL+'/users/'+''+full.id+''+'/edit" class="btn btn-sm btn-clean btn-icon btn-primary" title="تعديل">\
								<i class="las la-edit"></i>\
							</a>\
							<a href="javascript:;" onclick="return deleted_('+full.id+')" class="btn btn-sm btn-clean btn-icon btn-danger" title="حذف">\
								<i class="las la-trash"></i>\
							</a>\
						';
                    },
                },

            ],
        });
    };

    return {

        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };

}();

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
});

// /* When click deleted */
function deleted_(id) {
    Swal.fire({
        title: "هل أنت متأكد؟!",
        text: "سيتم الحذف الآن!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم, احذف",
        cancelButtonText: "ليس الآن",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-default"
        },
        reverseButtons: false
    }).then(function(result) {
        if (result.value) {
            console.log('sss')
            $.ajax({
                type: "DELETE",
                url: SITEURL + "/users/"+id,
                success: function (data) {
                    Swal.fire({
                        icon: "success",
                        title: "تم الحذف بنجاح!",
                        text: "سيتم الإرسال لسلة المهملات",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    location.reload();
                    // var oTable = $('#kt_datatable').KTDatatable();
                    // oTable.fnDraw(false);
                },
                error: function (data) {
                    // console.log('Error:', data);
                    Swal.fire({
                        icon: "error",
                        title: "خطأ!",
                        text: "لم يتم الحذف",
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }
    });
}

$('#filter').click(function(){
    from_date = $('#from_date').val() ;
    to_date = $('#to_date').val();
    filter_1 = $('#filter_1').val();
    filter_2 = $('#filter_2').val();
    filter_3 = $('#filter_3').val();
    filter_4 = $('#filter_4').val();
    filter_5 = $('#filter_5').val();

    if(from_date == ''){
        from_date=-1;
    }
    if(to_date == ''){
        to_date=-1;
    }
    if(filter_1 == ''){
        filter_1=-1;
    }
    if(filter_2 == ''){
        filter_2=-1;
    }
    if(filter_3 == ''){
        filter_3=-1;
    }
    if(filter_4 == ''){
        filter_4=-1;
    }
    if(filter_5 == ''){
        filter_5=-1;
    }

    var oTable = $('#kt_datatable').DataTable();
    $('#kt_datatable').addClass('d-none');
    oTable.destroy();
    KTDatatablesDataSourceAjaxServer.init();
    $('#kt_datatable').removeClass('d-none');

});

$('#refresh').click(function(){
    $('#from_date').val('');
    $('#to_date').val('');
    $('#filter_1').val('');
    $('#filter_2').val('');
    $('#filter_3').val('').trigger('change');
    $('#filter_4').val('');
    $('#filter_5').val('');
    from_date='';
    to_date = '';
    filter_1='';
    filter_2='';
    filter_3='';
    filter_4='';
    filter_5='';


    if(from_date == ''){
        from_date=-1;
    }
    if(to_date == ''){
        to_date=-1;
    }
    if(filter_1 == ''){
        filter_1=-1;
    }
    if(filter_2 == ''){
        filter_2=-1;
    }
    if(filter_3 == ''){
        filter_3=-1;
    }
    if(filter_4 == ''){
        filter_4=-1;
    }
    if(filter_5 == ''){
        filter_5=-1;
    }


    var oTable = $('#kt_datatable').DataTable();
    $('#kt_datatable').addClass('d-none');
    oTable.destroy();
    KTDatatablesDataSourceAjaxServer.init();
    $('#kt_datatable').removeClass('d-none');

});




