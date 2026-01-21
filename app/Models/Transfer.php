<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transfer extends Model
{
    use HasFactory;

    protected $primaryKey = 'transfer_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'transfer_id',
        'user_id',
        'target_user',
        'amount',
        'remarks',
        'balance_before',
        'balance_after',
        'status',
    ];

    protected $casts = [
        'created_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->transfer_id)) {
                $model->transfer_id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user', 'user_id');
    }
}