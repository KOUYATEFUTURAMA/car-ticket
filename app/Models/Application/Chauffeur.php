<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chauffeur extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['full_name_chauffeur', 'civilite','adresse_chauffeur', 'numero_permis', 'groupe_sanguin', 'contact_chauffeur','contact_conjoint', 'contact_en_cas_urgence', 'compagnie_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_fin_permis','date_naissance','date_prise_service'];
    
    public function compagnie() {
         return $this->belongsTo('App\Models\Application\Compagnie');
    }
}
