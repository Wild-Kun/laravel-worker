<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 用户头像
     * @return mixed|string
     */
    public function avatar()
    {
        return 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1595618947683&di=096599f826da31e0a4b394f44ad6dfc0&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fzhidao%2Fpic%2Fitem%2F0eb30f2442a7d933ed8c1316af4bd11373f001aa.jpg';
    }

    public function messages(){
        return $this->hasMany('App\Message');
    }
}
