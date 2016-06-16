function requestvchris(url2,id_niveau,id_ecrire) {

var xhr=null;

if (window.XMLHttpRequest)
{ // Firefox et autres
xhr = new XMLHttpRequest();
}
else if(window.ActiveXObject) // Internet Explorer
{
try
{
xhr = new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e) {}
}

}
if (!xhr) {
alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
xhr=false;
}



xhr.onreadystatechange = function() { traitementReponse(xhr,id_ecrire); };
//ajaxBox3_on();

xhr.open("POST",url2, true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
var obj=id_niveau;
data="val_sel="+obj;
xhr.send(data);
//alert(obj);
}

function request(url2,id_niveau,id_ecrire) {

var xhr=null;

if (window.XMLHttpRequest)
{ // Firefox et autres
xhr = new XMLHttpRequest();
}
else if(window.ActiveXObject) // Internet Explorer
{
try
{
xhr = new ActiveXObject("Msxml2.XMLHTTP");
}
catch (e)
{
try
{
xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e) {}
}

}
if (!xhr) {
alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
xhr=false;
}

xhr.onreadystatechange = function() { traitementReponse(xhr,id_ecrire); };
//ajaxBox3_on();

xhr.open("POST",url2, true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
var obj=document.getElementById(id_niveau);
data="val_sel="+obj.value;
xhr.send(data);
//alert(obj);
}

function traitementReponse(xhr,id_ecrire)
{
var affich="";

if (xhr.readyState == 4)
{
if(xhr.status == 200)
{

var affich_list=xhr.responseText;
var obj = document.getElementById(id_ecrire);
obj.innerHTML = affich_list;
}
else
{
alert('Un probleme est survenu avec la requete');
}
}
}