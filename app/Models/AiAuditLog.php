<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAuditLog extends Model
{
    protected $table = 'ai_audit_log';
    protected $guarded = ['id'];

    public $timestamps = false; // created_at فقط

    protected $casts = [
        'changes'    => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * كتابة سطر تدقيق موحّد.
     */
    public static function record(string $type, $id, string $action, array $changes = [], ?int $userId = null, ?string $ip = null): self
    {
        return static::create([
            'auditable_type' => $type,
            'auditable_id'   => (string) $id,
            'action'         => $action,
            'changes'        => $changes ?: null,
            'user_id'        => $userId ?? (auth()->id() ?? null),
            'ip'             => $ip ?? request()->ip(),
            'created_at'     => now(),
        ]);
    }
}
