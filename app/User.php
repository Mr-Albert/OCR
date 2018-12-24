<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Hash;
/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $userName
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = ['name', 'userName', 'password','default_password', 'remember_token'];
    
    
    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
       {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function setDefaultPasswordAttribute($input)
    {
        if ($input)
       {
            $this->attributes['default_password'] = $this->attributes['password'];

        }
    }
    
    
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    
    
    
}
