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

    </br>
    </br>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
      <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="fa fa-pencil-square-o pull-right" style="font-size:25px;color:black !important;margin-left:30px"></a>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
        <a href="{{url('forum/'.$cat.'/newTopic')}}" class="btn btn-info col-xs-12" style="margin-left:15px">Créer un Nouveau Topic </a>
      </div>
    </div>

    <!-- Menu de navigation -->
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
    <table class="table forum table-striped">
      <thead>
        <tr>
          
          @if(!empty($topic))
            <th class="cell-stat">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h3>Catégorie : {{$catName}}</h3>
              </div>
            </th>
            <th class="cell-stat text-center hidden-xs">Posts</th>
            <th class="cell-stat-2x hidden-sm hidden-xs">Last Post</th>
          @endif      
        </tr>
      </thead>
        <?php $compteur = 0 ?>
        @if(empty($topic)) <!-- If there is no topic to print -->
        <tbody>
          <tr>
            <h3> Aucun topics n'a été créé dans la catégorie {{$catName}} </h3>
          </tr>
        </tbody>
        @else
            <tbody id="topics"></tbody>
            <!-- ajax request will print the post to print here -->
        @endif
    </table>
  </body>
        
  <?php $lastTopicPrinted = 0 ?>
  <footer>
    </br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <i class="fa fa-facebook-5x"></i>
      <a href="{{url('forum')}}" class="btn btn-warning" style="margin-left:20px">Revenir à l'index </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <!-- TODO print button only when it s necessary-->
      <button id="next" class="btn btn-success pull-right" style="margin-right:20px">Suivant</button>
      </br>
    </div>
  </footer>
</html> 

<script>
  $(function(){
    // We instanciate an array to store the topic data to print
    var topicData = {
      topicId : [],
      topicTitle : [],
      creator : [],
    }
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });   
      // First ajax post request will get the next topics to print
    $.ajax({
      url: './{{$cat}}/next',
      type: "post",
      data: {'lastTopicPrinted': -1 }, //We want to start by the first topic so we set the variable lastTopicPrinted to 0
      success: function(data){ 
        obj = $.parseJSON(data); // Parse the data which has been encode using JSON
        for(var i=0;i<obj.topics.length;i++) {
          topicData.topicId.push(obj.topics[i].topic_id); // For each data needed we set the topicData
          topicData.creator.push(obj.topics[i].topic_createur);
          topicData.topicTitle.push(obj.topics[i].topic_titre);
        }
      },
      complete: function(data){ // When request is completed
          // Secondajax post request the will get number of post and the name of the topic creator
        $.ajax({
          url: 'getPostInfoById',
          type: "post",
          data: {'topicData': topicData},
          success: function(data){
            obj = $.parseJSON(data); // Parse the data which has been encode using JSON
            console.log(obj);
            console.log(obj['lastPostDate'][0]['post_time']);
            for(var i=0;i<obj['creatorName'].length;++i){ // for each post 
                // we append those line containing correct values at the correct place (using #topics)
                $('#topics').append("<tr id="+i+"><td><h4 class='col-lg-offset-1' ><a href='{{url('forum/'.$cat.'/')}}"+'/'+obj['topicData']['topicId'][i]+"' style='margin-left:20px'>"+obj['topicData']['topicTitle'][i]+"</a></h4></td><td class='cell-stat text-center hidden-xs'>"+obj['nbPost'][i]+"</td><td class='cell-stat hidden-sm hidden-xs'><div>posté par <a href='{{url('forum/')}}"+"/"+obj['topicData']['creator'][i]+"/myProfil'>"+obj['creatorName'][i]+"</a></div><small>"+obj['lastPostDate'][i]['post_time']+"</small></td></tr>");
            }
            /*                topicData.topicId = topicData.topicTitle = topicData.creator = '';*/
          }             
        });      
      }
    });  

    $('#next').click(function() {
      topicData = {
      topicId : [],
      topicTitle : [],
      creator : [],
    }
      // First ajax post request will get the next topics to print
      $.ajax({
        url: './{{$cat}}/next',
        type: "post",
        // Sending request  for the next topic using the last tr id printed
        data: {'lastTopicPrinted': $('table tr:last').attr('id') }, // get and send the last printed 'id' tr to get the next topics to print
        success: function(data){ 
          obj = $.parseJSON(data); // Parse the data which has been encode using JSON
          for(var i=0;i<obj.nbPost.length;i++) {
            topicData.topicId.push(obj.topics[i].topic_id);
            topicData.creator.push(obj.topics[i].topic_createur);
            topicData.topicTitle.push(obj.topics[i].topic_titre);
          }
        },
        complete: function(data){ // When request is completed
          // Second ajax post request the will get number of post and the name of the topic creator
          $.ajax({
            url: 'getPostInfoById',
            type: "post",
            data: {'topicData': topicData},
            success: function(data){
              obj = $.parseJSON(data); // Parse the data which has been encode using JSON
              for(var i=0;i<obj['nbPost'].length;++i){ // for each post
                // Append those line containing correct values at the correct place (using #topics)
                $('#topics').append("<tr id="+i+"><td><h4 class='col-lg-offset-1' ><a href='{{url('forum/'.$cat.'/')}}"+'/'+obj['topicData']['topicId'][i]+"' style='margin-left:20px'>"+obj['topicData']['topicTitle'][i]+"</a></h4></td><td class='cell-stat text-center hidden-xs'>"+obj['nbPost'][i]+"</td><td class='cell-stat hidden-sm hidden-xs'>posté par <a href='{{url('forum/')}}'"+'/'+obj['topicData']['creator'][i]+"'/myProfil'></td></tr>");
              }
              topicData.topicId = topicData.topicTitle = topicData.creator = '';       
            }             
          });    
        }
      });      
    });

    $('#id').click(function() {
        window.location.href = "'forum/'.$cat.'/'";
    });
  });
</script>


