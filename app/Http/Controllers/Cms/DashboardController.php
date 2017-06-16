<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use Validator;
use Session;
use Redirect;
use Hash;
use Auth;
use Illuminate\Routing\Route;
use App\Http\Controllers\CommonController;

class DashboardController extends Controller
{
    public $title = '';
	public $result = '';
	public $createdAt = '';
	public $className = '';
	public $methodName = '';
	public function __construct(Route $route) 
    { 
		$this->createdAt = date("Y-m-d H:i:s");
     
	  $CommonController = new CommonController; 
	  $url = $route->uri();
	  $url = @explode('/',$url);
	  $this->className = @$url['1'];
	  $this->methodName = @$url['2'];
	  $this->permision = $CommonController->userPermission($this->className);
	 $this->title = 'Trusted Accountant';
		
    }
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
    $CommonController = new CommonController;
		config('app.uploads_path');
		//file_exists($filename)
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
		$data['dashboard'] = trans('common.dashboard');
		
		$data['getYear'] = $CommonController->getYear();
		//echo date("Y");
		// kalnt
		$year = @$_GET['year'] ? @$_GET['year'] : date('Y');

		$data['year'] = $year;
		$data['graph'] = $this->graph($year);
		//new graph
		$yearqs = @$_GET['qs'] ? @$_GET['qs'] : date('Y');

		$data['qs'] = $yearqs;
		$data['graphqs'] = $this->graphqs($yearqs);

		$data['results'] = $this->show();

		//
		//dd($data['results']);
		return view('Cms/Dashboard/dashboard',$data); 
    }
	
	
	function show(){
	  
	  	   $sql = DB::select( DB::raw("SELECT count(id) as users, IF(id>0, 'users', 'users') as user  FROM users where status = 'actief' union 
            						(SELECT count(id_kl) as client, IF(id_kl>0, 'client', 'client') as user FROM klant where status = 'actief')  union 
            						(SELECT count(id_bd) as company, IF(id_bd>0, 'company', 'company') as user FROM bedrijf where status = 'actief') union 
            						(SELECT count(id_rm) as relatiemanager,IF(id_rm>0, 'relatiemanager', 'relatiemanager') as user FROM relatiemanager where status_rm = 'actief') union 
            						(SELECT count(id_mens) as our_people,IF(id_mens>0, 'our_people', 'our_people') as user FROM onze_mensen where status = 'actief') union 
            						(SELECT count(id_nw) as news,IF(id_nw>0, 'news', 'news') as user FROM nieuws ) union 
            						(SELECT count(id_ev) as event,IF(id_ev>0, 'event', 'event') as user FROM event ) union 
            						(SELECT count(id) as familiestatuut,IF(id>0, 'familiestatuut', 'familiestatuut') as user FROM familiestatuut) ") );
		   
		   return $sql;
		   
	}

   public function graph($year)
   {   
   		$total_ct = array();
   		$getCount = DB::select( DB::raw("SELECT COUNT(*) as count, MONTH(`created_at`) as month FROM klant WHERE YEAR(`created_at`) = ".$year." AND status = 'Actief' GROUP BY MONTH(`created_at`)"));
   		$mn = array('0'=>'Jan','1'=> 'Feb','2'=> 'Mrt','3'=> 'Apr','4'=> 'Mei','5'=> 'Jun','6'=> 'Jul','7'=> 'Aug','8'=> 'Sep','9'=> 'Okt','10'=> 'Nov','11'=> 'Dec');
   		$arr = array(0,0,0,0,0,0,0,0,0,0,0,0);

   		$i = 0;
   		foreach ($getCount as $key => $value) {
   			
   			$arr[$value->month-1] = $value->count;
   			
   		}
   		foreach ($mn as $key => $value) {
   			$total_ct[] = array('x'=> $value, 'y'=>$arr[$key]);
   		}
   		         
                 return json_encode($total_ct);               
   } 

   public function graphqs($yearqs)
   {   
   		$total_ct = array();
   		$getCount = DB::select( DB::raw("SELECT COUNT(*) as count, MONTH(`date`) as month FROM qs01 WHERE YEAR(`date`) = ".$yearqs." GROUP BY MONTH(`date`)"));
   		$mn = array('0'=>'Jan','1'=> 'Feb','2'=> 'Mrt','3'=> 'Apr','4'=> 'Mei','5'=> 'Jun','6'=> 'Jul','7'=> 'Aug','8'=> 'Sep','9'=> 'Okt','10'=> 'Nov','11'=> 'Dec');
   		$arr = array(0,0,0,0,0,0,0,0,0,0,0,0);

   		$i = 0;
   		foreach ($getCount as $key => $value) {
   			
   			$arr[$value->month-1] = $value->count;
   			
   		}
   		foreach ($mn as $key => $value) {
   			$total_ct[] = array('x'=> $value, 'y'=>$arr[$key]);
   		}
   		         
                 return json_encode($total_ct);               
   }
	
}
