<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Perm;

class Financial extends Model
{
    use HasFactory;

    // protected $guarded = ['worker_id'];
    // protected $primaryKey = 'worker_id';
    // public $incrementing = false;
//protected $dateFormat = 'U';

    /**
     * أمن: تعقيم قائمة أعداد صحيحة تُستخدم داخل IN(...) — تحافظ على نفس القيم
     * (رموز فئات 1..4) وتمنع حقن SQL. تُرجع "0" لو كانت القائمة فارغة لتفادي IN().
     */
    private static function intInList($list)
    {
        $parts = array_filter(array_map('trim', explode(',', (string) $list)), 'strlen');
        $ints = array_map('intval', $parts);
        return count($ints) ? implode(',', $ints) : '0';
    }


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->emp_job = Auth()->user()->emp_job;
        $this->user_id=Auth::user()->id;
    }


    public function scopeserachhistorycount($query, $financial_id)
    {
        $financial_id = TRIM($financial_id);
        $bind = [];
        $rs_stmt1 = " SELECT vh. financial_detail_history_id   FROM   financial_detail_history vh
          join   financial v on vh.financial_id =v.financial_id


         where   1=1  and v.is_deleted=0 ";
        if ($financial_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  vh.financial_id = ? ";
            $bind[] = $financial_id;
        }

        $results = count(DB::select($rs_stmt1, $bind));
        return $results;
    }


    public function scopeserachhistorydata($query, $financial_id)
    {
        $a = $_POST['length'];
        $b = $_POST['start'];
        $financial_id = TRIM($financial_id);
        $bind = [];
        if (isset($_POST['order'])) {
            $columnName = (int) ($_POST['order']['0']['column'] ?? 0);
            $columnSortOrder = (strtolower($_POST['order']['0']['dir'] ?? '') === 'asc') ? 'asc' : 'desc';
            if ($columnName != 0) {
                $ord = " order by  " . $columnName . " " . $columnSortOrder;
            } else {
                $ord = " ORDER BY  financial_detail_history_id   ASC  ";
            }

        } else {
            $ord = "  ORDER BY  financial_detail_history_id   desc  ";
        }

        $rs_stmt1 = " SELECT vh.*,u.name FROM  financial_detail_history vh
          join   financial v on vh.financial_id =v.financial_id

         left join  users u on vh.change_user =u.id

                    where    1=1 and v.is_deleted=0
 ";
        if ($financial_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  vh.financial_id = ? ";
            $bind[] = $financial_id;
        }

        $rs_stmt1 = $rs_stmt1 . $ord;
        $rs_stmt1 = $rs_stmt1 . "  limit " . (int) $b . "," . (int) $a . " ";
        $results = DB::select($rs_stmt1, $bind);
        return $results;
    }


    public function scopesumspendcounthome($query, $financial_month_m, $financial_month_y)
    {
        $financial_month_m = TRIM($financial_month_m);
        $financial_month_y = TRIM($financial_month_y);
        $bind = [];
        $rs_stmt1 = " 	 SELECT
p.financial_month_y,p.financial_month_m,
cd.financial_month_val as c1,


       COALESCE(count(cd.financial_detail_id), 0) as count_statement,
       COALESCE(sum(cd.financial_month_pay), 0) as sum_det_financial_month_pay,

       (cd.financial_month_val - COALESCE(sum(cd.financial_month_pay), 0)) as xx

            FROM   financial p
            left join  financial_detail cd on p.financial_id=cd.financial_id
            left join  workers w on p.worker_id=w.worker_id




      ";
        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
        join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }


        if ($financial_month_y != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_y = ? ";
            $bind[] = $financial_month_y;
        }


        if($financial_month_m!=''){
            $rs_stmt1 = $rs_stmt1 . "    group by p.financial_month_y,p.financial_month_m,p.financial_id ";
       }
        else{
            $rs_stmt1 = $rs_stmt1 . "    group by p.financial_month_y,p.financial_id ";
        }



        $results = DB::select($rs_stmt1, $bind);

        return $results;

    }













public function scopesumspendcountdesc($query, $financial_month_m, $financial_month_y, $worker_id,$manager_id,$from,$to)
{
    $financial_month_m = TRIM($financial_month_m);
    $financial_month_y = TRIM($financial_month_y);
    $from = TRIM($from);
    $to = TRIM($to);
    $bind = [];
    $rs_stmt1 = " 	 SELECT

    p.financial_month_val as c1,

   COALESCE(count(cd.financial_detail_id), 0) as count_statement,m.manager_name,
   COALESCE(sum(cd.financial_month_pay), 0) as sum_det_financial_month_pay,

    (p.financial_month_val - COALESCE(sum(cd.financial_month_pay), 0)) as xx

        FROM   financial p
        left join  financial_detail cd on p.financial_id=cd.financial_id
        left join  workers w on p.worker_id=w.worker_id

        left join  manager m on w.manager_id=m.manager_id



  ";
    if(  $this->emp_job!=1){
        $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

    }
    $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

    if(  $this->emp_job!=1){
        if (Perm::get_function_access(73)) {
            $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
        }
    }


    if ($financial_month_y != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_y = ? ";
        $bind[] = $financial_month_y;
    }
    if ($from  != "" and $to  != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.created_at between ? and ?  ";
        $bind[] = $from;
        $bind[] = $to;
    }


    if ($from  != "" and $to  == "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.created_at >= ?   ";
        $bind[] = $from;
    }

    if ($from  == "" and $to  != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.created_at <= ?   ";
        $bind[] = $to;
    }

    if ($financial_month_m != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_m = ? ";
        $bind[] = $financial_month_m;
    }
    if ($worker_id != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
        $bind[] = $worker_id;
    }
    if ($manager_id != "") {
        $rs_stmt1 = $rs_stmt1 . " and  w.manager_id = ? ";
        $bind[] = $manager_id;
    }

    $rs_stmt1 = $rs_stmt1 . "    group by p.financial_id ";



    $results = DB::select($rs_stmt1, $bind);

    return $results;

}






    public function scopeserachspendcountdesc($query, $financial_month_m, $financial_month_y, $worker_id,$manager_id,$from,$to)
    {
        $financial_month_m = TRIM($financial_month_m);
        $financial_month_y = TRIM($financial_month_y);
        $from = TRIM($from);
        $to = TRIM($to);

        $bind = [];

        $rs_stmt1 = " SELECT p.financial_id FROM   financial p
                left join  workers w on p.worker_id=w.worker_id

left join  manager m on w.manager_id=m.manager_id
";


        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }













if ($financial_month_y != "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_y = ? ";
    $bind[] = $financial_month_y;
}
if ($financial_month_m != "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_m = ? ";
    $bind[] = $financial_month_m;
}
if ($from  != "" and $to  != "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.created_at between ? and ?  ";
    $bind[] = $from;
    $bind[] = $to;
}


if ($from  != "" and $to  == "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.created_at >= ?   ";
    $bind[] = $from;
}

if ($from  == "" and $to  != "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.created_at <= ?   ";
    $bind[] = $to;
}

if ($worker_id != "") {
    $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
    $bind[] = $worker_id;
}
if ($manager_id != "") {
    $rs_stmt1 = $rs_stmt1 . " and  w.manager_id = ? ";
    $bind[] = $manager_id;
}


        $results = count(DB::select($rs_stmt1, $bind));
        return $results;
    }

    public function scopeserachspenddatarep($query,$financial_id, $financial_month_m, $financial_month_y, $worker_id,$manager_id)
    {
        $financial_month_m = TRIM($financial_month_m);
        $financial_month_y = TRIM($financial_month_y);

        $bind = [];

        $rs_stmt1 = " SELECT p.*,sh.worker_name,u.name,m.manager_name,
        cd.financial_detail_id as financial_detail_id,
        cd.financial_month_pay as det_financial_month_pay,
        cd.financial_month_remain as det_financial_month_remain,
        cd.note as det_note,
        cd.create_user as det_create_user,
        cd.created_at as det_created_at,
        cd.updated_user as det_updated_user,
        cd.updated_at as det_updated_at,
COALESCE(count(cd.financial_detail_id), 0) as count_statement,
COALESCE(sum(cd.financial_month_pay), 0) as sum_det_financial_month_pay
         FROM   financial p
         left join  financial_detail cd on p.financial_id=cd.financial_id
         join  workers sh on p.worker_id=sh.worker_id
         join  users u on p.create_user=u.id
         left join  workers w on p.worker_id=w.worker_id
left join  manager m on w.manager_id=m.manager_id

        ";
        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }
        if ($financial_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_id = ? ";
            $bind[] = $financial_id;
        }
        if ($financial_month_y != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_y = ? ";
            $bind[] = $financial_month_y;
        }

        if ($financial_month_m != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_m = ? ";
            $bind[] = $financial_month_m;
        }
        if ($worker_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
            $bind[] = $worker_id;
        }
        if ($manager_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  w.manager_id = ? ";
            $bind[] = $manager_id;
        }

        $rs_stmt1 = $rs_stmt1 . "    group by p.financial_id ";



        $results = DB::select($rs_stmt1, $bind);

        return $results;
    }
    public function scopeserachspenddatadesc($query, $financial_month_m, $financial_month_y, $worker_id,$manager_id, $from , $to)
    {
        $a = $_POST['length'];
        $b = $_POST['start'];
        $from = TRIM($from);
        $to = TRIM($to);
        $financial_month_m = TRIM($financial_month_m);
        $financial_month_y = TRIM($financial_month_y);
        $bind = [];
        if (isset($_POST['order'])) {
            $columnName = (int) ($_POST['order']['0']['column'] ?? 0);
            $columnSortOrder = (strtolower($_POST['order']['0']['dir'] ?? '') === 'asc') ? 'asc' : 'desc';
            if ($columnName != 0) {
                $ord = " order by  " . $columnName . " " . $columnSortOrder;
            } else {
                $ord = " ORDER BY financial_month_id DESC  ";
            }

        } else {
            $ord = "    ";
        }

        $rs_stmt1 = " SELECT p.*,sh.worker_name,sh.ssn,u.name,m.manager_name,
        cd.financial_detail_id as financial_detail_id,
        cd.financial_month_pay as det_financial_month_pay,
        cd.financial_month_remain as det_financial_month_remain,
        cd.note as det_note,
        cd.create_user as det_create_user,
        cd.created_at as det_created_at,
        cd.updated_user as det_updated_user,
        cd.updated_at as det_updated_at,
COALESCE(count(cd.financial_detail_id), 0) as count_statement,
COALESCE(sum(cd.financial_month_pay), 0) as sum_det_financial_month_pay
         FROM   financial p
         left join  financial_detail cd on p.financial_id=cd.financial_id
         join  workers sh on p.worker_id=sh.worker_id
         join  users u on p.create_user=u.id
         left join  workers w on p.worker_id=w.worker_id
left join  manager m on w.manager_id=m.manager_id

       ";
        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }
        if ($financial_month_y != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_y = ? ";
            $bind[] = $financial_month_y;
        }
        if ($from  != "" and $to  != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.created_at between ? and ?  ";
            $bind[] = $from;
            $bind[] = $to;
        }


        if ($from  != "" and $to  == "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.created_at >= ?   ";
            $bind[] = $from;
        }

        if ($from  == "" and $to  != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.created_at <= ?   ";
            $bind[] = $to;
        }

        if ($financial_month_m != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_month_m = ? ";
            $bind[] = $financial_month_m;
        }
        if ($worker_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
            $bind[] = $worker_id;
        }
        if ($manager_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  w.manager_id = ? ";
            $bind[] = $manager_id;
        }

        $rs_stmt1 = $rs_stmt1 . "    group by p.financial_id ";

        $rs_stmt1 = $rs_stmt1 . $ord;
        $rs_stmt1 = $rs_stmt1 . "    limit " . (int) $b . "," . (int) $a . " ";


        $results = DB::select($rs_stmt1, $bind);

        return $results;
    }

    public function scopeserachspendcountdet($query, $financial_id)
    {
        $financial_id = TRIM($financial_id);
        $bind = [];
        $rs_stmt1 = " SELECT p.financial_id

 FROM   financial p
          join  workers w on p.worker_id=w.worker_id

  ";
        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }
        if ($financial_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  financial_id = ? ";
            $bind[] = $financial_id;
        }


        $results = count(DB::select($rs_stmt1, $bind));
        return $results;
    }


    public function scopeserachspenddet($query, $financial_id)
    {
        $a = $_POST['length'];
        $b = $_POST['start'];
        $financial_id = TRIM($financial_id);
        $bind = [];
        if (isset($_POST['order'])) {
            $columnName = (int) ($_POST['order']['0']['column'] ?? 0);
            $columnSortOrder = (strtolower($_POST['order']['0']['dir'] ?? '') === 'asc') ? 'asc' : 'desc';
            if ($columnName != 0) {
                $ord = " order by  " . $columnName . " " . $columnSortOrder;
            } else {
                $ord = " ORDER BY cd.financial_detail_id desc  ";
            }

        } else {
            $ord = "    ";
        }

        $rs_stmt1 = " SELECT p.*,w.worker_name,u.name,

        cd.financial_detail_id as financial_detail_id,
        cd.financial_month_pay as det_financial_month_pay,
        cd.financial_month_remain as det_financial_month_remain,
                cd.financial_month_val as det_financial_month_val,

        cd.note as det_note,
        u2.name as det_create_user_name,
        cd.create_user as det_create_user,
        cd.created_at as det_created_at,
        cd.updated_user as det_updated_user,
        cd.updated_at as det_updated_at

          FROM
          financial p
          join  financial_detail cd on p.financial_id=cd.financial_id
         join  workers w on p.worker_id=w.worker_id
         join  users u on p.create_user=u.id
         join  users u2 on cd.create_user=u2.id

        ";
        if(  $this->emp_job!=1){
            $rs_stmt1 = $rs_stmt1 . "
    join workers_manager wm on w.manager_id=wm.manager_id and  wm.user_id=" . (int) $this->user_id;

        }
        $rs_stmt1 = $rs_stmt1 . " where  1=1 and p.is_deleted=0 ";

        if(  $this->emp_job!=1){
            if (Perm::get_function_access(73)) {
                $rs_stmt1 = $rs_stmt1 . " and  p.create_user = " . (int) $this->user_id . " ";
            }
        }
        if ($financial_id != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.financial_id = ? ";
            $bind[] = $financial_id;
        }

        $rs_stmt1 = $rs_stmt1 . $ord;
        $rs_stmt1 = $rs_stmt1 . " ORDER BY cd.financial_detail_id desc limit " . (int) $b . "," . (int) $a . " ";
        $results = DB::select($rs_stmt1, $bind);

        return $results;
    }


}



