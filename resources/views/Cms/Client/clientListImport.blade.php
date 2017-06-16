@extends('Cms.layouts.app')
@section('content') 

<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$dashboard}}</h2>
      
    </div>
    <div class="col-md-12 ">
      <div  class="panel panel-default">

        <div class="panel-body">
          <hr>
          <form action="{{url('cms/client/importCSV')}}" enctype="multipart/form-data" method="POST" >
            {{ csrf_field() }}
            <div class="row">
             
              </div>
               <div class="row">
      <div class="col-md-12">
        
          <div class="col-md-8">
            <div class="form-group">
              <label class="col-md-4">{{trans('common.selectCSVFileToImport')}}</label>
              <div class="col-md-8">  
                <input type="file" id="userfile" name="userfile" class="form-control input-large" style="height:100%" >
                
                <span class="help-block">
                  {{trans('common.pleaseUploadOnlyCSVFile')}}
                </span>                 
              </div>                
            </div>
          </div>
          <div class="col-md-4">
            
          </div>
            
      </div>                    
    </div>
             
            <div class="row">
              <div class="col-md-6">
                <div> <div class="alert alert-info">
                  <strong>{{trans('common.info')}}</strong> {{trans('common.toDownloadClick')}} 
                  <b><a  href="{{url('cms/client/sample')}}">{{trans('common.here')}}</a></b>
                </div>
                  <hr>
                </div>    
              </div>
            </div>
            <div class="row">
              <div class="col-md-7">
                
              </div>
              <div class="col-md-2"> <a href="{{url('cms/client')}}" class="btn btn-light-grey btn-block" > {{trans('common.back')}} <i class="fa fa-arrow-circle-left"></i></a> </div>
              <div class="col-md-3">
                <button class="btn btn-success btn-block" type="submit" > {{trans('common.upload')}} <i class="fa fa-arrow-circle-right"></i> </button>
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
$(document).ready(function() {
	
		        
		   
				
});
</script> 
@endpush