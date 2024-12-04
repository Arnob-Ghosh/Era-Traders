<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_number',
        'about',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }


    public static function getpermissionsByPermissionName($group_name)
    {
        
        $permissions = DB::table("permissions")
            ->select("permissions_name")
            ->addSelect(DB::raw("max(if(`permission_type` = 'create', name, null)) `p_create`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'create', id, null)) `p_create_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'edit', name, null)) `p_edit`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'edit', id, null)) `p_edit_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'view', name, null)) `p_view`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'view', id, null)) `p_view_id`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'destroy', name, null)) `p_destroy`"))
            ->addSelect(DB::raw("max(if(`permission_type` = 'destroy', id, null)) `p_destroy_id`"))
            ->where("group_name", "=", $group_name)
            ->groupBy("permissions_name")
            ->get();
        return $permissions;
    }
}
