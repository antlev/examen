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

    <div>
      <div class="col-lg-8 col-md-8 col-sm-6 col-xs-10">
        <h2>
          <div class="col-lg-offset-2 col-lg-8 hidden-sm hidden-xs">
            <a href="{{url('forum/'.$topic[0]->topic_cat)}}">Catégorie : {{$catName}}</a>
          </div>
          <div class="hidden-lg hidden-md">
            <a href="{{url('forum/'.$topic[0]->topic_cat)}}">{{$catName}}</a>
          </div>
        </h2>            
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-10">
        <div class="col-lg-6 col-md-6">
          <button class="btn btn-success" href="{{url('forum/'.$cat.'/')}}">Revenir à la catégorie</button>
        </div>

        <!-- Navigation menu -->
        <div class="dropdown col-lg-6 col-md-6 col-sm-6">
          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Navigation Forum
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

    <div class="col-lg-offset-1 col-lg-10 col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
      <div class="col-lg-2 col-md-3">
        <h2>Topic : {{$topic[0]->topic_titre}}</h2>
      </div>
      <div class="col-lg-offset-10 col-md-offset-10">
        <h4>(Vues : {{$topic[0]->topic_vu}})</h4>
      </div>
    </div>

      

    <?php $id = 0 ?> <!-- Variable $id allow to count the number of messages printed -->
    
    @foreach($posts as $post) <!-- print the categories -->
      <div class="row">
        @if($post->post_topic_id==$topic[0]->topic_id && $post->post_sup == 0)
          <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info">
            <div class="panel-body panel-info" style="min-height:70px">
              <h3> {{$post->post_texte}}  </h3>
              <?php $id++ ?>   <!-- On incrémente la variable id à chaque post -->
            </div>
            <div class="panel-footer panel-info" style="height:55px" >
              <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12"> 
                <div>
                  @if($post->post_edit == 1)
                    <div>
                      <div>Posté le {{$post->post_time}} par <a href="{{url('forum/'.$post->post_createur.'/myProfil')}}">{{Auth::getNamebyId($post->post_createur)}}</a> (Edité le {{$post->post_edit_time}})</div>
                    </div>
                  @else
                    <div>
                      <div>Posté le {{$post->post_time}} par <a href="{{url('forum/'.$post->post_createur.'/myProfil')}}">{{Auth::getNamebyId($post->post_createur)}}</a></div>
                    </div>                  
                  @endif
                </div>
              </div>
              @if(Auth::isAdmin())
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">   
                  <!-- Button 'Editer un message' -->
                  <div class="col-lg-5 col-md-6 col-sm-6 col-xs-6">
                    <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-warning" style="margin-left:15px">Editer</a> 
                  </div>
                  <!-- Button 'Supprimer un message' -->
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <button id="supPost{{$id}}" class="btn btn-danger" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button> 
                  </div>
                </div>
              @else(Auth::id() == $post->post_createur)
                <div class="col-lg-3 col-md-3 col-xs-3 panel-footer pull-right">   
                  <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/'.$post->post_id.'/editPost')}}" class="btn btn-danger" style="margin-left:15px">Editer</a> 
                  <button id="supPost{{$id}}"  href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/editPost')}}" class="btn btn-warning" style="margin-left:15px" data-id="{{$post->post_id}}">Supprimer</button>
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
      <script>
        $('#supPost{{$id}}').click(function() {
          $.ajax({
              url: '{{$topic[0]->topic_id}}/supPost',
              type: "post",
              data: {'postId': $(this).attr('data-id') },
              success: function(data){
                alert('Votre post a bien été supprimé')
                window.location.href = ""; // On redirige sur la même page
              }
          });  
        });
      </script>
    @endforeach

    @if($id==0) <!-- Si aucun message ne doit être affiché -->
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h3>Aucun message n'est sauvegardé pour ce topic '{{$topic[0]->topic_titre}}' </h3></div>
    @endif



  </body>
  </br>
  </br>
  <footer>
    <!-- Button 'Revenir aux catégories' -->
    <div class=" col-lg-offset-1 col-lg-8 col-md-offset-1 col-md-7 col-sm-6 col-xs-12">
      <a href="{{url('forum/'.$cat.'/')}}" class="btn btn-warning" style="margin-left:15px">Revenir aux catégories</a>
    </div>
    <!-- Button 'Ecrire un nouveau message' -->
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
      <a href="{{url('forum/'.$cat.'/'.$topic[0]->topic_id.'/newPost')}}" class="btn btn-info" style="margin-left:15px">Ecrire un nouveau message </a>
    </div>
  </footer>
</html> 

<script>
  $(function() {     
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    // Increment the number of view 
    $.ajax({
      url: './{{$topic[0]->topic_id}}/viewTopic',
      type: "post",
      success: function(data){ 
      },
    }); 
  });
</script>

