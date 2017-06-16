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

class PushNotificationController extends Controller
{
   
    public $title = '';
	public $result = '';
	public $createdAt = '';
	public $className = '';
	public $methodName = '';
	public $permision = '';
	public function __construct(Route $route) 
    {
	  $this->createdAt = date("Y-m-d H:i:s");
      $CommonController = new CommonController; 
	  $url = $route->uri();
	  $url = @explode('/',$url);
	  $this->className = @$url['1'];
	  $this->methodName = @$url['2'];
	  $this->permision = $CommonController->userPermission($this->className);
      $this->title = trans('common.pushNotification');
    }
	
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
	
		 $CommonController = new CommonController;
		 $access = $CommonController->checkFunctionPermission($this->permision,$this->className,'read');
		 if(empty($access)){
		 	Session::flash('error', trans('common.permissionNot')); 
	         return Redirect::to('cms/dashboard');
		 }
		 
		
		$data['className'] = $this->className;
        $data['title'] = $this->title;
		$data['dashboard'] = trans('common.pushNotification') ;
		
		$data['results'] = '';
		return view('Cms/PushNotification/pushnotification',$data); 
    }
	
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $post = $request->all();
	  if($post){
      
	   $rules = array('title' => 'required|max:128', 
	   				  'msg' =>   'required|max:250');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/pushnotification')->withErrors($validator)->withInput();
		}
		
		 $responce = $this->sendMessage($post['title'],$post['msg']);
		 if(!empty($responce)){
			$responce =  json_decode($responce);
			$id = $responce->id; 
			$recipients = $responce->recipients; 
			Session::flash('success', trans('common.sendSucessfully')); 
            return Redirect::to('cms/pushnotification');
		}
	      Session::flash('error', trans('common.pleaseTryAgain')); 
        return Redirect::to('cms/pushnotification');				  
		
		}
    }


	public function sendMessage($title,$contents){
		$content  = array("en" => $contents);
		$subtitle = array("en" => $title);
		
		$fields = array(
			'app_id' => "d65e1c0b-1b9e-4e6d-b40b-ec892c61c4bd",
			'included_segments' => array('All'),
      		'data' => array("foo" => "bar"),
			'contents' => $content,
			'subtitle' => $subtitle
		);
		
		$fields = json_encode($fields);
    //print("\nJSON sent:\n");
   // print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic ODhjZWMyMmYtZGY5YS00MjZhLWJiZmMtOTI5MzExYzU5Mjgx'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}



   
}
?>    