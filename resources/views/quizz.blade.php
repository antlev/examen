    @extends('layouts.app2')

@section('content')
    @if(!Auth::isAdmin()) <!-- SI la personne n'est pas admin, alors elle est redirigé vers la page d'accueil -->
       <script>window.location.href = '{{url('home')}}'</script>
        @endif
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

    <div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Créer un quizz (Question) </div>
                <br>
<button id="addQuestion" class="btn btn-defaut"><i style="color:green;" class="fa fa-plus-circle"></i>&nbsp;Ajouter une question</button>
                <div class="panel-body">

                    Question 1 : <input type="text" id="quizz_1">
                    &nbsp;
                    <div id="lastquizz"></div>
                    <!-- <pre>{{ var_dump(Auth::user())}} </pre> -->
                </div>
                <button class="btn btn-success pull-right" id="gotorep"><i class="fa fa-next"></i>Suivant</button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>


</script>


    <script src="{{asset('js/jquery-2.2.2.min.js')}}"></script>

<script>
    $( document ).ready(function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}); //token normé laravel pour proteger les requetes ajax

        $('#gotorep').click(function () { //on insert en base les questions crées
            var values = [];
            var jsonvar;
            var i = 1;
            $("input[id^='quizz_']").each(function () {

                var item1 = {
                    "data": {"nom": $(this).val()}
                };
                values.push(item1);

            });
            jsonvar = JSON.stringify({values});
            $.ajax({
                url: 'insertquestions',
                type: "post",
                data: {'insertQ': jsonvar},
                success: function (data) {
                    window.location.href = "{{url('quizz/reponse')}}";
                }
            });

        });


        $('#addQuestion').click(function () { //Sert a rajouter une question
            var previous = $('#lastquizz').prev().attr('id').replace('quizz_', '');
            var addone = parseInt(previous) + 1;
            $('#lastquizz').before('<br>Question ' + addone + ' : <input type="text" id="quizz_' + addone + '">');

        });

    });



</script>



