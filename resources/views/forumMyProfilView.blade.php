<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>
  <body>
    <div class="container" style="margin-top: 35px">
      <div class="page-header page-heading col-lg-12 text-center">
        <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
      </div>
    </div>
    </br>

    <!-- Barre de navigation -->
    <ul class="nav nav-pills col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
      <li role="presentation" class="active"><a href="{{url('forum/')}}">Index</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myProfil')}}">Profil</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myPosts')}}">Mes Messages</a></li>
      <li role="presentation"><a href="{{url('forum/admin')}}">Admin</a></li>
    <li role="presentation"><a href="{{url('')}}">Revenir au site M2L</a></li>
    </ul>
    
    <!-- Navigation menu -->
    <div class="dropdown col-lg-offset-6 col-lg-6 col-md-offset-6 col-md-6 col-sm-offset-6 col-sm-6">
      <div>
        <button class="btn btn-primary dropdown-toggle col-md-offset-7 col-sm-offset-6 col-md-4 col-sm-5 col-xs-12" type="button" data-toggle="dropdown">Navigation Forum
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li class="dropdown-header" >
            <a style="font-weight:bold" href="{{url('forum')}}">Index</a>
          </li>
          @foreach($categories as $cats)  <!-- print the categories in the dropdown menu -->
            <li class="dropdown-header">
              <a style="font-weight:bold" href="{{url('forum/'.$cats->cat_id.'/')}}">
                <h4>{{$cats->cat_nom}}</h4>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    <div class="col-lg-offset-1 col-lg-11 col-md-offset-1 col-md-12 col-sm-offset-1 col-sm-11">
      <h2  href="{{url('forum/'.Auth::id().'/myProfil')}}">
        <a href="{{url('forum/'.Auth::id().'/myProfil')}}">Profil de : {{Auth::getPrenomById($userId)}} {{Auth::getNomById($userId)}} ({{Auth::getNameById($userId)}})</a>
      </h2>
    </div>
    <div class='col-lg-offset-1 col-lg-10 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'>
      <div class="panel panel-info">
        <div class="panel-body panel-info">
          <div class="col-lg-5 col-md-6 col-sm-6">
            <h3>Nombre de messages postés : </h3>
          </div>
          <div class="col-lg-2 col-md-6">
            <h3>{{$nbPost}}</h3>
          </div>
        </div>
      </div>
    </div>
    <div class='col-lg-offset-1 col-lg-10 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'>
      <div class="panel panel-info">
        <div class="panel-body panel-info">
          <div class="col-lg-5 col-md-6 col-sm-6">
            <h3>Nombre de Topics créés : </h3>
          </div>
          <div class="col-lg-2 col-md-6">
            <h3>{{$nbTopic}}</h3>
          </div>
        </div>
      </div>
    </div>
  </body>
  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>
  </footer>
</html> 

