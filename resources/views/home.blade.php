@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <a style="text-decoration: none; color: #000000;" href="{{url('application/compagnies')}}">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-bank"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Compagnies</span>
                    <span class="info-box-number">{{$compagnies->count()}}</span>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('application/chauffeurs')}}">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                    <i class="fa fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Chauffeurs</span>
                    <span class="info-box-number">{{$chauffeurs->count()}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('parametre/localites')}}">
            <div class="info-box">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-map"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Localit&eacute;s</span>
                    <span class="info-box-number">{{$localites->count()}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('application/departs')}}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">
                    <i class="fa fa-list"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">D&eacute;parts du jour</span>
                    <span class="info-box-number">{{$departJour->count()}}</span>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Liste des prochains d&eacute;parts</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>D&eacute;part</th>
                                    <th>Destination</th>
                                    <th>Date du d&eacute;part</th>
                                    <th>Date d'arriv&eacute;e</th>
                                    <th>Place dispo.</th>
                                    <th>Compagnie</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($departs as $depart)
                                <tr>
                                    <td>{{$depart->localite_depart}}</td>
                                    <td>{{$depart->localite_arrive}}</td>
                                    <td>{{$depart->date_departs}}</td>
                                    <td>{{$depart->date_arrivees}}</td>
                                    <td>{{$depart->place_disponible}}</td>
                                    <td>{{$depart->libelle_compagnie}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                        <a href="{{route('application.departs.index')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
</div>
<div class="row">
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Nombre de d&eacute;part par jour selon les compagnies </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($compagnieByDeparts as $compagnie)
                            <span class="progress-text">{{$compagnie->libelle_compagnie}}</span>
                            <span class="progress-number"><b>{{$compagnie->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width:{{$compagnie->total}}%"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="{{route('application.departs.index')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div>  
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des compagnies par nombre de v&eacute;hicules </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($compagnieByVehicules as $compagnie)
                            <span class="progress-text">{{$compagnie->libelle_compagnie}}</span>
                            <span class="progress-number"><b>{{$compagnie->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width:{{$compagnie->total}}%"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="{{route('application.vehicules.index')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-md-6">  
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Liste des compagnies par nombre de chauffeurs </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="progress-group">
                            @foreach($compagnieByChauffeurs as $compagnie)
                            <span class="progress-text">{{$compagnie->libelle_compagnie}}</span>
                            <span class="progress-number"><b>{{$compagnie->total}}</b></span>
                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width:{{$compagnie->total}}%"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="{{route('application.chauffeurs.index')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
            </div>
        </div>
    </div>  
  
</div>
@endif
@if(Auth::user()->role == 'Compagnie')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <a style="text-decoration: none; color: #000000;">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-automobile"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">V&eacute;hicules</span>
                    <span class="info-box-number">{{$vehicules->count()}}</span>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('application/chauffeurs')}}">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                    <i class="fa fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Chauffeurs</span>
                    <span class="info-box-number">{{$chauffeurs->count()}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('application/departs')}}">
            <div class="info-box">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-calendar"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">D&eacute;parts du jour</span>
                    <span class="info-box-number">{{$departJour->count()}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a style="text-decoration: none; color: #000000;" href="{{url('application/departs')}}">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">
                    <i class="fa fa-list"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">D&eacute;parts total</span>
                    <span class="info-box-number">{{$departTotal->count()}}</span>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Liste des prochains d&eacute;parts</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>D&eacute;part</th>
                                    <th>Destination</th>
                                    <th>Date du d&eacute;part</th>
                                    <th>Date d'arriv&eacute;e</th>
                                    <th>Place dispo.</th>
                                    <th>V&eacute;hicule</th>
                                    <th>Chauffeur</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($departs as $depart)
                                <tr>
                                    <td>{{$depart->localite_depart}}</td>
                                    <td>{{$depart->localite_arrive}}</td>
                                    <td>{{$depart->date_departs}}</td>
                                    <td>{{$depart->date_arrivees}}</td>
                                    <td>{{$depart->place_disponible}}</td>
                                    <td>{{$depart->immatriculation}}</td>
                                    <td>{{$depart->full_name_chauffeur}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                        <a href="{{route('application.departs.index')}}" class="btn btn-xs btn-success pull-right">Voir plus</a>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
</div>
@endif
@endsection
