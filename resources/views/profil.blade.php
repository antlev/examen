@extends('layouts.app')

@section('content')

        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />  <!-- token normé de laravel -->


<div class="col-lg-5 col-lg-offset-5 col-md-6 col-md-offset-5">
<img id="imguser" name="myimage" width=40% src="{{ Auth::getPhotoById(Auth::id()) }}"> <!-- Récuperation de la photo du user grace a une fonction ajoutée dans la Class Auth et a la fonction native de laravel Auth::id qui renvoi l'UI du user -->
<br><br>
<input id="uploadFile" placeholder="Choose File" disabled="disabled" />
<div class="fileUpload btn btn-default">
    <span>Parcourir</span>
    <input id="uploadBtn" type="file" class="upload" />
</div>


</div>
<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-12">&nbsp;</div>
<div class="col-lg-2 col-lg-offset-4 col-md-2 ">

Pseudonyme : 
<div class="input-group">
<input type="text" class="form-control" id="pseudo" value="{{ ucfirst(Auth::user()->name) }}" disabled> <!-- On recupere toute les infos que laravel propose sur lutilisateur,et on choisis le name qui est le pseudonyme -->
<span class="input-group-addon">
        <i class="fa fa-edit" id="pseudo"></i>
    </span>
    </div>

Prenom :
<div class="input-group">
<input type="text" class="form-control"  id="prenom" value="{{ ucfirst(Auth::user()->prenom) }}" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="prenom"></i>
    </span>
    </div>
Nom : 
<div class="input-group">
<input type="text" class="form-control" id="nom"  value="{{ ucfirst(Auth::user()->nom)}}" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="nom"></i>
    </span>
    </div>
Sexe : 
<div class="input-group" id="civilitenot">
<select class="form-control" id="civilite">
<option  selected="selected">{{ ucfirst(Auth::user()->civilite)}}</option>

@if(strtoupper(Auth::user()->civilite) != 'HOMME') <option value="homme">Homme</option> @endif
@if(strtoupper(Auth::user()->civilite) != 'FEMME')<option value="femme">Femme</option> @endif
</select>
<span class="input-group-addon">
        <i class="fa fa-save" id="civilite"></i>
    </span>
    </div>
Date de naissance :

<div class="input-group">
<input type="text" class="form-control"  id="datenaiss" value="{{ Auth::user()->datenaiss }}" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="datenaiss"></i>
    </span>
    </div>

Adresse : 
<div class="input-group">
<input type="text" class="form-control" id="adresse" value="{{ Auth::user()->adresse}}" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="adresse"></i>
    </span>
    </div>

</div>


<div class="col-lg-2 col-md-6 ">
Sport : 
<div class="input-group">

<select class="form-control" id="sport">
    <option value="{{ Auth::user()->sport }}" selected="selected">{{ Auth::user()->sport}}</option>
@foreach(Auth::getAllSports() as $sport) <!-- On boucle sur tous les sports et on les affiches -->

@if($sport->nom_sports != Auth::user()->sport) <option value="{{ $sport->nom_sports }}" >{{ $sport->nom_sports }}</option> @endif
@endforeach
</select>
<span class="input-group-addon">
        <i class="fa fa-save" id="sport"></i>
    </span>
</div>
Code Postal :
<div class="input-group">
<input type="text" class="form-control" id="cp" value="{{ Auth::user()->cp}}" disabled>
<span class="input-group-addon">
        <i class="fa fa-edit" id="cp"></i>
    </span>
    </div>
Ville : 
<div class="input-group">
<input type="text" class="form-control" id="ville"  value="{{ ucfirst(Auth::user()->ville)}}" disabled> <!-- Premiere lettre mise en capital -->
<span class="input-group-addon">
        <i class="fa fa-edit" id="ville"></i>
    </span>
    </div>
Nationalité: 
<div class="input-group">
<input type="text" class="form-control" id="nationalite" value="{{ ucfirst(Auth::user()->nationalite)}}" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="nationalite"></i>
    </span>
    </div>

Mobile : 
<div class="input-group">
<input type="text" class="form-control" id="mobile" value="{{ Auth::user()->mobile}}" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="mobile"></i>
    </span>
    </div>


Téléphone  : 
<div class="input-group">
<input type="text" class="form-control" id="telephone" value="{{ Auth::user()->telephone}}" disabled> 
<span class="input-group-addon">
        <i class="fa fa-edit" id="telephone"></i>

    </span>
    </div>
    <div id="result"></div>
</div>



<style>
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
[data-notify="progressbar"] {
	margin-bottom: 0px;
	position: absolute;
	bottom: 0px;
	left: 0px;
	width: 100%;
	height: 5px;
}
</style>


<script>


   function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imguser').attr('src', e.target.result);
                $.ajax({
                type:"POST",
                url:"postimage",
                    data:{ 'img':e.target.result},
                    success: function(html){
                        successNotification();
                 },
                });
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


function successNotification() { //Créer un message de validation en bas a droite de lécran dans une information a été modifiée

$.notify({//Notify issue de la library notify , qui va permettre ici de creer de belle notification
	// options
	icon: 'glyphicon glyphicon-check',
	title: 'Modification : ',
	message: 'Les données ont été sauvegardées',
	target: '_blank'
},{
	// settings
	element: 'body',
	position: null, //la possibilitée de définir une position
	type: "success", //un type , qui va etre success ici donc une reussite, mais peut aussi etre warning , un avertissement ou danger un danger.
	allow_dismiss: true,//permet de pouvoir faire disparaitre la fenetre
	newest_on_top: false,//chaque nouvelle notif se mettra au dessus de lancienne si ceci est mis true
	showProgressbar: false,//une bar de progression au besoin
	placement: {
		from: "bottom",
		align: "right"
	},
	offset: 20,
	spacing: 10,
	z_index: 10311,
	delay: 5000,
	timer: 1000,
	url_target: '_blank',
	mouse_over: null,
	animate: {
		enter: 'animated fadeInDown',
		exit: 'animated fadeOutUp'
	},
	onShow: null,
	onShown: null,
	onClose: null,
	onClosed: null,
	icon_type: 'class',
	template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
		'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
		'<span data-notify="icon"></span> ' +
		'<span data-notify="title">{1}</span> ' +
		'<span data-notify="message">{2}</span>' +
		'<div class="progress" data-notify="progressbar">' +
			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
		'</div>' +
		'<a href="{3}" target="{4}" data-notify="url"></a>' +
	'</div>' 
});

}
document.getElementById("uploadBtn").onchange = function () {
    document.getElementById("uploadFile").value = this.value;
};
	$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$("input:file").change(function (){
	var postImg = $("#image").attr("name");// image name

    var dataString = 'img='+postImg;

$.ajax({
    type:"POST",
    url:"postimage", //on envoi en base de données l'image choisit
    data:dataString,
    success: function(html){
  
   },
 });

       
     });


$("#uploadBtn").change(function () {
        readURL(this);
    });

$("i").click(function(e){


        var idfa = e.target.id;
        var mod;


    $("select").each(function(){
        if ($(this).attr('id') == idfa) {
            $.ajax({
      url: 'changeUserInfo', //a chaque modification d'une selection on la sauvegarde en base de données et on appel la notification pour confirmer a l'utilisateur son action
      type: "post", 
      data: {'column': $(this).attr('id') ,'info': $('select#' + idfa).val() },
      success: function(data){
   successNotification();
      }
    });

}

});





$('input').each(function() {
     if($(this).is(':disabled') && ($(this).attr('id') == idfa)) { 
		$(this).removeAttr('disabled');
         $('i#'+idfa).toggleClass('fa-edit fa-save');
         $('i#'+idfa).css('color','green');
         mod=1;
     }
     //alert($(this).attr('id').attr('class'));
     if (!$(this).is(':disabled') && ($(this).attr('id') == idfa) && ($(this).attr('class') != 'fa-edit') && (mod!=1)) { 

       $('i#'+idfa).toggleClass('fa-save fa-edit');
         $('input#'+idfa).prop('disabled','true');
//alert($('input#' + idfa).val());
$.ajax({
      url: 'changeUserInfo',//a chaque modification d'un texte on la sauvegarde en base de données et on appel la notification pour confirmer a l'utilisateur son action
      type: "post", 
      data: {'column': $(this).attr('id') ,'info': $('input#' + idfa).val() },
      success: function(data){
        successNotification();
      }
    });  
}
     else { //console.log($(this).attr('id') + '-' + idfa); 
 }
});
mod = 0;

});
</script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

@endsection