<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Workers;
use App\Models\Shop;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Carbon\Carbon;
use Perm;
use PDF;

class GeneralController extends Controller
{


    public function sel_worker_list(Request $request)
    {
        $string = $request->q;
        $page = $request->page;
        //   $job= $request->job;
        $response = Workers::sel_worker_list($string, $page);
        //   dd($response);
        echo json_encode($response);
    }
    public function sel_worker_manager(Request $request)
    {
        $worker_id = $request->worker_id;

        $list = DB::table('manager')
        ->leftJoin('workers', 'manager.manager_id', '=', 'workers.manager_id')
        ->select('manager.*')
        ->where('workers.worker_id', $worker_id)->groupBy('workers.worker_id')->get();

        if (count($list) > 0) {
            $result['status'] = "true";
            $result['message'] = "يوجد له مجموعة";
            foreach ($list as $x) {
                $result['manager_name'] = $x->manager_name;
            }
        } else {
            $result['status'] = 'false';
            $result['code'] = '1';
            $result['message'] = "لا يوجد له مجموعة يجب تحديد مجموعة له";
        }
        echo json_encode($result);
        }


    public function sel_shop_list(Request $request)
    {
        $string = $request->q;
        $page = $request->page;
        //   $job= $request->job;
        $response = Shop::sel_shop_list($string, $page);
        //   dd($response);
        echo json_encode($response);
    }


    public function sel_shop_manager(Request $request)
    {
        $shop_id = $request->shop_id;

        $list = DB::table('manager')
        ->leftJoin('shop', 'manager.manager_id', '=', 'shop.manager_id')
        ->select('manager.*')
        ->where('shop.shop_id', $shop_id)->groupBy('shop.shop_id')->get();

        if (count($list) > 0) {
            $result['status'] = "true";
            $result['message'] = "يوجد له مجموعة";
            foreach ($list as $x) {
                $result['manager_name'] = $x->manager_name;
            }
        } else {
            $result['status'] = 'false';
            $result['code'] = '1';
            $result['message'] = "لا يوجد له مجموعة يجب تحديد مجموعة له";
        }
        echo json_encode($result);
        }
    public function sel_shop_pay(Request $request)
    {
        $shop_id = $request->shop_id;
        $list = DB::table('shop')->where(['shop_id' => $shop_id])->get();
        if (count($list) > 0) {
            $result['status'] = "true";
            $result['message'] = "يوجد بيانات";
            foreach ($list as $x) {
                $result['calculate_month_val'] = $x->calculate_month_val;
            }
        } else {
            $result['status'] = 'false';
            $result['code'] = '1';
            $result['message'] = "لا يوجد  قيمة مدخلة";
        }
        echo json_encode($result);
    }

    public function sel_worker_pay(Request $request)
    {
        $shop_id = $request->shop_id;
        $list = DB::table('workers')->where(['worker_id' => $worker_id])->get();
        if (count($list) > 0) {
            $result['status'] = "true";
            $result['message'] = "يوجد بيانات";
            foreach ($list as $x) {
                $result['calculate_month_val'] = $x->calculate_month_val;
            }
        } else {
            $result['status'] = 'false';
            $result['code'] = '1';
            $result['message'] = "لا يوجد  قيمة مدخلة";
        }
        echo json_encode($result);
    }


    public function chk_calculate(Request $request)
    {

        $month = $request->month;
        $year = $request->year;
        $calculate_month_desc = $request->calculate_month_desc;
        $shop_id = $request->shop_id;
        $list = DB::table('calculate')->where(['shop_id' => $shop_id, 'calculate_month_m' => $month, 'calculate_month_y' => $year, 'calculate_month_desc' => $calculate_month_desc, 'is_deleted' => 0])->get();
        if (count($list) > 0) {
            $result['status'] = "false";
            $result['message'] = "يوجد بيانات مدخلة لهذا الشهر توجه ادخال الدفع";
            foreach ($list as $x) {
                $result['calculate_month_val'] = $x->calculate_month_val;
            }
        } else {
            $result['status'] = 'true';
            $result['code'] = '1';
            $result['message'] = "يمكنك  ادخال بيانات الدفع";
        }
        echo json_encode($result);
    }


    public function chk_expense_worker(Request $request)
    {

        $month = $request->month;
        $year = $request->year;
        $expense_month_desc = $request->expense_month_desc;
        $worker_id = $request->worker_id;
        $list = DB::table('expense')->where(['worker_id' => $worker_id, 'expense_month_m' => $month, 'expense_month_y' => $year, 'expense_month_desc' => $expense_month_desc, 'is_deleted' => 0])->get();
        if (count($list) > 0) {
            $result['status'] = "false";
            $result['message'] = "يوجد بيانات مدخلة لهذا الشهر توجه ادخال الدفع";
            foreach ($list as $x) {
                $result['expense_month_val'] = $x->expense_month_val;
            }
        } else {
            $result['status'] = 'true';
            $result['code'] = '1';
            $result['message'] = "يمكنك  ادخال بيانات الدفع";
        }
        echo json_encode($result);
    }


    public function chk_expense_shop(Request $request)
    {

        $month = $request->month;
        $year = $request->year;
        $expense_month_desc = $request->expense_month_desc;
        $shop_id = $request->shop_id;
        $list = DB::table('expense')->where(['shop_id' => $shop_id, 'expense_month_m' => $month, 'expense_month_y' => $year, 'expense_month_desc' => $expense_month_desc, 'is_deleted' => 0])->get();
        if (count($list) > 0) {
            $result['status'] = "false";
            $result['message'] = "يوجد بيانات مدخلة لهذا الشهر توجه ادخال الدفع";
            foreach ($list as $x) {
                $result['expense_month_val'] = $x->expense_month_val;
            }
        } else {
            $result['status'] = 'true';
            $result['code'] = '1';
            $result['message'] = "يمكنك  ادخال بيانات الدفع";
        }
        echo json_encode($result);
    }

    

}
