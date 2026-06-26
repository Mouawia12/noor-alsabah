<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $table = "workers";

    /**
     * Get all of the comments for the Worker
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    
    {
        return $this->hasMany(Expense::class);
    }
    public function financials()
    {
        return $this->hasMany(Financial::class, 'worker_id');
    }
}
