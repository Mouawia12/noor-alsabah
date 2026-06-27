<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentContractImportBatch extends Model
{
    use HasFactory;

    protected $table = 'rent_contract_import_batch';
    protected $guarded = ['id'];

    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED  = 'completed';
    public const STATUS_FAILED     = 'failed';

    public function items(): HasMany
    {
        return $this->hasMany(RentContractImportItem::class, 'batch_id');
    }
}
