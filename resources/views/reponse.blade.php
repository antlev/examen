@extends('layouts.app2')

@section('content')
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Création des réponses </div>
                    <br>
                    <div class="panel-body">

                        <div id="lastquizz"></div>
                    </div>
                    <button class="btn btn-success pull-right" id="finished"><i class="fa fa-next"></i>Terminé</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>

<script>

    $( document ).ready(function() {

        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}); //token normé de laravel pour proteger les requetes ajax

        $.ajax({
            url: 'question',
            type: "post",
            success: function (data) {
                obj = $.parseJSON(data); //recuperation des questions en format json que je parse
                var general = 2;
                for (var i = 0; i < obj.length; i++) {
                    general++;
                    var idd = obj[i].id;
                    $('#lastquizz').before('' + //qui est ensuite ecrit en html avec les id et la possibiliter de mettre les réponses associées
                            '<div class="col-lg-12" id="quizz_' + obj[i].id + '" style="font-weight:bold"> Question ' + obj[i].id + ' : ' + obj[i].nom + '? &nbsp;' +
                            '<button class="btn btn-default addQuestion_' + obj[i].id + '" id="addQuestion_' + obj[i].id + '" onclick="addQ(' + idd + ',' + general + ')">' +
                            '<i style="color:green;cursor:pointer" class="fa fa-plus-circle" ></i>Ajouter une réponse</button>&nbsp;<br>' +
                            '</div>' +
                            '<div class="col-lg-12" id="quizzreponse_' + obj[i].id + '">' +
                            '<div class="col-lg-6" id="rep_' + general + '" style="font-weight:normal">Réponse : ' +
                            '<input type="text" style="width:60% !important" class="form-control" id="reponse_' + general + '"><br>' +
                            '</div>' +
                            '<div class="col-lg-6"><br>' +
                            '<input type="radio" id="' + general + '" name="groupe_' + obj[i].id + '">' +
                            '</div></div>');


                }
            }
        });


        $('#finished').click(function () {
            var values = []; //création de larray list


            $(':radio').each(function () {
                var BonneReponse = ""
                var id = $(this).attr("id");
                var idquestion = $(this).attr('name').replace('groupe_', ''); //on recupere le numero du groupe qui correspond au numero de la question
                if ($(this).prop('checked')) { //si dans la boucle du each , la checkbox actuelle est check, alors on la définit comme la bonne réponse à la question
                    BonneReponse = id;
                }
                else {
                    BonneReponse = ""; //si non on l'initialise a vide
                }

                var item1 = {
                    "data": {
                        "Question": idquestion,
                        "Reponse": id,
                        "BonneReponse": BonneReponse,
                        "PhraseReponse": $('#reponse_' + id).val()
                    }
                };
                values.push(item1); //on push dans larray values litem1


            });
            jsonvar = JSON.stringify({values}); //on transforme larray en json
            $.ajax({
                url: 'questionreponse', //on envoi a la route questionreponse qui ira chercher le controller avec la fonction associée
                type: "post",
                data: {'QuestionReponse': jsonvar},
                success: function (data) {
                    if (data == 1) { window.location.href  = "{{url('welcome')}}"; //une fois terminé si tout c'est bien passé, on est redirigé vers l'accueil
                    }
                    else {
                        alert('Une erreur est survenue, merci de contacter l\'administrateur');
                    }
                }
            });

        });

    });
    function addQ(quizz,x) { //cette fonction permet d'ajouter des champs réponses
        var j = 0;
        $(':radio').each(function() {
            j++; //on incremente J pour compter le nombre de checkbox déjà présente

        })
        j = j+3; //on y rajoutes +3 car si non il y a un gène avec les id unique
        var IdQuestion = j;
        var id = event.target.id;
        $('#quizz_'+quizz).after( //et juste apres la question on y en rajoute une autre réponse
                '<div class="col-lg-12" id="quizzreponse_'+quizz+'">'+
                '<div class="col-lg-6" style="font-weight:normal">' + 'Réponse : ' +
                '<input type="text" style="width:60% !important" class="form-control" id="reponse_' +  IdQuestion + '">' +
                '<br>' +
                '</div>' +
                '<div class="col-lg-6"><br>' +
                '<input type="radio" id="'+IdQuestion+'" name="groupe_' +quizz+ '">' +
                '</div></div>');


    }


</script>