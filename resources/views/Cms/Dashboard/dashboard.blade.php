@extends('Cms.layouts.app')
@section('content') 

<!-- main content -->

<div id="content">
  <div id="sortable-panel" class="">
    <div id="titr-content" class="col-md-12">
      <h2>{{$dashboard}}</h2>
      <h5></h5>
    </div>
    <div  class="row">
      <div class="col-md-12 ">  
      
      <!-- pie chart -->
										<?php
										//echo '<pre/>'; print_r($results[0]->users); die;
										?>
												<div class="col-md-3 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin purple">{{$results[0]->users}}</span>																
														<span class="text-center">
															<i class="fa fa-group text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.users')}}</span>
													</div>
												</div>
												<div class="col-md-3 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin green">{{$results[1]->users}}</span>																
														<span class="text-center">
															<i class="fa fa-user text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.clients')}}</span>
													</div>
												</div>			
												<div class="col-md-3 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin orange">{{$results[2]->users}}</span>																
														<span class="text-center">
															<i class="glyphicon glyphicon-film text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.company')}}</span>
													</div>
												</div>			
												<div class="col-sm-3 no-padd-left-right hidden-sm">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin darkblue">{{$results[3]->users}}</span>																
														<span class="text-center">
															<i class="glyphicon glyphicon-user text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.relationManager')}}</span>
													</div>
												</div>																																					
												
                                                
                                                <div class="col-md-4 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin darkblue">{{$results[4]->users}}</span>																
														<span class="text-center">
															<i class="fa fa-user text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.ourPeople')}}</span>
													</div>
												</div>
												<div class="col-md-4 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin purple">{{$results[5]->users}}</span>																
														<span class="text-center">
															<i class="glyphicon glyphicon-envelope text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.news')}}</span>
													</div>
												</div>			
												<div class="col-md-4 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin green">{{$results[6]->users}}</span>																
														<span class="text-center">
															<i class="fa fa-list text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.event')}}</span>
													</div>
												</div>	
										    
                                            <!-- <div class="col-md-3 no-padd-left-right">
													<div class="well well-sm text-center">
														<span class="h1 shadow-block no-margin orange">{{$results[7]->users}}</span>																
														<span class="text-center">
															<i class="fa fa-user text-muted"></i>
														</span>
														<span class="text-muted">{{trans('common.familiestatuut')}}</span>
													</div>
												</div> -->
											<!-- ./pie chart -->
      </div>
      <div class="col-md-12 ">  
		<div  class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">
					<i class="fa fa-bar-chart-o"></i>
					Klanten
					<div class="bars pull-right" style="margin-top: -5px;">
						<span>

				<div class="form-group" style="float:left;">
                  <form id="year_form">
                  <select class="form-control" name="year" id="year">
                    <option selected value="">{{trans('common.pleaseSelect')}}</option>
                @if (count($getYear) > 1)
                 @foreach ($getYear as $val)
                   <?php $sel = '';
                   		if($year == $val['id']){
                   	     $sel = 'selected';
                   	} ?>
                    <option  <?php echo $sel; ?> value="{{$val['id']}}">{{$val['name']}}</option>
                  @endforeach
                  @endif
                  </select>
                 </form> 
                </div>
						</span>
						<a href="#"><i class="maximum fa fa-expand" data-toggle="tooltip" data-placement="bottom" title="Maximize"></i></a>
						<a href="#"><i class="minimize fa fa-chevron-down" data-toggle="tooltip" data-placement="bottom" title="Collapse"></i></a>
						
					</div>
				</div>
			</div>
			<div class="panel-body">

				<div id="barChart-1" class="chart-placeholder"></div>

			</div>

		</div>
      </div>

 <!-- Quick Scan chart --> 
 
 <div class="col-md-12 ">  
		<div  class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">
					<i class="fa fa-bar-chart-o"></i>
					Quick Scans
					<div class="bars pull-right" style="margin-top: -5px;">
						<span>

				<div class="form-group" style="float:left;">
                  <form id="qs_form">
                  <select class="form-control" name="qs" id="qs_year">
                    <option selected value="">{{trans('common.pleaseSelect')}}</option>
                @if (count($getYear) > 1)
                 @foreach ($getYear as $val)
                   <?php $sel = '';
                   		if($qs == $val['id']){
                   	     $sel = 'selected';
                   	} ?>
                    <option  <?php echo $sel; ?> value="{{$val['id']}}">{{$val['name']}}</option>
                  @endforeach
                  @endif
                  </select>
                 </form> 
                </div>
						</span>
						<a href="#"><i class="maximum fa fa-expand" data-toggle="tooltip" data-placement="bottom" title="Maximize"></i></a>
						<a href="#"><i class="minimize fa fa-chevron-down" data-toggle="tooltip" data-placement="bottom" title="Collapse"></i></a>
						
					</div>
				</div>
			</div>
			<div class="panel-body">

				<div id="barChart-2" class="chart-placeholder"></div>

			</div>

		</div>
      </div>
   <!-- Quick scan chart End -->       


    </div>
    
    <!-- end .col-md-12 --> 
    
  </div>
  <!-- end col-md-12 --> 
</div>

<!-- end #content --> 
@endsection 

@push('css')
<link rel="stylesheet" href="{{ url('assets/vendors/morrisjs/css/morris.css') }}">
@endpush



@push('js') 
<script type="text/javascript" src="{{ url('assets/vendors/jquery-sparkline/js/jquery.sparkline.min.js') }}"></script> 
<script type="text/javascript" src="{{ url('assets/vendors/morrisjs/js/raphael.min.js') }}"></script> 
<script type="text/javascript" src="{{ url('assets/vendors/morrisjs/js/morris.min.js') }} "></script> 
<script type="text/javascript" src="{{ url('assets/vendors/easy-pie-chart/js/jquery.easypiechart.min.js') }} "></script>
<script type="text/javascript" src="{{ url('assets/vendors/momentjs/js/moment.min.js') }} "></script>
@endpush


@push('script') 
<script type="text/javascript">
$(document).ready(function() {

$('#year').change(function() {
 
 $('#year_form').submit();
 
});

$('#qs_year').change(function() {
 
 $('#qs_form').submit();
 
});

draw_graph(<?php echo $graph; ?>);

draw_qs_graph(<?php echo $graphqs; ?>)		
});
function draw_graph(value)
{  
    
	Morris.Bar({
				element: 'barChart-1',
				data: value,
				xkey: 'x',
				ykeys: ['y'],
				labels: ['Klanten'],
				barColors: function (row, series, type) {
					if (type === 'bar') {
						var red = Math.ceil(0 * row.y / this.ymax);
						return 'rgb(' + red + ',191,255)';
					}
					else {
						return '#000';
					}
				}
			});
}

function draw_qs_graph(value)
{
  
    
	Morris.Bar({
				element: 'barChart-2',
				data: value,
				xkey: 'x',
				ykeys: ['y'],
				labels: ['Scan'],
				barColors: function (row, series, type) {
					if (type === 'bar') {
						var red = Math.ceil(0 * row.y / this.ymax);
						return 'rgb(' + red + ',191,255)';
					}
					else {
						return '#000';
					}
				}
			});
}
</script> 

		
@endpush