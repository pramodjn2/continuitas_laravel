@extends('Cms.layouts.app')
@section('content') 

 
<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$title}} {{ucwords($results->nm_bd)}}</h2>
      
       <h5>&nbsp;</h5>
          <!-- <div class="actions">
           <a href="{{url('cms/editProfile')}}" class="btn btn-success ">Edit Profile</a>
          
            
          </div> -->
    </div>
    <div class="col-md-12 ">
      <div  class="panel panel-default">
        
        <div class="panel-body">
          <hr>
         
            <div class="row">
               <div class="col-md-6">
   
                        <table class="table table-bordered table-condensed table-hover">
                                            <thead>
                                                <tr>
                                                    <th colspan="3">{{trans('common.profileContactInformation')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            <tr>
                                                    <td>{{trans('common.nameCompany')}}</td>
                                                    <td>{{ucwords($results->nm_bd)}}</td>
                                                </tr> 
                                                
                                             <tr>
                                                    <td>{{trans('common.companyNumber')}}</td>
                                                    <td>{{$results->relnr_bd}}</td>
                                                </tr> 
                                                
                                                <tr>
                                                    <td>{{trans('common.kindOfCompany')}}</td>
                                                    <td>{{ucwords($results->ondvorm_bd)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.relationName')}}</td>
                                                    <td>{{$results->nm_rm}}</td>
                                                </tr>
                                               
                                                <tr>
                                                    <td>{{trans('common.status')}}</td>
                                                    <td>{{$results->status}}</td>
                                                </tr>
                                               
                                                                                                
                                            </tbody>
                                        </table>
					
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


@endpush

@push('js') 
<script type="text/javascript" src="{{url('assets/vendors/jquery-validation/js/jquery.validate.min.js')}}"></script> 
@endpush

@push('style')
 
@endpush

@push('script') 
 <script type="text/javascript">
			jQuery(function($) {
	//jquery tabs
				
					
			});
			
		</script>
@endpush
