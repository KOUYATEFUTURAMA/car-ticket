<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicule extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['descritpion_vehicule', 'immatriculation', 'type_vitesse', 'nombre_place', 'marque_id', 'moteur_id', 'puissance_id', 'type_vehicule_id', 'compagnie_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_next_visite'];
    
    public function compagnie() {
         return $this->belongsTo('App\Models\Application\Compagnie');
    }
    public function type_vehicule() {
         return $this->belongsTo('App\Models\Parametre\TypeVehicule');
    }
    public function puissance() {
         return $this->belongsTo('App\Models\Parametre\Puissance');
    }
    public function moteur() {
         return $this->belongsTo('App\Models\Parametre\Moteur');
    }
    public function marque() {
         return $this->belongsTo('App\Models\Parametre\Marque');
    }
}
