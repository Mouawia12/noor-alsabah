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
                        columns: [ 0,1,2,3  ]
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
                        columns: [ 0,1,2,3 ]
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
                        'id','name','side_type','tel','actions'],
                    // from_date:from_date,
                    // to_date:to_date,
                    filter_1:filter_1,
                    filter_2:filter_2,
                    filter_3:filter_3,
                    filter_4:filter_4,
                    filter_5:filter_5,
                    // filter_6:filter_6,
                },
            },
            columns: [
                {data: 'id',width: 100},
                {data: 'name',width: 180},
                {data: 'side_type',width: 180},
                {data: 'tel',width: 180},
                {data: 'actions',width: 120},

            ],
            columnDefs: [
                {
                    targets: -1,
                    title: 'الإجراءات',
                    orderable: false,
                    render: function(data, type, full, meta) {
                            return '\
							<a href="javascript:;" onclick="return updated_('+full.id+')" class="btn btn-sm btn-clean btn-icon btn-primary" title="تعديل">\
								<i class="las la-edit"></i>\
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

$('#filter').click(function(){
    // from_date = $('#from_date').val() ;
    // to_date = $('#to_date').val();
    filter_1 = $('#filter_1').val();
    filter_2 = $('#filter_2').val();
    filter_3 = $('#filter_3').val();
    filter_4 = $('#filter_4').val();
    filter_5 = $('#filter_5').val();
    // filter_6 = $('#filter_6').val();

    // if(from_date == ''){
    //     from_date=-1;
    // }
    // if(to_date == ''){
    //     to_date=-1;
    // }
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
    // if(filter_6 == ''){
    //     filter_6=-1;
    // }
    var oTable = $('#kt_datatable').DataTable();
    $('#kt_datatable').addClass('d-none');
    oTable.destroy();
    KTDatatablesDataSourceAjaxServer.init();
    $('#kt_datatable').removeClass('d-none');

});

$('#refresh').click(function(){
    // $('#from_date').val('');
    // $('#to_date').val('');
    $('#filter_1').val('');
    $('#filter_2').val('');
    $('#filter_3').val('').trigger('change');
    $('#filter_4').val('').trigger('change');
    $('#filter_5').val('').trigger('change');
    // $('#filter_6').val('').trigger('change');
    // from_date='';
    // to_date = '';
    filter_1='';
    filter_2='';
    filter_3='';
    filter_4='';
    filter_5='';
    // filter_6='';

    // if(from_date == ''){
    //     from_date=-1;
    // }
    // if(to_date == ''){
    //     to_date=-1;
    // }
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
    // if(filter_6 == ''){
    //     filter_6=-1;
    // }

    var oTable = $('#kt_datatable').DataTable();
    $('#kt_datatable').addClass('d-none');
    oTable.destroy();
    KTDatatablesDataSourceAjaxServer.init();
    $('#kt_datatable').removeClass('d-none');
});





