@extends('layouts.app')

@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">

    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

    <div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <br>
                <div class="panel panel-header" style="text-align: center"><br><br><b>Quizz du jour</b></div>
                <div class="panel-body">

                    <div id="quizzgame">


                    </div>

<br>

                    &nbsp;
                    <div id="lastquizz"></div>
                    <!-- <pre>{{ var_dump(Auth::user())}} </pre> -->
                </div>
                <button class="btn btn-success pull-right" id="gotoscore"><i class="fa fa-next"></i>Suivant</button>
            </div>
        </div>
    </div>
</div>

    <input type="hidden" id="count" value="">
@endsection


<script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>

<script>
$( document ).ready(function() {


    var count = 0;
    var total = 0;

    function makeCounter() {
        count = 0;
        return function() {
            count++;
            return count;
        };
    };
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }); //token normé de laravel pour proteger les requetes ajax
    $.ajax({ //Chargement des questions & reponses
        url: 'getgame', //Envoi 'getgame' a la route , qui va elle appelé la fonction du controller afin de charger le quizz
        type: "post",
        success: function(data) {
            obj = $.parseJSON(data); //Je parse le retour json reçu pour qu'il soit exploitable

            for (var i = 0; i < obj.length; i++) {
                if ($('#quizzgame').text().indexOf("Question " + obj[i].id) != -1) {

                    $('#question_' + obj[i].id).after("<div id='divrep_"+obj[i].reponseid+"'><input onclick='' name='r_"+obj[i].reponseid+"' id="+obj[i].id+" type='radio'> " + obj[i].choix+"<br></div>");
                } else {
                    $('#quizzgame').prepend("<div id='question_" + obj[i].id + "'><br><br><b>Question</b> " + obj[i].id + ": " + obj[i].nom + "?<br><div id='divrep_"+obj[i].reponseid+"'><input name='r_"+obj[i].reponseid+"' id="+obj[i].id+" type='radio'> " + obj[i].choix + "</div></div>");
                }

            }


        }
    });

$('#gotoscore').click(function() {
    total=0;
    $("input[name^='r_']").each(function () { //Verification des bonne réponses
        if(total<$(this).attr('id')) { total = $(this).attr('id'); }

        var _this = $(this);
            $.ajax({
                url: 'checkanswer',
                type: "post",
                data:{ 'question': $(this).attr('id'), 'reponse' : $(this).attr('name').replace("r_","")} ,
                success: function(data){
                    obj = $.parseJSON(data);
                           if(obj) {
                             if(_this.is(':checked'))  { //Si la réponse est la bonne réponse on met en couleur verte
                                 _this.parent('div').css('background-color','#5cb85c');

                             }
                                else { _this.parent('div').css('background-color','#d9534f'); } //Si non la bonne réponse en couleur rouge
                           }
                 }

             });

    });
    if(count>=total*0.5) { //Si la personne a la moyenne ou plus alors un message de félicitation
        swal("Good job!", "Vous avez reussi le quizz avec un score de :" +$('#count').val()+"/"+total, "success")
    } else {
        swal({
            title: "Dommage!",
            text: "Vous n'avez pas reussi le quizz votre score est de "+$('#count').val()+" bonne(s) reponse(s) sur "+total,
        });
    }
  });
});


</script>
<script src="{{asset('js/bootstrap.js')}}"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
