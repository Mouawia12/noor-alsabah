<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class Vacation extends Model
{
    use HasFactory;
   // protected $guarded = ['worker_id'];
   // protected $primaryKey = 'worker_id';
   // public $incrementing = false;
//protected $dateFormat = 'U';


public function scopeserachspendcountdesc($query,$vacation_month_m,$vacation_month_y,$worker_id){
    $vacation_month_m = TRIM($vacation_month_m);
    $vacation_month_y = TRIM($vacation_month_y);
    $worker_id = TRIM($worker_id);

    $bind = [];

    $rs_stmt1 = " SELECT vacation_id FROM   vacation where  1=1    ";

    /*    if ($vacation_month_y  != "") {
            $rs_stmt1 = $rs_stmt1 . " and  vacation_month_y = '$vacation_month_y' ";
            }*/

            if ($vacation_month_m  != "") {
            $rs_stmt1 = $rs_stmt1 . " and ( Month(start)= ? ||  Month(end)= ? )  ";
            $bind[] = $vacation_month_m;
            $bind[] = $vacation_month_m;
            }
            if ($vacation_month_y  != "") {
                $rs_stmt1 = $rs_stmt1 . " and ( Year(start)= ? ||  Year(end)= ? )  ";
                $bind[] = $vacation_month_y;
                $bind[] = $vacation_month_y;
                }

            if ($worker_id  != "") {
                $rs_stmt1 = $rs_stmt1 . " and  worker_id = ? ";
                $bind[] = $worker_id;
                }

      $results = count(DB::select($rs_stmt1, $bind));
    return  $results;
    }


    public function scopeserachspenddatadesc($query,$vacation_month_m,$vacation_month_y,$worker_id){
        $a = $_POST['length'];
$b = $_POST['start'];
$vacation_month_m = TRIM($vacation_month_m);
$vacation_month_y = TRIM($vacation_month_y);
$vacation_month_m = TRIM($vacation_month_m);
$worker_id = TRIM($worker_id);

$bind = [];

if(isset($_POST['order']))
            {
            $columnName = (int) ($_POST['order']['0']['column'] ?? 0);
            $columnSortOrder = (strtolower($_POST['order']['0']['dir'] ?? '') === 'asc') ? 'asc' : 'desc';
            if($columnName!=0){
            $ord =  " order by  ".$columnName. " ". $columnSortOrder ;
            }
            else{
            $ord =  " ORDER BY vacation_month_id DESC  " ;
            }

            }
            else{
            $ord =  "    " ;
            }

        $rs_stmt1 = " SELECT p.*,sh.worker_name, Month(start) as month,Year(start) as year,
COALESCE(sum(p.count_day), 0) as count_day
         FROM   vacation p
         join  workers sh on p.worker_id=sh.worker_id

         where  1=1  ";

if ($vacation_month_m  != "") {
    $rs_stmt1 = $rs_stmt1 . " and ( Month(start)= ? ||  Month(end)= ? )  ";
    $bind[] = $vacation_month_m;
    $bind[] = $vacation_month_m;
    }
    if ($vacation_month_y  != "") {
        $rs_stmt1 = $rs_stmt1 . " and ( Year(start)= ? ||  Year(end)= ? )  ";
        $bind[] = $vacation_month_y;
        $bind[] = $vacation_month_y;
        }

    if ($worker_id  != "") {
        $rs_stmt1 = $rs_stmt1 . " and  worker_id = ? ";
        $bind[] = $worker_id;
        }

            $rs_stmt1 = $rs_stmt1 . "    group by p.worker_id, Month(start),Year(start) ";

        $rs_stmt1 = $rs_stmt1  .$ord;
        $rs_stmt1 = $rs_stmt1 . "    limit " . (int) $b . "," . (int) $a . " ";


                $results = DB::select($rs_stmt1, $bind);

        return  $results;
        }

  public function scopeserachdet($query,$vacation_month_m,$vacation_month_y,$worker_id,$vacation_type_id){
    $vacation_month_m = TRIM($vacation_month_m);
    $vacation_month_y = TRIM($vacation_month_y);
    $worker_id = TRIM($worker_id);
    $vacation_type_id = TRIM($vacation_type_id);

    $bind = [];

    $rs_stmt1 = " SELECT vacation_id FROM   vacation where  1=1 and is_deleted=0   ";

            if ($worker_id  != "") {
                $rs_stmt1 = $rs_stmt1 . " and  worker_id = ? ";
                $bind[] = $worker_id;
                }
                if ($vacation_month_m  != "") {
                    $rs_stmt1 = $rs_stmt1 . " and ( Month(start)= ? ||  Month(end)= ? )  ";
                    $bind[] = $vacation_month_m;
                    $bind[] = $vacation_month_m;
                    }
                    if ($vacation_month_y  != "") {
                        $rs_stmt1 = $rs_stmt1 . " and ( Year(start)= ? ||  Year(end)= ? )  ";
                        $bind[] = $vacation_month_y;
                        $bind[] = $vacation_month_y;
                        }

                    if ($worker_id  != "") {
                        $rs_stmt1 = $rs_stmt1 . " and  worker_id = ? ";
                        $bind[] = $worker_id;
                        }


      $results = count(DB::select($rs_stmt1, $bind));
    return  $results;
    }


    public function scopeserachspenddet($query,$vacation_month_m,$vacation_month_y,$worker_id,$vacation_type_id){
        $a = $_POST['length'];
$b = $_POST['start'];
$vacation_month_m = TRIM($vacation_month_m);
$vacation_month_y = TRIM($vacation_month_y);
$worker_id = TRIM($worker_id);
$vacation_type_id = TRIM($vacation_type_id);

$bind = [];

if(isset($_POST['order']))
            {
            $columnName = (int) ($_POST['order']['0']['column'] ?? 0);
            $columnSortOrder = (strtolower($_POST['order']['0']['dir'] ?? '') === 'asc') ? 'asc' : 'desc';
            if($columnName!=0){
            $ord =  " order by  ".$columnName. " ". $columnSortOrder ;
            }
            else{
            $ord =  " ORDER BY cd.vacation_detail_id desc  " ;
            }

            }
            else{
            $ord =  "    " ;
            }

            $rs_stmt1 = " SELECT p.*,sh.worker_name, vt.vacation_type_name,j.job_name,wp.work_place_name,name
                     FROM   vacation p
                     join  workers sh on p.worker_id=sh.worker_id
                    left join  vacation_type vt on p.vacation_type_id=vt.vacation_type_id
                    left join  job j on sh.job_id=j.job_id
                   left join  work_place wp on sh.work_place_id=wp.work_place_id
                   left join  users u on p.create_user=u.id

                     where  1=1  ";

if ($vacation_month_m  != "") {
    $rs_stmt1 = $rs_stmt1 . " and ( Month(p.start)= ? ||  Month(p.end)= ? )  ";
    $bind[] = $vacation_month_m;
    $bind[] = $vacation_month_m;
    }
    if ($vacation_month_y  != "") {
        $rs_stmt1 = $rs_stmt1 . " and ( Year(p.start)= ? ||  Year(p.end)= ? )  ";
        $bind[] = $vacation_month_y;
        $bind[] = $vacation_month_y;
        }

    if ($worker_id  != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
        $bind[] = $worker_id;
        }
        $rs_stmt1 = $rs_stmt1  .$ord;
        $rs_stmt1 = $rs_stmt1 . " ORDER BY p.vacation_id desc limit " . (int) $b . "," . (int) $a . " ";
                $results = DB::select($rs_stmt1, $bind);

        return  $results;
        }












        public function scopeserachspendrep($query,$vacation_id,$vacation_month_m,$vacation_month_y,$worker_id,$vacation_type_id){
    $vacation_month_m = TRIM($vacation_month_m);
    $vacation_month_y = TRIM($vacation_month_y);
    $worker_id = TRIM($worker_id);
    $vacation_type_id = TRIM($vacation_type_id);
    $vacation_id = TRIM($vacation_id);

    $bind = [];

                $rs_stmt1 = " SELECT p.*,sh.worker_name, vt.vacation_type_name,j.job_name,wp.work_place_name,name
                         FROM   vacation p
                         join  workers sh on p.worker_id=sh.worker_id
                        left join  vacation_type vt on p.vacation_type_id=vt.vacation_type_id
                        left join  job j on sh.job_id=j.job_id
                       left join  work_place wp on sh.work_place_id=wp.work_place_id
                       left join  users u on p.create_user=u.id

                         where  1=1  ";
    if ($vacation_id  != "") {
        $rs_stmt1 = $rs_stmt1 . " and  p.vacation_id = ? ";
        $bind[] = $vacation_id;
    }

    if ($vacation_month_m  != "") {
        $rs_stmt1 = $rs_stmt1 . " and ( Month(p.start)= ? ||  Month(p.end)= ? )  ";
        $bind[] = $vacation_month_m;
        $bind[] = $vacation_month_m;
        }
        if ($vacation_month_y  != "") {
            $rs_stmt1 = $rs_stmt1 . " and ( Year(p.start)= ? ||  Year(p.end)= ? )  ";
            $bind[] = $vacation_month_y;
            $bind[] = $vacation_month_y;
            }

        if ($worker_id  != "") {
            $rs_stmt1 = $rs_stmt1 . " and  p.worker_id = ? ";
            $bind[] = $worker_id;
            }
            $rs_stmt1 = $rs_stmt1 . " ORDER BY p.vacation_id desc ";
                    $results = DB::select($rs_stmt1, $bind);

            return  $results;
            }



}



