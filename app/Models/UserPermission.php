<?php

namespace App\Models;

use App\Enums\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'permission'];

    protected $casts = [
        'permission' => Permission::class,
    ];

    /**
     * Get the user that owns this permission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
