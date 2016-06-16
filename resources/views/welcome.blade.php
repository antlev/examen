@extends('layouts.app')

@section('content')

        <meta name="csrf-token" content="<?php echo csrf_token() ?>" />

<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-icon.css') }}">


<input type="hidden" id="authname" value="{{ Auth::user()->name }}">
<input type="hidden" id="authid" value="{{ Auth::id() }}">
<input type="hidden" id="sport" value="{{ Auth::user()->sport }}">
<input type="hidden" id="dateshout" value="">




        <script src="{{ asset('js/shoutbox.js') }}"></script><!-- Appel js qui gère la shoutbox-->
        <script src="{{ asset('/node_modules/socket.io-client/socket.io.js') }}"></script><!-- Appel du module socket de node js -->


<div class="container">
<div class="col-lg-11 col-md-11">

<div id="shoutbox" style="border: 1px solid #ddd;overflow:auto;height:100px;"  >
<div id="last"></div>
</div>

<input type="text" id="typeField" class="form-control" placeholder="Ecrire son message">
<br>
</div>
<div class="col-lg-1 col-md-1">
<button class="btn btn-default" id="sendshout"><i class="fa fa-send"> Envoyer</i></button>
</div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Bienvenue sur le site de la Maison des ligues de Lorraine</h3></div>

                <div class="panel-body">
                    <p>La Maison des ligues de Lorraine (M2L) a pour mission de fournir des espaces et des services aux différentes ligues sportives régionales et à d'autre structures hébergées. 
                        La M2L est une structure financée par le Conseil de Lorraine dont l'administration est déléguée au Comité Régional Olympique et Sportif de Lorraine(CROSL)</p>
                    <p>La maison des ligues de Lorraine est une association regroupant plusieurs sports  : </p>
                    <ul>
                        <li>Football</li>
                        <li>Handball</li>
                        <li>Tennis</li>
                        <li>Basksetball</li>
                        <li>Rugby</li>
                    </ul>
                    <p>Pour chacun de ses sports sont mis à disposition des terrains pour les différents sports que se soient pour des entrainements, des matchs ou des tournois.</p>
                    <p>Ainsi que des entraineur qualifiées pour encadrer les différents joueurs du club.</p>
                    <p>Dans un soucis de clarté et de simplicité d'utilisation le fonctionnement du site est ouvert à tous.</p>
                    <p>Dans un premier temps pour se connecter il faudra <strong>s'enregistrer</strong> grâce au bouton du même nom présent en haut à gauche afin de créer son compte et de pouvoir se connecter.</p>
                    <p>Ensuite si vous souhaitez vous inscrire en temps que joueur il faudra remplir notre formulaire d'inscription dans le menu ci-dessus.</p>
                    <p>Une fois le formulaire rempli vous aurez accès dans votre calendrier aux différentes planifications que votre entraineur aura créé afin de vous tenir informer des évènements avec l'heure et le lieu.</p>
                </div>
            </div>
        </div>
    </div>
</div>
        <script src="{{asset('js/jquery-2.2.2.min.js')}}"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@endsection
