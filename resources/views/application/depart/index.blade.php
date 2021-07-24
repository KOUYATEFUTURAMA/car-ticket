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
<div class="col-md-3">
    <select class="form-control" id="localiteDepart">
        <option value="0" >-- Localit&eacute;s du d&eacute;part --</option>
        @foreach($localites as $localite)
        <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
        @endforeach
    </select>
</div>
<div class="col-md-3">
    <select class="form-control" id="localiteArrive">
        <option value="0" >-- Localit&eacute;s de destination --</option>
        @foreach($localites as $localite)
        <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
        @endforeach
    </select>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Date du départ">
    </div>
</div>
@if(Auth::user()->compagnie_id==null)
<div class="col-md-3">
    <select class="form-control" id="searchByCompagnie">
        <option value="0" >-- Toutes les compagnies --</option>
        @foreach($compagnies as $compagnie)
        <option value="{{$compagnie->id}}"> {{$compagnie->libelle_compagnie}}</option>
        @endforeach
    </select>
</div>
<div class="col-md-1">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
@endif
<table id="table" class="table table-warning table-striped box box-success"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('application',['action'=>'liste-departs'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="localite_depart.libelle_localite">D&eacute;part</th>
            <th data-field="localite_arrive.libelle_localite">Destination</th>
            <th data-field="date_departs">Date du d&eacute;part</th>
            <th data-field="date_arrivees">Date d'arriv&eacute;e</th>
            <th data-field="tarif" data-formatter="montantFormatter" data-align="center">Tarif</th>
            <th data-field="place_disponible" data-align="center">Place dispo.</th>
            <th data-field="place_vendue" data-align="center" data-visible="false">Place vendue</th>
            <th data-field="vehicule.immatriculation" data-visible="false">V&eacute;hicule</th>
            <th data-formatter="nomFormatter" data-visible="false">Chauffeur</th>
            <th data-field="statut" data-formatter="statutFormatter">Statut</th>
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
                        <i class="fa fa-calendar fa-2x"></i>
                        Gestion des d&eacute;parts
                    </span>
                </div>
                <div class="modal-body">
                    <input type="text" class="hidden" id="idDepartModifier" ng-hide="true" ng-model="depart.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Localit&eacute; du d&eacute;part *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <select name="localite_depart" id="localite_depart" class="form-control" required>
                                        <option value="">-- Sectionner la localit&eacute; --</option>
                                        @foreach($localites as $localite)
                                        <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date et heure du d&eacute;part *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="depart.date_departs" id="date_depart" name="date_depart" placeholder="Date du départ" required>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tarif *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="number" min="0" class="form-control" ng-model="depart.tarif" id="tarif" name="tarif" placeholder="Tarif" required>                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Localit&eacute; de destination *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <select name="localite_arrive" id="localite_arrive" class="form-control" required>
                                        <option value="">-- Sectionner la localit&eacute; --</option>
                                        @foreach($localites as $localite)
                                        <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date et heure d'arriv&eacute;e *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="depart.date_arrivees" id="date_arrivee" name="date_arrivee" placeholder="Date d'arrivée" required>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Statut *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <select name="statut" id="statut" ng-model="depart.statut" ng-init="depart.statut='1'" class="form-control" required>
                                        <option value="1">En cours</option>
                                        <option value="2">D&eacute;part effectu&eacute;</option>
                                        <option value="3">D&eacute;part annul&eacute;</option>
                                    </select>                               
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Chauffeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <select name="chauffeur_id" id="chauffeur_id" class="form-control" required>
                                        <option value="">-- Sectionner le chauffeur --</option>
                                        @foreach($chauffeurs as $chauffeur)
                                        <option value="{{$chauffeur->id}}"> {{$chauffeur->civilite.'. '.$chauffeur->full_name_chauffeur}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>V&eacute;hicule *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <select name="vehicule_id" id="vehicule_id" class="form-control" required>
                                        <option value="">-- Sectionner le v&eacute;hicule --</option>
                                        @foreach($vehicules as $vehicule)
                                        <option value="{{$vehicule->id}}"> {{$vehicule->immatriculation}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Places disponibles *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="number" min="1" class="form-control" ng-model="depart.place_disponible" id="place_disponible" name="place_disponible" placeholder="Nbr place à vendre" required>                                
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
                <div class="modal-body">
                    <input type="text" class="hidden" id="idDepartSupprimer"  ng-model="depart.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le d&eacute;part <br/>de <b>@{{depart.localite_depart.libelle_localite}}</b> vers <b>@{{depart.localite_arrive.libelle_localite}}</b> pour le <b>@{{depart.date_departs}}</b></div>
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
        $scope.populateForm = function (depart) {
            $scope.depart = depart;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.depart = {};
        };
    });

    appCT.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (depart) {
            $scope.depart = depart;
        };
        $scope.initForm = function () {
            $scope.depart = {};
        };
    });
    
    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
       
        $("#localite_depart, #localite_arrive, #vehicule_id, #chauffeur_id, #localiteDepart, #localiteArrive, #searchByCompagnie").select2({width: '100%', allowClear: true});
        
        $('#date_depart, #date_arrivee').datetimepicker({
            timepicker: true,
            formatDate: 'd-m-Y',
            formatTime: 'H:i',
            format: 'd-m-Y H:i',
            local : 'fr',
            minDate : new Date()
        }); 
        
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date()
        }); 
        
        $("#localiteDepart, #localiteArrive").change(function (e) {
            var user_compagnie = $("#user_compagnie").val();
            if(user_compagnie==""){
               $("#searchByCompagnie").select2("val", 0); 
            }
            
            var date = $("#searchByDate").val();
            var arriver = $("#localiteArrive").val();
            var depart = $("#localiteDepart").val();
            if(arriver===depart && (arriver!=0 || depart!=0)){
                alert("Vous avez choisi la même localité pour la recherche...");
                 return false;
            }
            if(date == "" && arriver==0 && depart==0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('application', ['action' => 'liste-departs'])}}"});
            }
            if(date != "" && arriver==0 && depart==0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-date/' + date});
            }
            if(date == "" && arriver!=0 && depart!=0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-localites/' + depart + '/' + arriver});
            }
            if(date != "" && arriver!=0 && depart!=0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-localites-date/' + depart + '/' + arriver + '/'+date});
            }
        });
        
        $("#searchByDate").change(function (e) {
            var user_compagnie = $("#user_compagnie").val();
            if(user_compagnie==""){
               $("#searchByCompagnie").select2("val", 0); 
            }
            var date = $("#searchByDate").val();
            var arriver = $("#localiteArrive").val();
            var depart = $("#localiteDepart").val();
            if(date == "" && arriver==0 && depart==0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('application', ['action' => 'liste-departs'])}}"});
            }
            if(date != "" && arriver==0 && depart==0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-date/' + date});
            }
            if(date == "" && arriver!=0 && depart!=0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-localites/' + depart + '/' + arriver});
            }
            if(date != "" && arriver!=0 && depart!=0){
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-localites-date/' + depart + '/' + arriver + '/'+date});
            }
        });
        $("#searchByCompagnie").change(function (e) {
            var compagnie = $("#searchByCompagnie").val();
            if(compagnie == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('application', ['action' => 'liste-departs'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../application/liste-departs-by-compagnie/' + compagnie});
            }
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#localite_depart, #localite_arrive, #vehicule_id, #chauffeur_id").val('').trigger('change');
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
                var url = "{{route('application.departs.store')}}";
             }else{
                var id = $("#idDepartModifier").val();
                var methode = 'PUT';
                var url = 'departs/' + id;
             }
            editerDepartAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idDepartSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('departs/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idDepart) {
        ajout= false;
        var $scope = angular.element($("#formAjout")).scope();
        var depart =_.findWhere(rows, {id: idDepart});
         $scope.$apply(function () {
            $scope.populateForm(depart);
        });
        $("#localite_depart").select2("val", depart.localite_depart.id);
        $("#localite_arrive").select2("val", depart.localite_arrive.id);
        $("#vehicule_id").select2("val", depart.vehicule_id);
        $("#chauffeur_id").select2("val", depart.chauffeur_id);
     
        $(".bs-modal-ajout").modal("show");
    }
  
    function deleteRow(idDepart) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var depart =_.findWhere(rows, {id: idDepart});
           $scope.$apply(function () {
              $scope.populateForm(depart);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
     function imprimePdf(){
        var user_compagnie = $("#user_compagnie").val();
        if(user_compagnie==""){
            var compagnie = $("#searchByCompagnie").val();
            var arrivee = $("#localiteArrive").val();
            var depart = $("#localiteDepart").val();
            var date = $("#searchByDate").val();
            if(compagnie==0 && arrivee == 0 && depart==0 && date==""){
                window.open("liste-departs-pdf/" ,'_blank');
            }
            if(compagnie!=0){
                window.open("liste-departs-by-compagnie-pdf/" + compagnie,'_blank');
            }
            if(arrivee != 0 && depart!=0 && date==""){
                window.open("liste-departs-by-localites-pdf/" + depart + "/" + arrivee,'_blank');
            }
            if(arrivee == 0 && depart==0 && date!=""){
                window.open("liste-departs-by-date-pdf/" + date,'_blank');
            }
            if(arrivee != 0 && depart!=0 && date!=""){
                window.open("liste-departs-by-localites-date-pdf/" + depart + "/" + arrivee + "/" + date,'_blank');
            }
        }else{
            window.open("liste-departs-by-compagnie-pdf/" + user_compagnie,'_blank');
        }
    }
    function nomFormatter(id, row) { 
          return row.chauffeur.civilite + '. ' + row.chauffeur.full_name_chauffeur;
    }
    function montantFormatter(montant){
        return "<span class='text-bold'>" +  $.number(montant) + "</span>";
    }
    function statutFormatter(statut){
        if(statut==1){
            return "<span class='text-bold text-green'>En cours</span>";
        }
        if(statut==2){
            return "<span class='text-bold'>Départ effectué</span>";
        }
        if(statut==3){
            return "<span class='text-bold text-red'>Départ annulé</span>";
        }
    }
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    function editerDepartAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
        jQuery.ajax({
            type: methode,
            url: url,
            cache: false,
            data: formData,
            success:function (reponse, textStatus, xhr){
                if (reponse.code === 1) {
                    var $scope = angular.element($formObject).scope();
                    $scope.$apply(function () {
                        $scope.initForm();
                    });
                    if (ajout) { //creation
                        $table.bootstrapTable('refresh');
                    } else { //Modification
                        $table.bootstrapTable('updateByUniqueId', {
                            id: reponse.data.id,
                            row: reponse.data
                        });
                        $table.bootstrapTable('refresh');
                        $(".bs-modal-ajout").modal("hide");
                    }
                    $("#localite_depart, #localite_arrive, #vehicule_id, #chauffeur_id").val('').trigger('change');
                    $formObject.trigger('eventAjouter', [reponse.data]);
                }
                $.gritter.add({
                    // heading of the notification
                    title: "Car-Ticket",
                    // the text inside the notification
                    text: reponse.msg,
                    sticky: false,
                    image: basePath + "/assets/img/gritter/confirm.png",
                });
            },
            error: function (err) {
                var res = eval('('+err.responseText+')');
                var messageErreur = res.message;
                $.gritter.add({
                    // heading of the notification
                    title: "Car-Ticket",
                    // the text inside the notification
                    text: messageErreur,
                    sticky: false,
                    image: basePath + "/assets/img/gritter/confirm.png",
                });
                $formObject.removeAttr("disabled");
                $ajoutLoader.hide();
            },
            beforeSend: function () {
                $formObject.attr("disabled", true);
                $ajoutLoader.show();
            },
            complete: function () {
                $ajoutLoader.hide();
            },
        });
    };
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


