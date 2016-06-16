<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>M2L</title>
    <link href="{{asset('css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-theme.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-responsive.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/jquery.datetimepicker.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>



    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/home') }}">
                M2L
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <!--  <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Accueil</a></li>
                </ul> -->
            @if(Auth::isinscrit())
                <ul class="nav navbar-nav">
                    <li><a href="{{url('user')}}">Formulaire joueur</a></li>
                </ul>
            @endif
            <ul class="nav navbar-nav">
                <li><a href="{{url('members')}}">Membres</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('calendar')}}">Calendrier</a></li>
            </ul>
            @if(!Auth::isAdmin())
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('list_view/lieux') }}">Les Lieux</a></li>
                </ul>
            @endif
            <ul class="nav navbar-nav">
                <li><a href="{{url('forum')}}">Forum</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('quizz/game')}}">Quizz</a></li>
            </ul>
            <ul class="nav navbar-nav col-lg-offset-3">
                <li class="dropdown" onclick="if($(this).attr('class')!='dropdown open') { $(this).addClass('open') } else { $(this).removeClass('open') }">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                        <i class="fa fa-envelope fa-fw"></i> <span class="badge alert-success" id="nombremessage">{{Auth::getNbMsg(Auth::id())}}</span>

                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">

                        @foreach(Auth::getMsg(Auth::id()) as $messages)
                            <li data-toggle="modal" data-target="#myMessage" id="checkMessage" onclick="$('#msgdate').val('{{$messages['date']}}'),$('#msgid').val('{{$messages['emetteur']}}')">
                                <input type="hidden" id="msgid" value="">
                                <input type="hidden" id="msgdate" value="">
                                <input type="hidden" id="currentid" value="{{Auth::id()}}">
                                <a href="#">
                                    <div>

                                        @if(Auth::getPhotoById($messages['emetteur'])!="")  <img width=30% src="{{Auth::getPhotoById($messages['emetteur'])}}" alt="">
                                        @else
                                            @if(Auth::getSexById($messages['emetteur'])=='homme') <img class="direct-chat-img" src="http://bootdey.com/img/Content/user_3.jpg" alt="">
                                            @else <img class="direct-chat-img" src="http://bootdey.com/img/Content/user_2.jpg" alt=""> @endif

                                        @endif
                                        <strong>{{Auth::getNameById($messages['emetteur'])}}</strong>
                                        <!--   <span class="pull-right text-muted" >
                                <em >Le {{$messages['date']}}</em>
                            </span> -->
                                    </div>

                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Connexion</a></li>
                    <li><a href="{{ url('/register') }}">Enregistrement</a></li>
                @else
                    <li class="dropdown" onclick="if($(this).attr('class')!='dropdown open') { $(this).addClass('open') } else { $(this).removeClass('open') }">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" >
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/admin_profil') }}"><i class="fa fa-btn fa-user"></i>Mon Profil</a></li>

                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Déconnexion</a></li>
                            @if(Auth::isAdmin())
                                <hr>
                                <li><a href="{{ url('quizz') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer un Quizz</a></li>

                                <li><a href="{{ url('list_view/users') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Utilisateurs</a></li>
                                <li><a href="{{ url('list_view/roles') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Roles</a></li>
                                <li><a href="{{ url('list_view/user_roles') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Roles d'utilisateur</a></li>
                                <li><a href="{{ url('list_view/participants') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Participants</a></li>
                                <li><a href="{{ url('list_view/planning') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Plannings</a></li>
                                <li><a href="{{ url('list_view/lieux') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Lieux</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<script src="{{asset('js/jquery-2.2.2.min.js')}}"></script>

@if(isset($table))
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@endif

<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{asset('js/moment-with-locales.js')}}"></script>
<script src="{{asset('js/boostrap-datetimepicker.js')}}"></script>
<script src="{{ asset('js/bootstrap-notify.js') }}"></script>
@yield('content')

        <!-- JavaScripts -->
<!-- // <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="myMessage">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header" id="answerTo">Répondre à </div>
            <div class="modal-body">
                Message reçu :
                <textarea class="form-control" style="width:100% !important" id="incoming_message" disabled></textarea>
                <hr>
                Votre réponse :
                <textarea  class="form-control" style="width:100% !important" id="sending_message"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" id="sendprivmsg" data-dismiss="modal">Répondre</button>
                <button class="btn btn-danger"  data-dismiss="modal" >Annuler</button>
            </div>
        </div>

    </div>
</div>
</html>
<script>
    $( document ).ready(function() {
        var pathArray = window.location.pathname.split( '/' );
        url = pathArray[pathArray.length-1]
        if(url == 'calendar' || url == 'user'){
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = "{{asset('js/bootstrap.js')}}";
            $("head").append(s);
        }
    });
    $('#sendprivmsg').click(function() {

        $.ajax({
            type:"POST",
            url:"sendprivmsg",
            data:{'from':$('#currentid').val(),'to':$('#msgid').val(),'message':$('#sending_message').val()},
            success: function(data){
                swal("Message", "Message envoyé avec succès!", "success")
            },
        });

    })
    $("#myMessage").on("shown.bs.modal", function () {//When the modal show up it will load every private unread message
        $.ajax({
            type:"POST",
            url:"getprivmsg",
            data:{'id':$('#msgid').val(),'date':$('#msgdate').val()},
            success: function(data){
                obj = $.parseJSON(data);
                var id = "";
                var message = "";
                var nom = "";
                var date= "";
                for (var i = 0; i < obj.length; i++) {
                    id = obj[i].id;
                    message = obj[i].message;
                    nom = obj[i].nom;
                    date = obj[i].date;

                }
                $('#incoming_message').val(message);
                $('#answerTo').append('<b>'+nom+' </b>('+date+')');
                $.ajax({
                    type:"POST",
                    url:"readmsg",
                    data:{'id':id },
                    success: function(data){
                        var m = $('#nombremessage').html();
                        if(m>0) { $('#nombremessage').html(m-1); }
                    },
                });
            },
        });

    });

</script>
