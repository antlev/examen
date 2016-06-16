<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/forum.css')}}">
    
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>

  <div class="container" style="margin-top: 35px">
    <div class="page-header page-heading col-lg-12 text-center">
      <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
    </div>
  </div>
  <!-- Barre de navigation -->
  <ul class="nav nav-pills col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
    <li role="presentation" class="active"><a href="{{url('forum/')}}">Index</a></li>
    <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myProfil')}}">Profil</a></li>
    <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myPosts')}}">Mes Messages</a></li>
    <li role="presentation"><a href="{{url('forum/admin')}}">Admin</a></li>
    <li role="presentation"><a href="{{url('')}}">Revenir au site M2L</a></li>
  </ul>
  </br>

  <div class="col-lg-12">
    <div class="col-lg-6">
      <div class="col-lg-offset-1 ">
        <h3 style="margin-left:15px">Vous êtes dans la catégorie <a href="{{url('forum/'.$cat)}}">{{$catName}}</a></h3></br>
      </div>
    </div>

    <!-- Menu de navigation -->
    <div class="dropdown col-lg-6 col-md-6 col-sm-6">
      <button class="btn btn-primary dropdown-toggle col-lg-offset-8 col-md-offset-7 col-sm-offset-6 col-lg-3 col-md-4 col-sm-5 col-xs-12" type="button" data-toggle="dropdown">Navigation Forum
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li class="dropdown-header" >
          <a style="font-weight:bold" href="{{url('forum')}}">Index</a>
        </li>
        @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
          <li class="dropdown-header">
            <a style="font-weight:bold" href="{{url('forum/'.$cats->cat_id.'/')}}">
              <h4>{{$cats->cat_nom}}</h4>
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    <h4 style="margin-left:15px">Titre du Topic : </h4>

    <table class="table forum table-striped">
      <thead>
        <tr>
          <th class="cell-stat"></th>
          <input id="titleTopic" class="form-control" style="width:85%;margin-left:10px;margin-right:10px!important" rows="6" placeholder="Ecrivez ce que vous voulez"> </input> </br>
          <h4 style="margin-left:15px">Message : </h4>
          <textarea id="msgTopic" rows="10" class="form-control" style="width:85%;margin-left:10px;margin-right:10px!important" rows="6" placeholder="Ecrivez ce que vous voulez"> </textarea>
        </tr>
      </thead>
      </br>
    </table>
    <a href="{{url('forum/'.$cat)}}" class="btn btn-warning" style="margin-left:15px"> Revenir aux catégories </a>
    <button id="saveMsgTopic" class="btn btn-info" style="margin-left:15px">Créer le Topic et Poster le Message </button>
  </body>
</html> 

<script>
$(function() { 
  
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

  $('#saveMsgTopic').click(function() {
    if($('#msgTopic').val() == " " ) {
      alert('Votre message est vide');
    } else if ( $('#titleTopic').val() == " " ) {
      alert('Votre titre est vide');
    } else {
      alert('toto');
      $.ajax({
        // Envoie des données en post
        url: '../{{$cat}}/saveMsgTopic',
        type: 'post',
        data: {'titleTopic': $('#titleTopic').val(),'msgTopic': $('#msgTopic').val() },
        success: function(data){
          window.location.href = "{{url('forum/'.$cat)}}";

        }
      });
    }  
  });
});
</script>