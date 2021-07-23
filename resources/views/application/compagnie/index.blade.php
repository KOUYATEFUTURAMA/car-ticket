@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur')
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
<div class="col-md-6">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-primary"
               data-pagination="true"
               data-search="true" 
               data-toggle="table"
               data-url="{{url('application',['action'=>'liste-compagnies'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="libelle_compagnie" data-searchable="true">Compagnie</th>
            <th data-field="adresse_complet">Adresse</th>
            <th data-field="contact_compagnie">Contact</th>
            <th data-field="email_compagnie">E-mail</th>
            <th data-field="responsable">Responsable</th>
            <th data-field="contact_responsable">Contact Resp.</th>
            <th data-field="longitude"data-visible="false">Longitude</th>
            <th data-field="latitude" data-visible="false">Latitude</th>
            <th data-field="logo" data-align="center" data-formatter="imageFormatter">Image </th>
            @if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
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
                        <i class="fa fa-institution fa-2x"></i>
                        Gestion des compagnies
                    </span>
                </div>
                <div class="modal-body">
                    <input type="text" class="hidden" id="idCompagnieModifier" name="idCompagnie" ng-hide="true" ng-model="compagnie.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nom de la compagnie *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="compagnie.libelle_compagnie" id="libelle_compagnie" name="libelle_compagnie" placeholder="Nom de la compagnie" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Situation g&eacute;ographique *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="compagnie.adresse_complet" id="adresse_complet" name="adresse_complet" placeholder="Adresse géographie" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact de la compagnie *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="compagnie.contact_compagnie" id="contact_compagnie" name="contact_compagnie" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact de la compagnie" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>E-mail *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="email" class="form-control" ng-model="compagnie.email_compagnie" id="email_compagnie" name="email_compagnie" placeholder="Adresse mail de la compagnie" required>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logo de la compagnie</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-photo"></i>
                                    </div>
                                    <input type="file" class="form-control" name="logo">                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nom complet du responsable *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="compagnie.responsable" id="responsable" name="responsable" placeholder="Nom et prénom du responsable de la compagnie" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact du responsable*</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="compagnie.contact_responsable" id="contact_responsable" name="contact_responsable" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact du responsable" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Longitude</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="compagnie.longitude" id="longitude" name="longitude" placeholder="Longitude...">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Latitude</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="compagnie.latitude" id="latitude" name="latitude" placeholder="Latitude...">
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
                    <input type="text" class="hidden" id="idCompagnieSupprimer"  ng-model="compagnie.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la compagnie <br/><b>@{{compagnie.libelle_compagnie}}</b></div>
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

<script type="text/javascript">
    var ajout = true;
    var $table = jQuery("#table"), rows = [];
    
    appCT.controller('formAjoutCtrl', function ($scope) { 
        $scope.populateForm = function (compagnie) {
            $scope.compagnie = compagnie;
        };
        $scope.initForm = function () {
            ajout = true;
            $scope.compagnie = {};
        };
    });

    appCT.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (compagnie) {
            $scope.compagnie = compagnie;
        };
        $scope.initForm = function () {
            $scope.compagnie = {};
        };
    });
    
    $(function () {
       $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
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
                var url = "{{route('application.compagnies.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('application.update-compagnie')}}";
             }
             var formData = new FormData($(this)[0]);
            editerCompagnieAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCompagnieSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('compagnies/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idCompagnie) {
        ajout= false;
        var $scope = angular.element($("#formAjout")).scope();
        var compagnie =_.findWhere(rows, {id: idCompagnie});
         $scope.$apply(function () {
            $scope.populateForm(compagnie);
        });
     
        $(".bs-modal-ajout").modal("show");
    }
  
    function deleteRow(idCompagnie) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var compagnie =_.findWhere(rows, {id: idCompagnie});
           $scope.$apply(function () {
              $scope.populateForm(compagnie);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function imprimePdf(){
        window.open("liste-compagnies-pdf/" ,'_blank');
    }

    function imageFormatter(image) { 
          return image ? "<a target='_blank' href='" + basePath + '/' + image + "'>Voir le logo</a>" : "";
    }

    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }

    
    function editerCompagnieAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
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


