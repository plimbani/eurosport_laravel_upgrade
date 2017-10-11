<?php

namespace Laraspace\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Duro85\Roles\Traits\HasRoleAndPermission;
use Duro85\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\CanResetPassword;
use Laraspace\Notifications\MyOwnResetPassword as ResetPasswordNotification;
use Laraspace\Models\UserOtp;

class User extends Authenticatable implements HasRoleAndPermissionContract, CanResetPassword
{
    use Notifiable, HasRoleAndPermission, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'person_id',
        'username',
        'user_image',
        'name',
        'email',
        'organisation',
        'password',
        'token',
        'is_verified',
        'timezone',
        'is_online',
        'last_login_time',
        'is_active',
        'last_active_time',
        'is_blocked',
        'blocked_time',
        'blocker_id',
        'settings',
        'is_mobile_user',
        'is_desktop_user',
        'registered_from',
        'locale',
        'fcm_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'person_id' => 'integer',
        'username' => 'string',
        'email' => 'string',
        'password' => 'string',
        'token' => 'string',
        'is_verified' => 'boolean',
        'timezone' => 'string',
        'is_online' => 'boolean',
        'last_login_time' => 'datetime',
        'is_active' => 'boolean',
        'last_active_time' => 'datetime',
        'is_blocked' => 'boolean',
        'blocked_time' => 'datetime',
        'blocker_id' => 'integer',
        'settings' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login_time',
        'last_active_time',
        'blocked_time',
    ];

    /**
     * Get Personal details of user.
     */
    public function profile()
    {
        return $this->belongsTo('Laraspace\Models\Person', 'person_id');
    }

    /**
     * Get all permissions from roles.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function rolePermissions()
    {
        $permissionModel = app(config('roles.models.permission'));

        if (!$permissionModel instanceof Model) {
            throw new InvalidArgumentException('[roles.models.permission] must be an instance of \Illuminate\Database\Eloquent\Model');
        }

        return $permissionModel::select(['permissions.*', 'permission_role.created_at as pivot_created_at', 'permission_role.updated_at as pivot_updated_at'])
                ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')->join('roles', 'roles.id', '=', 'permission_role.role_id')
                ->whereIn('roles.id', $this->getRoles()->pluck('id')->toArray()) ->orWhere('roles.level', '<', $this->level())
                ->groupBy(['permissions.id', 'pivot_created_at', 'pivot_updated_at']);
    }

    /**
     * Check if the user has a permission.
     *
     * @param int|string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->getPermissions()->contains(function ($value, $key) use ($permission) {
            return $permission == $value->id || Str::is($permission, $value->slug);
        });
    }

    /**
     * Person Detail
     *
     * @return [type] [description]
     */
    public function personDetail()
    {
        return $this->belongsTo('Laraspace\Models\Person', 'person_id');
    }
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $mobileUserRoleId = Role::where('slug', 'mobile.user')->first()->id;
        $name = (isset($this->personDetail->first_name)) ? $this->personDetail->first_name : $this->name;
        $send_otp='';
        $subject = 'Euro-Sportring Tournament Planner - Reset password';
        // Set OTP
        if($this->roles()->first()->id == $mobileUserRoleId) {
            $subject = 'Euro-Sportring - Password Reset';
            // $send_otp = str_random(4);
            // $encoded_otp = base64_encode($this->id."|".$send_otp);

            // $userOTP = new UserOtp();
            // $userOTP->user_id = $this->id;
            // $userOTP->encoded_key = $encoded_otp;
            // $userOTP->save();
            // //Session::set('opt_value', $encoded_otp);
            // if(isset($_SESSION['otp_key']))
            //   unset($_SESSION['otp_key']);
            // $_SESSION['otp_key'] = $send_otp;
            // request()->session()->put('otp_value', $encoded_otp);
        }
        $this->notify(new ResetPasswordNotification($token, $name,$this->email,$send_otp, $subject));
    }
    public function settings()
    {
        return $this->hasOne('Laraspace\Models\Settings', 'user_id');
    }

    public function defaultFavouriteTournament()
    {
        return $this->hasMany('Laraspace\Models\UserFavourites', 'user_id')->where('is_default', 1);
    }

    public function tournaments()
    {
        return $this->belongsToMany('Laraspace\Models\Tournament', 'tournament_user', 'user_id','tournament_id');
    }
}
