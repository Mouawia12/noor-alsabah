<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Perm;

class Municip extends Model
{
    use HasFactory;

    protected $primaryKey = 'municip_id';
    protected $table = "shop_municip";
    // أمن: حماية المفتاح الأساسي من الإسناد الجماعي (كان $guarded = [] يفتح كل الأعمدة)
    protected $guarded = ['municip_id'];

   // public $incrementing = false;
//protected $dateFormat = 'U';





}



