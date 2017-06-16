@extends('Cms.layouts.app')
@section('content') 

 
<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$title}} {{ucwords($results->naam_kl)}}</h2>
      
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
                                                    <td>{{trans('common.name')}}</td>
                                                    <td>{{ucwords($results->naam_kl)}}</td>
                                                </tr> 
                                                
                                             <tr>
                                                    <td>{{trans('common.email')}}</td>
                                                    <td>{{ucfirst($results->email_kl)}}</td>
                                                </tr> 
                                                
                                                <tr>
                                                    <td>{{trans('common.initial')}}</td>
                                                    <td>{{ucwords($results->vl_kl)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.gender')}}</td>
                                                    <td>{{ucfirst($results->sexe_kl)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.telephoneNumber')}}</td>
                                                    <td>{{ucfirst($results->tel_kl)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.company')}}</td>
                                                    <td>{{ucfirst($results->company)}}</td>
                                                </tr>                                              
                                                <tr>
                                                    <td>{{trans('common.premium')}}</td>
                                                    <td>{{ucfirst($results->premium_kl)}}</td>
                                                </tr> 
                                               
                                                <tr>
                                                    <td>{{trans('common.createdDate')}}</td>
                                                    <td>{{ucfirst($results->created_at)}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>{{trans('common.updatedDate')}}</td>
                                                    <td>{{ucfirst($results->updated_at)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.status')}}</td>
                                                    <td>{{ucfirst($results->status)}}</td>
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
