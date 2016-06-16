@extends('layouts.app')


@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>

    <script type="text/javascript" charset="utf-8">
    var data = {};
        $(document).ready(function() {
            var table = $('#vue_list').DataTable({
            	"language":{
            		"url": "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            	}
            });

            var pathArray = window.location.pathname.split( '/' );
            data.table = pathArray[pathArray.length-1];
            $('#vue_list tbody').on('click', 'td', function () {
                var data1 = table.row( $(this).parents('tr') ).data();
                console.log(data1);
            });
        });
    </script>
	<body>
    <div class="container">

        <table id="vue_list" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    @foreach($names as $nom)
                        @if($nom != 'id')
                            <th class="{{$nom}}">{{trans("view.$nom")}}</th>
                        @else
                            <th style="display:none"></th>
                        @endif
                    @endforeach
                   <!--  @if(Auth::isAdmin())
                        <th></th>
                    @endif -->
                </tr>
            </thead>
            <tbody>
                @foreach($types as $type)
                <tr>
                    @foreach($names as $value)
                        @if($value == 'id')
                            <td style="display:none">{{$type->$value}}</td>
                        @else
                            <td class="{{$value}}">{{$type->$value}}</td>
                        @endif
                    @endforeach
                   <!--  @if(Auth::isAdmin())
                        <td><button  class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                        </button></td>
                    @endif -->
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script type="text/javascript">
        $('#vue_list')
            .removeClass('display')
            .addClass('table table-striped table-bordered');
    </script>
</body>
@endsection