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

class PermissionController extends Controller
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
      $this->title = trans('common.permissions');
    }
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = $this->title;
		$data['className'] = $this->className;
		$data['dashboard'] = trans('common.permissions');
		$data['results'] = '';
		
		$CommonController = new CommonController;
		$data['userGroup'] = $CommonController->userGroup();
		
		return view('Cms/Permission/permission',$data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = $this->title;
		$data['className'] = $this->className;
		
		$CommonController = new CommonController;
		$data['userGroup'] = $CommonController->userGroup();
		
        return view('Cms/Permission/permissionAdd',$data); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
		$data['userStatus'] = $CommonController->userStatus();
		$data['results'] = '';
		$data['id'] = $id;
		
		
		$data['permissionsClass'] = $CommonController->permissionsClass();
		$data['permissionsActions'] = $CommonController->permissionsActions();
		$data['cmsPermissions'] = $CommonController->cmsPermissions($id);
		
	    $data['title'] = $this->title;
	    $data['className'] = $this->className;
        return view('Cms/Permission/permissionAdd',$data); 
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
		 $role_id = $post['role_id'];
		 if(!empty($role_id)){
		  DB::table('cms_permissions')->where('role_id', $role_id)->delete();
		 }
			
		$checkbox = @$post['checkbox'];
		if(!empty($checkbox)){
		
			 
			foreach($checkbox as $key => $val){
			   $class_id = $key;
			   $permissions_data = '';
				   foreach($val as $pval){
						  $permissions_data[] = array('role_id' => $role_id,'class_id' => $class_id,
					                                    'action_id' => $pval);
					} 
					DB::table('cms_permissions')->insert($permissions_data);
					//echo '<pre/>'; print_r($permissions_data); die;
			}
		}
			Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
		return Redirect::to('cms/permission');
		}
		return Redirect::to('cms/permission');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
