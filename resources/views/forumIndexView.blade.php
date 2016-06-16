<!DOCTYPE HTML>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />
    <script src="{{asset('js/jquery-2.1.1.min.js')}}" rel="stylesheet"> </script>
    <script src="{{asset('js/bootstrap.min.js')}}" rel="stylesheet"> </script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <style type="text/css">
      [class*="col"] { margin-bottom: 20px; }
      img { width: 100%; }
    </style>
  </head>

  <div class="container" style="margin-top: 35px">
    <div class="page-header page-heading col-lg-12 text-center">
      <h1 onclick="location.href='{{url('forum')}}'" >Forum De La Maison Des Ligues</h1>
    </div>
    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
      <h3 class="lead">Bonjour {{Auth::getNameById(Auth::id())}}</h3>
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

    <!-- Menu de navigation -->
    <div class="dropdown col-lg-offset-6 col-lg-6 col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6">
      <button class="btn btn-primary dropdown-toggle col-lg-offset-8 col-md-offset-7 col-sm-offset-6 col-lg-3 col-md-4 col-sm-5 col-xs-12" type="button" data-toggle="dropdown">Navigation Forum
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

  <table class="table forum table-striped">
    <thead>
      @foreach($forum as $forum_as) <!-- print the categories -->
      <tr>
        <th class="cell-stat"></th>
        <th>
          <h3>{{$forum_as->forum_name}}<br><small>{{$forum_as->forum_desc}}</small></h3>
        </th> 
        <th class="cell-stat text-center hidden-xs hidden-sm">Topics</th>
        <th class="cell-stat text-center hidden-xs hidden-sm">Posts</th>
        <th class="cell-stat-2x hidden-xs hidden-sm">Dernier Post</th>
      </tr>
    </thead>


    
      <tbody>
        <?php $numCat = 0 ?>
        @foreach($categories as $cat)  <!-- On affiche les sous_catégories -->
          @if($forum_as->forum_id == $cat->forum_id) <!-- Uniquement si elles appartiennent à la catégorie affichée -->
          <tr>
            <td class="text-center"><i class="fa fa-heart fa-2x text-primary"></i></td>
            <td>
               <h4><a href="{{url('forum/'.$cat->cat_id)}}">{{$cat->cat_nom}}</a><br><small>{{$cat->cat_desc}}</small></h4> <!-- href renvoie l'id de la categorie au controleur si l'on clique dessus -->
            </td>
            <td class="text-center hidden-xs hidden-sm"><h4>{{$nbTopic[$numCat]}}</h4></td>
            <td class="text-center hidden-xs hidden-sm"><h4>{{$nbPost[$numCat]}}</h4></td>
              <td class="hidden-xs hidden-sm">
                @if(is_array($lastPost[$numCat]))
                  <div>posté par <a href="{{url('forum/'.$lastPost[$numCat]->cat_id.'/'.$lastPost[$numCat]->topic_id)}}">{{$lastPostCreator[$numCat]}}</a><br> </div>
                <small>le {{$lastPost[$numCat]->post_time}}</small>
                  @endif
              </td>
          </tr>
          @endif
          <?php $numCat++ ?>
        @endforeach
      </tbody>
    @endforeach

  </body>
</html> 

