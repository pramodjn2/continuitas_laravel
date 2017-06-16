<?php /*$id = @session('id');
if(empty($id)){
header('Location: /cms/');
exit;		
}*/
?>
<!DOCTYPE html>
<html>
<head>
<title>
<?=@$title ? @$title : ''?> {{config('app.site_name')}}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{url('assets/vendors/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/vendors/font-awesome/css/font-awesome.min.css')}}">

<!-- Related css to this page -->
@stack('css')
<!-- Yeptemplate css --><!-- Please use *.min.css in production -->
<link rel="stylesheet" href="{{url('assets/css/yep-style.css')}}">
<link rel="stylesheet" href="{{url('assets/css/yep-vendors.css')}}">

<!-- favicon -->
<link rel="shortcut icon" href="{{url('assets/img/favicon/favicon.ico')}}" type="image/x-icon">
<link rel="icon" href="{{url('assets/img/favicon/favicon.ico')}}" type="image/x-icon">
</head><body id="mainbody" >
<div id="container" class="container-fluid skin-3"> 
  <!-- sidebar menu --> 
  @include('Cms/layouts/header') 
  <!-- /end #sidebar --> 
  
  <!-- sidebar menu --> 
  @include('Cms/layouts/leftbar') 
  <!-- /end #sidebar --> 
  
  <!-- main content  -->
  
  <div id="main" class="main">
  <div class="row"> 
    <!-- breadcrumb section -->
    <div class="ribbon">
      <ul class="breadcrumb">
        <li> <i class="fa fa-home"></i> <a href="{{url('cms/dashboard')}}">Home</a> </li>
        <li> <a href="{{url('cms/'.$className)}}">{{ucwords($className)}}</a> </li>
        
      </ul>
    </div>
    
    
<div class="col-md-12">
  <div class="form-group"> @if (Session::has('success'))
  
    <div class="alert alert-success">{!! Session::get('success') !!}</div>
    @endif
    
    @if(Session::has('error'))
    <div class="alert alert-danger"> {!! Session::get('error') !!} </div>
    @endif 
    
    @if ($errors->has())
    <div class="alert alert-danger"> @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach </div>
    @endif </div>
</div>

  	@yield('content') 
    <!-- end .row --> 
  </div>
    <!-- end #content --> 
 </div>
<!-- ./end #main  --> 

  
  <!-- footer --> 
  @include('Cms/layouts/footer') 
  <!-- /footer --> 

<!-- end #container --> 

<!-- Related JavaScript Library to This Pagee --> 
@stack('js') 


<!-- Plugins Script --> 
@stack('style')

<!-- Plugins Script --> 
@stack('script')
</body>
</html>
