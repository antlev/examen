
<meta http-equiv="content-type" content="text/html; charset=utf-8" />


<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

<script src="{{ asset('js/jquery-2.1.1.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('js/login/functions.js') }}"></script>
<script src="{{ asset('js/login/checkbox.js') }}"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
                       <div class="wrap">
                <p class="form-title">
                    Ligue des Lorraines</p>
<br>
<br>

<br>
{!! Form::open(array('url'=>'/login','method'=>'POST', 'id'=>'myform')) !!}
    {!! csrf_field() !!}
    <div>
        Email
   {!! Form::text('email',old('email'),array('id'=>'','class'=>'form-control span6','placeholder' => 'Email' )) !!}    
</div>

    <div>
        Password
  {!! Form::password('password',array('class'=>'form-control span6', 'placeholder' => 'Please Enter your Password'  )) !!}    
</div>
<div class="remember-forgot">
                    <div class="row">
                        <div class="col-md-6">
    <div>
        
    </div>
</div></div></div>
<br>
{!! Form::submit('Se connecter', array('class'=>'btn btn-success send-btn','style'=>'width:100% !important')) !!}
<div>&nbsp;</div>
 <span class="button-checkbox">
<button type="button" class="btn btn-default">Se rappeler</button>
<input type="checkbox" class="hidden" name="remember" />
    </span>
|     
<button class="btn btn-default" onclick="window.location.href='{{url('register')}}'"><i class="fa fa-sign-in" style="cursor:pointer"></i> Inscription</button>
{!! Form::close() !!}


@if(strstr($errors->default,'These credentials do not match our records'))  
<script>

errorLogin();
</script>


@endif
                       </div></div></div></div>



