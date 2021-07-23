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
               data-url="{{url('application',['action'=>'liste-chauffeurs'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-formatter="nomFormatter" data-searchable="true">Nom complet</th>
            <th data-field="date_naissances">Date naiss.</th>
            <th data-field="contact_chauffeur">Contact</th>
            <th data-field="adresse_chauffeur">Adresse</th>
            <th data-field="groupe_sanguin" data-align="center">Groupr Sang.</th>
            <th data-field="numero_permis">N° permis</th>
            <th data-field="date_fin_permiss">Date validit&eacute; permis</th>
            <th data-field="date_prise_services">Date prise service</th>
            <th data-field="contact_en_cas_urgence" data-visible="false">Contact en cas urgence</th>
            <th data-field="contact_conjoint" data-visible="false">Contact conjoint(e)</th>
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
    <div class="modal-dialog" style="width: 70%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-user fa-2x"></i>
                        Gestion des chauffeurs
                    </span>
                </div>
                <div class="modal-body">
                    <input type="text" class="hidden" id="idChauffeurModifier" ng-hide="true" ng-model="chauffeur.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Civilit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="civilite" id="civilite" ng-model="chauffeur.civilite" ng-init="chauffeur.civilite='M'" class="form-control" required>
                                        <option value="M">Monsieur</option>
                                        <option value="Mme">Madame</option>
                                        <option value="Mlle">Mademoiselle</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom complet du chauffeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="chauffeur.full_name_chauffeur" id="full_name_chauffeur" name="full_name_chauffeur" placeholder="Nom et prénom(s) du chauffeur" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="chauffeur.date_naissances" id="date_naissance" name="date_naissance" placeholder="Date de naissance" required>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Groupe Sanguin *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="groupe_sanguin" id="groupe_sanguin" ng-model="chauffeur.groupe_sanguin" ng-init="chauffeur.groupe_sanguin='A+'" class="form-control" required>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact du chauffeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="chauffeur.contact_chauffeur" id="contact_chauffeur" name="contact_chauffeur" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact du chauffeur" required>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact du conjoint(e)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="chauffeur.contact_conjoint" id="contact_conjoint" name="contact_conjoint" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact du conjoint(e)">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact en cas d'urgence *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="chauffeur.contact_en_cas_urgence" id="contact_en_cas_urgence" name="contact_en_cas_urgence" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact en cas d'urgence" required>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de prise de service *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="chauffeur.date_prise_services" id="date_prise_service" name="date_prise_service" value="<?= date('d-m-Y'); ?>" required>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Adresse du chauffeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="chauffeur.adresse_chauffeur" id="adresse_chauffeur" name="adresse_chauffeur" placeholder="Adresse du domicile du chauffeur" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° permis de conduire *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="chauffeur.numero_permis" id="numero_permis" name="numero_permis" placeholder="N° permis de conduire" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de validit&eacute; du permis *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="chauffeur.date_fin_permiss" id="date_fin_permis" name="date_fin_permis" value="<?= date('d-m-Y'); ?>" required>                                
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
                    <input type="text" class="hidden" id="idChauffeurSupprimer"  ng-model="chauffeur.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le chauffeur <br/><b>@{{chauffeur.civilite +'. ' + chauffeur.full_name_chauffeur}}</b></div>
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
        $scope.populateForm = function (chauffeur) {
            $scope.chauffeur = chauffeur;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.chauffeur = {};
        };
    });

    appCT.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (chauffeur) {
            $scope.chauffeur = chauffeur;
        };
        $scope.initForm = function () {
            $scope.chauffeur = {};
        };
    });
    
    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
       
        $("#searchByCompagnie").select2({width: '100%', allowClear: true});
        
       $('#date_prise_service, #date_naissance').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        }); 
        
        $('#date_fin_permis').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date()
        }); 
        
        $("#searchByCompagnie").change(function (e) {
            var compagnie = $("#searchByCompagnie").val();
            if(compagnie == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('application', ['action' => 'liste-chauffeurs'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-chauffeurs-by-compagnie/' + compagnie});
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
                var url = "{{route('application.chauffeurs.store')}}";
             }else{
                var id = $("#idChauffeurModifier").val();
                var methode = 'PUT';
                var url = 'chauffeurs/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idChauffeurSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('chauffeurs/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idChauffeur) {
        ajout= false;
        var $scope = angular.element($("#formAjout")).scope();
        var chauffeur =_.findWhere(rows, {id: idChauffeur});
         $scope.$apply(function () {
            $scope.populateForm(chauffeur);
        });
     
        $(".bs-modal-ajout").modal("show");
    }
  
    function deleteRow(idChauffeur) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var chauffeur =_.findWhere(rows, {id: idChauffeur});
           $scope.$apply(function () {
              $scope.populateForm(chauffeur);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
     function imprimePdf(){
        var user_compagnie = $("#user_compagnie").val();
        if(user_compagnie==""){
            var compagnie = $("#searchByCompagnie").val();
            if(compagnie==0){
                window.open("liste-chauffeurs-pdf/" ,'_blank');
            }else{
                window.open("liste-chauffeurs-by-compagnie-pdf/" + compagnie,'_blank');
            }
        }else{
            window.open("liste-chauffeurs-by-compagnie-pdf/" + user_compagnie,'_blank');
        }
    }
    function nomFormatter(id, row) { 
          return row.civilite + '. ' + row.full_name_chauffeur;
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


