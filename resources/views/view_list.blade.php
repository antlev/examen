@extends('layouts.app')

@section('content')
<style>

body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
}

#top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
}

#calendar {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 10px;
}
.ui-autocomplete {
    z-index: 215000000 !important;
}
.spinner {
    position: fixed;
    top: 50%;
    left: 50%;
    margin-left: -50px; /* half width of the spinner gif */
    margin-top: -50px; /* half height of the spinner gif */
    text-align:center;
    z-index:1234;
    overflow: auto;
    width: 150px; /* width of the spinner gif */
    height: 150px; /*hight of the spinner gif +2px to fix IE8 issue */
}

</style>
    <script type="text/javascript" charset="utf-8">
    // Initialisation de l'objet data
    var data = {
        name: '',
        id: '',
        action: '',
        lieux: []
    };
        $(document).ready(function() {
            // fonction pour raffraichir une page
            function timeRefresh(timeoutPeriod) 
            {
                $('#spinner').show();
                setTimeout("location.reload(true);",timeoutPeriod);
            }
            // Initialisation du datatable 
            var table = $('#vue_list').DataTable({
                "language":{
                    "url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
                },
                'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'width': '1%',
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox">';
                    }
                }],
                'order': [[1, 'desc']],
            });
            var pathArray = window.location.pathname.split( '/' );
            // Récupération de la table 
            data.table = pathArray[pathArray.length-1];
            // Au click du boutton de suppréssion
            $('#delete').on('click', function(){
                var i= 0;
                // Confirmation de l'action 
                if(confirm("Etes vous sur de vouloir supprimer ces informations ?")){
                    // Suppréssion de toutes les lignes cochés
                    $( table.cells().nodes() ).find(':checkbox').each(function(){
                        if($(this)[0].checked){
                            i=1;
                            data.data = table.row($(this).parents('tr')).data();
                            $.ajax({
                                type: 'GET',
                                url: '../supp_view',
                                data: data,
                            });
                        }
                    });
                }
                // On refresh seulement si on à coché une valeur a supprimer
                if(i == 1){timeRefresh('1000');}                
            });

            // Séléctionner toutes les lignes 
            $('#select_all').change(function(){
                $('#delete').prop('disabled', !$(this).is(':checked'));
                $('#edit').prop('disabled', true);
                var cells = table.cells( ).nodes();
                $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
            });

            // Au click d'une case a cocher
            $('.dt-body-center').on('click', function(){
                // On récupère le nombre de case coché
                var i = $( "input:checked" ).length;
                // On cache les boutton si aucune ligne n'est séléctionné
                if(i == 0){$('#delete, #edit').prop('disabled', true);}
                // On affiche tout les bouttons si une seul case est coché
                else if(i == 1){$('#delete, #edit').prop('disabled', false);}
                // On cache le boutton edition si plus d'une case est cocher et affiche la suppression
                else{
                    $('#delete').prop('disabled', false);
                    $('#edit').prop('disabled', true);
                }
            });
            // Au double click d'une case
            $('#vue_list tbody').on('dblclick', 'td', function(){
                if(!{{Auth::isAdmin()}}){return;}
                // On récupère les infos de la ligne
                data1 = table.row( $(this).parents('tr') ).data();
                data.id = data1[1];
                //Si l'input existe déjà (on redouble-clique sur le TD) on fait rien.
                var input = $(this).find('input');
                if(input.length < 1){
                    var origin = $(this).find('span');
                    if(origin.context.className.indexOf('nok') != -1){return;}
                    data.name = origin.context.id;
                    //Quick security, si le span n'existe pas, je le créé.
                    if(origin.length === 0){
                        $(this).wrapInner('<span></span>');
                        origin = $(this).find('span');
                    }
                    //Je supprime toute selection faite causé par double clique
                    if (window.getSelection){
                        window.getSelection().removeAllRanges();
                    } 
                    else if (document.selection) {
                        document.selection.empty();
                    }

                    //on supprime les précedents input s'ils existent
                    $('.editspan').remove();
                    $('span').show();

                    //on cache la valeur
                    origin.hide();
                    //et on crée l'input
                    if( origin.context.id == 'sport' ){
                        $(this).append('<span class="editspan"><select id="select"><option></option><option value="Football">Football</option><option value="Basket">Basketball</option><option value="Handball">Handball</option><option value="Tennis">Tennis</option><option value="Rugby">Rugby</option><option value="Aucun">Aucun</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else if(origin.context.id == 'type' && data.table == 'planning'){
                        $(this).append('<span class="editspan"><select id="select"><option value="Entrainement">Entrainement</option><option value="Reunion">Réunion</option><option value="Match">Match</option><option value="Tournoi">Tournoi</option><option value="Fête">Fête</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else if(origin.context.id == 'role'){
                        $(this).append('<span class="editspan"><select id="select"><option value="Invités">Invités</option><option value="Entraineurs">Entraineurs</option><option value="Membres">Membres</option><option value="Joueurs">Joueurs</option><option value="Direction">Direction</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else if (origin.context.id == 'categorie'){
                        $(this).append('<span class="editspan"><select id="select"><option></option><option value="Junior">Junior</option><option value="U-11">U-11</option><option value="U-13">U-13</option><option value="U-15">U-15</option><option value="U-17">U-17</option><option value="U-20">U-20</option><option value="Senior">Senior</option><option value="Loisir">Loisir</option></select><span style="cursor: pointer" class="update">✓</span></span>');
                        $('#select').val(origin.text());
                    }
                    else if(origin.context.id == 'lieu_name'){
                        $(this).append('<span class="editspan"><input id="lieu" style="width: 150px;" value="'+origin.text()+'"></input><span style="cursor: pointer" class="update">✓</span></span>');
                        autocomplete('lieu', 'lieux','sport', data1[6],  'nom');
                        data.lieu = data1;
                    }
                    else {
                        $(this).append('<span class="editspan"><input style="width: 150px;" value="'+origin.text()+'"></input><span style="cursor: pointer" class="update">✓</span></span>');
                        data.all = data1;
                    }
                }
            });
            //quand on appuit sur le bouton d'edit
            $('#vue_list tbody td').on('click', '.update', function(){
                editAjax($(this));
            });

            //quick edit en cas de double clique
            function editAjax(item){
                // Différente vérification selon le type de champs
                if(data.name == 'datenaiss'){
                    // On vérifie le format et envoie le message d'erreur selon la valeur 
                    if(item.prev().val() != ""){
                        date = item.prev().val();
                        // On décompose la date pour faire une vérification des données
                        split = date.split('/');
                        if(split.length != 3){err('La date est mal rempli veuillez respecter le format Jour/mois/Année'); return;}
                        else if(split[0].length != 2 || split[1].length != 2 || split[2].length != 4){err('La date est mal rempli veuillez respecter le format Jour/mois/Année'); return;}
                        else{
                            var d = new Date();
                            year = d.getFullYear()-5;
                            if(split[0] == "00" || split[0] > 31){err('Le nombre de jour défini est faux');return;}
                            if(split[1] == "00" || split[1] > 12){err('Le nombre de mois est compris entre 01 et 12');return;}
                            if(split[2] < 1920  || split[2] > year){err('L\' année saisie est incorrecte ');return;}
                        }
                    }
                    else{err('La date est vide');return;}
                }
                // On vérifie que les données de début et de fin soit bien rempli
                else if(data.name == 'debut' || data.name == 'fin'){
                    if(data.name =='debut'){
                        // Si le début et supérieur à la fin
                        if(data.all[4] < item.prev().val()){
                            err('La date de début doit être inférieur à celle du fin');
                            return;
                        }
                        pos = 3;
                    }
                    else{
                        // Si la fin est inférieur au début
                        if(data.all[3] > item.prev().val()){
                            err('La date de Fin doit être supérieur à celle du début');
                            return;
                        }
                        pos = 4;
                    }
                    // On décompose les deux dates pour comparer les valeurs
                    split = item.prev().val().split('-');
                    split2 = data.all[pos].split('-' );
                    split[2] = split[2].split(' ');
                    split2[2] = split2[2].split(' ');
                    if(split.length != split2.length){err('Format de la date non respecté');return;}
                    else if(split[0] != split2[0]){err('L\'année ne doit pas être changé'); return;}
                    else if(split[1] != split2[1]){err('Le mois ne doit pas être changé');return;}
                    else if(split[2][0] != split2[2][0]){err('Le jour ne doit pas être changé'); return;}
                }
                // Vérification si le lieu fait partie de la M2L
                else if(data.name == 'lieu_name'){
                    if(data.lieux.indexOf(item.prev().val()) == -1){
                       err('Ce lieu n\'appartient pas à la M2L </br> Voici la liste des éléments disponibles : '+data.lieux.toString());
                       return;
                    }
                }
                // Vérification de format du mail
                else if (data.name == 'email'){
                    if(isEmail(item.prev().val()) == false){
                        err('Le mail est incorrecte');
                        return;
                    }
                }
                else if(data.name == 'admin'){
                    if(item.prev().val() != 0 && item.prev().val() != 1){
                        err('Un admin est égale à 0 ou 1');
                        return;
                    }
                }
                data.name = data.name == 'role' ? 'id_role' : data.name;
                // Appel ajax pour mettre à jour la valeur 
                $.get("../update", {
                    value: item.prev().val(),
                    id: data.id,
                    name: data.name,
                    table: data.table,
                }).done(function() {
                    timeRefresh('1000');
                });
            }
            // Fonction pour afficher le message d'erreur
            function err(text){
                document.getElementById('error').className = 'alert alert-danger';
                document.getElementById('error').style.display = "";
                document.getElementById('error').innerHTML = '<strong>Attention!</strong> '+text;
            }
            // Fonction pour faire l'autocompletion 
            function autocomplete(field, table, name, type,  nom){
                // Initialisation des données à envoyer
                datas = {'action': 'autocomplete', type: type, name: name, table: table, nom:nom};
                // Appel ajax pour l'autocompletion
                $.ajax({
                  type: "GET",
                  url: '../lieu',
                  data: datas,
                  success:function(response){
                    response = JSON.parse(response);
                    var lieu = new Array();
                    for(i in response){
                      lieu.push(response[i].nom);
                    }
                    // On stock les lieux pour vérification
                    data.lieux = lieu;
                    $("#"+field).autocomplete({
                      source: lieu
                    });
                  }
                });
            }
            // Mettre les lieux selon les sports
            $('#sport_1').change(function(){
                autocomplete('lieu_name_1', 'lieux','sport', $('#sport_1').val(), 'nom');
            });
            // En cas de creation ou click edit 
            $('#create, #edit').on('click', function(){
                // Préparation selon la table ou l'action
                data.action = $(this).context.id == 'create' ? 'insert' : 'update';
                if(data.table == 'user_roles'){
                    autocomplete('name_1', 'users');                     
                }
                else if(data.table == 'participants'){
                    autocomplete('nom_planning_1', 'participants')
                }
            });
            // En cas de création
            $('#create').on('click', function(){
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    $('#'+data2.id).val('');
                });
            });
            // Au click du bouton d'édition
            $('#edit').on('click', function(){
                data.action = 'update';
                // Récupération des infos 
                value = table.row( $( "input:checked" ).parents('tr')).data();
                data.id = value[1];
                // Gestion des index pour acceder à la valeur 
                diff = value.length - $( '#myModal input, #myModal select' ).length;
                if(data.table == 'user_roles'){diff = 4;}
                else if(data.table != 'users'){diff -= 2;}
                // Parcour du modal d'édition pour le remplir avec les informations récupéré
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    $('#'+data2.id).val(value[index+diff]);
                });
            });
            // Sauvegarde du modal d'édition 
            $('#save').on('click', function(){
                valid = true;
                data.create = [];
                // parcours de toutes les case du modal
                $( '#myModal input, #myModal select' ).each(function(index,data2){
                    // Vérification des infos modifié
                    id = $(this).parent()[0].id;
                    clas = $(this).parent()[0].className;
                    if($('#'+data2.id).val() == '' || (data2.id == 'email_1' && isEmail($('#'+data2.id).val()) == false)){
                        valid = false;
                        if(clas.indexOf('has') == -1){$('#'+id).addClass('has-error');}
                        else if(clas.indexOf('success') != -1){$('#'+id).removeClass('has-success').addClass('has-error');}
                    }
                    else{
                        if(clas.indexOf('has') == -1){$('#'+id).addClass('has-success');}
                        else if(clas.indexOf('error') != -1){$('#'+id).removeClass('has-error').addClass('has-success');}
                        id2 = data2.id.replace('_1', '');
                        data.create.push({
                            value: $('#'+data2.id).val(),
                            name: id2,
                        });
                    }
                });
                // Si les vérifications sont concluante
                if(valid == true){
                    // Envoie des données du modal
                    $.ajax({
                        url: "../create",
                        type: "get",
                        data : data,
                    });
                    timeRefresh('1000');
                }
           });
            // fonction de vérification de mail
            function isEmail(myVar){
                // Définition de l'expression régulière d'une adresse email
                var regEmail = new RegExp('^[0-9a-z._-]+@{1}[0-9a-z.-]{2,}[.]{1}[a-z]{2,5}$','i');

                return regEmail.test(myVar);
            }
        });
    </script>

<body>
    <!-- spinner -->
    <div id="spinner" class="spinner" style="display:none;">
        <img src="{{asset('img/spin.gif')}}">
    </div>
    <div class="container">
        <div id="error" style="display:none;" align="left"></div>
        @if(Auth::isAdmin())
            <div class="container">
                <button class="btn btn-danger" id="delete" disabled> Supprimer</button>
                @if($table != 'planning')
                    <button class="btn btn-success" id="create" data-toggle="modal" data-target="#myModal">Création</button>
                @endif
                <button class="btn btn-info" id="edit" data-toggle="modal" data-target="#myModal" disabled>Edition</button>
            </div>
            </br>
        @endif
        <table id="vue_list" class="display" cellspacing="0" width="100%">
            <!-- En tête de la table -->
            <thead>
                <tr>
                    @if(Auth::isAdmin())
                    <th><input id="select_all" value="1" type="checkbox"></th>
                    @endif
                    @foreach($names as $nom => $edit)
                        @if(strpos($nom,'id') === false)
                            <th>{{trans("view.$nom")}}</th>
                        @else
                            <th style="display:none"></th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <!-- remplissage du corps de la table -->
            <tbody>
                @foreach($types as $type)
                <tr>
                    @if(Auth::isAdmin())
                    <td></td>
                    @endif
                    @foreach($names as $key => $value)
                        @if(strpos($key,'id') === false)
                            <td class="tedit {{$value}}" id ="{{$key}}">{{$type->$key}}</td>
                        @else
                            <td style="display:none">{{$type->$key}}</td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Modal pour la creation ou édition -->
    <div id="myModal" class="modal " role="form">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{trans("view.$table")}}</h4>
                </div>
                <div class="modal-body">
                @foreach($names as $nom => $edit)
                    @if($edit == 'ok' || ($nom == 'name' && $table == 'user_roles') || ($table == 'participants' && $nom == 'nom_planning'))
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{trans("view.$nom")}}</label>
                            <div class="col-md-6" id="{{'class_'.$nom}}">
                                @if($nom == 'sport')
                                <select class="form-control" id="{{$nom.'_1'}}">
                                    <option></option>
                                    <option value="Football">Football</option>
                                    <option value="Basket">Basketball</option>
                                    <option value="Handball">Handball</option>
                                    <option value="Tennis">Tennis</option>
                                    <option value="Rugby">Rugby</option>
                                    <option value="Aucun">Aucun</option>
                                </select>
                                @elseif($nom == 'type')
                                <select class="form-control" id="{{$nom.'_1'}}">
                                    <option></option>
                                    <option value="Entrainement">Entrainement</option>
                                    <option value="Reunion">Réunion</option>
                                    <option value="Match">Match</option>
                                    <option value="Tournoi">Tournoi</option>
                                    <option value="Fête">Fête</option>
                                </select>
                                @elseif($nom == 'categorie')
                                    <select class="form-control" id="{{$nom.'_1'}}">
                                        <option></option>
                                        <option value="Junior">Junior</option>
                                        <option value="U-11">U-11</option>
                                        <option value="U-13">U-13</option>
                                        <option value="U-15">U-15</option>
                                        <option value="U-17">U-17</option>
                                        <option value="U-20">U-20</option>
                                        <option value="Senior">Senior</option>
                                        <option value="Loisir">Loisir</option>
                                    </select>
                                @elseif($nom == 'role')
                                    <select class="form-control" id="{{$nom.'_1'}}">
                                        <option></option>
                                        <option value="Invités">Invités</option>
                                        <option value="Entraineurs">Entraineurs</option>
                                        <option value="Membres">Membres</option>
                                        <option value="Joueurs">Joueurs</option>
                                        <option value="Direction">Direction</option>
                                    </select>                                
                                @else
                                    <input type="text" class="form-control" id="{{$nom.'_1'}}">
                                @endif
                            </div>
                        </div></br></br>
                    @endif
                @endforeach
                </div>
                <div class="modal-footer">
                    <div id="error" style="display:none;" align="left"></div>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                      <button type="button" class="btn btn-primary" id="save">Sauvegarde</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#vue_list')
            .removeClass('display')
            .addClass('table table-striped table-bordered');        
    </script>
</body>

@endsection