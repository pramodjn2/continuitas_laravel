@extends('Cms.layouts.app')
@section('content') 

 
<!-- main content -->
<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$title}} {{ucwords($results->fullName)}}</h2>
      
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
                                                    <td>{{ucwords($results->fullName)}}</td>
                                                </tr> 
                                                
                                             <tr>
                                                    <td>{{trans('common.role')}}</td>
                                                    <td>{{$results->role}}</td>
                                                </tr> 
                                                
                                                <tr>
                                                    <td>{{trans('common.gender')}}</td>
                                                    <td>{{ucwords($results->gender)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.email')}}</td>
                                                    <td>{{$results->email}}</td>
                                                </tr>
                                                                                              
                                               
                                               
                                                <tr>
                                                    <td>{{trans('common.createdDate')}}</td>
                                                    <td>{{$results->created_at}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>{{trans('common.updatedDate')}}</td>
                                                    <td>{{$results->updated_at}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{trans('common.status')}}</td>
                                                    <td>{{$results->status}}</td>
                                                </tr>
                                               
                                                                                                
                                            </tbody>
                                        </table>
					
              </div>
              <div class="col-md-6">
				
						
                        
                      
                        <div class="center">
                                            <h4>{{ucwords($results->fullName)}}</h4>
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="user-image"id="userImage">
                                                    <div class="fileupload-new thumbnail">
                                                        <?php	
														
/* IMAGE CHECK IF EXITE OR NOT*/
  function imageCheck($image, $url){
		 $filename= $url.$image;
		if(!empty($image)){
			if(file_exists(config('app.uploads_path').$image)){
			  return $image;
			}else{
			  return 'no_avatar.jpg';
			}
		}else{
		   return 'no_avatar.jpg';
		}
	}
		$header_logo =  $results->avatar;					
		$url = url('uploads/');
		$header_logo = imageCheck($header_logo,$url);
													
                                                        ?>
                                                        <img src="{{url('uploads/'.$header_logo)}}" alt="" style="width:200px;">
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <hr>
                                            
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
