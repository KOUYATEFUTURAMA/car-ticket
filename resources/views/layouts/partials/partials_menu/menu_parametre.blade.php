<li class="{{request()->is('parametre/*') ? 'active treeview' : 'treeview'}}">
          <a href="#">
              <i class="fa fa-cogs"></i> <span>Param&egrave;tre</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
               <li class="{{Route::currentRouteName() === 'parametre.localites.index'
                        ? 'active' : ''
                  }}">
                  <a href="{{route('parametre.localites.index')}}">
                      &nbsp;&nbsp;&nbsp;<i class="fa fa-map-marker"></i> Localit&eacute;
                  </a>
                </li>
              <li class="{{Route::currentRouteName() === 'parametre.marques.index'
                        ? 'active' : ''
                  }}">
                  <a href="{{route('parametre.marques.index')}}">
                      &nbsp;&nbsp;&nbsp;<i class="fa fa-cubes"></i> Marque
                  </a>
              </li>
              <li class="{{Route::currentRouteName() === 'parametre.moteurs.index'
                        ? 'active' : ''
                  }}">
                  <a href="{{route('parametre.moteurs.index')}}">
                      &nbsp;&nbsp;&nbsp;<i class="fa fa-chrome"></i> Moteur
                  </a>
              </li>
               <li class="{{Route::currentRouteName() === 'parametre.puissances.index'
                        ? 'active' : ''
                  }}">
                  <a href="{{route('parametre.puissances.index')}}">
                      &nbsp;&nbsp;&nbsp;<i class="fa fa-bolt"></i> Puissance
                  </a>
              </li>
               <li class="{{Route::currentRouteName() === 'parametre.type-vehicules.index'
                        ? 'active' : ''
                  }}">
                  <a href="{{route('parametre.type-vehicules.index')}}">
                      &nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i> Type de v&eacute;hicule
                  </a>
              </li>
          </ul>
