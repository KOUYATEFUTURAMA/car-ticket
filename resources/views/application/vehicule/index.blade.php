@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur' or Auth::user()->role == 'Compagnie')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
@if(Auth::user()->compagnie_id==null)
<div class="col-md-3">
    <select class="form-control" id="searchByCompagnie">
        <option value="0" >-- Toutes les compagnies --</option>
        @foreach($compagnies as $compagnie)
        <option value="{{$compagnie->id}}"> {{$compagnie->libelle_compagnie}}</option>
        @endforeach
    </select>
</div>
@endif
<div class="col-md-6">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-primary"
               data-pagination="true"
               data-search="true" 
               data-toggle="table"
               data-url="{{url('application',['action'=>'liste-vehicules'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="descritpion_vehicule" data-searchable="true">V&eacute;hicule</th>
            <th data-field="immatriculation" data-searchable="true">Matricule</th>
            <th data-field="nombre_place">Place</th>
            <th data-field="marque.libelle_marque">Marque</th>
            <th data-field="moteur.libelle_moteur">Moteur</th>
            <th data-field="puissance.libelle_puissance">Puissance</th>
            <th data-field="type_vehicule.libelle_type_vehicule">Type</th>
            <th data-field="type_vitesse">Bo&icirc;te</th>
            <th data-field="date_next_visites">Prochaine visite</th>
            @if(Auth::user()->compagnie_id==null)
            <th data-field="compagnie.libelle_compagnie">Compagnie</th>
            @endif
            @if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Compagnie')
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
            @endif
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 65%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-car fa-2x"></i>
                        Gestion des v&eacute;hicules
                    </span>
                </div>
                <div class="modal-body">
                    <input type="text" class="hidden" id="idVehiculeModifier" ng-hide="true" ng-model="vehicule.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Matricule du v&eacute;hicule *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="vehicule.immatriculation" id="immatriculation" name="immatriculation" placeholder="Matricule du véhicule" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Description *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="vehicule.descritpion_vehicule" id="descritpion_vehicule" name="descritpion_vehicule" placeholder="Descritpion du véhicule" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre de places *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="number" min="1" class="form-control" ng-model="vehicule.nombre_place" id="nombre_place" name="nombre_place" placeholder="Nbr place" required>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Marque *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <select name="marque_id" id="marque_id" ng-model="vehicule.marque_id" class="form-control" required>
                                        <option value="">-- Sectionner la marque --</option>
                                        @foreach($marques as $marque)
                                        <option value="{{$marque->id}}"> {{$marque->libelle_marque}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Moteur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-chrome"></i>
                                    </div>
                                    <select name="moteur_id" id="moteur_id" ng-model="vehicule.moteur_id" class="form-control" required>
                                        <option value="">-- Sectionner le moteur --</option>
                                        @foreach($moteurs as $moteur)
                                        <option value="{{$moteur->id}}"> {{$moteur->libelle_moteur}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Puissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bolt"></i>
                                    </div>
                                    <select name="puissance_id" id="puissance_id" ng-model="vehicule.puissance_id" class="form-control" required>
                                        <option value="">-- Sectionner la puissance --</option>
                                        @foreach($puissances as $puissance)
                                        <option value="{{$puissance->id}}"> {{$puissance->libelle_puissance}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bo&icirc;te *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="type_vitesse" id="type_vitesse" ng-model="vehicule.type_vitesse" ng-init="vehicule.type_vitesse='Manuelle'" class="form-control" required>
                                        <option value="Manuelle">Manuelle</option>
                                        <option value="Automatique">Automatique</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="type_vehicule_id" id="type_vehicule_id" ng-model="vehicule.type_vehicule_id" class="form-control" required>
                                        <option value="">-- Sectionner le type --</option>
                                        @foreach($type_vehicules as $type_vehicule)
                                        <option value="{{$type_vehicule->id}}"> {{$type_vehicule->libelle_type_vehicule}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de prochaine visite *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="vehicule.date_next_visites" id="date_next_visite" name="date_next_visite" placeholder="Date du visite prochaine" required>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal suppresion -->
<div class="modal fade bs-modal-suppression" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formSupprimer" ng-controller="formSupprimerCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Confimation de la suppression
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idVehiculeSupprimer"  ng-model="vehicule.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le v&eacute;hicule immatricul&eacute; <br/><b>@{{vehicule.immatriculation}}</b></div>
                        <div class="text-center vertical processing">Suppression en cours</div>
                        <div class="pull-right">
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Non</button>
                            <button type="submit" class="btn btn-danger btn-sm ">Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<form>
    <input type="hidden" id="user_compagnie" value="{{Auth::user()->compagnie_id}}">
</form>
<script type="text/javascript">
    var ajout = true;
    var $table = jQuery("#table"), rows = [];
    
    appCT.controller('formAjoutCtrl', function ($scope) { 
        $scope.populateForm = function (vehicule) {
            $scope.vehicule = vehicule;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.vehicule = {};
        };
    });

    appCT.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (vehicule) {
            $scope.vehicule = vehicule;
        };
        $scope.initForm = function () {
            $scope.vehicule = {};
        };
    });
    
    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
       
        $("#searchByCompagnie").select2({width: '100%', allowClear: true});
        
       $('#date_next_visite').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date()
        }); 
        
        $("#searchByCompagnie").change(function (e) {
            var compagnie = $("#searchByCompagnie").val();
            if(compagnie == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('application', ['action' => 'liste-vehicules'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-vehicules-by-compagnie/' + compagnie});
            }
        });
        
        $("#formAjout").submit(function (e) {
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formAjout .loader-overlay");

             if (ajout==true) {
                var methode = 'POST';
                var url = "{{route('application.vehicules.store')}}";
             }else{
                var id = $("#idVehiculeModifier").val();
                var methode = 'PUT';
                var url = 'vehicules/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idVehiculeSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('vehicules/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idVehicule) {
        ajout= false;
        var $scope = angular.element($("#formAjout")).scope();
        var vehicule =_.findWhere(rows, {id: idVehicule});
         $scope.$apply(function () {
            $scope.populateForm(vehicule);
        });
     
        $(".bs-modal-ajout").modal("show");
    }
  
    function deleteRow(idVehicule) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var vehicule =_.findWhere(rows, {id: idVehicule});
           $scope.$apply(function () {
              $scope.populateForm(vehicule);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
     function imprimePdf(){
        var user_compagnie = $("#user_compagnie").val();
        if(user_compagnie==""){
            var compagnie = $("#searchByCompagnie").val();
            if(compagnie==0){
                window.open("liste-vehicules-pdf/" ,'_blank');
            }else{
                window.open("liste-vehicules-by-compagnie-pdf/" + compagnie,'_blank');
            }
        }else{
            window.open("liste-vehicules-by-compagnie-pdf/" + user_compagnie,'_blank');
        }
    }
    function nomFormatter(id, row) { 
          return row.civilite + '. ' + row.full_name_vehicule;
    }

    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


