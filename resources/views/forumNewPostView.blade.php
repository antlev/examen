<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/forum.css')}}">
    <script src="{{ asset('js/jquery-2.1.1.min.js')}}"></script>

        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

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


  <div>

    <h4 class=" col-lg-offset-1 col-lg-5 col-md-offset-1 col-md-5 col-sm-offset-1 col-sm-5 hidden-xs" >Votre Message : </h4>

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
    <h4 class="hidden-lg hidden-md hidden-sm" >Votre Message : </h4>
  </div>


  <table class="table forum table-striped">
    <thead>
        <th class="cell-stat"></th>
        <div class="col-lg-offset-1 col-lg-10 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10">
          <textarea rows="10" id="msgToSend" class="form-control "></textarea>
        </div>
    </thead>
  </table>
    
  </br>
  <div class="col-lg-offset-1 col-lg-2 col-md-offset-1 col-md-2 col-sm-offset-1 col-sm-2">
      <a href="{{url('forum/'.$cat.'/'.$topic_id)}}" class="btn btn-warning" style="margin-left:15px"> Revenir au topic </a>
  </div>
  <div class="col-lg-offset-6 col-lg-2 col-md-offset-5 col-md-2 col-sm-offset-5 col-sm-2">
      <button class=" btn btn-info" style="margin-left:15px" id="savePost"> Sauvegarder votre Post </button>
  </div>

</html> 

<script>
$(function() { 
  

  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  // Requête ajax permettant de sauvegarder le message en post
  $('#savePost').click(function() {
    if($('#msgToSend').val() == ''){
      alert('Votre message est vide');
    } else{
      $.ajax({
          url: 'savePost',
          type: "post",
          data: {'msg': $('#msgToSend').val() },
          success: function(data){
            alert('Votre message a été posté avec succès') ; 
            // On redirige ensuite
            window.location.href = "{{url('forum/'.$cat.'/'.$topic_id)}}";
          }
      });  
    }
  });
});
</script>