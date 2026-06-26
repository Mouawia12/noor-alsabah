<script language="javascript">
$(document).ready(function() {
$('[data-switch=true]').bootstrapSwitch();
$('[data-inputmask]').inputmask();
create_table_history2("#tbl_data_point");
create_table_history2("#tbl_data_point_show");
create_table_history2("#tbl_history_data");
create_table_history2("#tbl_gcc_data");
create_table_history2("#tbl_lic_vw");
create_table_history2("#tbl_doffa_point");
create_table_history2("#tbl_history_data_all");
create_table_history2("#tbl_mosa_point");
create_table_history2("#tbl_car_data");
create_table_history2("#tbl_work_data");
create_table_history2("#tbl_mosa_order");
create_table_history2("#tbl_aid_point");
create_table_history2("#tbl_DISA_MOSA");
create_table_history2("#tbl_state_point");
create_table_history2("#tbl_data_point2");
create_table_history2("#tbl_data_point2_ALL");
create_table_history("#tbl_history_file");
create_table_history2("#tbl_car_data_wife");
create_table_history2("#tbl_work_wife_data");
create_table_history2("#tbl_mosa_point_wife");
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
.kt-user-card-v2 .kt-user-card-v2__details {
width: 100%;
}
.table.dataTable {
font-size: 11px;
}
.kt-widget11 .table thead>tr>td {
color: #000;
text-align: right;
}
.kt-widget11 .table tbody>tr>td {
padding-top: 12px;
}

.dataTables_wrapper .dataTable th {
color: #fff !important;
}
.table-bordered {
border: 1px solid #343a40;
}
/*.table-bordered th {
border: 0;
border-bottom-width: 0;
}*/
.kt-widget11 .table tbody>tr>td {
color: #212529;
}
</style>
<?php
if ($TAB_ID == 1) {
if (get_function_access(15) and $this->job!=1 ) {

    if ($CHK_VALUE_BANNED == 1) {

if ($Z_REG_48_ALL_DATA_POINTS_TB_BY_ID != 0) {
?>
<div class="kt-widget12">
<div class="kt-widget12__content">
<div class="kt-widget12__item">
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="flaticon-medal"></i>
الدرجة/<?php echo "31" ?></span>
<span class="kt-widget12__value kt-font-success kt-font-bolder"><?php echo $SUM_POINTS ?></span>
</div>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-percentage"></i> النسبة</span>
<span class="kt-widget12__value kt-font-success kt-font-bolder"><?php echo $SUM_PERCENT ?></span>
</div>

<?php if ($z_REG_48_PACKAGE_DETAILS_VW != 0) {  ?>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-tags"></i> الدفعة</span>
<span class="kt-widget12__value kt-font-success kt-font-small" style="font-size: 1rem;"><?php echo $PACKAGE_NAME_VW ?></span>
</div>
<?php  } else {  ?>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-tags"></i> الدفعة</span>
<span class="kt-widget12__value kt-font-success kt-font-bolder">---</span>
</div>
<?php  }  ?>
</div>
</div>
</div>
<div class="table-responsive-xl">
<table id="tbl_data_point_show" name="tbl_data_point_show" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#eff2f7;background-color:#343a40;text-align:center">
<tr>
<th style="color: #ffb822 !important;">العمر</th>
<th style="color: #ffb822 !important;">عدد الأبناء</th>
<th style="color: #ffb822 !important;">شهادة مهنية</th>
<th style="color: #ffb822 !important;">يملك سيارة</th>
<th style="color: #ffb822 !important;">اذ كان يعمل في سوق العمل المحلي</th>
<th style="color: #ffb822 !important;">يتلقى مخصصات حكومية</th>
<th style="color: #ffb822 !important;">الزوجة تعمل (حكومة, وكالة, صاحب عمل)</th>
<th style="color: #ffb822 !important;">سجل تجاري</th>
<?php if ( $this->job == 1) {  ?>
<th style="color: #ffb822 !important;">سنة التسجيل</th>
<?php } ?>
</tr>
</thead>
<tbody>
<tr>
</td>
<td> <?php echo $AGE_FINAL_POINT ?></td>
<td> <?php echo $SONS_FINAL_POINT ?></td>
<td> <?php echo  $VOCATIONAL_FINAL_POINT ?></td>
<td> <?php echo  $IF_CAR_OWNER_FINAL_POINT ?></td>
<td> <?php echo  $IF_WORKER_FINAL_POINT ?></td>
<td> <?php echo  $IF_ALLOTMENTS_FINAL_POINT ?></td>
<td> <?php echo  $IF_WIFE_MOL_MOF_UN_EMP_FINAL_POINT ?></td>
<td> <?php echo  $IF_ECONOMIC_FINAL_POINT ?></td>
<?php if ( $this->job == 1) {  ?>
<td> <?php echo  $REG_YEAR_POINTS ?></td>
<?php } ?>
</tr>
</tbody>
</table>
</div>
<?php }
    }




}

if (get_function_access(90) || $this->job==1 ) {


if ($Z_REG_48_ALL_DATA_POINTS_TB_BY_ID != 0) {
?>
<div class="kt-widget12">
<div class="kt-widget12__content">
<div class="kt-widget12__item">
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="flaticon-medal"></i>
الدرجة/<?php echo "31" ?></span>
<span class="kt-widget12__value kt-font-success kt-font-bolder"><?php echo $SUM_POINTS ?></span>
</div>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-percentage"></i> النسبة</span>
<span class="kt-widget12__value kt-font-success kt-font-bolder"><?php echo $SUM_PERCENT ?></span>
</div>

<?php if ($z_REG_48_PACKAGE_DETAILS_VW != 0) {  ?>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-tags"></i> الدفعة</span>
<span class="kt-widget12__value kt-font-success kt-font-small" style="font-size: 1rem;"><?php echo $PACKAGE_NAME_VW ?></span>
</div>
<?php  } else {  ?>
<div class="kt-widget12__info">
<span class="kt-widget12__desc kt-font-blue kt-font-bolder"> <i class="fa fa-tags"></i> الدفعة</span>
<span class="kt-widget12__value kt-font-success kt-font-bolder">---</span>
</div>
<?php  }  ?>
</div>
</div>
</div>
<div class="table-responsive-xl">
<table id="tbl_data_point_show" name="tbl_data_point_show" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#eff2f7;background-color:#343a40;text-align:center">
<tr>
<th style="color: #ffb822 !important;">العمر</th>
<th style="color: #ffb822 !important;">عدد الأبناء</th>
<th style="color: #ffb822 !important;">شهادة مهنية</th>
<th style="color: #ffb822 !important;">يملك سيارة</th>
<th style="color: #ffb822 !important;">اذ كان يعمل في سوق العمل المحلي</th>
<th style="color: #ffb822 !important;">يتلقى مخصصات حكومية</th>
<th style="color: #ffb822 !important;">الزوجة تعمل (حكومة, وكالة, صاحب عمل)</th>
<th style="color: #ffb822 !important;">سجل تجاري</th>
<?php if ( $this->job == 1) {  ?>
<th style="color: #ffb822 !important;">سنة التسجيل</th>
<?php } ?>
</tr>
</thead>
<tbody>
<tr>
</td>
<td> <?php echo $AGE_FINAL_POINT ?></td>
<td> <?php echo $SONS_FINAL_POINT ?></td>
<td> <?php echo  $VOCATIONAL_FINAL_POINT ?></td>
<td> <?php echo  $IF_CAR_OWNER_FINAL_POINT ?></td>
<td> <?php echo  $IF_WORKER_FINAL_POINT ?></td>
<td> <?php echo  $IF_ALLOTMENTS_FINAL_POINT ?></td>
<td> <?php echo  $IF_WIFE_MOL_MOF_UN_EMP_FINAL_POINT ?></td>
<td> <?php echo  $IF_ECONOMIC_FINAL_POINT ?></td>
<?php if ( $this->job == 1) {  ?>
<td> <?php echo  $REG_YEAR_POINTS ?></td>
<?php } ?>
</tr>
</tbody>
</table>
</div>
<?php }




}

?>










<?php
if (get_function_access(78) ) {
if($ALL_SPECIAL_SHEET>=1){
$ALL_SPECIAL_SHEET='fa fa-id-card-alt  kt-font-orange kt-font-bolder';
}
else{
$ALL_SPECIAL_SHEET='fa fa-id-card-alt  kt-font-gray-200 kt-font-bolder';
}
}
else{
$ALL_SPECIAL_SHEET='fa fa-id-card-alt  kt-font-gray-200 kt-font-bolder';
}
?>

<table id="tbl_history_data" name="tbl_history_data" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#eff2f7;background-color:#343a40;text-align:center">
<tr>
<th style="width:25% !important;color: #ffb822 !important;"> <i class=" <?php echo $ALL_SPECIAL_SHEET?>"></i> البيانات الاساسية من السجل المدني </th>
<th style="width:25% !important;color: #ffb822 !important;"> <i class="fa fa-file-alt  kt-font-light kt-font-bolder"></i> سجل التسجيل </th>
<th style="width:25% !important;color: #ffb822 !important;"> <i class="fa fa-id-card-alt  kt-font-gray-200 kt-font-bolder"></i> البيانات الاساسية لسجل التصاريح </th>
</tr>
</thead>
<tbody>
<?php
if (get_function_access(39)) {
if ($CHK_VALUE_BANNED == "1") {
$FULL_NAME = '<span class="kt-font-bolder kt-font-success">' . "$FULL_NAME" . '</span> <i class="fa fa-user-check kt-font-success "></i>';
} else if ($CHK_VALUE_BANNED == "0") {
$FULL_NAME = '<span class="kt-font-bolder kt-font-danger">' . "$FULL_NAME" . '</span> <i class="fa fa-user-times kt-font-danger"></i>';
} else if ($CHK_VALUE_BANNED == '' || $CHK_VALUE_BANNED == NULL) {
$FULL_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$FULL_NAME" . '</span> <i class="fa  fa-hourglass-end  kt-font-blue"></i>';
} else {
$FULL_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$FULL_NAME" . '</span> <i class="fa  fa-hourglass-end  kt-font-blue"></i>';
}
} else {
$FULL_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$FULL_NAME" . '</span>';
}
if($z_MOL_48_REG_FILTER_TB!=0){
if ($APP_DT != '') {
$APP_DT = date("Y-m-d", strtotime($APP_DT));
if( $APP_DT=='01/01/1970'){
$APP_DT= '';
}
}
else{
$APP_DT= '---';
}
$AGE_MTIT = '<span class="kt-font-bolder kt-font-blue">' . "$AGE_MTIT" . '</span>';
$NO_OF_SON = '<span class="kt-font-bolder kt-font-blue">' . "$NO_OF_SON_MTIT" . '</span>';
$SOCIAL_STATUS_MTITI = '<span class="kt-font-bolder kt-font-blue">' . "$SOCIAL_STATUS_MTITI" . '</span>';
$APP_DT = '<span class="kt-font-bolder kt-font-blue">' . "$APP_DT" . '</span>';
$WORK_TYPE1 = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE1_PER" . '</span>';
$WORK_TYPE2 = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE2_PER" . '</span>';
$WORK_TYPE3 = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE3_PER" . '</span>';
$WORK_TYPE4 = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE4_PER" . '</span>';
}
else{
if ($RECORD_DATE_PER != '') {
$RECORD_DATE = date("Y-m-d", strtotime($RECORD_DATE_PER));
if( $RECORD_DATE=='01/01/1970'){
$RECORD_DATE= '';
}
}
else{
$RECORD_DATE= '---';
}
$RECORD_DATE = '<span class="kt-font-bolder kt-font-blue">' . "$RECORD_DATE" . '</span>';
$AGE_DB_PER = '<span class="kt-font-bolder kt-font-blue">' . "$AGE_DB_PER" . '</span>';
$MOI_CHILD_NO_DB_PER = '<span class="kt-font-bolder kt-font-blue">' . "$MOI_CHILD_NO_DB_PER" . '</span>';
$MOI_STATUS_DESC_PER = '<span class="kt-font-bolder kt-font-blue">' . "$MOI_STATUS_DESC_PER" . '</span>';
$WORK_TYPE1_PER = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE1_PER" . '</span>';
$WORK_TYPE2_PER = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE2_PER" . '</span>';
$WORK_TYPE3_PER = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE3_PER" . '</span>';
$WORK_TYPE4_PER = '<span class="kt-font-bolder kt-font-blue">' . "$WORK_TYPE4_PER" . '</span>';
}
if ($IF_MOBILE_VERIFIED == '1') {
$MOBILE = '<i class="fa fa-check-double kt-font-success"></i> <span class="kt-font-bolder kt-font-success">' . "$MOBILE" . '</span>';
} else if ($IF_MOBILE_VERIFIED == '0') {
$MOBILE = '<i class="fa fa-ban kt-font-danger"></i> <span class="kt-font-bolder kt-font-danger">' . "$MOBILE" . '</span>';
} else if ($IF_MOBILE_VERIFIED == '') {
$MOBILE = '<i class="fa fa-hourglass-half kt-font-blue"></i> <span class="kt-font-bolder kt-font-blue">' . "$MOBILE" . '</span>';
} else {
$MOBILE = "";
}
$WORKER_ID = '<span class="kt-font-bolder kt-font-blue">' . "$WORKER_ID" . '</span>';
$MAIN_MOBILE_NO = '<span class="kt-font-bolder kt-font-blue">' . "$MAIN_MOBILE_NO" . '</span>';
$ALTER_MOBILE_NO = '<span class="kt-font-bolder kt-font-blue">' . "$ALTER_MOBILE_NO" . '</span>';
$REGION_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$REGION_NAME" . '</span>';
$SSN_MTIT = '<span class="kt-font-bolder kt-font-blue">' . "$SSN_MTIT" . '</span>';
$MOBILE_MTIT = '<span class="kt-font-bolder kt-font-blue">' . "$MOBILE_MTIT" . '</span>';
$VACCINATION_TYPE = '<span class="kt-font-bolder kt-font-blue">' . "$VACCINATION_TYPE" . '</span>';
$LICENSE_NO = '<span class="kt-font-bolder kt-font-blue">' . "$LICENSE_NO" . '</span>';
$STATUS_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$STATUS_NAME" . '</span>';
$DATE_FROM = '<span class="kt-font-bolder kt-font-blue">' . "$DATE_FROM" . '</span>';
$DATE_TO = '<span class="kt-font-bolder kt-font-blue">' . "$DATE_TO" . '</span>';
$MOI_CHILD_NO_DB = '<span class="kt-font-bolder kt-font-blue">' . "$MOI_CHILD_NO_DB" . '</span>';
$MOI_STATUS_DESC = '<span class="kt-font-bolder kt-font-blue">' . "$MOI_STATUS_DESC" . '</span>';
$COUNTER = '<span class="kt-font-bolder kt-font-danger">' . "$COUNTER" . '</span>';
$MOF_EMP_STATUS = '<span class="kt-font-bolder kt-font-danger">' . "$MOF_EMP_STATUS" . '</span>';
$TYPE_ID = '<span class="kt-font-bolder kt-font-danger">' . "$TYPE_ID" . '</span>';
$TYPE_NAME = '<span class="kt-font-bolder kt-font-danger">' . "$TYPE_NAME" . '</span>';
$PER_DATE_FROM = '<span class="kt-font-bolder kt-font-danger">' . "$PER_DATE_FROM" . '</span>';
$PER_DATE_TO = '<span class="kt-font-bolder kt-font-danger">' . "$PER_DATE_TO" . '</span>';
$emp_name_p = '<span class="kt-font-bolder kt-font-dark">' . "$emp_name_p" . '</span>';
$CREATE_DATE = '<span class="kt-font-bolder kt-font-dark">' . "$CREATE_DATE" . '</span>';

if ($ALLOW_TO_CROSS == '1') {
$ALLOW_TO_CROSS_DESC = '<i class="fa fa-check-double kt-font-success"></i> <span class="kt-font-bolder kt-font-success">' . "$ALLOW_TO_CROSS_DESC" . '</span>';
} else if ($ALLOW_TO_CROSS == '0') {
$ALLOW_TO_CROSS_DESC = '<i class="fa fa-ban kt-font-danger"></i> <span class="kt-font-bolder kt-font-danger">' . "$ALLOW_TO_CROSS_DESC" . '</span>';
} else if ($ALLOW_TO_CROSS == '2') {
$ALLOW_TO_CROSS_DESC = '<i class="fa fa-hourglass-half kt-font-blue"></i> <span class="kt-font-bolder kt-font-blue">' . "$ALLOW_TO_CROSS_DESC" . '</span>';
} else if ($ALLOW_TO_CROSS == '3') {
$ALLOW_TO_CROSS_DESC = '<i class="fa fa-hourglass-half kt-font-info"></i> <span class="kt-font-bolder kt-font-info">' . "$ALLOW_TO_CROSS_DESC" . '</span>';
}  else {
$ALLOW_TO_CROSS_DESC = "";
}
$SHEET_NO = '<span class="kt-font-bolder kt-font-danger">' . "$SHEET_NO" . '</span>';
?>
<tr>
<td style="width:25% !important;"> <?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-user"></i> الاسم : ' . "$FULL_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-address-card"></i> رقم الهوية : ' . "$WORKER_ID" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-birthday-cake "></i> الحالة الاجتماعية : ' . "$MOI_STATUS_DESC" . '</span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-venus-double "></i> العمر : ' . "$AGE_DB" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-lightbulb "></i> عدد الاولاد : ' . "$MOI_CHILD_NO_DB" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-location-arrow "></i> المدينة  : ' . "$REGION_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-tty  "></i> رقم الجوال المسجل : ' . "$MOBILE " . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-map-marked-alt"></i> العنوان :' . "$REGION_NAME" . ' </span></p>
</div>
</div>' ?></td>
<?php  if($z_MOL_48_REG_FILTER_TB!=0){ ?>
<td style="width:25% !important;"> <?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-venus-double "></i> العمر وقت التسجيل في الرابط : ' . "$AGE_MTIT" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-lightbulb "></i> عدد الاولاد وقت التسجيل في الرابط : ' . "$NO_OF_SON" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-birthday-cake "></i> الحالة الاجتماعية وقت التسجيل في الرابط : ' . "$SOCIAL_STATUS_MTITI" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-thermometer  "></i> اللقاح  : ' . "$VACCINATION_TYPE" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-hourglass-half   "></i> تاريخ التقديم  : ' . "$APP_DT" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-hammer"></i> المهنة الاولى  : ' . "$WORK_TYPE1" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa    fa-wrench"></i> المهنة الثانية  : ' . "$WORK_TYPE2" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa    fa-tools"></i> المهنة الثالثة  : ' . "$WORK_TYPE3" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-screwdriver"></i> المهنة الرابعة : ' . "$WORK_TYPE4" . '</span></p>
</div>
</div>' ?>
<?php }  else if($z_MOL_48_REG_FILTER_TB==0){ ?>
<td style="width:25% !important;"> <?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-venus-double "></i> العمر وقت  : ' . "$AGE_DB_PER" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-lightbulb "></i> عدد الاولاد وقت التسجيل  : ' . "$MOI_CHILD_NO_DB_PER" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-birthday-cake "></i> الحالة الاجتماعية وقت التسجيل   : ' . "$MOI_STATUS_DESC_PER" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-thermometer  "></i> اللقاح  : - </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-hourglass-half   "></i> تاريخ التقديم  : ' . "$RECORD_DATE" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-hammer"></i> المهنة الاولى  : ' . "$WORK_TYPE1_PER" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa    fa-wrench"></i> المهنة الثانية  : ' . "$WORK_TYPE2_PER" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa    fa-tools"></i> المهنة الثالثة  : ' . "$WORK_TYPE3_PER" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-screwdriver"></i> المهنة الرابعة : ' . "$WORK_TYPE4_PER" . '</span></p>
</div>
</div>' ?>
<?php } ?>
<td style="width:25% !important;">
<?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="flaticon-edit-1 "></i> رقم التصريح : ' . "$LICENSE_NO" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="flaticon-bell-1 "></i>  حالة التصريح  : ' . "$STATUS_NAME" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="flaticon2-calendar-7 "></i>  تاريخ السريان من  : ' . "$DATE_FROM" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="flaticon2-calendar-8 "></i>  تاريخ السريان إلى  : ' . "$DATE_TO" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-dolly-flatbed "></i> حركة المعبر: ' . "$COUNTER" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-exclamation-triangle "></i> حالة التوظيف : ' . "$MOF_EMP_STATUS" . ' </span></p>
' ?>
<?php if (get_function_access(56)) { ?>
<?php echo '
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-campground "></i> المشغل : ' . "$TYPE_NAME" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i> تاريخ بداية التصريح (المشغل) : ' . "$PER_DATE_FROM" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i> تاريخ نهاية التصريح (المشغل) : ' . "$PER_DATE_TO " . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i> حالة السماح : ' . "$ALLOW_TO_CROSS_DESC " . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i>رقم الكشف : ' . "$OP_SHEET_NO " . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i>اسم المدخل : ' . "$emp_name_p " . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fab fa-diaspora "></i>تاريخ الادخال  : ' . "$CREATE_DATE " . ' </span></p>

' ?>
<?php } ?>
<?php echo '
</div>
</div>' ?></td>
</td>
</tr>
</tbody>
</table>
<div class="row">
<div class="col-xl-6 col-lg-6">
<?php
if (get_function_access(40)) {
$z_IMP_MADANYA_FINAL_portal_vw = count($IMP_MADANYA_FINAL_portal_vw);
if ($z_IMP_MADANYA_FINAL_portal_vw == 0) {
$SEQ_NO_MADANYA = "";
$SSN_MADANYA = "";
$STATUS_ID_MADANYA = "";
$STATUS_NAME_MADANYA = "";
$LOC_MADANYA = "";
$MOBILE_MADANYA = "";
$FULL_NAME_MADANYA = "";
$ID1_MADANYA = "";
$SOURCE_FILE_ID_MADANYA = "";
$INSERT_DATE_MADANYA = "";
}
?>
<div class="tab-content">
<!--begin::tab 1 content-->
<div class="tab-pane active" id="kt_widget11_tab1_content">
<!--begin::Widget 11-->
<div class="kt-widget11">
<div class="table-responsive">
<table id="tbl_gcc_data" name="tbl_gcc_data" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center">
<tr>
<th colspan="2" style="color: #ffb822 !important;border-bottom-width: 0;">
الردود من طرف هيئة العامة للشؤون المدنية</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th style="width:10%" style="color:#FFF8DD !important">#</th>
<th style="width:50%" style="color:#FFF8DD !important">حالة الرد</th>
<!--<th style="width:40%" style="color:#FFF8DD !important">تاريخ الرد</th>-->
</tr>
</thead>
<tbody>
<?php
if ($z_IMP_MADANYA_FINAL_portal_vw != 0) {
$i = 1;
foreach ($IMP_MADANYA_FINAL_portal_vw as $x) {
$SEQ_NO_MADANYA = $x->SEQ_NO;
$SSN_MADANYA = $x->SSN;
$STATUS_ID_MADANYA = $x->STATUS_ID;
$STATUS_NAME_MADANYA = $x->STATUS_NAME;
$LOC_MADANYA = $x->LOC;
$MOBILE_MADANYA = $x->MOBILE;
$FULL_NAME_MADANYA = $x->FULL_NAME;
$ID1_MADANYA = $x->ID1;
$SOURCE_FILE_ID_MADANYA = $x->SOURCE_FILE_ID;
$INSERT_DATE_MADANYA = $x->INSERT_DATE;
if ($INSERT_DATE_MADANYA != '') {
$INSERT_DATE_MADANYA = date("Y-m-d", strtotime($INSERT_DATE_MADANYA));
} else {
$INSERT_DATE_MADANYA = '-';
}
if ($STATUS_ID_MADANYA == "15") {
$STATUS_NAME_MADANYA_desc = '<span class="kt-badge kt-badge--inline kt-badge--success">' . "$STATUS_NAME_MADANYA" . '</span>';
} else {
$STATUS_NAME_MADANYA_desc = '<span class="kt-badge kt-badge--inline kt-badge--danger">' . "$STATUS_NAME_MADANYA" . '</span>';
}
?>
<tr>
<td style="text-align:center"><?php echo $SEQ_NO_MADANYA ?></td>
<td style="text-align:center"><?php echo  $STATUS_NAME_MADANYA_desc ?></td>
<!--<td style="text-align:center"><?php echo  $INSERT_DATE_MADANYA ?></td>-->
</tr>
<?php }
} else { ?>
<tr colspan="3">
<td colspan="3" style="text-align:center">
لا يوجد بيانات</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
<?php   } ?>
</div>
<div class="col-xl-6 col-lg-6">
<table id="tbl_doffa_point" name="tbl_doffa_point" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="2" style="color: #ffb822 !important;border-bottom-width: 0;">بيانات الدفعه</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>رقم الدفعة</th>
<th>وصف الدفعه</th>
</tr>
</thead>
<tbody>
<?php if ($z_REG_48_PACKAGE_DETAILS_VW != 0) {  ?>
<tr style="text-align:center">
<td> <?php echo $PACKAGE_NO_VW ?></td>
<td> <?php echo $PACKAGE_NAME_VW ?></td>
</tr>
<?php } else { ?>
<tr colspan="2">
<td colspan="2" style="text-align:center">
لا يوجد بيانات</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
<?php
if (get_function_access(77)) {
?>
<div class="row">
<div class="col-xl-12 col-lg-12">
<?php
$z_ALL_LIC_VW = count($ALL_LIC_VW);
if ($z_ALL_LIC_VW == 0) {
$IDC = "";
$PERMIT_TYPE = "";
$START_DATE = "";
$END_DATE = "";
$STATUS = "";
$OLD_START_DATE = "";
$OLD_END_DATE = "";
$INSERT_DATE = "";
}
?>
<div class="tab-content">
<!--begin::tab 1 content-->
<div class="tab-pane active" id="kt_widget11_tab1_content">
<!--begin::Widget 11-->
<div class="kt-widget11">
<div class="table-responsive">
<table id="tbl_lic_vw" name="tbl_lic_vw" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center">
<tr>
<th colspan="6" style="color: #ffb822 !important;border-bottom-width: 0;">كشوفات الحاسوب</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th style="width:5%" style="color:#FFF8DD !important">#</th>
<th style="width:19%" style="color:#FFF8DD !important">نوع التصريح</th>
<th style="width:19%" style="color:#FFF8DD !important">تاريخ بداية التصريح </th>
<th style="width:19%" style="color:#FFF8DD !important">تاريخ نهاية التصريح </th>
<th style="width:19%" style="color:#FFF8DD !important">تاريخ الكشف</th>
<th style="width:19%" style="color:#FFF8DD !important">الحالة</th>
</tr>
</thead>
<tbody>
<?php
if ($z_ALL_LIC_VW != 0) {
$i = 1;
foreach ($ALL_LIC_VW as $x) {
$IDC = $x->IDC;
$PERMIT_TYPE =$x->PERMIT_TYPE;
$START_DATE = $x->START_DATE;
$END_DATE = $x->END_DATE;
$STATUS = $x->STATUS;
$OLD_START_DATE =$x->OLD_START_DATE;
$OLD_END_DATE = $x->OLD_END_DATE;
$INSERT_DATE = $x->INSERT_DATE;
if ($START_DATE != '') {
$START_DATE = date("Y-m-d", strtotime($START_DATE));
} else {
$START_DATE = '-';
}
if ($END_DATE != '') {
$END_DATE = date("Y-m-d", strtotime($END_DATE));
} else {
$END_DATE = '-';
}
?>
<tr>
<td style="text-align:center"><?php echo $i ?></td>
<td style="text-align:center"><?php echo $PERMIT_TYPE ?></td>
<td style="text-align:center"><?php echo  $OLD_START_DATE ?></td>
<td style="text-align:center"><?php echo  $OLD_END_DATE ?></td>
<td style="text-align:center"><?php echo  $INSERT_DATE ?></td>
<td style="text-align:center"><?php echo  $STATUS ?></td>
</tr>
<?php $i++; }
} else { ?>
<tr colspan="6">
<td colspan="6" style="text-align:center">
لا يوجد بيانات</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<?php   } ?>
<div class="row" >
<div class="col-xl-12 col-lg-12" >
  <div class="col-lg-3" style="display:none">
                                <div class="form-group">
                                <label class="kt-font-danger kt-font-bolder">عرض رسائل sms المرسلة</label>
                                    <div class="input-group">
                                        <input data-switch="true" type="checkbox" id='store_state' name='store_state' class="alert-status" checked
    data-on-text="نعم" data-handle-width="200" onchange="show_store_main_div(<?php echo $WORKER_ID_SMS ?>);"
                                            data-off-text="لا"
                                            data-on-color="info" data-off-color="danger">
                                    </div>
                                </div>
                            </div>
<div class="tab-content" id="sms_archive">
<!--begin::tab 1 content-->
</div>
</div>
</div>
<?php } else  if ($TAB_ID == 2) { ?>
<table id="tbl_history_data_all" name="tbl_history_data_all" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#eff2f7;background-color:#343a40;text-align:center">
<tr>
<th style="width:50% !important;">السجل المدني</th>
<th style="width:50% !important;">بيانات الاتصال</th>
</tr>
</thead>
<tbody>
<?php
if (get_function_access(39)) {
if ($CHK_VALUE_BANNED == "1") {
$USER_DEP_FULL_NAME = '<span class="kt-font-bolder kt-font-success">' . "$USER_DEP_FULL_NAME" . '</span> <i class="fa fa-user-check kt-font-success "></i>';
} else if ($CHK_VALUE_BANNED == "0") {
$USER_DEP_FULL_NAME = '<span class="kt-font-bolder kt-font-danger">' . "$USER_DEP_FULL_NAME" . '</span> <i class="fa fa-user-times kt-font-danger"></i>';
} else if ($CHK_VALUE_BANNED == '' || $CHK_VALUE_BANNED == NULL) {
$USER_DEP_FULL_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$USER_DEP_FULL_NAME" . '</span> <i class="fa  fa-hourglass-end  kt-font-blue"></i>';
} else {
$USER_DEP_FULL_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$USER_DEP_FULL_NAME" . '</span> <i class="fa  fa-hourglass-end  kt-font-blue"></i>';
}
} else {
$USER_DEP_FULL_NAME = '<span class="kt-font-bolder kt-font-success">' . "$USER_DEP_FULL_NAME" . '</span>';
}
$IDNO = '<span class="kt-font-bolder kt-font-blue">' . "$IDNO" . '</span>';
$User_chided_Num = '  <span id="count_div_elec2' . $User_chided_Num . '"  name="count_div_elec2' . $User_chided_Num . '"  class="kt-badge kt-badge--outline kt-badge--outline-2x kt-badge--blue">' . "$User_chided_Num" . '</span>';
$SEX = '<span class="kt-font-bolder kt-font-blue">' . "$SEX" . '</span>';
$state_live = '<span class="kt-font-bolder kt-font-blue">' . "$state_live" . '</span>';
$BIRTH_DT = '<span class="kt-font-bolder kt-font-blue">' . "$BIRTH_DT" . '</span>';
$DETH_DT = '<span class="kt-font-bolder kt-font-danger">' . "$DETH_DT" . '</span>';
$BIRTH_PMAIN = '<span class="kt-font-bolder kt-font-blue">' . "$BIRTH_PMAIN" . '</span>';
$CI_RELIGION = '<span class="kt-font-bolder kt-font-blue">' . "$CI_RELIGION" . '</span>';
$SOCIAL_STATUS = '<span class="kt-font-bolder kt-font-blue">' . "$SOCIAL_STATUS" . '</span>';
$CI_REGION = '<span class="kt-font-bolder kt-font-blue">' . "$CI_REGION" . '</span>';
$MAIN_MOBILE_NO = '<span class="kt-font-bolder kt-font-blue">' . "$MAIN_MOBILE_NO" . '</span>';
$ALTER_MOBILE_NO = '<span class="kt-font-bolder kt-font-blue">' . "$ALTER_MOBILE_NO" . '</span>';
$USERMOBILE = '<span class="kt-font-bolder kt-font-blue">' . "$USERMOBILE" . '</span>';
$USERTELEPHONE = '<span class="kt-font-bolder kt-font-blue">' . "$USERTELEPHONE" . '</span>';
$USEREMAIL = '<span class="kt-font-bolder kt-font-blue">' . "$USEREMAIL" . '</span>';
$GOV_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$GOV_NAME" . '</span>';
$CITY_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$CITY_NAME" . '</span>';
$PART_NAME = '<span class="kt-font-bolder kt-font-blue">' . "$PART_NAME" . '</span>';
$ADDRESS_DET = '<span class="kt-font-bolder kt-font-blue">' . "$ADDRESS_DET" . '</span>';
$DISA_TYPE = '<span class="kt-font-bolder kt-font-blue">' . "$DISA_TYPE" . '</span>';
?>
<tr>
<td style="width:50% !important;"> <?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-user"></i> الاسم : ' . "$USER_DEP_FULL_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-address-card"></i> رقم الهوية : ' . "$IDNO" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-venus-double "></i> الجنس : ' . "$SEX" . ' </span></p>
<p>  <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-ambulance"></i> الحالة : ' . "$state_live" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-birthday-cake "></i> تاريخ الميلاد : ' . "$BIRTH_DT" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-book-dead "></i> تاريخ الوفاة : ' . "$DETH_DT" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-flag-checkered "></i> مكان الميلاد  : ' . "$BIRTH_PMAIN" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-moon  "></i> الديانة  : ' . "$CI_RELIGION" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-mars-double "></i> الحالة الاجتماعية  : ' . "$SOCIAL_STATUS" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-neuter "></i> المنطقة  : ' . "$CI_REGION" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-location-arrow "></i> المدينة  : ' . "$CI_CITY" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa   fa-users by"></i> عدد الابناء : ' . "$User_chided_Num" . '</span></p>
</div>
</div>' ?></td>
<td style="width:50% !important;"> <?php echo '<div class="kt-user-card-v2">
<div class="kt-user-card-v2__details">
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-mobile-alt"></i> رقم الجوال : ' . "$MAIN_MOBILE_NO" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-phone"></i> الجوال الإضافي : ' . "$ALTER_MOBILE_NO" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-phone-square"></i> رقم الجوال في التسجيل الموحد : ' . "$USERMOBILE" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="flaticon2-phone"></i> رقم التلفون : ' . "$USERTELEPHONE" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-envelope"></i> الايميل :' . "$USEREMAIL" . ' </span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa  fa-map-marker-alt"></i> المحافظة : ' . "$GOV_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-hourglass-start"></i> المدينة : ' . "$CITY_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-flag-checkered"></i> مصدر المعلومة : ' . "$PART_NAME" . '</span></p>
<p> <span class="kt-user-card-v2__name kt-font-bolder kt-font-dark"><i class="fa fa-check-square"></i> اسم المعلم :' . "$ADDRESS_DET  " . ' </span></p>
</div>
</div>' ?></td>
</tr>
</tbody>
</table>
<?php if ($z_WORK_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_work_data" name="tbl_work_data" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات العمل من الحاسوب الحكومي</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>الرقم الوظيفي </th>
<th>الوزارة</th>
<th>المهنة</th>
<th> سنوات الخبرة </th>
<th> الوحدة التابع لها في الوزراة </th>
<th> تاريخ بداية العمل </th>
<th> تاريخ نهاية العمل </th>
<th>الفئة</th>
<th>الدرجة</th>
<th> حالة الموظف </th>
<th> الحالة العملية للموظف </th>
<th> الحالة الحالية للموظف</th>
<th> مصدر المعلومة</th>
</tr>
</thead>
<tbody>
<?php if ($z_WORK_DATA_REC != 0) {
$i = 1;
foreach ($WORK_DATA_REC as $res_work) {
$EMP_NO = $res_work['EMP_NO'];
$EMP_DOC_ID = $res_work['EMP_DOC_ID'];
$MINISTRY_CD = $res_work['MINISTRY_CD'];
$MINISTRY_NAME = $res_work['MINISTRY_NAME'];
$JOB_START_DT = $res_work['JOB_START_DT'];
$CLASS_CD = $res_work['CLASS_CD'];
$CLASS_NAME = $res_work['CLASS_NAME'];
$DEGREE_CD = $res_work['DEGREE_CD'];
$DEGREE_NAME = $res_work['DEGREE_NAME'];
$EMP_STATE_CD = $res_work['EMP_STATE_CD'];
$EMP_STATE_DESC = $res_work['EMP_STATE_DESC'];
$EMP_WORK_STATUS_CD = $res_work['EMP_WORK_STATUS_CD'];
$EMP_WORK_STATUS = $res_work['EMP_WORK_STATUS'];
$EMP_STATE_NOW = $res_work['EMP_STATE_NOW'];
$EMP_STATE_NOW_TXT = $res_work['EMP_STATE_NOW_TXT'];
$UNIT_NEW_CD = $res_work['UNIT_NEW_CD'];
$MINISTRY_UNIT_NAME = $res_work['MINISTRY_UNIT_NAME'];
$MIN_DATA_SOURCE_CD = $res_work['MIN_DATA_SOURCE_CD'];
$MIN_DATA_SOURCE = $res_work['MIN_DATA_SOURCE'];
$JOB_CD = $res_work['JOB_CD'];
$JOB_DESC = $res_work['JOB_DESC'];
$END_DATE = $res_work['END_DATE'];
$WORK_YEARS = $res_work['WORK_YEARS'];
$EMP_BIRTH_DT = $res_work['EMP_BIRTH_DT'];
$AGE = $res_work['AGE'];
$CI_PERSONAL_CD = $res_work['CI_PERSONAL_CD'];
$CI_PERSONAL_DESC = $res_work['CI_PERSONAL_DESC'];
$CI_DEAD_DESC = $res_work['CI_DEAD_DESC'];
$CI_DEAD_DT = $res_work['CI_DEAD_DT'];
$EMP_FULL_NAME = $res_work['EMP_FULL_NAME'];
?>
<tr style="text-align:center">
<td> <?php echo $EMP_NO ?></td>
<td> <?php echo $MINISTRY_NAME ?></td>
<td> <?php echo $JOB_DESC ?></td>
<td> <?php echo $WORK_YEARS ?></td>
<td> <?php echo $MINISTRY_UNIT_NAME ?></td>
<td> <?php echo $JOB_START_DT ?></td>
<td> <?php echo $END_DATE ?></td>
<td> <?php echo $CLASS_NAME ?></td>
<td> <?php echo $DEGREE_NAME ?></td>
<td> <?php echo $EMP_STATE_DESC ?></td>
<td> <?php echo $EMP_WORK_STATUS ?></td>
<td> <?php echo $EMP_STATE_NOW_TXT ?></td>
<td> <?php echo $MIN_DATA_SOURCE ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_MOSA_DISABLED_BYID != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_DISA_MOSA" name="tbl_DISA_MOSA" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="6" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات الاعاقة حسب وزارة التنمية</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>نوع الاعاقة</th>
<th>مصدر المعلومة </th>
</tr>
</thead>
<tbody>
<tr style="text-align:center">
<td> <?php echo $DISA_TYPE ?></td>
<td> <?php echo 'وزارة الشؤون الاجتماعية' ?></td>
</tr>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_GET_AID_RECIP_INFO_BYID != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_aid_point" name="tbl_aid_point" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="6" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات اخر مساعدات اغاثية </th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>اسم المنحة</th>
<th>اسم المؤسسة</th>
<th>نوع المنحة</th>
<th>العملة</th>
<th>المبلغ</th>
<th>تاريخ الاستلام</th>
</tr>
</thead>
<tbody>
<tr style="text-align:center">
<td> <?php echo $SRV_INF_NAME ?></td>
<td> <?php echo $ORG_NM_MON ?></td>
<td> <?php echo $SRV_TYPE_MAIN_NM ?></td>
<td> <?php echo $CURRENCY ?></td>
<td> <?php echo $RECP_AID_AMOUNT ?></td>
<td> <?php echo $RECP_DELV_DT ?></td>
</tr>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_TRAN_DATA_REC != 0) {
   // echo $ARRIVE_FROM_DESC;

if ($CTZN_STATUS_TYPE_CD_FULL == '1') {
$CTZN_STATUS = '<span class="font-bolder kt-font-danger">' . "$CTZN_STATUS_FULL" . '</span>';
$CTZN_TRANS_DT = '<span class="font-bolder kt-font-danger">' . "$CTZN_TRANS_DT_FULL" . '</span>';
$ARRIVE_FROM_DESC = '<span class="font-bolder kt-font-danger">' . "$ARRIVE_FROM_DESC_FULL" . '</span>';
$PASSPORT_NO = '<span class="font-bolder kt-font-danger">' . "$PASSPORT_NO_FULL" . '</span>';
} else {
$CTZN_STATUS = '<span class="font-bolder kt-font-success">' . "$CTZN_STATUS_FULL" . '</span>';
$CTZN_TRANS_DT = '<span class="font-bolder kt-font-success">' . "$CTZN_TRANS_DT_FULL" . '</span>';
$ARRIVE_FROM_DESC = '<span class="font-bolder kt-font-success">' . "$ARRIVE_FROM_DESC_FULL" . '</span>';
$PASSPORT_NO = '<span class="font-bolder kt-font-success">' . "$PASSPORT_NO_FULL" . '</span>';
}
?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_state_point" name="tbl_state_point" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="4" style="color: #ffb822 !important;border-bottom-width: 0;">بيانات التواجد تفصيلي</th>

</tr>
<tr style="background-color: #083da6 !important;">
<th>الحالة</th>
<th>تاريخ الحالة</th>
<th>جهة السفر</th>
<th>رقم الجواز</th>
</tr>
</thead>
<tbody>
<tr style="text-align:center">
<td> <?php echo $CTZN_STATUS; ?></td>
<td> <?php echo $CTZN_TRANS_DT; ?></td>
<td> <?php echo $ARRIVE_FROM_DESC; ?></td>
<td> <?php echo $PASSPORT_NO; ?></td>
</tr>
</tbody>
</table>
</div>
<?php } ?>



<!--
<?php if ($z_TRAN_DATA_REC_PR != 0) {
if ($CTZN_STATUS_TYPE_CD_PR == '1') {
$CTZN_STATUS_PR = '<span class="font-bolder kt-font-danger">' . "$CTZN_STATUS_PR" . '</span>';
$CTZN_TRANS_DT_PR = '<span class="font-bolder kt-font-danger">' . "$CTZN_TRANS_DT_PR" . '</span>';
$ARRIVE_FROM_DESC_PR = '<span class="font-bolder kt-font-danger">' . "$ARRIVE_FROM_DESC_PR" . '</span>';
$PASSPORT_NO_PR = '<span class="font-bolder kt-font-danger">' . "$PASSPORT_NO_PR" . '</span>';
} else {
$CTZN_STATUS_PR = '<span class="font-bolder kt-font-success">' . "$CTZN_STATUS_PR" . '</span>';
$CTZN_TRANS_DT_PR = '<span class="font-bolder kt-font-success">' . "$CTZN_TRANS_DT_PR" . '</span>';
$ARRIVE_FROM_DESC_PR = '<span class="font-bolder kt-font-success">' . "$ARRIVE_FROM_DESC_PR" . '</span>';
$PASSPORT_NO_PR = '<span class="font-bolder kt-font-success">' . "$PASSPORT_NO_PR" . '</span>';
}
?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_state_point" name="tbl_state_point" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="4" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات التواجد</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>الحالة</th>
<th>تاريخ الحالة</th>
</tr>
</thead>
<tbody>
<tr style="text-align:center">
<td> <?php echo $CTZN_STATUS_PR; ?></td>
<td> <?php echo $CTZN_TRANS_DT_PR; ?></td>
</tr>
</tbody>
</table>
</div>
<?php } ?>
-->









<!--   <?php if ($z_TRADE_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_data_point2" name="tbl_data_point2"
class="table table-striped table-bordered table-hover table-checkable order-column">
<thead
style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;" >
بيانات السجل التجاري حسب برنامج التصاريح</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>رقم التسجيل</th>
<th>الاسم</th>
<th>الفاعلية</th>
<th> رقم السجل التجاري </th>
<th> نوع السجل التجاري </th>
<th>اسم الشركة </th>
<th> تاريخ بداية السجل التجاري </th>
<th>التصنيف</th>
<th>العلامة التجارية</th>
<th> المدينة</th>
<th>الحالة العملية </th>
<th> وصف الانطباع</th>
<th> وصف نوع الشركة</th>
</tr>
</thead>
<tbody>
<?php if ($z_TRADE_DATA_REC != 0) {
$i = 1;
foreach ($TRADE_DATA_REC as $res1) {
$PERSON_ID = $res1['PERSON_ID'];
$REGISTER_NO = $res1['REGISTER_NO'];
$PERSON_NAME = $res1['PERSON_NAME'];
$IS_VALID = $res1['IS_VALID'];
$IS_VALID_DESC = $res1['IS_VALID_DESC'];
$REC_CODE = $res1['REC_CODE'];
$REC_TYPE = $res1['REC_TYPE'];
$REC_TYPE_DESC = $res1['REC_TYPE_DESC'];
$COMP_NAME = $res1['COMP_NAME'];
$START_DATE = $res1['START_DATE'];
$WORK_CLASS_ID = $res1['WORK_CLASS_ID'];
$WORK_CLASS_DESC = $res1['WORK_CLASS_DESC'];
$BRAND_NAME = $res1['BRAND_NAME'];
$CITY_ID = $res1['CITY_ID'];
$CITY_DESC = $res1['CITY_DESC'];
$STATUS_ID = $res1['STATUS_ID'];
$STATUS_ID_DESC = $res1['STATUS_ID_DESC'];
$PERSON_TYPE = $res1['PERSON_TYPE'];
$PERSON_TYPE_DESC = $res1['PERSON_TYPE_DESC'];
$COMP_TYPE_ID = $res1['COMP_TYPE_ID'];
$COMP_TYPE_DESC = $res1['COMP_TYPE_DESC'];
?>

<tr style="text-align:center">
<td> <?php echo $REGISTER_NO ?></td>
<td> <?php echo $PERSON_NAME ?></td>
<td> <?php echo $IS_VALID_DESC ?></td>
<td> <?php echo $REC_CODE ?></td>
<td> <?php echo $REC_TYPE_DESC ?></td>
<td> <?php echo $COMP_NAME ?></td>
<td> <?php echo $START_DATE ?></td>
<td> <?php echo $WORK_CLASS_DESC ?></td>
<td> <?php echo $BRAND_NAME ?></td>
<td> <?php echo $CITY_DESC ?></td>
<td> <?php echo $STATUS_ID_DESC ?></td>
<td> <?php echo $PERSON_TYPE_DESC ?></td>
<td> <?php echo $COMP_TYPE_DESC ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>-->
<?php if ($z_TRADE_DATA_REC_ALL != 0 ) {


?>
<table id="tbl_data_point2_ALL" name="tbl_data_point2_ALL" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;">
جميع بيانات السجل التجاري</th>
</tr>
<tr style="background-color: #083da6 !important;">

<th>رقم التسجيل</th>
<th>الاسم</th>
<th>الفاعلية</th>
<th> رقم السجل التجاري </th>
<th> نوع السجل التجاري </th>
<th>اسم الشركة </th>
<th> تاريخ بداية السجل التجاري </th>
<th>التصنيف</th>
<th>العلامة التجارية</th>
<th> المدينة</th>
<th>الحالة العملية </th>
<th> وصف الانطباع</th>
<th> وصف نوع الشركة</th>
</tr>
</thead>
<tbody>
<?php if ($z_TRADE_DATA_REC_ALL != 0) {
$i = 1;
foreach ($TRADE_DATA_REC_ALL as $res1_ALL) {
$PERSON_ID = $res1_ALL['PERSON_ID'];
$REGISTER_NO = $res1_ALL['REGISTER_NO'];
$PERSON_NAME = $res1_ALL['PERSON_NAME'];
$IS_VALID = $res1_ALL['IS_VALID'];
$IS_VALID_DESC = $res1_ALL['IS_VALID_DESC'];
$REC_CODE = $res1_ALL['REC_CODE'];
$REC_TYPE = $res1_ALL['REC_TYPE'];
$REC_TYPE_DESC = $res1_ALL['REC_TYPE_DESC'];
$COMP_NAME = $res1_ALL['COMP_NAME'];
$START_DATE = $res1_ALL['START_DATE'];
$WORK_CLASS_ID = $res1_ALL['WORK_CLASS_ID'];
$WORK_CLASS_DESC = $res1_ALL['WORK_CLASS_DESC'];
$BRAND_NAME = $res1_ALL['BRAND_NAME'];
$CITY_ID = $res1_ALL['CITY_ID'];
$CITY_DESC = $res1_ALL['CITY_DESC'];
$STATUS_ID = $res1_ALL['STATUS_ID'];
$STATUS_ID_DESC = $res1_ALL['STATUS_ID_DESC'];
$PERSON_TYPE = $res1_ALL['PERSON_TYPE'];
$PERSON_TYPE_DESC = $res1_ALL['PERSON_TYPE_DESC'];
$COMP_TYPE_ID = $res1_ALL['COMP_TYPE_ID'];
$COMP_TYPE_DESC = $res1_ALL['COMP_TYPE_DESC'];
$BRAND_NAME = '<span class="font-bolder kt-font-success">' . "$BRAND_NAME" . '</span>';
?>
<tr style="text-align:center">
<td> <?php echo $REGISTER_NO ?></td>
<td> <?php echo $PERSON_NAME ?></td>
<td> <?php echo $IS_VALID_DESC ?></td>
<td> <?php echo $REC_CODE ?></td>
<td> <?php echo $REC_TYPE_DESC ?></td>
<td> <?php echo $COMP_NAME ?></td>
<td> <?php echo $START_DATE ?></td>
<td> <?php echo $WORK_CLASS_DESC ?></td>
<td> <?php echo $BRAND_NAME ?></td>
<td> <?php echo $CITY_DESC ?></td>
<td> <?php echo $STATUS_ID_DESC ?></td>
<td> <?php echo $PERSON_TYPE_DESC ?></td>
<td> <?php echo $COMP_TYPE_DESC ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_AID_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_mosa_point" name="tbl_mosa_point" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات وزارة التنمية الاجتماعية</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>المستفيد</th>
<th>رقم المساعدة</th>
<th>نوع المساعدة</th>
<th>تصنيف المساعدة</th>
<th>فترات المساعدة</th>
<th>مبلغ المساعدة</th>
<th>المصدر</th>
<th>تاريخ الادخال</th>
<th>تاريخ التعديل</th>
<th>تاريخ بداية المساعدة</th>
<th>تاريخ مهاية المساعدة</th>
<th>علاقة المستفيد</th>
<th>وصف ملف المساعدة</th>
</tr>
</thead>
<tbody>
<?php if ($z_AID_DATA_REC != 0) {
$i = 1;
foreach ($AID_DATA_REC as $res_aid) {
$AID_CLASS = $res_aid['AID_CLASS'];
$AID_TYPE = $res_aid['AID_TYPE'];
$AID_AMOUNT = $res_aid['AID_AMOUNT'];
$AID_PERIODIC = $res_aid['AID_PERIODIC'];
$AID_SOURCE = $res_aid['AID_SOURCE'];
$AID_SEQ = $res_aid['AID_SEQ'];
$INSERT_DATE = $res_aid['INSERT_DATE'];
$UPDATE_DATE = $res_aid['UPDATE_DATE'];
$ST_BENEFIT_DATE = $res_aid['ST_BENEFIT_DATE'];
$END_BENEFIT_DATE = $res_aid['END_BENEFIT_DATE'];
$RELATIONSHIP_CD = $res_aid['RELATIONSHIP_CD'];
$RELATIONSHIP = $res_aid['RELATIONSHIP'];
$FILE_STATUS_CD = $res_aid['FILE_STATUS_CD'];
$FILE_STATUS_DESC = $res_aid['FILE_STATUS_DESC'];
$benefit_user = 'وكيل';
$benefit_user = '<span class="font-bolder kt-font-danger">' . "$benefit_user" . '</span>';
if ($RELATIONSHIP_CD == '1' && $FILE_STATUS_CD == '1') {
$benefit_user = 'المستفيد نفسه';
$benefit_user = '<span class="font-bolder kt-font-success">' . "$benefit_user" . '</span>';
}

?>
<tr style="text-align:center">
<td> <?php echo $benefit_user ?></td>
<td> <?php echo $AID_SEQ ?></td>
<td> <?php echo $AID_TYPE ?></td>
<td> <?php echo $AID_CLASS ?></td>
<td> <?php echo $AID_PERIODIC ?></td>
<td> <?php echo $AID_AMOUNT ?></td>
<td> <?php echo $AID_SOURCE ?></td>
<td> <?php echo $INSERT_DATE ?></td>
<td> <?php echo $UPDATE_DATE ?></td>
<td> <?php echo $ST_BENEFIT_DATE ?></td>
<td> <?php echo $END_BENEFIT_DATE ?></td>
<td> <?php echo $RELATIONSHIP ?></td>
<td> <?php echo $FILE_STATUS_DESC ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_CAR_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_car_data" name="tbl_car_data" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="11" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات المركبة</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>رقم السيارة</th>
<th>رقم السابق للسيارة</th>
<th>الشركة المصنعة</th>
<th>تاريخ التسجيل</th>
<th>رقم الشاصي</th>
<th>الموديل</th>
<th>اللون</th>
<th>وقود المركبة</th>
<th>استخدام المركبة</th>
<th>نوع المالك</th>
<th>رقم المحرك</th>
</tr>
</thead>
<tbody>
<?php if ($z_CAR_DATA_REC != 0) {
$i = 1;
foreach ($CAR_DATA_REC as $res_car) {
$CAR_NO = $res_car['CAR_NO'];
$CAR_PREV_NO = $res_car['CAR_PREV_NO'];
$CAR_COMPANY_NAME = $res_car['CAR_COMPANY_NAME'];
$REG_DATE = $res_car['REG_DATE'];
$SHASI_NO = $res_car['SHASI_NO'];
$LICENSE_DATE = $res_car['LICENSE_DATE'];
$MODEL_YEAR = $res_car['MODEL_YEAR'];
$CAR_COLOR_NAME = $res_car['CAR_COLOR_NAME'];
$FUEL_TYPE_NAME = $res_car['FUEL_TYPE_NAME'];
$USING_TYPE_NAME = $res_car['USING_TYPE_NAME'];
$OWNER_TYPE_NAME = $res_car['OWNER_TYPE_NAME'];
$ENGINE_NO = $res_car['ENGINE_NO'];
?>
<tr style="text-align:center">
<td> <?php echo $CAR_NO ?></td>
<td> <?php echo $CAR_PREV_NO ?></td>
<td> <?php echo $CAR_COMPANY_NAME ?></td>
<td> <?php echo $REG_DATE ?></td>
<td> <?php echo $SHASI_NO ?></td>
<td> <?php echo $MODEL_YEAR ?></td>
<td> <?php echo $CAR_COLOR_NAME ?></td>
<td> <?php echo $FUEL_TYPE_NAME ?></td>
<td> <?php echo $USING_TYPE_NAME ?></td>
<td> <?php echo $OWNER_TYPE_NAME ?></td>
<td> <?php echo $ENGINE_NO ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_SPOUSE_WORK_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_work_wife_data" name="tbl_work_data" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات عمل الشريك من الحاسوب الحكومي
</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>الرقم الوظيفي </th>
<th>الوزارة</th>
<th>المهنة</th>
<th> سنوات الخبرة </th>
<th> الوحدة التابع لها في الوزراة </th>
<th> تاريخ بداية العمل </th>
<th> تاريخ نهاية العمل </th>
<th>الفئة</th>
<th>الدرجة</th>
<th> حالة الموظف </th>
<th> الحالة العملية للموظف </th>
<th> الحالة الحالية للموظف</th>
<th> مصدر المعلومة</th>
</tr>
</thead>
<tbody>
<?php if ($z_SPOUSE_WORK_DATA_REC != 0) {
$i = 1;
foreach ($SPOUSE_WORK_DATA_REC as $res_work_supp) {
$EMP_NO = $res_work_supp['EMP_NO'];
$EMP_DOC_ID = $res_work_supp['EMP_DOC_ID'];
$MINISTRY_CD = $res_work_supp['MINISTRY_CD'];
$MINISTRY_NAME = $res_work_supp['MINISTRY_NAME'];
$JOB_START_DT = $res_work_supp['JOB_START_DT'];
$CLASS_CD = $res_work_supp['CLASS_CD'];
$CLASS_NAME = $res_work_supp['CLASS_NAME'];
$DEGREE_CD = $res_work_supp['DEGREE_CD'];
$DEGREE_NAME = $res_work_supp['DEGREE_NAME'];
$EMP_STATE_CD = $res_work_supp['EMP_STATE_CD'];
$EMP_STATE_DESC = $res_work_supp['EMP_STATE_DESC'];
$EMP_WORK_STATUS_CD = $res_work_supp['EMP_WORK_STATUS_CD'];
$EMP_WORK_STATUS = $res_work_supp['EMP_WORK_STATUS'];
$EMP_STATE_NOW = $res_work_supp['EMP_STATE_NOW'];
$EMP_STATE_NOW_TXT = $res_work_supp['EMP_STATE_NOW_TXT'];
$UNIT_NEW_CD = $res_work_supp['UNIT_NEW_CD'];
$MINISTRY_UNIT_NAME = $res_work_supp['MINISTRY_UNIT_NAME'];
$MIN_DATA_SOURCE_CD = $res_work_supp['MIN_DATA_SOURCE_CD'];
$MIN_DATA_SOURCE = $res_work_supp['MIN_DATA_SOURCE'];
$JOB_CD = $res_work_supp['JOB_CD'];
$JOB_DESC = $res_work_supp['JOB_DESC'];
$END_DATE = $res_work_supp['END_DATE'];
$WORK_YEARS = $res_work_supp['WORK_YEARS'];
$EMP_BIRTH_DT = $res_work_supp['EMP_BIRTH_DT'];
$AGE = $res_work_supp['AGE'];
$CI_PERSONAL_CD = $res_work_supp['CI_PERSONAL_CD'];
$CI_PERSONAL_DESC = $res_work_supp['CI_PERSONAL_DESC'];
$CI_DEAD_DESC = $res_work_supp['CI_DEAD_DESC'];
$CI_DEAD_DT = $res_work_supp['CI_DEAD_DT'];
$EMP_FULL_NAME = $res_work_supp['EMP_FULL_NAME'];
?>
<tr style="text-align:center">
<td> <?php echo $EMP_NO ?></td>
<td> <?php echo $MINISTRY_NAME ?></td>
<td> <?php echo $JOB_DESC ?></td>
<td> <?php echo $WORK_YEARS ?></td>
<td> <?php echo $MINISTRY_UNIT_NAME ?></td>
<td> <?php echo $JOB_START_DT ?></td>
<td> <?php echo $END_DATE ?></td>
<td> <?php echo $CLASS_NAME ?></td>
<td> <?php echo $DEGREE_NAME ?></td>
<td> <?php echo $EMP_STATE_DESC ?></td>
<td> <?php echo $EMP_WORK_STATUS ?></td>
<td> <?php echo $EMP_STATE_NOW_TXT ?></td>
<td> <?php echo $MIN_DATA_SOURCE ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_SPOUSE_CAR_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_car_data_wife" name="tbl_car_data_wife" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="11" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات المركبة للشــريـك</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>رقم السيارة</th>
<th>رقم السابق للسيارة</th>
<th>الشركة المصنعة</th>
<th>تاريخ التسجيل</th>
<th>رقم الشاصي</th>
<th>الموديل</th>
<th>اللون</th>
<th>وقود المركبة</th>
<th>استخدام المركبة</th>
<th>نوع المالك</th>
<th>رقم المحرك</th>
</tr>
</thead>
<tbody>
<?php if ($z_SPOUSE_CAR_DATA_REC != 0) {
$i = 1;
foreach ($SPOUSE_CAR_DATA_REC as $res_car_wife) {
$CAR_NO = $res_car_wife['CAR_NO'];
$CAR_PREV_NO = $res_car_wife['CAR_PREV_NO'];
$CAR_COMPANY_NAME = $res_car_wife['CAR_COMPANY_NAME'];
$REG_DATE = $res_car_wife['REG_DATE'];
$SHASI_NO = $res_car_wife['SHASI_NO'];
$LICENSE_DATE = $res_car_wife['LICENSE_DATE'];
$MODEL_YEAR = $res_car_wife['MODEL_YEAR'];
$CAR_COLOR_NAME = $res_car_wife['CAR_COLOR_NAME'];
$FUEL_TYPE_NAME = $res_car_wife['FUEL_TYPE_NAME'];
$USING_TYPE_NAME = $res_car_wife['USING_TYPE_NAME'];
$OWNER_TYPE_NAME = $res_car_wife['OWNER_TYPE_NAME'];
$ENGINE_NO = $res_car_wife['ENGINE_NO'];
?>
<tr style="text-align:center">
<td> <?php echo $CAR_NO ?></td>
<td> <?php echo $CAR_PREV_NO ?></td>
<td> <?php echo $CAR_COMPANY_NAME ?></td>
<td> <?php echo $REG_DATE ?></td>
<td> <?php echo $SHASI_NO ?></td>
<td> <?php echo $MODEL_YEAR ?></td>
<td> <?php echo $CAR_COLOR_NAME ?></td>
<td> <?php echo $FUEL_TYPE_NAME ?></td>
<td> <?php echo $USING_TYPE_NAME ?></td>
<td> <?php echo $OWNER_TYPE_NAME ?></td>
<td> <?php echo $ENGINE_NO ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<?php if ($z_SPOUSE_AID_DATA_REC != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_mosa_point_wife" name="tbl_mosa_point_wife" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="13" style="color: #ffb822 !important;border-bottom-width: 0;">
بيانات وزارة التنمية الاجتماعية للشريك </th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>المستفيد</th>
<th>رقم المساعدة</th>
<th>نوع المساعدة</th>
<th>تصنيف المساعدة</th>
<th>فترات المساعدة</th>
<th>مبلغ المساعدة</th>
<th>المصدر</th>
<th>تاريخ الادخال</th>
<th>تاريخ التعديل</th>
<th>تاريخ بداية المساعدة</th>
<th>تاريخ مهاية المساعدة</th>
<th>علاقة المستفيد</th>
<th>وصف ملف المساعدة</th>

</tr>
</thead>
<tbody>
<?php if ($z_SPOUSE_AID_DATA_REC != 0) {
$i = 1;
foreach ($SPOUSE_AID_DATA_REC as $res_aid) {
$AID_CLASS = $res_aid['AID_CLASS'];
$AID_TYPE = $res_aid['AID_TYPE'];
$AID_AMOUNT = $res_aid['AID_AMOUNT'];
$AID_PERIODIC = $res_aid['AID_PERIODIC'];
$AID_SOURCE = $res_aid['AID_SOURCE'];
$AID_SEQ = $res_aid['AID_SEQ'];
$INSERT_DATE = $res_aid['INSERT_DATE'];
$UPDATE_DATE = $res_aid['UPDATE_DATE'];
$ST_BENEFIT_DATE = $res_aid['ST_BENEFIT_DATE'];
$END_BENEFIT_DATE = $res_aid['END_BENEFIT_DATE'];
$RELATIONSHIP_CD = $res_aid['RELATIONSHIP_CD'];
$RELATIONSHIP = $res_aid['RELATIONSHIP'];
$FILE_STATUS_CD = $res_aid['FILE_STATUS_CD'];
$FILE_STATUS_DESC = $res_aid['FILE_STATUS_DESC'];
$benefit_user = 'وكيل';
$benefit_user = '<span class="font-bolder kt-font-danger">' . "$benefit_user" . '</span>';
if ($RELATIONSHIP_CD == '1' && $FILE_STATUS_CD == '1') {
$benefit_user = 'المستفيد نفسه';
$benefit_user = '<span class="font-bolder kt-font-success">' . "$benefit_user" . '</span>';
}
?>
<tr style="text-align:center">
<td> <?php echo $benefit_user ?></td>
<td> <?php echo $AID_SEQ ?></td>
<td> <?php echo $AID_TYPE ?></td>
<td> <?php echo $AID_CLASS ?></td>
<td> <?php echo $AID_PERIODIC ?></td>
<td> <?php echo $AID_AMOUNT ?></td>
<td> <?php echo $AID_SOURCE ?></td>
<td> <?php echo $INSERT_DATE ?></td>
<td> <?php echo $UPDATE_DATE ?></td>
<td> <?php echo $ST_BENEFIT_DATE ?></td>
<td> <?php echo $END_BENEFIT_DATE ?></td>
<td> <?php echo $RELATIONSHIP ?></td>
<td> <?php echo $FILE_STATUS_DESC ?></td>
</tr>
<?php }
} ?>
</tbody>
</table>
</div>
<?php } ?>
<!--
<div class="col-xl-12 col-lg-12">

<table id="tbl_data_point" name="tbl_data_point"
class="table table-striped table-bordered table-hover table-checkable order-column">
<thead
style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="11" style="color: #ffb822 !important;border-bottom-width: 0;" >
فحص البيانات من الحكومية</th>
</tr>
<tr style="background-color: #083da6 !important;">

<th>رقم العمر</th>
<th>عدد الابناء</th>
<th>out_gaza</th>
<th>out_gaza_dt</th>
<th>have_trade</th>
<th>spouse_work_stat</th>
<th>emp_prog_2017 </th>
<th>start_dt</th>
<th>end_dt</th>
<th> month_cnt</th>
<th> aid</th>

</tr>
</thead>
<tbody>
<tr style="text-align:center">

<td> <?php echo $check_status['age']; ?></td>
<td> <?php echo $check_status['child']; ?></td>
<td> <?php echo $check_status['out_gaza']; ?></td>
<td> <?php echo $check_status['out_gaza_dt']; ?></td>
<td> <?php echo $check_status['have_trade']; ?></td>
<td> <?php echo $check_status['spouse_work_stat']; ?></td>
<td> <?php echo $check_status['emp_prog_2017']; ?></td>
<td> <?php echo $check_status['start_dt']; ?></td>
<td> <?php echo $check_status['end_dt']; ?></td>
<td> <?php echo $check_status['month_cnt']; ?></td>
<td> <?php echo $check_status['aid']; ?></td>

</tr>
</tbody>
</table>
</div>-->
<?php if ($z_GET_MOSA_DATA_BYID != 0) {  ?>
<div class="col-xl-12 col-lg-12">
<table id="tbl_mosa_order" name="tbl_mosa_order" class="table table-striped table-bordered table-hover table-checkable order-column">
<thead style="color:#fff;background-color:#343a40;text-align:center;border-bottom:0 !important">
<tr>
<th colspan="6" style="color: #ffb822 !important;border-bottom-width: 0;">
طلبات وزارة التنمية الاجتماعية</th>
</tr>
<tr style="background-color: #083da6 !important;">
<th>رقم الهوية</th>
<th>اسم</th>
<th>هوية رب الاسرة</th>
<th>القرابة</th>
<th>اعاقة</th>
<th>نوع الطلب</th>
</tr>
</thead>
<tbody>
<tr style="text-align:center">
<td> <?php echo $CIT_NAME ?></td>
<td> <?php echo $ID_RAB_OSRA ?></td>
<td> <?php echo $REL_NAME ?></td>
<td> <?php echo $NRELATIONSHIP ?></td>
<td> <?php echo $NDISABLED ?></td>
<td> <?php echo $TYPE_APPLICATION_DESC ?></td>
</tr>
</tbody>
</table>
</div>
<?php } ?>
<?php } ?>
<script type="text/javascript">
function show_store_main_div(WORKER_ID_SMS) {
    var store_state = $('#store_state:checked').length;
    if (store_state == 1) {
      //  $('#store_main_div').css('display', '');
      view_all_sms_arcive(WORKER_ID_SMS);
    } else {
        $('#sms_archive').html('');
    }
}
function view_all_sms_arcive(WORKER_ID_SMS) {
    $.ajax({
     'url': 'all_sms_arcive',
        async: false,
        'type': 'POST',
        'data': {
           WORKER_ID_SMS:WORKER_ID_SMS,
            'csrf_test_name': Cookies.get('csrf_cookie_name')
        },
        beforeSend: function () {
            load_message();
        },
        complete: function () {
            unload_message();
        },
        'success': function (data) {
            var container = $('#sms_archive');
            if (data) {
                container.html(data);
                unload_message();
            }
        }
    });
}
show_store_main_div(<?php echo $WORKER_ID_SMS?>);
</script>
