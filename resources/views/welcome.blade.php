@extends('layouts.site')
@section('content')

    <section class="probootstrap-cover overflow-hidden relative"  style="background-image:url('../assets/front/assets/images/bg_2.png');" data-stellar-background-ratio="0.5"  id="section-home">
    <div class="overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md">
                <h2 class="heading mb-2 display-4 font-light probootstrap-animate">Explorez la C&ocirc;te d'Ivoire avec facilité</h2>
                <p class="lead mb-5 probootstrap-animate">
            </div> 
            <div class="col-md probootstrap-animate">
                    <form method="post" action="{{route('serche-depart')}}" class="probootstrap-form">
                        @csrf
                    <div class="form-group">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="id_label_single">D&eacute;part</label>
                                    <label for="id_label_single" style="width: 100%;">
                                        <select class="js-example-basic-single js-states form-control" name="depart" id="id_label_single" style="width: 100%;">
                                            @foreach($localites as $localite)
                                            <option value="{{$localite->id}}">{{$localite->libelle_localite}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="id_label_single2">Destination</label>
                                    <div class="probootstrap_select-wrap">
                                        <label for="id_label_single2" style="width: 100%;">
                                            <select class="js-example-basic-single js-states form-control" name="destination" id="id_label_single2" style="width: 100%;">
                                                @foreach($localites as $localite)
                                                <option value="{{$localite->id}}">{{$localite->libelle_localite}}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END row -->
                        <div class="row mb-5">
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="probootstrap-date-departure">Date </label>
                                    <div class="probootstrap-date-wrap">
                                        <span class="icon ion-calendar"></span> 
                                        <input type="text" id="probootstrap-date-departure" name="date" class="form-control" value="<?=date('d-m-Y');?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END row -->
                        <div class="row">
                            <div class="col-md">
                                <input type="submit" value="Rechercher" style="cursor:pointer;" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
    <!-- END section -->

    <section class="probootstrap_section" id="section-city-guides">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Les meilleurs endroits du pays</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="{{asset('assets/front/assets/images/img_1.jpg')}}" alt="Free Template by ProBootstrap.com" class="img-fluid">
              <div class="probootstrap-text">
                <h3>Abidjan</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="{{asset('assets/front/assets/images/img_2.jpg')}}" alt="Free Template by ProBootstrap.com" class="img-fluid">
              <h3>Yamoussoukro</h3>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="{{asset('assets/front/assets/images/img_3.jpg')}}" alt="Free Template by ProBootstrap.com" class="img-fluid">
              <h3>Korhogo</h3>
            </a>
          </div>
          <div class="col-lg-3 col-md-6 probootstrap-animate mb-3">
            <a href="#" class="probootstrap-thumbnail">
              <img src="{{asset('assets/front/assets/images/img_4.jpg')}}" alt="Free Template by ProBootstrap.com" class="img-fluid">
              <h3>Sans P&eacute;dro</h3>
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
    
    <section class="probootstrap_section">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Nos services</h2>
          </div>
        </div>
      </div>
    </section>

    <section class="probootstrap-section-half d-md-flex" id="section-about">
      <div class="probootstrap-image probootstrap-animate" data-animate-effect="fadeIn" style="background-image: url('../assets/front/assets/images/img_2.jpg')"></div>
      <div class="probootstrap-text">
        <div class="probootstrap-inner probootstrap-animate" data-animate-effect="fadeInRight">
          <h2 class="heading mb-4">Service Clients</h2>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          <p><a href="#" class="btn btn-primary">Read More</a></p>
        </div>
      </div>
    </section>

    <section class="probootstrap-section-half d-md-flex">
      <div class="probootstrap-image order-2 probootstrap-animate" data-animate-effect="fadeIn" style="background-image: url('../assets/front/assets/images/img_3.jpg')"></div>
      <div class="probootstrap-text order-1">
        <div class="probootstrap-inner probootstrap-animate" data-animate-effect="fadeInLeft">
          <h2 class="heading mb-4">Options de paiement</h2>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          <p><a href="#" class="btn btn-primary">Learn More</a></p>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="probootstrap_section">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Voyager avec nous</h2>
          </div>
        </div>
        <div class="row probootstrap-animate">
          <div class="col-md-12">
            <div class="owl-carousel js-owl-carousel">
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-teatro-de-la-caridad"></span>
                <em>Teatro de la Caridad</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-royal-museum-of-the-armed-forces"></span>
                <em>Royal Museum of the Armed Forces</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-parthenon"></span>
                <em>Parthenon</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-marina-bay-sands"></span>
                <em>Marina Bay Sands</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-samarra-minaret"></span>
                <em>Samarra Minaret</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-chiang-kai-shek-memorial"></span>
                <em>Chiang Kai Shek Memorial</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-heuvelse-kerk-tilburg"></span>
                <em>Heuvelse Kerk Tilburg</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-cathedral-of-cordoba"></span>
                <em>Cathedral of Cordoba</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-london-bridge"></span>
                <em>London Bridge</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-taj-mahal"></span>
                <em>Taj Mahal</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-leaning-tower-of-pisa"></span>
                <em>Leaning Tower of Pisa</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-burj-al-arab"></span>
                <em>Burj al Arab</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-gate-of-india"></span>
                <em>Gate of India</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-osaka-castle"></span>
                <em>Osaka Castle</em>
              </a>
              <a class="probootstrap-slide" href="#">
                <span class="flaticon-statue-of-liberty"></span>
                <em>Statue of Liberty</em>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="probootstrap_section bg-light">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Plus de services</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_1.jpg')">
              </div>
              <div class="media-body">
                <h5 class="mb-3">01. Service Title Here</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
              </div>
            </div>
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_2.jpg')">
              </div>
              <div class="media-body">
                <h5 class="mb-3">02. Service Title Here</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_4.jpg')">
              </div>
              <div class="media-body">
                <h5 class="mb-3">03. Service Title Here</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
              </div>
            </div>
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_5.jpg')">
              </div>
              <div class="media-body">
                <h5 class="mb-3">04. Service Title Here</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="probootstrap_section" id="section-feature-testimonial">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-12 text-center mb-5 probootstrap-animate">
              <h2 class="display-4 border-bottom probootstrap-section-heading">T&eacute;moignage</h2>
            <blockquote class="">
              <p class="lead mb-4"><em>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</em></p>
              <p class="probootstrap-author">
                <a href="#" target="_blank">
                  <img src="{{asset('assets/front/assets/images/person_1.jpg')}}" alt="Free Template by ProBootstrap.com" class="rounded-circle">
                  <span class="probootstrap-name">James Smith</span>
                  <span class="probootstrap-title">Chief Executive Officer</span>
                </a>
              </p>
            </blockquote>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="probootstrap_section bg-light">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Informations</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_1.jpg')">
              </div>
              <div class="media-body">
                <span class="text-uppercase">January 1st 2018</span>
                <h5 class="mb-3">Travel To United States Without Visa</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                <p><a href="#">Read More</a></p>
              </div>
            </div>
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_2.jpg')">
              </div>
              <div class="media-body">
                <span class="text-uppercase">January 1st 2018</span>
                <h5 class="mb-3">Travel To United States Without Visa</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                <p><a href="#">Read More</a></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_4.jpg')">
              </div>
              <div class="media-body">
                <span class="text-uppercase">January 1st 2018</span>
                <h5 class="mb-3">Travel To United States Without Visa</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                <p><a href="#">Read More</a></p>
              </div>
            </div>
            <div class="media probootstrap-media d-flex align-items-stretch mb-4 probootstrap-animate">
              <div class="probootstrap-media-image" style="background-image: url('../assets/front/assets/images/img_5.jpg')">
              </div>
              <div class="media-body">
                <span class="text-uppercase">January 1st 2018</span>
                <h5 class="mb-3">Travel To United States Without Visa</h5>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                <p><a href="#">Read More</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->

    <section class="probootstrap_section">
      <div class="container">
        <div class="row text-center mb-5 probootstrap-animate">
          <div class="col-md-12">
            <h2 class="display-4 border-bottom probootstrap-section-heading">Compagnies de transport</h2>
          </div>
        </div>
        <div class="row probootstrap-animate">
          <div class="col-md-12">
            <div class="owl-carousel js-owl-carousel-2">
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_2.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_1.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_3.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_4.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_5.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_2.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_1.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_3.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_4.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
              <div>
                <div class="media probootstrap-media d-block align-items-stretch mb-4 probootstrap-animate">
                  <img src="{{('../assets/front/assets/images/sq_img_5.jpg')}}" alt="Free Template by ProBootstrap" class="img-fluid">
                  <div class="media-body">
                    <h5 class="mb-3">02. Service Title Here</h5>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
                  </div>
                </div>
              </div>
              <!-- END slide item -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END section -->
@endsection
