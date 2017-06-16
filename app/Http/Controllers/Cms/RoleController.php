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

class RoleController extends Controller
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
	  
	   $this->title = trans('common.roletitle');
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
		$data['dashboard'] = trans('common.roletitle');
		
		$data['results'] = $this->show();
		return view('Cms/Role/role',$data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
		$CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
		
       
		$data['userStatus'] = $CommonController->userStatus();
 
        $data['className'] = $this->className;
	     $data['title'] = $this->title;
        return view('Cms/Role/roleAdd',$data); 
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
	  
	   $blanckArray = array('name'=>'',
						'label'=>'',
						'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('name' => 'required|max:40', 
	   				  'label' =>   'required|max:40',    
                      'status' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/role/add')->withErrors($validator)->withInput();
		}
		
		    
			
	
	    $data = array('name' => $post['name'],
			                  'label' => $post['label'],
							   'status' => strtolower($post['status']),
							   'created_at' => $this->createdAt
							  );
							  
			 		  
						
		$insertId =  DB::table('roles')->insertGetId($data);
		
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/role')->withInput();
			
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    	if(Session::has('role_id')) 
		{
			$role_id =  Session::get('role_id'); 
		}
         $sql = DB::table('roles as r')
		 ->select('r.id','r.name', 'r.label','r.status','r.created_at')
		 ->where('r.id','>=',$role_id)
		->get();

		return  $sql;
		 return  json_encode($sql);
    }

 
public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendUserNumber')); 
		 return Redirect::to('cms/role/add')->withInput();
		}
       
	   $where = array('r.id' => $id);
	   $sql = DB::table('roles as r')
		->select('r.id','r.name','r.label','r.status','r.created_at')
		 ->where($where)
		 ->groupBy('r.id')
		 ->first();
			
		return  $sql;
		 
   }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
 	   $CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
		
		$data['userStatus'] = $CommonController->userStatus();
		$data['results'] = $this->getById($id);
		
		
	    $data['title'] = $this->title;
		 $data['className'] = $this->className;
        return view('Cms/Role/roleEdit',$data); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $post = $request->all();
	  if($post){
	  
	 
	     $blanckArray = array('name'=>'',
						'label'=>'',
						'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('name' => 'required', 
	   				  'label' => 'required',
					  'status' => 'required');

       $id = $post['id'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/role/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		
		    $data = array('name' => $post['name'],
			                  'label' => $post['label'],
							   'status' => strtolower($post['status']),
							   'updated_at' => $this->createdAt
							  );
		
			
	    $getProductId =  DB::table('roles')->where('id', $id)->update($data);
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/role');
			
		}
		 return Redirect::to('cms/role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
		$CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
		
        if(empty($id)){
		  Session::flash('error', trans('common.invalidToken')); 
          return Redirect::to('cms/role');
		}
		
		$sql = DB::table('roles')
            ->select('*')
			->where('id',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status' => 'Inactive');
			
			DB::table('roles')->where('id', $id)->update($data); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/role');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/role');
    }
}
