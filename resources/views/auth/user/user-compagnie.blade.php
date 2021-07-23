@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur')
 <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
 <script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
 <script src="{{asset('assets/js/underscore-min.js')}}"></script>
 <script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
 <script src="{{asset('assets/js/fonction_crude.js')}}"></script>
 <script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
 <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
 <link href="{{asset('assets/plugins/iCheck/square/orange.css')}}" rel="stylesheet">
 <link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
 <table id="table" class="table table-warning table-striped box box-warning"
                           data-pagination="true"
                           data-search="true"
                           data-toggle="table"
                           data-show-columns="false"
                           data-url="{{url('auth', ['action'=>'liste-users-compagnie'])}}"
                           data-unique-id="token"
                           data-toolbar="#toolbar"
                           data-show-toggle="false">
        <thead>
           <tr>
            <th data-field="full_name" data-sortable="true" data-searchable="true">Utisateur</th>
            <th data-field="email" data-searchable="true">E-mail</th>
            <th data-field="contact" data-searchable="true">Contact</th>
            <th data-field="compagnie.libelle_compagnie" data-searchable="true">Compagnie</th>
            <th data-field="statut_compte" data-formatter="etatCompteFormatter">Etat</th>
            <th data-field="last_login">Derni&egrave;re connexion</th>
            <th data-field="id" data-width="80px" data-align="center" data-formatter="optionFormatter"><i class="fa fa-wrench"></i></th>
        </tr>
        </thead>
    </table>
<!-- Modal fermeture de compte -->
<div class="modal fade bs-modal-lokked-acount" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formLokedAcount" ng-controller="formLokedAcountCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Confimation de l'op&eacute;ration
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserLokedAcount"  ng-model="user.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir <b>@{{user.statut_compte==1?'désactiver' : 'activer'}}</b> le compte de l'utilisateur <br/><b>@{{user.full_name}}</b></div>
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

<!-- Modal reset password -->
<div class="modal fade bs-modal-reset-password" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formPasswordReset" ng-controller="formPasswordResetCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Confimation de l'op&eacute;ration
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserPasswordReset"  ng-model="user.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir r&eacute;initialiser le mot de passe de cet utilisateur <br/><b>@{{user.full_name}}</b></div>
                        <div class="text-center vertical processing">R&eacute;initialisation en cours</div>
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

    appCT.controller('formLokedAcountCtrl', function ($scope) {
        $scope.populateForm = function (user) {
        $scope.user = user;
        };
        $scope.initForm = function () {
        $scope.user = {};
        };
    });
    appCT.controller('formPasswordResetCtrl', function ($scope) {
        $scope.populateForm = function (user) {
        $scope.user = user;
        };
        $scope.initForm = function () {
        $scope.user = {};
        };
    });
     $(function () {
     	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });

        $("#formLokedAcount").submit(function (e) {
		    e.preventDefault();
		    var id = $("#idUserLokedAcount").val();
		    var formData = $(this).serialize();
		    var $question = $("#formLokedAcount .question");
		    var $ajaxLoader = $("#formLokedAcount .processing");
		    lokedAcountAction('users/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
    	});

        $("#formPasswordReset").submit(function (e) {
            e.preventDefault();
            var id = $("#idUserPasswordReset").val();
            var formData = $(this).serialize();
            var $question = $("#formPasswordReset .question");
            var $ajaxLoader = $("#formPasswordReset .processing");
            resetPasswordAction('reset_password_manualy/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
     });

    function lokedAcountRow(idUser) {
        var $scope = angular.element($("#formLokedAcount")).scope();
        var user =_.findWhere(rows, {id: idUser});
        $scope.$apply(function () {
        $scope.populateForm(user);
        });
        $(".bs-modal-lokked-acount").modal("show");
    }

    function updatePasswordRow(idUser) {
    var $scope = angular.element($("#formPasswordReset")).scope();
    var user =_.findWhere(rows, {id: idUser});
    $scope.$apply(function () {
    $scope.populateForm(user);
    });
    $(".bs-modal-reset-password").modal("show");
    }
    function optionFormatter(id, row) {
        if(row.statut_compte==0){
            return '<button class="btn btn-xs btn-success" data-placement="left" data-toggle="tooltip" title="Activer" onClick="javascript:lokedAcountRow(' + id + ');"><i class="fa fa-check"></i></button>';
            }else{
                 return '<button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Reset password" onClick="javascript:updatePasswordRow(' + id + ');"><i class="fa fa-refresh"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Désactiver" onClick="javascript:lokedAcountRow(' + id + ');"><i class="fa fa-remove"></i></button>';
            }
    
    }
    function etatCompteFormatter(etat){
        return etat==1 ? "<span class='label label-success'>Active</span>":"<span class='label label-danger'>Fermé</span>";
    }

//Réinitialiser un mot de passe
function resetPasswordAction(url, formData, $question, $ajaxLoader, $table) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (reponse) {
            if (reponse.code === 1) {
                $(".bs-modal-reset-password").modal("hide");
                $table.bootstrapTable('refresh');
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
            //alert(res.message);
            //alert(Object.getOwnPropertyNames(res));
            $.gritter.add({
                // heading of the notification
                title: "Car-Ticket",
                // the text inside the notification
                text: res.message,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png"
            });
            $ajaxLoader.hide();
            $question.show();
        },
        beforeSend: function () {
            $question.hide();
            $ajaxLoader.show();
        },
        complete: function () {
            $ajaxLoader.hide();
            $question.show();
        }
    });
}

//Fermer un compte
   //Réinitialiser un mot de passe
function lokedAcountAction(url, formData, $question, $ajaxLoader, $table) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (reponse) {
            if (reponse.code === 1) {
                $(".bs-modal-lokked-acount").modal("hide");
                $table.bootstrapTable('refresh');
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
            //alert(res.message);
            //alert(Object.getOwnPropertyNames(res));
            $.gritter.add({
                // heading of the notification
                title: "Car-Ticket",
                // the text inside the notification
                text: res.message,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png"
            });
            $ajaxLoader.hide();
            $question.show();
        },
        beforeSend: function () {
            $question.hide();
            $ajaxLoader.show();
        },
        complete: function () {
            $ajaxLoader.hide();
            $question.show();
        }
    });
}
</script>
@else 
@include('layouts.partials.look_page')
@endif
@endsection