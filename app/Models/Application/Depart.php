<?php

namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depart extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['localite_depart', 'localite_arrive', 'compagnie_id', 'tarif', 'place_disponible', 'place_vendue', 'vehicule_id', 'chauffeur_id', 'statut', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_arrivee','date_depart'];
    
    public function chauffeur() {
         return $this->belongsTo('App\Models\Application\Chauffeur');
    }
    
    public function vehicule() {
         return $this->belongsTo('App\Models\Application\Vehicule');
    }
    
    public function compagnie() {
         return $this->belongsTo('App\Models\Application\Compagnie');
    }
    
    public function localite_arrive() {
         return $this->belongsTo('App\Models\Parametre\Localite','localite_arrive');
    }
    
    public function localite_depart() {
         return $this->belongsTo('App\Models\Parametre\Localite','localite_depart');
    }
}
