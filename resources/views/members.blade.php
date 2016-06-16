@extends('layouts.app')

@section('content')
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <!--
        The codes are free, but we require linking to our web site.
        Why to Link?
        A true story: one girl didn't set a link and had no decent date for two years, and another guy set a link and got a top ranking in Google!
        Where to Put the Link?
        home, about, credits... or in a good page that you want
        THANK YOU MY FRIEND!
    -->
    <title>table user list - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>" />


    <link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert.css')}}">
</head>
    <style type="text/css">
        body{
            background:#eee;
        }
        .main-box.no-header {
            padding-top: 20px;
        }
        .main-box {
            background: #FFFFFF;
            -webkit-box-shadow: 1px 1px 2px 0 #CCCCCC;
            -moz-box-shadow: 1px 1px 2px 0 #CCCCCC;
            -o-box-shadow: 1px 1px 2px 0 #CCCCCC;
            -ms-box-shadow: 1px 1px 2px 0 #CCCCCC;
            box-shadow: 1px 1px 2px 0 #CCCCCC;
            margin-bottom: 16px;
            -webikt-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }
        .table a.table-link.danger {
            color: #e74c3c;
        }
        .label {
            border-radius: 3px;
            font-size: 0.875em;
            font-weight: 600;
        }
        .user-list tbody td .user-subhead {
            font-size: 0.875em;
            font-style: italic;
        }
        .user-list tbody td .user-link {
            display: block;
            font-size: 1.25em;
            padding-top: 3px;
            margin-left: 60px;
        }
        a {
            color: #3498db;
            outline: none!important;
        }
        .user-list tbody td>img {
            position: relative;
            max-width: 50px;
            float: left;
            margin-right: 15px;
        }

        .table thead tr th {
            text-transform: uppercase;
            font-size: 0.875em;
        }
        .table thead tr th {
            border-bottom: 2px solid #e7ebee;
        }
        .table tbody tr td:first-child {
            font-size: 1.125em;
            font-weight: 300;
        }
        .table tbody tr td {
            font-size: 0.875em;
            vertical-align: middle;
            border-top: 1px solid #e7ebee;
            padding: 12px 8px;
        }
        .left-inner-addon {
            position: relative;
        }
        .left-inner-addon input {
            padding-left: 30px;
        }
        .left-inner-addon i {
            position: absolute;
            padding: 10px 12px;
            pointer-events: none;
        }


        #searchbox input, #searchbox input:hover{
            -webkit-transition: all 1s ease-in-out;
            -moz-transition: all 1s ease-in-out;
            -o-transition: all 1s ease-in-out;
            transition: all 1s ease-in-out;
        }

        #searchbox input:hover{
            width: 200px;
        }

        #searchbox input{
            width:  30px;
        }

    </style>
    <div class="container">
      <h3 align="center">Liste des membres inscrits , tous sports confondus</h3>


        <form id="searchbox" method="get" class="pull-right">
            <input type="text" name="q" width="1" id="search">
            <i class="fa fa-search"></i>

        </form>

        <body>
        <hr>
        <div class="container bootstrap snippet">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box no-header clearfix">
                        <div class="main-box-body clearfix">
                            <div class="table-responsive">
                                <table class="table user-list">
                                    <thead>
                                    <tr>
                                        <th><span>Pseudonyme</span></th>
                                        <th><span>Date de naissance</span></th>
                                        <th class="text-center"><span>Sport</span></th>
                                        <th><span>Email</span></th>
                                        <th>Contacter</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $users)
                                        <tr>
                                        <td>
                                          @if($users['photo']!="")  <img src="{{$users['photo']}}" alt="">
                                            @else
                                                @if($users['civilite']=='homme') <img src="http://bootdey.com/img/Content/user_3.jpg" alt="">
                                                    @else <img src="http://bootdey.com/img/Content/user_2.jpg" alt=""> @endif

                                        @endif
                                            <a href='#' id="members_{{$users['id']}}" class="user-link">{{$users['name']}}</a>
                                            <span class="user-subhead">{{$users['ville']}}</span>
                                        </td>
                                        <td>{{$users['datenaissance']}}</td>
                                        <td class="text-center">
                                            <span class="label label-success">{{$users['sport']}}</span>
                                        </td>
                                        <td>
                                            <a href="#">{{$users['email']}}</a>
                                        </td>
                                        <td style="width: 20%;">
                                            <a href="#" class="table-link">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-envelope fa-stack-1x fa-inverse" data-toggle="modal" data-target="#MessageIt" onclick="$('#keepid').val('{{$users['id']}}'),$('#keepname').val('{{$users['name']}}')"></i>
                                            </span>
                                            </a>

                                        </td>
                                    </tr>
                                        @endforeach

                                    <input type="hidden" id="keepname" value="">
                                    <input type="hidden" id="keepid" value="">
                                    <input type="hidden" id="currentid" value="{{Auth::id()}}">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

        </script>
        </body>
        </div>
</div>
<div class="modal fade" id="MessageIt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Nouveau message</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Destinataire:</label>
                        <input type="text" class="form-control" id="recipient-name" disabled>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="sendPrivMsg" data-dismiss="modal">Envoyer</button>
            </div>
        </div>
    </div>
</div>
        </html>



<script>

$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

$('[id^="members_"]').click(function() { //redirige vers la page profil du user
    var id = $(this).attr('id');
  window.location.href = 'members/'+id.replace("members_","");
      
});
    $('#sendPrivMsg').click(function() {
        $.ajax({
            type:"POST",
            url:"sendprivmsg",
            data:{'from':$('#currentid').val(),'to':$('#keepid').val(),'message':$('#message-text').val()},
            success: function(data){
                swal("Message", "Message envoyé avec succès!", "success")
            },
        });
    })
    $("#MessageIt").on("shown.bs.modal", function () {
$('#recipient-name').val($('#keepname').val());
    });
    $("#search").keyup(function () { //Fonction de recherche
        var value = this.value.toLowerCase().trim();

        $("table tr").each(function (index) { //va chercher dans toute les colonnes du  table le mot ecrit
            if (!index) return;
            $(this).find("td").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;
            });
        });
    });


});

</script>
<script src="{{asset('js/jquery-2.2.2.min.js')}}"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
@endsection
