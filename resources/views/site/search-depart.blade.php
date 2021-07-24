@extends('layouts.site')
@section('content')
    <section class="probootstrap_section">
            <div class="container">
                <div class="col-md probootstrap-animate">
                    <form method="post" action="{{route('serche-depart')}}" class="probootstrap-form">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id_label_single">D&eacute;part</label>
                                        <label for="id_label_single" style="width: 100%;">
                                            <select class="js-example-basic-single js-states form-control" name="depart" id="id_label_single" style="width: 100%;">
                                                @foreach($localites as $localite)
                                                <option value="{{$localite->id}}" {{$localite->id == $depart ? 'selected' : ''}}>{{$localite->libelle_localite}}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id_label_single2">Destination</label>
                                        <div class="probootstrap_select-wrap">
                                            <label for="id_label_single2" style="width: 100%;">
                                                <select class="js-example-basic-single js-states form-control" name="destination" id="id_label_single2" style="width: 100%;">
                                                    @foreach($localites as $localite)
                                                    <option value="{{$localite->id}}" {{$localite->id == $arrivee ? 'selected' : ''}}>{{$localite->libelle_localite}}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="probootstrap-date-departure">Date </label>
                                        <div class="probootstrap-date-wrap">
                                            <span class="icon ion-calendar"></span> 
                                            <input type="text" id="probootstrap-date-departure" name="date" class="form-control" value="{{$dates}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="probootstrap-date-departure"> </label>
                                    <button type="submit" class="btn btn-primary" style="cursor:pointer;">Rechercher</button>
                                </div>
                                <div class="col-md-12"><br/>
                                    <a href="/"> <------- Retour</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row text-center mb-5 probootstrap-animate">
                    <div class="col-md-12">
                        <h4 class=" border-bottom probootstrap-section-heading">Liste des d&eacute;parts</h4>
                    </div>
                </div>
                <div class="row">
                    @if($departs->count()>0)
                        @foreach($departs as $depart)
                        <div class="col-md-2">
                            <span style="color: #000; font-weight: bold;">{{$depart->libelle_compagnie}}</span><br/>
                            <img src="{{url($depart->logo)}}" height="100" width="100"/>
                        </div>
                        <div class="col-md-6"><br/><br/>
                            <span style="color: #000; font-weight: bold;">{{$depart->depart}} <------------> {{$depart->arrive}}</span><br/>
                            <span style="color: #000; font-weight: bold;">Place disponible : {{$depart->place_disponible}}&nbsp;&nbsp;&nbsp;  Tarif : {{number_format($depart->tarif, 0, ',', ' ')}} F CFA</span>
                        </div>
                        <div class="col-md-4"><br/><br/>
                            <span style="color: #000; font-weight: bold;">{{$depart->date_departs}} <--> {{$depart->date_arrivees}}</span><br/>
                            <button class="btn btn-primary btn-sm" style="cursor:pointer;">Choisir votre si&egrave;ge</button>
                        </div>
                        @endforeach
                    @else
                        <div class="col-md-12 text-center">
                            <h4 class="probootstrap-section-heading">Aucun r&eacute;sultat pour votre recherche</h4>
                        </div>
                    @endif
                </div>
            </div>
    </section>
@endsection


