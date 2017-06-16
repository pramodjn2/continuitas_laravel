@extends('Cms.layouts.app')
@section('content') 


<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$title}}</h2>
    </div>
    <div class="col-md-12 ">
      <div  class="panel panel-default">
        
        <div class="panel-body">
          <hr>
         
            <div class="row">
              <div class="col-md-12">
                <div class="errorHandler alert alert-danger no-display"> <i class="fa fa-times-sign"></i> You have some form errors. Please check below. </div>
                <div class="successHandler alert alert-success no-display"> <i class="fa fa-ok"></i> Your form validation is successful! </div>
              </div>
              <div class="col-md-6">
				<div id="tabs">
					 @include('Cms/Profile/navigation'); 
					<div id="tabs-11">
						<p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Duis orci. Aliquam sodales tortor vitae ipsum. Ut et mauris vel pede varius sollicitudin.</p>
					</div>
					<div id="tabs-22">
						<p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla..</p>
					</div>
					<div id="tabs-33">
						<p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Praesent eu risus hendrerit ligula tempus pretium.</p>
					</div>
				</div>
              </div>
              
            </div>
            
            
         
        </div>
      </div>
      
    </div>
    <!-- end .col-md-6 --> 
    
  </div>
  <!-- end col-md-12 --> 
</div>

<!-- end #content --> 
@endsection 

@push('css')

<link rel="stylesheet" href="{{url('assets/vendors/jquery-ui-bootstrap/css/jquery-ui.custom.min.css')}}">
@endpush

@push('js') 
<script type="text/javascript" src="{{url('assets/vendors/jquery-validation/js/jquery.validate.min.js')}}"></script> 
@endpush

@push('style')
<style>
	 #cke_2_toolbox{
		display: none;
	 }
	 #image,#tekstVierkant,#fotoBreed{display: none;}
</style>
@endpush

@push('script') 
 <script type="text/javascript">
			jQuery(function($) {
	//jquery tabs
				$( "#tabs" ).tabs();
					
			});
			
		</script>
@endpush