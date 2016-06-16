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
    <div class="container" style="margin-top: 35px">
      <h1 onclick="location.href='{{url('forum')}}'" >Admin du Forum De La Maison Des Ligues</h1>
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
    </br>  
  </head>
  <body>
    <div>
      <h2 class="col-lg-offset-1 col-lg-11">Supprimer tous les posts d'un utilisateur</h2>
      <div class="col-lg-offset-1 col-lg-11 col-md-6">
        <div class="col-lg-2">
          <h4>Par id  du créateur</h4>
        </div>
        <input class="col-lg-7" rows="10" id="idToSup" class="form-control"></input>
        <button  id="supById" class="btn btn-warning col-lg-offset-1 col-lg-1 col-md-offset-1">Supprimer</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11 col-md-6">
        <div class="col-lg-2">
          <h4>Par pseudo  du créateur</h4>
        </div>
        <input class="col-lg-7" rows="10" id="pseudoToSup" class="form-control"></input>
        <button  id="supByPseudo" class="btn btn-warning col-lg-offset-1 col-lg-1 col-md-offset-1">Supprimer</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11 col-md-12">
        <div class="col-md-4 col-lg-5">
          <div class="col-lg-6 ">
            <h4>Prenom du créateur</h4>
          </div>
          <input class="col-lg-6" rows="10" id="surnameToSup" class="form-control"></input>
        </div>
        <div class="col-md-6 col-lg-7">
          <div class="col-lg-3 ">
            <h4>Nom  du créateur</h4>
          </div>
          <input class="col-lg-4" rows="10" id="nameToSup" class="form-control"></input>
          <button id="supByName" class="btn btn-warning col-lg-offset-1 col-lg-2 col-md-offset-1">Supprimer</button>
        </div>
      </div>
    </div>
    <div>
      <h2 class="col-lg-offset-1 col-lg-11">Supprimer un post</h2>
      <div class="col-lg-offset-1 col-lg-11 col-md-4">
        <div class="col-lg-2">
          <h4>Par id  du post</h4>
        </div>
        <input class="col-lg-7" rows="10" id="postIdToSup" class="form-control"></input>
        <button  id="supByPostId" class="btn btn-warning col-lg-offset-1 col-lg-1 col-md-offset-1">Supprimer</button>
      </div>
    </div>

    <div>
      <h2 class="col-lg-offset-1 col-lg-11 col-md-12">Afficher tous les posts d'un utilisateur</h2>
      <div class="col-lg-offset-1 col-lg-11 col-md-6">
        <div class="col-lg-2">
          <h4>Par id du créateur</h4>
        </div>
        <input id="idToPrint" class="col-lg-7" rows="10"  class="form-control"></input>
        <button id="printById" class="btn btn-primary col-lg-offset-1 col-lg-1 col-md-offset-1">Afficher</button>
      </div>

      <div class="col-lg-offset-1 col-lg-11  col-md-6">
        <div class="col-lg-2">
          <h4>Par pseudo du créateur</h4>
        </div>
        <input id="pseudoToPrint" class="col-lg-7" rows="10"  class="form-control"></input>
        <button id="printByPseudo" class="btn btn-primary col-lg-offset-1 col-lg-1 col-md-offset-1">Afficher</button>
      </div>
      <div class="col-lg-offset-1 col-lg-11">
        <div class="col-md-6 col-lg-5">
          <div class="col-lg-5">
            <h4>Prenom du créateur</h4>
          </div>
          <input class="col-lg-5" rows="10" id="surnameToPrint" class="form-control"></input>
        </div>
        <div class="col-md-6 col-lg-7">
          <div class="col-lg-3 ">
            <h4>Nom du créateur</h4>
          </div>
          <input class="col-lg-4" rows="10" id="nameToPrint" class="form-control"></input>
          <button id="printByName" class="btn btn-primary col-lg-offset-1 col-lg-2 col-md-offset-1">Afficher</button>
        </div>
      </div>
    </div>

    <div>
      <h2 class="col-lg-offset-1 col-lg-11 col-md-12">Afficher un post</h2>
      <div class="col-lg-offset-1 col-lg-11  col-md-6">
        <div class="col-lg-2">
          <h4>Par id du post</h4>
        </div>
        <input id="postIdToPrint" class="col-lg-7" rows="10"  class="form-control"></input>
        <button id="printByPostId" class="btn btn-primary col-lg-offset-1 col-lg-1 col-md-offset-1">Afficher</button>
      </div>
    </div>

    <tbody id="topics"></tbody>

    <div>
      <div class="col-lg-offset-9">
        </br>
        </br>
        <a href="{{url('forum')}}" class="btn btn-success" style="margin-left:15px"> Revenir à l'index du forum</a>
      </div> 
    </div> 
  </body>
</html> 

<script>

    $(function(){
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      
      $('#supById').click(function() {
        if( $('#idToSup').val() == ''){
          alert('Please enter an id')
        } else {
          $.ajax({
          url: './admin/supById',
          type: "post",
          data: {'idToSup': $('#idToSup').val() },
          success: function(data){ 
            if(data == 0){
              alert("Aucun messages correspondant à la requête n'a pu être supprimé")
            } else {
              alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
            }
          } 
        });
        }      
      });
      $('#supByName').click(function() {
        if( $('#surnameToSup').val() == '' || $('#nameToSup').val() == '' ){
          alert('Veulliez entrer un nom et un prénom');
        } else {
          $.ajax({
            url: './admin/supByName',
            type: "post",
            // TODO TOCHECK
            data: {'nameToSup': $('#nameToSup').val(), 'surnameToSup': $('#surnameToSup').val() },
            success: function(data){
              console.log(data); 
              if(data == -1){
                alert("Plusieurs utilisateur correspondent veuillez supprimer avec l'id");
              } else if (data == 1) {
                alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });
      $('#supByPseudo').click(function() {
        if( $('#pseudoToSup').val() == ''){
          alert('Veulliez entrer un pseudo svp');
        } else {
          $.ajax({
            url: './admin/supByPseudo',
            type: "post",
            // TODO TOCHECK
            data: {'pseudoToSup': $('#pseudoToSup').val()},
            success: function(data){
              console.log(data); 
              if (data == 1) {
                alert("Tous les messages de l'utilisateur (id) "+$('#supById').val()+" ont été supprimé" );
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });
      $('#supByPostId').click(function() {
        if( $('#postIdToSup').val() == ''){
          alert('Please enter an id')
        } else {
          $.ajax({
            url: './admin/supByPostId',
            type: "post",
            data: {'postIdToSup': $('#postIdToSup').val() },
            success: function(data){ 
              console.log(data); 
              if(data == 0){
                alert("Aucun messages correspondant à la requête n'a pu être supprimé")
              } else {
                //TODO afficher id
                alert("Tous les messages de l'utilisateur (id) "+$('#supByPostId').val()+" ont été supprimé" );
              }
            } 
          });
        }      
      });
      $('#supByPostDate').click(function() {
        if( $('#postDateToSup').val() == ''){
          alert('Please enter an id')
        } else {
          $.ajax({
            url: './admin/supByPostDate',
            type: "post",
            data: {'postDateToSup': $('#postDateToSup').val() },
            success: function(data){
              if(data == 0){
                alert("Aucun messages correspondant à la requête n'a pu être supprimé")
              } else {
                //TODO afficher id
                alert("Tous les messages de l'utilisateur (id) "+$('#supByPostDate').val()+" ont été supprimé" );
              }
            } 
          });
        }      
      });
      $('#printById').click(function() {
        if( $('#idToPrint').val() == ''){
          alert('Please enter an id')
        } else {
          $.ajax({
            url: './admin/printById',
            type: "post",
            data: {'idToPrint': $('#idToPrint').val() },
            success: function(data){
              obj = $.parseJSON(data); // Parse the data which has been encode using JSON 
              console.log(obj[0]['post_texte']);
              if(data == 0){
                alert("Aucun messages correspond à la requête")
              } else {
                for(var i=0;i<obj.length;i++) {
                  $('#topics').append("<div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'><h3>Message n°"+i+"</h3></div><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info'><div class='panel-body panel-info' style='min-height:70px'><h4> "+obj[i]['post_texte']+"  </h4></div></div</div>");
                }
              }
            }
            });
          }      
        });
      $('#printByPseudo').click(function() {
        if( $('#pseudoToPrint').val() == ''){
          alert('Please enter an pseudo')
        } else {
          $.ajax({
            url: './admin/printByPseudo',
            type: "post",
            data: {'pseudoToPrint': $('#pseudoToPrint').val() },
            success: function(data){
              console.log(data);S 
              if(data == 0){
                alert("Aucun messages correspond à la requête")
              } else {
                for(var i=0;i<obj.length;i++) {
                  $('#topics').append("<div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'><h3>Message n°"+i+"</h3></div><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info'><div class='panel-body panel-info' style='min-height:70px'><h4> "+obj[i]['post_texte']+"  </h4></div></div</div>");
                }
              }
            } 
          });
        }      
      });

    $('#printByName').click(function() {
        if( $('#surnameToPrint').val() == '' || $('#nameToPrint').val() == '' ){
          alert('Veulliez entrer un nom et un prénom');
        } else {
          $.ajax({
            url: './admin/printByName',
            type: "post",
            data: {'nameToPrint': $('#nameToPrint').val(), 'surnameToPrint': $('#surnameToPrint').val() },
            success: function(data){
              console.log(data); 
              if(data == -1){
                alert("Plusieurs utilisateur correspondent veuillez utiliser l'id");
              } else if (data == 1) {
                for(var i=0;i<obj.length;i++) {
                  $('#topics').append("<div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'><h3>Message n°"+i+"</h3></div><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info'><div class='panel-body panel-info' style='min-height:70px'><h4> "+obj[i]['post_texte']+"  </h4></div></div</div>");
                }
              } else {
                alert('Aucun post ne correspond à votre requête')
              }
            } 
          });
        }      
      });
      $('#printByPostId').click(function() {
        if( $('#postIdToPrint').val() == ''){
          alert('Please enter an id')
        } else {
                  $.ajax({
          url: './admin/printByPostId',
          type: "post",
          data: {'postIdToPrint': $('#postIdToPrint').val() },
          success: function(data){ 
            console.log(data);
            if(data == 0){
              alert("Aucun messages correspondant à la requête n'a pu être supprimé")
            } else {
              for(var i=0;i<obj.length;i++) {
                $('#topics').append("<div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'><h3>Message n°"+i+"</h3></div><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info'><div class='panel-body panel-info' style='min-height:70px'><h4> "+obj[i]['post_texte']+"  </h4></div></div</div>");
              }
            }
          } 
        });
        }      
      });

      $('#printByPostDate').click(function() {
        if( $('#postDateToPrint').val() == ''){
          alert('Please enter an id')
        } else {
                  $.ajax({
          url: './admin/printByPostDate',
          type: "post",
          data: {'postDateToPrint': $('#postDateToPrint').val() },
          success: function(data){ 
            console.log(data);
            if(data == 0){
              alert("Aucun messages correspondant à la requête n'a pu être supprimé")
            } else {
              for(var i=0;i<obj.length;i++) {
                $('#topics').append("<div class='row'><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1'><h3>Message n°"+i+"</h3></div><div class='col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-10 col-md-10 col-sm-10 col-xs-10 col-offset-1 panel panel-info'><div class='panel-body panel-info' style='min-height:70px'><h4> "+obj[i]['post_texte']+"  </h4></div></div</div>");
              }            }
          } 
        });
        }      
      });
  });

</script>