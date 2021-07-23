@extends('layouts.app')
@section('content')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<div class="col-md-12">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-yellow">
            <div class="widget-user-image">
            	<img class="img-circle" src="{{asset('images/profil.png')}}" alt="User Avatar">
            </div>
            <a href="{{route('auth.infos-profil-to-update')}}" class="btn btn-default pull-right">Modifier mes infos</a>
            <h3 class="widget-user-username">{{$user->full_name}}</h3>
            <h5 class="widget-user-desc">{{$user->role}}</h5>
        </div>
        <div class="box-footer no-padding">
        	<div class="row">
        		<div class="col-md-4">
		            <ul class="nav nav-stacked">
		            	<li><a>Nom : <b>{{$user->full_name}}</b></a></li>
		                <li><a>Contact : <b>{{$user->contact}}</b></a></li>
		                <li><a>E-mail : <b>{{$user->email}}</b></a></li>
		            </ul>
		        </div>
		        <div class="col-md-4">
		            <ul class="nav nav-stacked">
		              
		                <li><a>Role : <b>{{$user->role}}</b></a></li>
		                <li><a><button onClick="updatePasswordRow({{$user->id}});" class="btn btn-warning">Modifier mot de passe</button></a></li>
		            </ul>
		        </div>
		        <div class="col-md-4">
		            <ul class="nav nav-stacked">
		                <li><a>Inscrit le : <b>{{$user->created}}</b></a></li>
		                <li><a>Etat compte : <b>{{$user->statut_compte= 1 ? 'Actif':'Désactivé'}}</b></a></li>
		                <li><a>Derni&egrave;re connexion : <b>{{$user->last_login}}</b></a></li>
		            </ul>
		        </div>
		    </div>
        </div>
    </div>
</div>
<!-- Modal reset password -->
<div class="modal fade bs-modal-update-password" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formUpdatePassword" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-key fa-2x"></i>
                        Modifier mon mot de passe
                    </span>
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserPasswordModifier"/>
                    <input type="text" class="hidden" name="email" value="{{$user->email}}"/>
                    <div class="row">
                    	<div class="col-md-12">
                    		<div class="form-group">
                				<label>Ancien mot de passe *</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-lock"></i>
				                  </div>
				                  <input type="password" class="form-control" name="password" placeholder="Ancien mot de passe" required>
				                </div>
              				</div>
                    	</div>
                    	<div class="col-md-12">
                    		<div class="form-group">
                				<label>Nouveau mot de passe *</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-lock"></i>
				                  </div>
				                  <input type="password" minlength="8" class="form-control" name="new_password" placeholder="Nouveau mot de passe" required>
				                </div>
              				</div>
                    	</div>
                    	<div class="col-md-12">
                    		<div class="form-group">
                				<label>Confirmation du nouveau mot de passe *</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-lock"></i>
				                  </div>
				                  <input minlength="8" id="password-confirm" type="password" class="form-control form-control-rounded" name="password_confirmation" autocomplete="new-password" placeholder="Confirmer le nouveau mot de passe" required>
				                </div>
              				</div>
                    	</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-send"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
	function updatePasswordRow(idUser) {
            window.location.href = basePath + "/auth/update-password-page";
        };
</script>
@endsection