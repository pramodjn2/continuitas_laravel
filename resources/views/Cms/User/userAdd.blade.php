@extends('Cms.layouts.app')
@section('content')
<style>
#filetag{display: none;}
</style>
<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$title}}</h2>
    </div>
    <div class="col-md-12 ">
      <div  class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title"> <i class="fa fa-edit"></i> {{$title}} {{trans('common.addForm')}}
            <div class="bars pull-right"> <a href="#"><i class="maximum fa fa-expand" data-toggle="tooltip" data-placement="bottom" title="Maximize"></i></a> <a href="#"><i class="minimize fa fa-chevron-down" data-toggle="tooltip" data-placement="bottom" title="Collapse"></i></a> </div>
          </div>
        </div>
        <div class="panel-body">
          <hr>
          <form method="post" action="{{url('cms/user/store')}}" role="form" id="form1" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-times-sign"></i> You have some form errors. Please check below. </div>
                <div class="successHandler alert alert-success no-display"> <i class="fa fa-ok"></i> Your form validation is successful! </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"> {{trans('common.firstName')}} <span class="symbol required">*</span> </label>
                  <input type="text" placeholder="{{trans('common.firstName')}}" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                  <label class="control-label"> {{trans('common.lastName')}} <span class="symbol required"></span> </label>
                  <input type="text" placeholder="{{trans('common.lastName')}}" class="form-control" id="surname" name="surname">
                </div>
                <div class="form-group">
                  <label class="control-label"> {{trans('common.email')}} <span class="symbol required">*</span> </label>
                  <input type="email" placeholder="{{trans('common.email')}}" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                  <label class="control-label"> {{trans('common.password')}} <span class="symbol required">*</span> </label>
                  <input type="password" placeholder="{{trans('common.password')}}" class="form-control" name="password" id="password">
                </div>
                <div class="form-group">
                  <label class="control-label"> {{trans('common.gender')}} <span class="symbol required">*</span> </label>
                  <div>
                    <div class="radio radio-primary radio-inline">
                      <input type="radio"  value="heer" name="gender" id="gender_male" >
                      <label for="gender_male">{{trans('common.male')}}</label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                      <input type="radio"  value="mevrouw" name="gender" id="gender_female" checked >
                      <label for="gender_female">{{trans('common.female')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"> {{trans('common.userGroup')}} <span class="symbol required">*</span> </label>
                  <select class="form-control"  name="userGroup" id="userGroup">
                    <option selected value="">{{trans('common.pleaseSelect')}}</option>
                    
                    
                 @if (count($userGroup) > 0)
                 @foreach ($userGroup as $val)
                 
                  
                    
                    <option value="{{$val->id}}">{{$val->name}}</option>
                    
                    
                  @endforeach
                  @endif
                  
                  
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label"> {{trans('common.status')}} <span class="symbol required">*</span> </label>
                  <select class="form-control"  name="status" id="status">
                    <option selected value="">{{trans('common.pleaseSelect')}}</option>
                    
                    
                 @if (count($userStatus) > 0)
                 @foreach ($userStatus as $val)
                  
                    
                    <option value="{{$val['id']}}">{{$val['name']}}</option>
                    
                    
                  @endforeach
                  @endif
                  
                   
                  
                  
                  </select>
                </div>
               
                
                <div class="form-group col-md-6">
                  <div class="form-group_error">
                    <p><img id="preview" src="{{url('uploads/no_avatar.jpg')}}" style="max-height: 118px;"> </p>
                  </div>
                </div>
                
                <div class="form-group col-md-6">
                  <label for="filetag" class="btn btn-success" role="button"> {{trans('common.selectAvtar')}}</label>
                  <input  type="file" id="filetag" class="form-control"  name="avatar_logo" style="height:100%" accept="image/*" 	>
                </div>
                
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div> <span class="symbol required"></span>{{trans('common.requiredFields')}}
                  <hr>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-7">
                <p> </p>
              </div>
              <div class="col-md-2"> <a href="{{url('cms/user')}}" class="btn btn-light-grey btn-block" > {{trans('common.back')}} <i class="fa fa-arrow-circle-left"></i></a> </div>
              <div class="col-md-3">
                <button class="btn btn-success btn-block" type="submit"> {{trans('common.save')}} <i class="fa fa-arrow-circle-right"></i> </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- end panel --> 
    </div>
    <!-- end .col-md-6 --> 
    
  </div>
  <!-- end col-md-12 --> 
</div>

<!-- end #content --> 
@endsection 

@push('css')
@endpush

@push('js') 
<script type="text/javascript" src="{{url('assets/vendors/jquery-validation/js/jquery.validate.min.js')}}"></script> 
@endpush


@push('script') 
<script type="text/javascript">


var fileTag = document.getElementById("filetag"),
    preview = document.getElementById("preview");
    
fileTag.addEventListener("change", function() {
  changeImage(this);
});

function changeImage(input) {
  var reader;

  if (input.files && input.files[0]) {
    reader = new FileReader();

    reader.onload = function(e) {
      preview.setAttribute('src', e.target.result);
    }
     $('#delete_item_1').show();
    reader.readAsDataURL(input.files[0]);
  }
}


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
		                name: {
		                    minlength: 2,
		                    required: true
		                },
		                email: {
		                    required: true,
		                    email: true
		                },
		                password: {
		                    minlength: 6,
		                    required: true
		                },
		                gender: {
		                    required: true
		                },
		                userGroup: {
		                    required: true,
		                },
		                status: {
		                    required: true
		                }
		            },
		            messages: {
		                firstname: "Please specify your first name",
		                lastname: "Please specify your last name",
		                email: {
		                    required: "We need your email address to contact you",
		                    email: "Your email address must be in the format of name@domain.com"
		                },
		                gender: "Controleer een gender!"
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
@endpush