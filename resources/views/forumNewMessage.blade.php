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
  <div class="page-header page-heading">
    <img src="{{asset('img/logo.png')}}" style="width:8%">
    <h1 href="{{url('forum')}}" >Forum De La Maison Des Ligues</h1>
  </div>
</div>

<!-- Menu de navigation -->
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle pull-right" type="button" data-toggle="dropdown" style="margin-right:20px">Navigation Forum
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li class="dropdown-header" ><a style="font-weight:bold" href="{{url('forum')}}">Index</a></li>
    @foreach($categories as $cats)  <!-- On affiche les sous_catégories -->
      <li class="dropdown-header"><a href="{{url('forum/'.$cats->cat_id.'/')}}">{{$cats->cat_nom}}</a></li>
      @foreach($topics as $topic_as) <!-- On affiche les catégories -->
        @if($topic_as->topic_cat==$cats->cat_id)
          <li><a href="{{url('forum/'.$cats->cat_id.'/'.$topic_as->topic_id)}}">{{$topic_as->topic_titre}}</a></li>
        @endif
      @endforeach
      </li>
    @endforeach
  <ul>
</div>

<h4 style="margin-left:15px">Message : </h4>

  <table class="table forum table-striped">
    <thead>
        <th class="cell-stat"></th>
        <textarea rows="10" id="msgToSend" class="form-control"></textarea>
    </thead>
  </table>
    
  </br>
  <a href="{{url('forum/'.$cat.'/'.$topic_id)}}" class="btn btn-warning" style="margin-left:15px"> Revenir au topic </a>
  <button class="btn btn-info" style="margin-left:15px" id="saveMsg"> Sauvegarder votre message </button>

</html> 

<script>
$(function() { 
  
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  $('#saveMsg').click(function() {
    if($('#msgToSend').val() == ''){
      alert('Votre message est vide');
    } else{
      $.ajax({
          url: 'saveMsg',
          type: "post",
          data: {'msg': $('#msgToSend').val() },
          success: function(data){
            window.location.href = "{{url('forum/'.$cat.'/'.$topic_id)}}";
          }
      });  
    }
  });
});
</script>