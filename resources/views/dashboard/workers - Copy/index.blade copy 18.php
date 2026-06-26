<script language="javascript">
$(document).ready(function() {
$('[data-inputmask]').inputmask();
show_govdata_tab(1);
create_table_history2("#tbl_history_data");
create_table_history2("#tbl_data_point");
create_table_history("#tbl_history_file");
});
function create_table_history2(table_name) {
    $(table_name).DataTable({
        "ordering": false,
        "paging": false,
        bFilter: false,
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
        },

    });
}
function create_table_history(table_name) {
    $(table_name).DataTable({
        "ordering": false,
        "paging": false,
        bFilter: false,
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
        },
        "columnDefs": [{
                responsivePriority: 1,
                targets: 0
            },
            {
                "className": "dt-center",
                "targets": "_all"
            },

        ]
    });
}
</script>
<style type="text/css">
.kt-user-card-v2 .kt-user-card-v2__details {width: 100%;}
.table.dataTable {font-size: 11px;}
.kt-widget11 .table thead>tr>td {color: #000;text-align: right;}
.kt-widget11 .table tbody>tr>td {padding-top: 12px;}
.kt-badge.kt-badge--lg {height: 35px;width: 35px;font-size: 1rem;text-align: center;}
.progress {background-color: #e1e1ef;}
.modal-content {background-color: #fff;}
.kt-widget.kt-widget--user-profile-3 .kt-widget__top .kt-widget__media img {
  width: 110px;
  border-radius: 8px;
}
</style>
<!--<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">-->
    <div class="row">
                            <input type="text" style="display:none" id="WORKER_ID_VAL" name="WORKER_ID_VAL" im-insert="true"
                        data-inputmask="'alias' : 'integer' " value="<?php echo $WORKER_ID ?>" class="form-control">
                    <input type="text" style="display:none" id="DETAILS_ID_VAL" name="DETAILS_ID_VAL" im-insert="true"
                        data-inputmask="'alias' : 'integer' " value="<?php echo $DETAILS_ID ?>" class="form-control">
        <div class="col-lg-12">
            <!--	<div class="kt-portlet">-->
            <div class="kt-portlet kt-portlet--height-fluid">
<?php    if (get_function_access(15) and $this->job!=1) {
if ($CHK_VALUE_BANNED == 1) {
echo $grade_show2 ;
}
}else  if (get_function_access(90) || $this->job==1) {
echo $grade_show2 ;
}



?>
<!--
-->

             <!--   <div class="kt-portlet__head">

                    <div class="kt-portlet__head-label">


                        <h3 class="kt-portlet__head-title">
مستودع البيانات


                        </h3>
                    </div>
                </div>-->
                <div class="kt-portlet__body">

<?php    if ($user_id=='413346578' || $user_id=='800097818'  ) {
   if ($status=='success' ) {
?>
<div class="kt-widget__media kt-hidden-">
<img src="<?php echo $photo_url ?>" alt="image" style="width: 110px;">
</div>

<?php } else{ ?>
<?php
/*echo "انتهت الكوته يا سامر خليهم بدون كوته" */
?>
<?php }}?>
               <?php
if ( get_function_access(28) ) { ?>

                    <ul class="nav nav-tabs  nav-tabs-line" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active kt-font-dark kt-font-bolder" data-toggle="tab" onclick='show_govdata_tab(1)'
                                role="tab"><i class="fab fa-phabricator  kt-font-success kt-font-bolder"></i>البيانات
                                العامل</a>
                        </li>
                 <li class="nav-item">
                            <a class="nav-link kt-font-dark kt-font-bolder" data-toggle="tab" href="#kt_tabs_1_3" onclick='show_govdata_tab(2)'
                                role="tab"><i class="fa  fa-database  kt-font-orange kt-font-bolder"></i>فحص البيانات الحكومية</a>
                        </li>
                       <li class="nav-item dropdown kt-font-dark kt-font-bolder" style="display:none">
                            <a class="nav-link dropdown-toggle kt-font-dark kt-font-bolder" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="true" aria-expanded="false"><i
                                    class="flaticon-placeholder-2 kt-font-info kt-font-bolder"></i>بيانات الحكومية</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " data-toggle="tab" href="#kt_tabs_1_2">بيانات الشؤون</a>
                                <a class="dropdown-item" data-toggle="tab" href="#kt_tabs_1_2">بيانات المواصلات</a>
                                <a class="dropdown-item" data-toggle="tab" href="#kt_tabs_1_2">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="tab" href="#kt_tabs_1_2">Separated link</a>
                            </div>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_data" role="tabpanel">
                        </div>
                    </div>
                                        <?php }?>

                </div>
            </div>
        </div>
    </div>
<!--</div>-->
