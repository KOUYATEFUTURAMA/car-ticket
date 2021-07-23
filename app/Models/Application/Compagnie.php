<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compagnie extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['libelle_compagnie', 'adresse_complet', 'contact_compagnie', 'email_compagnie', 'responsable', 'contact_responsable', 'longitude', 'latitude', 'logo', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at'];
}
