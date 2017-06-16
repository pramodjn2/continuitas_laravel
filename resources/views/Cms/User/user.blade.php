@extends('Cms.layouts.app')
@section('content') 

<!-- main content -->

    <div id="content"> 
      <div id="sortable-panel" class="">
        <div id="titr-content" class="col-md-12">
          <h2>{{$dashboard}}</h2>
          <h5>&nbsp;</h5>
          <div class="actions">
          <a href="#" class="btn btn-danger" id="delete" onclick="return confirm('{{trans('common.confirmDelete')}}')">{{trans('common.delete')}}</a>
           <a href="{{url('cms/user/add')}}" class="btn btn-success ">{{trans('common.addnew')}}</a>
           <a href="{{url('cms/user/export')}}" class="btn btn-primary ">{{trans('common.export')}}</a>
           <a href="{{url('cms/user/import')}}" class="btn btn-primary ">{{trans('common.import')}}</a>
            
          </div>
        </div>
        <div class="col-md-12 ">
          <div  class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-title"> <i class="fa fa-table"></i> {{$title}} {{trans('common.tabel')}}

                <div class="bars pull-right">
                  <a href="#"><i class="maximum fa fa-expand" data-toggle="tooltip" data-placement="bottom" title="Maximize"></i></a> <a href="#"><i class="minimize fa fa-chevron-down" data-toggle="tooltip" data-placement="bottom" title="Collapse"></i></a>  
                </div>
              </div>
            </div>
            <div class="panel-body">
              <table id="example1" class="table table-striped table-bordered width-100 cellspace-0" >
                <thead>
                  <tr>
                    <th><input tabindex="1" type="checkbox" name="checkAll" id="checkAll"></th>
		                <th>{{trans('common.avtar')}}</th>
                    <th>{{trans('common.name')}}</th>
                    <th>{{trans('common.email')}}</th>
                    <th>{{trans('common.gender')}}</th>
                    <th>{{trans('common.role')}}</th>
                    <th>{{trans('common.status')}}</th>
                    <!-- <th>{{trans('common.createdDate')}}</th> -->
                    <th>{{trans('common.action')}}</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($results as $val)
                <?php
              		$header_logo = 'no_avatar.jpg';
              		if($val->avatar != '') {
              		$header_logo =  $val->avatar;
              		$url = url('uploads/');
              		$header_logo = imageCheck($header_logo,$url);
              		}
              		?>
                <tr>
                  <td><input tabindex="1" type="checkbox" name="user" id="{{$val->id}}" value="{{$val->id}}"></td>
                  <td><img src="{{ url('uploads/'.$header_logo) }} " alt="{{$val->avatar}}" height="50" width="50"></td>
                  <td>{{ $val->fullName }}</td>
                  <td>{{ $val->email }}</td>
                  <td>{{ ucwords($val->gender) }}</td>
                  <td>{{ $val->role }}</td>
                  <td>{{ $val->status }}</td>
                  <!-- <td>{{ $val->created_at }}</td> -->
                  <td class="center"><div class="visible-md visible-lg visible-sm visible-xs"> 
                  <a href="user/edit/{{$val->id}}" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"> <i class="fa fa-edit"></i></a> 
                  <a href="user/view/{{$val->id}}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Share"> <i class="fa fa-share"></i></a>
                  <a onclick="return confirm('{{trans('common.confirmDelete')}}')" href="user/delete/{{$val->id}}" class="btn btn-xs btn-bricky tooltips" data-placement="top" data-original-title="Remove"> <i class="fa fa-times fa fa-white"></i></a> </div></td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- end panel --> 
        </div>
        <!-- end .col-md-12 --> 
        
        <!-- end .col-md-12 --> 
        
      </div>
      <!-- end col-md-12 --> 
    </div>
  
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
 ?>
 
<!-- end #content --> 
@endsection 

@push('css')
<link rel="stylesheet" href="{{url('assets/vendors/jquery-datatables/css/dataTables.bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/vendors/jquery-datatables/css/dataTables.responsive.min.css')}}">
<link rel="stylesheet" href="{{url('assets/vendors/jquery-datatables/css/dataTables.tableTools.min.css')}}">
<link rel="stylesheet" href="{{url('assets/vendors/jquery-datatables/css/dataTables.colVis.min.css')}}">
@endpush

@push('js') 
<script type="text/javascript" src="{{url('assets/vendors/jquery-datatables/js/jquery.dataTables.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-datatables/js/dataTables.bootstrap.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-datatables/js/dataTables.responsive.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-datatables/js/dataTables.tableTools.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/vendors/jquery-datatables/js/dataTables.colVis.min.js')}}"></script> 
<script type="text/javascript" src="{{url('assets/js/custom_datatable.js')}}"></script> 
@endpush


@push('script') 
<script type="text/javascript">
$(document).ready(function() {
data_table('example1');


$("#checkAll").click(function(){
        var x = document.getElementsByName("user");
    var i;
    for (i = 0; i < x.length; i++) {
          if (x[i].type == "checkbox" && x[i].checked == false) {
              x[i].checked = true;
          }else{ x[i].checked = false;  }
      }
    });
 
$("#delete").click(function(){
    var x = document.getElementsByName("user");
    var i; 
    var ids = []; 
    for (i = 0; i < x.length; i++) {
        if (x[i].type == "checkbox" && x[i].checked == true) {
            
           ids.push(x[i].value); 

        }
    }  
     $.ajax({
                url:'user/deleteM',
                dataType:'json',
                type:'get',
                data: {
                  ids: ids
                },
                success:  function (response) {
                    
                   
                   location.reload();
          
          
                },              
        });
  });

});


</script> 
@endpush