$(document).ready(function () {

$('#submit').click(function(e) {
e.preventDefault();
// Create Base64 Object
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

// Define the string
valid = true;
var username = document.getElementById('name').value;
var email = document.getElementById('email').value;
var password = document.getElementById('password').value;
var password2 = document.getElementById('password_confirmation').value;
// Encode the String
var usernameenc = Base64.encode(username);
var emailenc = Base64.encode(email);
var passwordenc = Base64.encode(password);
var password2enc = Base64.encode(password2);
console.log(usernameenc);
console.log(emailenc);
console.log('pass 1 : '+password);
console.log('pass 2 : '+password2);
if(email == ''){
  alert('Mail non rempli');
  valid = false;
}
else if (!(document.getElementById('email').value.indexOf("@")>=0) || !(document.getElementById('email').value.indexOf(".")>=0)) {
  alert("Mail invalide !");
  valid = false;
}
if(username == ''){
  alert('Le \"Nom\" n\'est pas renseigné');
  valid = false;
}
else{
  $.ajax({
    type: "get",
    url: "verif",
    data: {name: usernameenc, action: "verif_name" , email: emailenc},
    success: function(data){
      data = JSON.parse(data);
      console.log(data);
      if(data.name != 'ok'){
        alert('Nom déjà utilisé');
        verif = false;
      }
      else if(data.mail != 'ok'){
        alert('Email déjà utilisé');
      }
      else{
        if(valid){
          data = {'username': usernameenc, 'email': emailenc, 'password': passwordenc};
          create_user(data);
        }
      }
    }
  });
}
if(password == ''){
  alert('Mot de passe non rempli');
  valid = false;
}
else if(password2 == ''){
  alert('Confirmation du mot de passe vide');
  valid = false;
}
else if (password.length < 6){
  alert('Nombre de caractère insuffisant pour le mot de passe');
  valid = false;
}
else if(password != password2){
  alert('Mot de passe différent');
  valid = false;
}

});
function create_user(data){
  $.ajax({
    type: 'get',
    url: 'register2',
    data: data,
  });
    document.location.href= "/laravel/public/index.php/auth/login";
}
 
});