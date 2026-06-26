@extends('layouts.app')
@section('module', 'نظام الحوسبة')
@section('sub', 'الاداري ')
@section('title', "$page_title")
@section('content')
    @if (session()->has('alert.success'))
        <div class="alert alert-success">
            {{ session('alert.success') }}
        </div>
    @endif


    <div id="user_reg" class="alert alert-danger d-none"></div>
    <form id="save_expense" name="save_expense" class="form" action="{{ route('dashboard.expense.store') }}"
        enctype="multipart/form-data" autocomplete="off" method="POST">
        @csrf
        <div class="d-flex flex-column flex-lg-row">
            <div class="mb-10 flex-lg-row-fluid mb-lg-0">
                <div class="card">
                    <div class="px-1 card-body">
                        <div class="p-5 mb-6 alert alert-dismissible d-flex flex-column flex-sm-row w-100"
                            id="errorBox_expense" style="display: none !important">
                            <span class="mb-5 svg-icon svg-icon-2hx svg-icon-light me-4 mb-sm-0">
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
                                <span id="displayErrors_expense" class="mb-2 fw-bolder text-light"></span>
                            </div>
                            <button type="button"
                                class="top-0 m-2 position-absolute position-sm-relative m-sm-0 end-0 btn btn-icon ms-sm-auto"
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
                        <div class="mb-0">


                            <div class="mb-5 row gx-5">
                                <div class="mb-5 col-12 col-lg-5 col-md-12 col-sm-12">
                                    <label for="expense_type_id" class="mb-3 form-label fs-6 fw-bolder text-danger">نوع المصروف</label>
                                    <div>
                                        <select class="form-select fw-bolder text-danger expense_type_id" data-control="select2" id="expense_type_id"
                                            name="expense_type_id" dir="rtl" onchange='load_expense_form(this.value,1)'   data-url="{{ route('dashboard.expense.load_expense_form') }}" >
                                            <option value="">اختر ..</option>
                                            @foreach ($expense_type as $x)
                                                <option value="{{ $x->expense_type_id }} ">{{ $x->expense_type_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 row gx-5" id='load_expense_form'>
                            </div>
                            <div class="mb-2 d-flex justify-content">
                                <button type="submit" id="kt_docs_formvalidation_text_submit"
                                    class="mr-2 btn btn-primary font-weight-bold" name="submitButton">حفظ
                                    البيانات</button>
                                &nbsp;&nbsp;
                                <button type="reset" class="mr-2 btn btn-light font-weight-bold">تفريغ البيانات</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



@endsection
@section('scripts')
    <script type="text/javascript"
        src="{{ asset('assets/module/expense_j.js') }}?t={{ config('global.ver.version_all') }}"></script>
    <script src="{{ asset('assets/js/custom/documentation/forms/select2.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://unpkg.com/jspdf-font@1.0.7/dist/arabic.js"></script>

<script>
    function exportPDF() {
            // تهيئة PDF مع دعم العربية
            window.jsPDF = window.jspdf.jsPDF;
            let doc = new jsPDF('l', 'pt', 'a4');

            // إضافة الخط العربي
            doc.addFileToVFS('Amiri-Regular.ttf', arabic.font);
            doc.addFont('Amiri-Regular.ttf', 'Amiri', 'normal');
            doc.setFont('Amiri');

            // الحصول على بيانات الجدول
            let dataTable = $('#expense_tbl').DataTable();
            let headers = [];

            $('#expense_tbl thead tr th').each(function() {
                if (!$(this).hasClass('actions')) {
                    headers.push($(this).text());
                }
            });

            // تجهيز البيانات
            let exportData = [];
            dataTable.rows().every(function() {
                let rowData = this.data();
                let row = [];
                for (let i = 0; i < rowData.length - 1; i++) {
                    row.push(rowData[i]);
                }
                exportData.push(row);
            });

            // إضافة العنوان
            doc.setFontSize(18);
            doc.text("تقرير المصروفات", doc.internal.pageSize.width/2, 40, {
                align: 'center',
                direction: 'rtl'
            });

            // إعداد الجدول
            doc.autoTable({
                head: [headers],
                body: exportData,
                startY: 60,
                styles: {
                    font: 'Amiri',
                    fontSize: 9,
                    cellPadding: 4,
                    halign: 'right',
                    direction: 'rtl'
                },
                headStyles: {
                    fillColor: [181, 181, 195],
                    textColor: [0, 0, 0],
                    fontSize: 10,
                    fontStyle: 'bold',
                    halign: 'right'
                },
                columnStyles: {
                    0: {cellWidth: 30}, // رقم
                    1: {cellWidth: 60}, // نوع الرئيسي
                    2: {cellWidth: 60}, // نوع المصروف
                    3: {cellWidth: 50}, // التصنيف
                    4: {cellWidth: 50}, // المحل
                    5: {cellWidth: 50}, // العامل
                    6: {cellWidth: 50}, // المجموعة
                    7: {cellWidth: 40}, // الشهر
                    8: {cellWidth: 50, halign: 'center'}, // المبلغ شامل الضريبة
                    9: {cellWidth: 40, halign: 'center'}, // الضريبة
                    10: {cellWidth: 50, halign: 'center'}, // المبلغ دون الضريبة
                    11: {cellWidth: 40, halign: 'center'}, // المدفوع
                    12: {cellWidth: 40, halign: 'center'}, // المتبقي
                    13: {cellWidth: 40}, // الحالة
                    14: {cellWidth: 60}, // ملاحظة
                    15: {cellWidth: 50}, // المدخل
                    16: {cellWidth: 60}  // تاريخ الادخال
                },
                footStyles: {
                    fillColor: [181, 181, 195],
                    textColor: [74, 12, 231],
                    fontStyle: 'bold'
                },
                didDrawPage: function(data) {
                    // إضافة رقم الصفحة
                    doc.setFont('Amiri');
                    doc.setFontSize(8);
                    doc.text('صفحة ' + doc.internal.getCurrentPageInfo().pageNumber,
                            doc.internal.pageSize.width - 20,
                            doc.internal.pageSize.height - 10,
                            { align: 'right', direction: 'rtl' });
                },
                margin: { top: 20, right: 20, bottom: 20, left: 20 },
                tableWidth: 'auto',
                theme: 'grid',
                // إضافة دعم للأرقام العربية والعملة
                didParseCell: function(data) {
                    if (data.section === 'body' && [8,9,10,11,12].includes(data.column.index)) {
                        // تنسيق الأرقام والعملة
                        if (data.cell.raw !== null && !isNaN(data.cell.raw)) {
                            data.cell.text = Number(data.cell.raw).toFixed(2) + ' ر.س';
                        }
                    }
                }
            });

            // حفظ الملف
            doc.save('تقرير_المصروفات.pdf');
        }

    </script>

@endsection
