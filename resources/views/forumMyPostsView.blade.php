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

    <!-- Barre de navigation -->
    <ul class="nav nav-pills col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
      <li role="presentation" class="active"><a href="{{url('forum/')}}">Index</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myProfil')}}">Profil</a></li>
      <li role="presentation"><a href="{{url('forum/'.Auth::id().'/myPosts')}}">Mes Messages</a></li>
      <li role="presentation"><a href="{{url('forum/admin')}}">Admin</a></li>
      <li role="presentation"><a href="{{url('')}}">Revenir au site M2L</a></li>
    </ul>

    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
      <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <div class='col-lg-6 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'>
            <h2>Vous avez posté {{$nbPost}} messages sur le forum</h2>
        </div>

        <!-- Menu de navigation -->
        <div class="dropdown col-lg-6 col-md-6 col-sm-6">
          <button class="btn btn-primary dropdown-toggle col-lg-offset-6 col-md-offset-7 col-sm-offset-6 col-lg-6 col-md-4 col-sm-5 col-xs-12" type="button" data-toggle="dropdown">Navigation Forum
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
    </div>

<!-- TODO DO NOT UNDERSTAND WHY "$postCat[$id][0]->cat_nom" CAUSE AN ERROR and $postCat[0][0]->cat_nom don t -->
  
    <?php $id = -1 ?> <!-- Variable $id permettant de compter le nombre de messages affiché, permettant d'avoir un id -->
    @foreach($posts as $post) <!-- Print the topic -->
      @if($post->post_sup == 0) <!-- If the post hasn't been deleted -->
        <?php $id++ ?>   <!-- On incrémente la variable id à chaque post -->
        <div class="row">
          <div class=" col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
              <h3>Message n°{{$id}}
                <a href="{{url('forum/'.$postCat[$id][0]->cat_id.'/'.$post->post_topic_id)}}"> (Topic:{{$postCat[$id][0]->topic_titre}})</a>
                <a href="{{url('forum/'.$postCat[$id][0]->cat_id)}}"> (Catégorie:{{$postCat[$id][0]->cat_nom}})</a></h3>
          </div>
          <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info">
            <div class="panel-body panel-info" style="min-height:70px">
              <h4> {{$post->post_texte}}  </h4>
            </div>
            <div class="panel-footer panel-info" style="height:55px" >
              <div class="col-lg-7 col-md-6 col-sm-6 col-xs-7"> 
                <h5>posté le {{$post->post_time}} par {{Auth::getPrenombyId($post->post_createur)}} {{Auth::getNombyId($post->post_createur)}} </h5>
              </div>
              @if(Auth::isAdmin() || Auth::id() == $post->post_createur)
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-5">  
                  <!-- Button 'Voir la discussion' -->
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="{{url('/forum/'.$postCat[$id][0]->cat_id.'/'.$post->post_id)}}" class="btn btn-success" style="margin-left:15px">Voir la discussion</a> 
                  </div> 
                  <!-- Button 'Editer un message' -->
                  <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-lg-3 col-md-3 col-sm-3 col-xs-4">
                    <a href="{{url('forum/'.$postCat[$id][0]->cat_id.'/'.$post->post_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-warning" style="margin-left:15px">Editer</a> 
                  </div>
                  <!-- Button 'Supprimer un message' -->
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <button id="supPost{{$id}}" class="btn btn-danger" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button> 
                  </div>
                </div>
              @endif
              </div>
            </div>
        </div>
      <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });   

        $('#supPost{{$id}}').click(function() {
          alert('ok');
          $.ajax({
              url: '{{$post->post_id}}/supPost',
              type: "post",
              data: {'postId': $(this).attr('data-id') },
              success: function(data){
                window.location.href = ""; // On redirige sur la même page
              }
          });  
        });
        
        $('#editPost{{$id}}').click(function() {
          alert('ok');
          window.location.href = "";

        });

        $('#goToPost{{$id}}').click(function() {
          alert('ok');
          $.ajax({
              url: '{{$post->post_id}}/supPost',
              type: "post",
              data: {'postId': $(this).attr('data-id') },
              success: function(data){
                window.location.href = ""; // On redirige sur la même page
              }
          });  
        });


      </script>
    @endif
  @endforeach

      </tbody>
    </table>
  </body>

  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>

  </footer>
</html> 

