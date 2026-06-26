<?php
if(count($citzn_info) > 0){
  /*+"idno": "413346578"
    +"fname_arb": "مهند"
    +"sname_arb": "ابراهيم"
    +"tname_arb": "محمود"
    +"lname_arb": "السميري"
    +"mother_arb": "فوزيه"
    +"prev_lname_arb": null
    +"deth_dt": null
    +"eng_name": "MOHANAD IBRAHIM MAHMOUD ALSEMEIRI"
    +"birth_dt": "12/09/1986"
    +"street_arb": "-"
    +"sex_cd": "1"
    +"social_status_cd": "20"
    +"region_cd": "869"
    +"city_cd": "918"
    +"religion_cd": "20"
    +"birth_main_cd": "60"
    +"birth_sub_cd": null
    +"sex": "ذكر"
    +"social_status": "متزوج"
    +"ci_region": "شمال غزة"
    +"ci_city": "بيت حانون"
    +"ci_religion": "مسلم"
    +"birth_pmain": "الســــعودية"
    +"birth_psub": null*/



foreach ($citzn_info as $x) {
$idno = $x->idno;
$full_name = $x->fname_arb . ' ' . $x->sname_arb . ' ' . $x->tname_arb . ' ' . $x->lname_arb;
$FNAME_ARB=$x->fname_arb ;
$SNAME_ARB=$x->sname_arb ;
$TNAME_ARB=$x->tname_arb ;
$LNAME_ARB=$x->lname_arb ;
$eng_name = $x->eng_name;
$mother_arb = $x->mother_arb;
$prev_lname_arb = $x->prev_lname_arb;
$deth_dt = $x->deth_dt;
$birth_dt = $x->birth_dt;
$street_arb = $x->street_arb;
$sex_cd = $x->sex_cd;
$social_status_cd = $x->social_status_cd;
$region_cd= $x->region_cd;
$city_cd= $x->city_cd;
$religion_cd= $x->religion_cd;
$birth_main_cd= $x->birth_main_cd;
$birth_sub_cd= $x->birth_sub_cd;
$sex= $x->sex;
$social_status= $x->social_status;
$ci_region= $x->ci_region;
$ci_city= $x->ci_city;

$birth_pmain= $x->birth_pmain;
$birth_psub= $x->birth_psub;
$ci_religion= $x->ci_religion;





}
}
else{
$idno = '';
$full_name = '';
$FNAME_ARB= '';
$SNAME_ARB= '';
$TNAME_ARB= '';
$LNAME_ARB= '';
$eng_name = '';
$mother_arb ='';
$prev_lname_arb ='';
$deth_dt ='';
$birth_dt ='';
$street_arb ='';
$sex_cd = '';
$social_status_cd = '';
$region_cd = '';
$city_cd= '';
$religion_cd= '';
$birth_main_cd= '';
$birth_sub_cd= '';
$sex= '';
$social_status= '';
$ci_region= '';
$ci_city= '';
$birth_pmain= '';
$birth_psub= '';
$ci_religion=  '';

}



?>







<div class="card-header cursor-pointer">
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">البيانات الاساسية</h3>
    </div>
    <!--	<a href="../../demo1/dist/account/settings.html" class="btn btn-primary align-self-center">Edit Profile</a>-->
</div>
<div class="card-body p-9">


    <!--<div class="row mb-7">-->
    <!--	<label class="col-lg-4 fw-bold text-muted">الاسم الرباعي</label>-->
    <!--	<div class="col-lg-8">-->
    <!--		<span class="fw-bolder fs-6 text-gray-800"><?php echo $full_name ?></span>-->
    <!--	</div>-->
    <!--</div>-->







    <div class="row mb-10">

        <div class="row col-md-6 mb-5">

            <label class="col-lg-4 fw-bolder text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> الاسم الرباعي :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $full_name ?></span>
            </div>

        </div>

        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bolder text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i>ُ English Name :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $eng_name ?></span>
            </div>

        </div>
    </div>





    <div class="row mb-10">

        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bolder text-info"> <i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i>
                اسم الام :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $mother_arb ?></span>
            </div>

        </div>

        <div class="row col-md-6 mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> الجنس :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $sex ?></span>
            </div>

        </div>

    </div>





    <div class="row mb-10">

        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bold text-info"> <i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i>تاريخ الميلاد :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $birth_dt ?></span>
            </div>

        </div>


        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> مكان الميلاد :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $birth_pmain ?></span>
            </div>

        </div>

    </div>







    <div class="row mb-10">

        <div class="row col-md-6 mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> الحالة الاجتماعية :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $social_status ?></span>
            </div>

        </div>

        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> الديانة :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $ci_religion ?></span>
            </div>

        </div>
    </div>




    <div class="row mb-10">

        <div class=" row col-md-6  mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> المحافظة :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $ci_region ?></span>
            </div>

        </div>


        <div class="row col-md-6 mb-5">

            <label class="col-lg-4 fw-bold text-info"><i class="far fa-arrow-alt-circle-left fa-fw text-dark"></i> المدينة :</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?php echo $ci_city ?></span>
            </div>

        </div>


    </div>





    <!-- <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted"> تاريخ الميلاد</label>
        <div class="col-lg-8 fv-row">
            <span class="fw-bold text-gray-800 fs-6"><?php echo $birth_dt ?></span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">اسم الام
            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title=""
                data-bs-original-title="Phone number must be active"
                aria-label="Phone number must be active"></i></label>
        <div class="col-lg-8 d-flex align-items-center">
            <span class="fw-bolder fs-6 text-gray-800 me-2"><?php echo $mother_arb ?></span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted"> الجنس</label>
        <div class="col-lg-8">
            <a href="#" class="fw-bold fs-6 text-gray-800 text-hover-primary"><?php echo $sex ?></a>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted"> الحالة الاجتماعية
            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title=""
                data-bs-original-title="Country of origination" aria-label="Country of origination"></i></label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800"><?php echo $social_status?></span>
        </div>
    </div>
    <div class="row mb-7">
        <label class="col-lg-4 fw-bold text-muted">المحافظة</label>
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800"><?php echo $ci_region?></span>
        </div>
    </div>
    <div class="row mb-10">
        <label class="col-lg-4 fw-bold text-muted">المدينة</label>
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800"><?php echo $ci_city?></span>
        </div>
    </div> -->




    <!--	<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
											<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black"></rect>
													<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="black"></rect>
													<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="black"></rect>
												</svg>
											</span>

											<div class="d-flex flex-stack flex-grow-1">
												<div class="fw-bold">
													<h4 class="text-gray-900 fw-bolder">We need your attention!</h4>
													<div class="fs-6 text-gray-700">Your payment was declined. To start using tools, please
													<a class="fw-bolder" href="../../demo1/dist/account/billing.html">Add Payment Method</a>.</div>
												</div>
											</div>
										</div>-->
</div>
