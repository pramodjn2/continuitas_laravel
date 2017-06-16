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
              <h2>{{trans('common.changePassword')}}</h2>
              <br>
            </div>
            <form class="form-horizontal" action="{{ url('/cms/emailverify') }}" method="post" id="form1">
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
                <div  class="input-group"> <span class="input-group-addon"><i class="fa fa-key"></i></span>
                   <input id="password" type="password" class="form-control" name="password" placeholder="{{trans('login.password')}}" maxlength="40" minimum="6"  required value="{{ old('password') }}">
                </div>
                <div class="spacing"></div>
                <div  class="input-group"> <span class="input-group-addon"><i class="fa fa-key"></i></span>
                  <input id="confirmPassword" type="password" class="form-control" name="confirmPassword" placeholder="{{trans('login.password')}}" maxlength="40" minimum="6"  required value="{{ old('confirmPassword') }}">
                  
                  <input id="verifycode" type="hidden" class="form-control" name="verifycode" value="{{$verifycode}}">
                </div>
                <div class="spacing"></div>
                
                <button id="singlebutton" name="singlebutton" class="btn btn-success btn-sm  pull-right">{{trans('common.changePassword')}}</button>
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
<script type="text/javascript" src="{{url('assets/vendors/jquery-validation/js/jquery.validate.min.js')}}"></script> 


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
			
			
			$(document).ready(function() {
	
		        var form = $('#form1');
		        var errorHandler1 = $('.errorHandler', form);
		        var successHandler1 = $('.successHandler', form);
		        $.validator.addMethod("FullDate", function () {
		            //if all values are selected
		            if ($("#dd").val() != "" && $("#mm").val() != "" && $("#yyyy").val() != "") {
		                return true;
		            } else {
		                return false;
		            }
		        }, 'Please select a day, month, and year');
		        form.validate({
		            errorElement: "span", // contain the error msg in a span tag
		            errorClass: 'help-block',
		            errorPlacement: function (error, element) { // render error placement for each input type
		                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
		                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
		                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr("name") == "yyyy") {
		                    error.insertAfter($(element).closest('.form-group').children('div'));
		                } else {
		                    error.insertAfter(element);
		                    // for other inputs, just perform default behavior
		                }
		            },
					
					
						
		            ignore: "",
		            rules: {
		                password: {
		                    minlength: 6,
							maxlength:40,
		                    required: true
		                },
		                confirmPassword: {
		                    minlength: 6,
							maxlength:40,
							equalTo:'#password',
		                    required: true
		                },
		               },
		            messages: {
		                firstname: "Please specify your first name",
		                lastname: "Please specify your last name",
		                email: {
		                    required: "We need your email address to contact you",
		                    email: "Your email address must be in the format of name@domain.com"
		                },
		                gender: "Please check a gender!"
		            },
		            groups: {
		                DateofBirth: "dd mm yyyy",
		            },
		            invalidHandler: function (event, validator) { //display error alert on form submit
		                successHandler1.hide();
		                errorHandler1.show();
		            },
		            highlight: function (element) {
		                $(element).closest('.help-block').removeClass('valid');
		                // display OK icon
		                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
		                // add the Bootstrap error class to the control group
		            },
		            unhighlight: function (element) { // revert the change done by hightlight
		                $(element).closest('.form-group').removeClass('has-error');
		                // set error class to the control group
		            },
		            success: function (label, element) {
		                label.addClass('help-block valid');
		                // mark the current input as valid and display OK icon
		                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
		            },
		            submitHandler: function (form) {
		                successHandler1.show();
		                errorHandler1.hide();
		                // submit form
		                 form.submit();
		            }
		        });
		   
				
});				
		</script>
</body>
</html>