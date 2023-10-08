<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    /** Default Permission **/

    const DEFAULT_DASHBOARD_PERMISSION = 'dashboard.index';

    protected $table = 'permissions';
    protected $fillable = ['name','ident', 'description', 'active'];

    protected $casts = [
        'active' => 'bool',
        'created_at' => 'datetime:Y-m-d - H:i:s',
        'updated_at' => 'datetime:Y-m-d - H:i:s',
        'deleted_at' => 'datetime:Y-m-d - H:i:s'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
