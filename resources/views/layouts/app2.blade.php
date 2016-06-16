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
            <ul class="nav navbar-nav">
                <li><a href="{{url('users')}}">Formulaire joueur</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('members')}}">Membres</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('calendar')}}">Calendrier</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('forum')}}">Forum</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li><a href="{{url('quizz/game')}}">Quizz</a></li>
            </ul>
            <ul class="nav navbar-nav col-lg-offset-3">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">

                    </a>

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
                                <li><a href="{{ url('list_view/user_roles') }}"><i class="fa fa-btn fa-sign-out"></i>Administrer les Roles</a></li>
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


@yield('content')

        <!-- JavaScripts -->
<!-- // <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="myMessage">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header" id="answerTo">Répondre à </div>
            Message reçu :
            <textarea class="form-control" style="width:100% !important" id="incoming_message" disabled></textarea>
            <hr>
            Votre réponse :
            <textarea  class="form-control" style="width:100% !important" id="sending_message"></textarea>
            <div class="modal-footer">
                <button class="btn btn-default" id="sendprivmsg" data-dismiss="modal">Répondre</button>
                <button class="btn btn-danger"  data-dismiss="modal" >Annuler</button>
            </div>
        </div>

    </div>
</div>
</html>

