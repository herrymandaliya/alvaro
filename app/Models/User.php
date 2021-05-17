<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','surname','id_number','local','cell_phone','whatsapp_number','birth_date','birth_place','photo','email','street','no','sector','municipality','circumscription','neighborhood','urbanization','electoral_college','college_location','unit_of_change','city','province','country','housing_type','housing_condition','monthly_fee','position','national','regional','provincial','municipal',
        'distrital','change_unit','study_level','profession','institution_name','language','employment_status','working_company_name','position_held_by','tlf_company','belongs_company_name','city_and_address',
        // 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
