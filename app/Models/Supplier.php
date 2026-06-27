<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    protected $guarded = ['supplier_id'];

    /** تطبيع اسم المورد للمطابقة الضبابية (إزالة المسافات الزائدة والتشكيل البسيط). */
    public static function normalizeName(?string $name): string
    {
        $name = trim((string) $name);
        $name = preg_replace('/\s+/u', ' ', $name);
        return mb_strtolower($name);
    }
}
