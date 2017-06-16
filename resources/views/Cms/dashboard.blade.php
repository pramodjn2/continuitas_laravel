@extends('Cms.layouts.app')
@section('content') 

<!-- start: PAGE --> 
<!-- main content  -->
<div id="main" class="main">
  <div class="row"> 
    <!-- breadcrumb section -->
    <div class="ribbon">
      <ul class="breadcrumb">
        <li> <i class="fa fa-home"></i> <a href="#">Home</a> </li>
        <li> <a href="#">Library</a> </li>
        <li> <a href="#">Data</a> </li>
      </ul>
    </div>
    
    <!-- main content -->
    <div id="content">
      <div id="sortable-panel" class="">
        <div id="titr-content" class="col-md-12">
          <h2>Simple Tables</h2>
          <h5>static tables samples...</h5>
          <div class="actions">
            <button class="btn btn-success ">Add new</button>
            <button class="btn btn-default ">Options</button>
          </div>
        </div>
        <div class="col-md-12 ">
          <div  class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-title"> <i class="fa fa-table"></i> Responsive Table
                <div class="bars pull-right"> <a href="#"><i class="maximum fa fa-expand" data-toggle="tooltip" data-placement="bottom" title="Maximize"></i></a> <a href="#"><i class="minimize fa fa-chevron-down" data-toggle="tooltip" data-placement="bottom" title="Collapse"></i></a> <a href="#"><i data-target="#panel2" data-dismiss="alert" data-toggle="tooltip" data-placement="bottom" title="Close" class="fa fa-times"></i></a> </div>
              </div>
            </div>
            <div class="panel-body no-padding"> 
              <!-- responsive table -->
              
              <div class="res-table">
                <table class="table table-striped table-hover table-fixed ellipsis-table" >
                  <thead>
                    <tr>
                      <th class="width-5 center"> <div class="checkbox">
                          <input id="checkbox10" type="checkbox" >
                          <label for="checkbox10"> </label>
                        </div>
                      </th>
                      <th class="width-20">Sender</th>
                      <th class="width-40">Subject</th>
                      <th class="width-15">Date</th>
                      <th class="width-20">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <th class="center"> <div class="checkbox">
                          <input id="checkbox20" type="checkbox" >
                          <label for="checkbox20"> </label>
                        </div>
                      </th>
                      <td data-title="Sender">Lucas Kriebel (via Twitter)</td>
                      <td data-title="Subject">Lucas Kriebel (@LucasKriebel) has sent you a direct message on Twitter!</td>
                      <td data-title="Date">11:49 AM</td>
                      <td data-title="Action" class="center"><div class="visible-md visible-lg hidden-sm hidden-xs"> <a href="#" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a> <a href="#" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="Share"><i class="fa fa-share"></i></a> <a href="#" class="btn btn-xs btn-bricky tooltips" data-placement="top" data-original-title="Remove"><i class="fa fa-times fa fa-white"></i></a> </div>
                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                          <div class="btn-group"> <a class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" href="#"> <i class="fa fa-cog"></i> <span class="caret"></span> </a>
                            <ul role="menu" class="dropdown-menu pull-right">
                              <li role="presentation"> <a role="menuitem" tabindex="-1" href="#"> <i class="fa fa-edit"></i> Edit </a> </li>
                              <li role="presentation"> <a role="menuitem" tabindex="-1" href="#"> <i class="fa fa-share"></i> Share </a> </li>
                              <li role="presentation"> <a role="menuitem" tabindex="-1" href="#"> <i class="fa fa-times"></i> Remove </a> </li>
                            </ul>
                          </div>
                        </div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /end .res-table --> 
            </div>
          </div>
          <!-- end panel --> 
        </div>
        <!-- end .col-md-12 --> 
        
      </div>
      <!-- end col-md-12 --> 
    </div>
    <!-- end #content --> 
  </div>
  <!-- end .row --> 
</div>
<!-- ./end #main  --> 

<!-- end: PAGE --> 
@endsection 
@push('js') 
<!--<script src="{{url('assets/js/index.js')}}"></script>
-->@endpush