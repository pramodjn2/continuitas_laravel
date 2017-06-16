<!DOCTYPE html>
<html>
<head>
<title><?=$title ? $title : ''?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{url('assets/vendors/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
<!-- <link rel="stylesheet" href="{{url('assets/css/yep-rtl.css')}}-->
<!-- Related css to this page -->
<link rel="stylesheet" href="{{url('assets/vendors/animate/css/animate.min.css')}}">
<!-- Yeptemplate css --><!-- Please use *.min.css in production -->
<link rel="stylesheet" href="{{url('assets/css/yep-style.css')}}">
<link rel="stylesheet" href="{{url('assets/css/yep-vendors.css')}}">
<!-- favicon -->
<link rel="shortcut icon" href="{{url('assets/img/favicon/favicon.ico')}}" type="image/x-icon">
<link rel="icon" href="{{url('assets/img/favicon/favicon.ico')}}" type="image/x-icon">
</head>

<!-- You can use .rtl CSS in #login-page -->
<body id="mainbody" class="login-page" >
<canvas id="spiders" class="hidden-xs" ></canvas>
<div class="">
  <div style="margin: 5% auto; position: relative; width: 400px;">
    <div id="sign-form" class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <div class="text-center">
              <h2>{{config('app.site_name')}}</h2>
              <br>
            </div>
            <form class="form-horizontal" action="{{ url('/cms/login') }}" method="post">
               <div class="form-group"> @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger"> {{ Session::get('error') }} </div>
        @endif 
        
       @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif
        </div>
              <fieldset>
               {{ csrf_field() }}
                <div class="spacing hidden-md"></div>
                <div  class="input-group"> <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input id="login-username" type="email" class="form-control" name="email" value="" placeholder="{{trans('login.enterYourEmail')}}" maxlength="40" required value="{{ old('email') }}">
                </div>
                <div class="spacing"></div>
                <div  class="input-group"> <span class="input-group-addon"><i class="fa fa-key"></i></span>
                  <input id="login-password" type="password" class="form-control" name="password" placeholder="{{trans('login.password')}}" maxlength="40" minimum="5"  required value="{{ old('password') }}">
                </div>
                <div class="spacing"></div>
                <div class="checkbox checkbox-primary">
                  <input id="remember" type="checkbox"  >
                  <label for="remember">{{trans('common.rememberMe')}}</label>
                </div>
                <button id="singlebutton" name="singlebutton" class="btn btn-success btn-sm  pull-right">{{trans('common.signIn')}}</button>
              </fieldset>
            </form>
            <a id="forget" href="javascript:void(0);" class="grey">{{trans('common.forgetPassword')}} </a> </div>
        </div>
      </div>
    </div>
    <div id="q-sign-in" class="panel panel-default" style="display:none;">
      <div class="panel-body text-center"> {{trans('common.iHaveAnAccount')}} <a id="sign-in" href="javascript:void(0);"> <b> {{trans('common.clickHere')}}</b></a> </div>
    </div>
    <div id="forget-form" class="panel panel-default animated " style="display:none;">
      <div class="panel-body">
        <div class="text-center">
          <h2>{{config('app.site_name')}}</h2>
          <h5 class="grey">{{trans('common.enterEmail')}}</h5>
          <br>
        </div>
        <div class="row">
          <div class="col-md-12">
             <form class="form-horizontal" action="{{ url('/cms/forgetPassword') }}" method="post">
              <fieldset>
             
                <div class="spacing hidden-md"></div>
                <div  class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input id="login-email-1" type="email" class="form-control" name="email" placeholder="{{trans('common.enterYourEmail')}}" maxlength="40" required>
                   {{ csrf_field() }}
                </div>
                <div class="spacing"></div>
                <div class="spacing"><br>
                </div>
                <button id="singlebutton1" name="singlebutton" class="btn btn-info btn-sm pull-right">{{trans('common.submit')}}</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- General JS script library--> 
<script type="text/javascript" src="{{url('assets/vendors/jquery/jquery.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-ui/js/jquery-ui.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/bootstrap/js/bootstrap.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-searchable/js/jquery.searchable.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-fullscreen/js/jquery.fullscreen.min.js')}}"></script> 

<!-- Yeptemplate JS Script --><!-- Please use *.min.js in production --> 
<script type="text/javascript" src="{{url('assets/js/yep-script.js')}}"></script> 

<!-- Related JavaScript Library to This Pagee --> 

<!-- Plugins Script --> 
<script type="text/javascript">
			$(function(){
				$('#forget').on('click', function(event) {	
					$('#sign-form').hide();		
					$('forget-form').show();
		
					$('#q-sign-in').show();
					$('#q-register').hide();

					$('#forget-form').show();								
					$('#forget-form').addClass('animated bounce');						
				});
			});

			$(function(){
				$('#sign-in').on('click', function(event) {
					$('#forget-form').hide();		
					$('#register-form').hide();		

					$('#q-sign-in').hide();	
					$('#q-register').show();

					$('#sign-form').show();			
					$('#sign-form').addClass('animated bounce');
				});
			});

			$(function(){
				$('#register').on('click', function(event) {
					$('#forget-form').hide();		
					$('#sign-form').hide();		

					$('#q-sign-in').show()
					$('#q-register').hide();	

					$('#register-form').show();			
					$('#register-form').addClass('animated bounce');
				});
			});				
		</script>
</body>
</html>