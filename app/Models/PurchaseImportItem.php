<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseImportItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_import_item';
    protected $guarded = ['id'];

    protected $casts = [
        'extracted_json'   => 'array',
        'field_confidence' => 'array',
        'confidence'       => 'float',
        'is_duplicate'     => 'boolean',
        'reviewed_at'      => 'datetime',
    ];

    public const STATUS_PENDING      = 'pending';
    public const STATUS_PROCESSING   = 'processing';
    public const STATUS_NEEDS_REVIEW = 'needs_review';
    public const STATUS_APPROVED     = 'approved';
    public const STATUS_REJECTED     = 'rejected';
    public const STATUS_FAILED       = 'failed';
    public const STATUS_MERGED       = 'merged'; // صفحة تكملة دُمجت مع فاتورتها (مخفية)

    public function batch(): BelongsTo
    {
        return $this->belongsTo(PurchaseImportBatch::class, 'batch_id');
    }
}
