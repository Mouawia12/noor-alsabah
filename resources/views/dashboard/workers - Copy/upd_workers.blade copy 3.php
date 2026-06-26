<style type="text/css">
    .select2-selection__arrow b {
        display: none !important;
    }

    .tox-notifications-container {
        display: none !important;
    }
</style>





<form id="upd_workers_data" name="upd_workers_data" class="form" action="{{ route('dashboard.workers.updstore') }}"
    method="post" enctype="multipart/form-data" autocomplete="off">
    @csrf


    <input name="worker_id_db" id="worker_id_db" value="{{ $workers->worker_id }}" im-insert="true"
        data-inputmask="'alias' : 'integer' " type="text" style="display:none"
        class="form-control kt-font-dark kt-font-bolder " readonly placeholder="worker_id_db"
        aria-describedby="basic-addon1">





    <input name="avatar_db" id="avatar_db" value="{{ $workers->avatar }}" im-insert="true" type="text"
        style="display:none" class="form-control kt-font-dark kt-font-bolder " readonly placeholder="avatar_db"
        aria-describedby="basic-addon1">








    <!--begin::Layout-->
    <div class="d-flex flex-column flex-lg-row">

        <!--begin::Content-->

        <div class="flex-lg-row-fluid mb-10 mb-lg-0 ">


            <div class="card">
                <div class="card-body ">
                    <div class="alert alert-dismissible   d-flex flex-column flex-sm-row w-100 p-5 mb-6"
                        id="errorBox_worker" style="display: none !important">
                        <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path opacity="0.3"
                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                    fill="black"></path>
                                <path
                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                        <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                            <span id="displayErrors_worker" class="mb-2  fw-bolder text-light"></span>
                        </div>
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            data-bs-dismiss="alert">
                            <span class="svg-icon svg-icon-2x svg-icon-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                        rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="black"></rect>
                                </svg>
                            </span>
                        </button>
                    </div>


                    <div class="alert alert-dismissible bg-success d-flex flex-column flex-sm-row w-100 p-5 mb-6"
                        id="successBox_worker" style="display: none !important">
                        <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path opacity="0.3"
                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                    fill="black"></path>
                                <path
                                    d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                        <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                            <h4 class="mb-2 text-light">نجاح</h4>
                            <span id="displaysuccess_worker"></span>
                        </div>
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            data-bs-dismiss="alert">
                            <span class="svg-icon svg-icon-2x svg-icon-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                        rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2"
                                        rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="mb-0">
                        <div class="row gx-5 mb-5">
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">صورة الشخصية للعامل</label>
                                <div class="col-lg-8">
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                        style="background-image: url(/assets/media/avatars/blank.png)">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="background-image: url( {{ $workers->avatar }})"></div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            data-bs-dismiss="click" title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>

                                            <input type="file" name="avatar" accept="image/*" />
                                            <input type="hidden" name="avatar_remove" />
                                        </label>

                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            data-bs-dismiss="click" title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            data-bs-dismiss="click" title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">نوع المسموح: png, jpg, jpeg.</div>
                                </div>
                            </div>
                           <div class=" col-12 col-lg-4 col-md-12 col-sm-12 mb-5"><label for="worker_name"
                                    class="form-label required fs-6 fw-bold text-dark mb-3">اسم العامل</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-tools fa-fw text-dark"></i></span></div><input
                                        type="text" name="worker_name" id="worker_name"
                                        value="{{ $workers->worker_name }}" class="form-control fw-bold  text-dark"
                                        placeholder="اسم العامل" autocomplete="off">
                                </div>
                            </div>
                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="dob" class="form-label required fs-6 fw-bold text-dark mb-3"> تاريخ
                                    الميلاد :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="far fa-calendar-alt fa-fw text-dark"></i></span></div><input
                                        type="text" name="dob" id="dob"
                                        class="form-control fw-bold  text-dark input_date_"
                                        value="{{ $workers->dob }}" placeholder="تاريخ الميلاد" value=""
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="mobile" class="form-label required fs-6 fw-bold text-dark mb-3">رقم هاتف
                                    الموظف</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-phone-volume fa-fw text-dark"></i></span></div><input
                                        type="text" name="mobile" id="mobile" value="{{ $workers->mobile }}"
                                        class="form-control fw-bold text-dark text-info" minlenght="1"
                                        maxlength="20" placeholder="رقم هاتف الموظف ">
                                </div>
                            </div>


                            <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="ssn" class="form-label  fs-6 fw-bold text-dark mb-3">رقم صاحب
                                    العمل</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-mobile-alt fa-fw text-dark"></i></span></div><input
                                        type="text" name="phone" id="phone" value="{{ $workers->phone }}"
                                        class="form-control fw-bold text-dark text-info" minlenght="1"
                                        maxlength="20" placeholder="رقم صاحب العمل">
                                </div>
                            </div>


                            <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="nation_id" class="form-label  fs-6 fw-bold text-dark mb-3">الجنسية</label>
                                <div>
                                    <select class="form-select fw-bold form-select_u  " data-control="select2"
                                        id="nation_id" name="nation_id" dir="rtl" data-placeholder="الجنسية">
                                        <option value="">اختر ..</option>
                                        @foreach ($nation as $x)
                                            <option @selected($workers->nation_id == $x->nation_id) value="{{ $x->nation_id }} ">
                                                {{ $x->nation_name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="passport_no" class="form-label  fs-6 fw-bold text-dark mb-3">رقم
                                    الجواز</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-passport fa-fw text-dark"></i></span></div><input
                                        type="text" name="passport_no" id="passport_no"
                                        value="{{ $workers->passport_no }}"
                                        class="form-control fw-bold text-dark text-info" minlenght="1"
                                        maxlength="50" placeholder="رقم الجواز">
                                </div>
                            </div>


                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="dop" class="form-label  fs-6 fw-bold text-dark mb-3">تاريخ
                                    انتهاء الجواز :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="far fa-calendar-minus fa-fw text-dark"></i></span></div><input
                                        type="text" name="dop" id="dop" value="{{ $workers->dop }}"
                                        class="form-control fw-bold  text-dark input_date_"
                                        placeholder="تاريخ انتهاء الجواز" autocomplete="off">
                                </div>
                            </div>


                            <input name="passportfile_db" id="passportfile_db" value="{{ $workers->passportfile }}"
                                im-insert="true" type="text" style="display:none"
                                class="form-control kt-font-dark kt-font-bolder " readonly
                                placeholder="passportfile_db" aria-describedby="basic-addon1">
                            <div class=" col-12 col-lg-5 col-md-12 col-sm-12 mb-5">
                                <label for="doe" class="form-label  fs-6 fw-bold text-dark mb-3">تحميل
                                    الجواز :</label>
                                <div class="input-group mb-3">
                                    @if ($workers->passportfile)
                                        <a class="btn btn-lg   btn-success  "
                                            style="padding: 0.7rem 1rem !important;border-radius: 0;" target='_new'
                                            href=" {{ $workers->passportfile }}">
                                            <span>
                                                <i class="la  la-cloud-download" style="color:#fff"></i>
                                            </span>
                                        </a>
                                        <a class="btn btn-lg   btn-danger remove"
                                            style="padding: 0.7rem 1rem !important;border-radius: 0;"
                                            onclick="del_file('{{ $workers->worker_id }}','{{ $workers->passportfile }}','passportfile')">
                                            <span>
                                                <i class="fas fa-trash-alt fa-fw " style="color:#fff"></i>
                                            </span>
                                        </a>
                                    @endif
                                    <input class="form-control custom-file-input" type="file" name='passportfile'
                                        id='passportfile'>

                                </div>
                            </div>
                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5 border-success border ">
                                <label class="form-label  fs-6 fw-bold text-danger mb-3">حالة التواجد :</label>
                                <div class=" fv-row fv-plugins-icon-container fv-plugins-bootstrap5-row-invalid">
                                    <div class="d-flex align-items-center mt-3">
                                        <label class="form-check form-check-inline form-check-solid me-5 is-invalid">
                                            <input class="form-check-input" name="inside" id="inside"
                                                @if ($workers->inside) checked @endif type="checkbox"
                                                value="1">
                                            <span class="fw-bold ps-2 fs-6 fw-bold  text-dark">نعم داخل المملكة</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-content border-dark my-10 mb-8"><span class="w-150px fw-bold text-danger">بيانات الإقامة</span></div>
                            <div class="col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="ssn" class="form-label required fs-6 fw-bold text-dark mb-3">رقم
                                    الإقامة</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="far fa-id-card fa-fw text-dark"></i></span></div><input
                                        type="text" name="ssn" id="ssn"
                                        class="form-control fw-bold text-dark text-info"
                                        data-inputmask="'alias' : 'decimal'" value="{{ $workers->ssn }}"
                                        minlenght="1" maxlength="20" placeholder="رقم الإقامة ">
                                </div>
                            </div>
                            <input name="ssnfile_db" id="ssnfile_db" value="{{ $workers->ssnfile }}"
                                im-insert="true" type="text" style="display:none"
                                class="form-control kt-font-dark kt-font-bolder " readonly placeholder="ssnfile_db"
                                aria-describedby="basic-addon1">
                            <div class=" col-12 col-lg-6 col-md-12 col-sm-12 mb-5">
                                <label for="ssnfile" class="form-label  fs-6 fw-bold text-dark mb-3">تحميل
                                    الإقامة :</label>
                                <div class="input-group mb-3">
                                    @if ($workers->ssnfile)
                                        <a class="btn btn-lg   btn-success  "
                                            style="padding: 0.7rem 1rem !important;border-radius: 0;" target='_new'
                                            href=" {{ $workers->ssnfile }}">
                                            <span>
                                                <i class="la  la-cloud-download" style="color:#fff"></i>
                                            </span>
                                        </a>
                                        <a class="btn btn-lg   btn-danger remove"
                                            style="padding: 0.7rem 1rem !important;border-radius: 0;"
                                            onclick="del_file('{{ $workers->worker_id }}','{{ $workers->ssnfile }}','ssnfile')">
                                            <span>
                                                <i class="fas fa-trash-alt fa-fw " style="color:#fff"></i>
                                            </span>
                                        </a>
                                    @endif
                                    <input class="form-control custom-file-input" type="file" name='ssnfile' id='ssnfile'>
                                </div>
                            </div>
                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="dos" class="form-label required fs-6 fw-bold text-dark mb-3">تاريخ
                                    اصدار الاقامة :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-calendar-alt fa-fw text-dark"></i></span></div><input
                                        type="text" name="dos" id="dos" value="{{ $workers->dos }}"
                                        class="form-control fw-bold  text-dark input_date_"
                                        placeholder="تاريخ اصدار الاقامة" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="doe" class="form-label required fs-6 fw-bold text-dark mb-3">تاريخ
                                    إنتهاء الإقامة :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fas fa-calendar-alt fa-fw text-dark"></i></span></div><input
                                        type="text" name="doe" id="doe" value="{{ $workers->doe }}"
                                        class="form-control fw-bold  text-dark input_date_"
                                        placeholder="تاريخ إنتهاء الإقامة" value="" autocomplete="off">
                                </div>
                            </div>


                            <div class="separator separator-content border-dark my-10 mb-8"><span class="w-150px fw-bold text-danger">بيانات العمل</span></div>
                            <div class=" col-12 col-lg-2 col-md-12 col-sm-12 mb-5">
                                <label for="dow" class="form-label  fs-6 fw-bold text-dark mb-3"> تاريخ
                                    التعيين :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="far fa-calendar-alt fa-fw text-dark"></i></span></div><input
                                        type="text" name="dow" id="dow" value="{{ $workers->dow }}"
                                        class="form-control fw-bold  text-dark input_date_"
                                        placeholder="تاريخ التعيين" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-12 col-sm-12 mb-5">
                                <label for="work_place_id"
                                    class="form-label required fs-6 fw-bold text-dark mb-3">مكان العمل</label>
                                <div>
                                    <select class="form-select fw-bold form-select_u " data-control="select2"
                                        id="work_place_id" name="work_place_id" dir="rtl"
                                        data-placeholder="مكان العمل">
                                        <option value="">اختر ..</option>
                                        @foreach ($work_place as $x)
                                            <option @selected($workers->work_place_id == $x->work_place_id) value="{{ $x->work_place_id }}">
                                                {{ $x->work_place_name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 col-md-12 col-sm-12 mb-5">
                                <label for="job_id" class="form-label  fs-6 fw-bold text-dark mb-3">المهنة</label>
                                <div>
                                    <select class="form-select fw-bold form-select_u  " data-control="select2"
                                        id="job_id" name="job_id" dir="rtl" data-placeholder="المهنة">
                                        <option value="">اختر ..</option>
                                        @foreach ($job as $x)
                                            <option @selected($workers->job_id == $x->job_id) value="{{ $x->job_id }} ">
                                                {{ $x->job_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class=" col-12 col-lg-4 col-md-12 col-sm-12 mb-5" id="container_file"
                                name="container_file">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <a type="button" id="add_file"
                                        class="btn btn-secondary kt-font-info kt-font-bolder"
                                        style='border-color:#232b51;'><i class="la la-chain"></i>تحميل أوراق اخرى</a>
                                </div>
                                <br />
                                <?php $z_attac = count($workers_attach);
                                if ($z_attac == 0) {
$workers_attach_id  = "";
$worker_id  = "";
$workers_attach_name= "";
$workers_attach_extension= "";
$workers_attach_url= "";
                                }

                                if ($z_attac != 0) {
                                    $i_att = 1;
                                    foreach ($workers_attach as $x) {
$workers_attach_id = $x->workers_attach_id;
$worker_id = $x->worker_id;
$workers_attach_name= $x->workers_attach_name;
$workers_attach_extension= $x->workers_attach_extension;
$workers_attach_url= $x->workers_attach_url;


?>
<div class="form-group row repeat_emp_<?php echo $i_att ?> ">
    <input type="text" name="image_url_emp[]" id="image_url_emp_<?php echo $i_att ?>"
    value="<?php echo $workers_attach_name ?>"
    class="form-control kt-font-dark kt-font-bolder" style="display:none"
    placeholder="ملف مرفق">
    <input type="text" name="emp_att_id[]" id="emp_att_id_<?php echo $i_att ?>"
    value="<?php echo $workers_attach_id ?>"
    class="form-control kt-font-dark kt-font-bolder" style="display:none"
    placeholder="emp_att_id">
    <?php if ($workers_attach_id != "") { ?>
    <?php } ?>
    <div class="input-group mb-3">
    <div class="custom-file">
        <input type="file" name="files[]"
            class="custom-file-input  kt-font-dark kt-font-bolder"
            placeholder="ملف مرفق"
            id="files_<?php echo $i_att ?>">
        <label class="custom-file-label kt-font-primary kt-font-bolder"
            for="customFile" value="<?php echo $workers_attach_name ?>"
            data-browse="تحميل"><?php echo $workers_attach_extension ?></label>
    </div>
    <div class="input-group-append">
        <a class="btn btn-sm btn-danger btnborder" style="padding: 0.7rem 1rem;"
            onclick="del_issue_attach('<?php echo $workers_attach_name ?>', '<?php echo 'emp' ?>', '<?php echo $workers_attach_id ?>', '<?php echo $i_att ?>', '<?php echo '1' ?>')">
            <span>
                <i class="la la-minus" style="color:#fff"></i>
            </span>
        </a>
        <a class="btn btn-sm btn-success btnborder" style="padding: 0.7rem 1rem;"
            onclick="down_file('<?php echo $workers_attach_name ?>', '<?php echo 'emp' ?>')">
            <span>
                <i class="la  la-cloud-download" style="color:#fff"></i>
            </span>
        </a>
    </div>
    </div>
    </div>
    <?php $i_att++;
    } }
    else{ ?>

                                <div class="form-group row">
                                    <div class="input-group ">
                                        <div class="form-control ">
                                            <input type="file" class="form-control custom-file-input"
                                                placeholder="ملف مرفق" name="files[]" multiple>
                                        </div>
                                        <div class="input-group-append" style="padding: 0.7rem 1rem;">
                                            <a class="btn btn-lg btn-danger remove" style="padding: 0.7rem 1rem;">
                                                <span>
                                                    <i class="la la-minus" style="color:#fff"></i>
                                                </span>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <?php } ?>

                            </div>


                            <div class=" col-12 col-lg-12 col-md-12 col-sm-12  mb-5">
                                <label for="note" class="  form-label fs-6 fw-bold text-dark mb-3">ملاحظة
                                </label>
                                <textarea name="note" rows="1" class="form-control fw-bold" id="note" placeholder="ملاحظة">{{ $workers->note }}</textarea>
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Actions-->
                        <div class="text-center mb-0  ">
                            <button type="submit" id="kt_docs_submitsss"
                                class="btn btn-primary font-weight-bold mr-2" name="submitButton">حفظ
                                البيانات</button>
                            <div class="overlay-layer bg-dark bg-opacity-5" id='wait_block'
                                style="display: none !important">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@section('styles')
    <style>
        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-bottom: 2px;
        }
    </style>
    <script type="text/javascript" src="{{ asset('assets/module/woker_j.js') }}?t={{ config('global.ver.version_all') }}"></script>
    <script>
        $('.input_date_').flatpickr({
            format: 'dd-mm-yyyy',
            "locale": "ar",
        });
        $('#add_file').on('click', function() {
            var newfield =
                '<div class="form-group row repeat"><div class="input-group "><div class="form-control custom-file"><input type="file" class="form-control custom-file-input" name="files[]" ></div><div class="input-group-append" style="padding: 0.7rem 1rem;"><a class="btn btn-lg btn-danger remove"  ><span><i class="la la-minus" style="color:#fff"></i></span></a></div></div></div>';
            $('#container_file').append(newfield);
        });
        $(document).on('click', '.remove', function() {
            $(this).parent().parent().parent('div').remove();
        });
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val();
            if (fileName.length > 23) {
                fileName = fileName.substr(0, 11) + "..." + fileName.substr(-10);
            }
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        $(document).ready(function() {
            $(".form-select_u").select2({
                dropdownParent: $('#view_prim_const_m .modal-content')
            });
        });

        function del_file(worker_id, ssnfile_url, type) {
            swal.fire({
                text: 'هل انت متأكد من الحذف',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'تأكيد الحذف',
                showCancelButton: true,
                cancelButtonText: 'الغاء الامر',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger'
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('dashboard.workers.delete_file') }}",
                        'type': 'POST',
                        'dataType': 'json',
                        'async': false,
                        'data': {
                            worker_id: worker_id,
                            ssnfile_url: ssnfile_url,
                            type: type,

                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        'success': function(resp) {
                            if (resp.status == false) {
                                document.documentElement.scrollTop = 0;
                                swal.fire('خطأ', resp.message);
                            } else {
                                swal.fire('تم الحف بنجاح', resp.message);
                            }

                        }
                    });
                } else if (result.dismiss === 'cancel') {
                    swal.fire('الغاء الامر', 'خطأ');
                }
            });
        }
    </script>
