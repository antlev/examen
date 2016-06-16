
function getMonthName(month) { //Transforme le mois numerique en lettre
switch(month) {
case '01': 
return 'Janvier'
break;
case '02': 
return 'Février'
break;
case '03':
return 'Mars'
break;
case '04': 
return 'Avril'
break;
case '05': 
return 'Mai'
break;
case '06': 
return 'Juin'
break;
case '07': 
return 'Juillet'
break;
case '08': 
return 'Août'
break;
case '09': 
return 'Septembre'
break;
case '10':
return 'Octobre'
break;
case '11': 
return 'Novembre'
break;
case '12': 
return 'Décembre'
break;
   }
}

function toBottom() { //Force le scroll vers le bas de la div

  $('div').animate({scrollTop: 99999});

}


function checkSport(valsport) {  //Recupere licon du sport
  var sport = "";

if(valsport == 'Football') { sport = sport + "<i class='fa fa-futbol-o'></i>"; }
if(valsport == 'Handball') { sport = sport + "<i class='fa fa-handall-o'></i>"; }
if(valsport == 'Tennis') { sport = sport + "<i class='fa fa-tennis'></i>"; }
if(valsport == 'Basket') { sport = sport + "<i class='fa fa-futbol-o'></i>"; }
return sport;
}

function getShouts() { //Charge tous les messages de la shoutbox

  var explodate;
  var year;
  var day;
  var month;
$.ajax({
      url: 'index.php/shouts',
      type: "post",
      //dataType: 'json',
      success: function(data){
      //  alert(data);
         obj = $.parseJSON(data);
         for(var i=0;i<obj.length;i++) { 
          exploheure = obj[i].date.split(" ");
          explodate = exploheure[0].split('-');

          year = explodate[0]; //je decoupe la date a fin d'en recuperer lannée le mois et le jour
          month = explodate[1];
          day = explodate[2];
       

          check = (checkSport(obj[i].sport == '') ? defaultico : checkSport(obj[i].sport)); //si il a un sport alors on recupere licon, si non met l'icone par défaut

          $('#last').before(check+ " " + "<b>" + obj[i].name + " </b>" +  " :" + " " + obj[i].msg + "<small class='pull-right'>Le " + day + "-" + getMonthName(month) + "-" + year + "- à " + exploheure[1] + "</small><br>");
          //Juste avant le repere de fin on y rajoute le message
          }
        
      }
    });


}
$(function() { 


            toBottom(); //on force le scroll vers le bas
            var socket = io.connect('http://172.18.0.134:8080'); //j'initialise lécoute du socket.io sur le port 8080

             socket.on('getshouts', function() { //si socket.io recois getshouts en message, alors il effectue 2 actions
                  getShouts();
                  toBottom(); 
            })


                var defaultico = "<i class='fa fa-user'></i>"; //Default icon
	            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }); //Je defini le token csrf normé de laravel afin de proteger les POST

                getShouts();


                $('#sendshout').click(function() { //Des que le bouton envoyer de la shoutbox est pressé
                socket.emit('getshouts'); //On envoit le message getshouts au serveur, qui lui même va le transmettre a tous les utilisateurs

            var d = new Date();

            var month = d.getMonth()+1;
            var year = d.getFullYear();
            var day = d.getDate();           //je decoupe la date et lheure
            var hour = d.getHours();
            var minute = d.getMinutes();
            var secondes = d.getSeconds();

            var hours = 0;
            var check;


            sport = checkSport($('#sport').val()); //recuperation de l'icone du sport

            check = (sport == '') ? defaultico : sport;

            days = (day < 10) ? '0' + day : day;
            months = (month < 10) ? '0' + month : month;         //Formatage de la date
            hours = (hour < 10) ? '0' + hour : hour;
            minutes = (minute < 10) ? '0' + minute : minute;

            $('#last').before(check + " " + "<b>" + $('#authname').val() + " </b>" +  " :" + " " + $('#typeField').val() + "<small class='pull-right'>Le " + days + "-" + getMonthName(months) + "-" + year + " à " + hour + ":" + minutes + ":" + secondes + "</small><br>");

                $.ajax({ //Et on envoit au controller 'msg' avec comme post le iduser,date, et le message en question
                url: 'index.php/msg',
                type: "post",
                data: {'iduser':$('#authid').val() ,'date':year + "-" + months + "-" + days + " " + hours + ":" + minutes + ":" + secondes,'msg': $('#typeField').val() },
                 success: function(data){
                 $('#typeField').val('');
        toBottom();
                 }
        });




});

});
