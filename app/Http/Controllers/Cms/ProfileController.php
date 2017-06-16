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

class ProfileController extends Controller
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
	  $this->title = trans('common.profile');
	 // $this->permision = $CommonController->userPermission($this->className);
	 // dd($this->permision);
		
    }
	
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$target_dir = config('app.uploads_path');
		$data['className'] = $this->className;		
        $data['title'] = $this->title;
		$data['dashboard'] = trans('common.profile').' '.trans('common.dashboard');
		$data['results'] = $this->show();
		return view('Cms/Profile/profile',$data); 
    }
	
	
	function changePassword(){
		
		$id = session('id');
		
			
			
		 $data['title'] = $this->title = trans('common.changePassword');
		 $data['user_dashboard'] = trans('common.changePassword');
		 $data['className'] = $this->className;
	
		$data['results'] = $this->show();
		return view('Cms/Profile/changePassword',$data); 
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$CommonController = new CommonController;
		
		
		$data['userStatus'] = $CommonController->userStatus();
		$data['userGroup'] = $CommonController->userGroup();
		
		$data['className'] = $this->className;
	    $data['title'] = $this->title;
        return view('Cms/User/userAdd',$data); 
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
		  
	 	$oldpass = $post['oldPassword'];
		$confirmPassword = $post['confirmPassword'];
			
        $rules = array('oldPassword' => 'required|min:6', 
	   				  'confirmPassword' => 'required|min:6',    
                      'newPassword' => 'required|min:6');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/changePassword')->withErrors($validator);
		}
		$id = session('id');
	    
		$sql = DB::table('users')
            ->select('*')
			->where('id',$id)
            ->first();
			
			
		
		 if ( Hash::check($post['oldPassword'], $sql->password)) {
		
			$data = array('password' => bcrypt($post['confirmPassword']),
						  'updated_at' => $this->createdAt);
			$getProductId =  DB::table('users')->where('id', $id)->update($data);
			
			Session::flash('success', trans('common.passwordChanged')); 
			return Redirect::to('cms/changePassword');
		 }else{
		     Session::flash('error', trans('common.oldPasswordIncorrect')); 
			return Redirect::to('cms/changePassword');	 
		}
		}
		return Redirect::to('cms/changePassword');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
	 
	   $id = session('id');
       $where = array('u.id' => $id);
	   $sql = DB::table('users as u')
		->leftJoin('role_user as ur', 'ur.user_id', '=', 'u.id')
		->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
		->select('u.id','u.name', DB::raw('CONCAT(u.name, " ", u.surname) AS fullName'),
		 'u.surname','u.email','u.gender', 'u.avatar','u.password','u.status','u.created_at','u.updated_at','r.name as role','r.label')
		 ->where($where)
		->first();
		return  $sql;
		 
   }


    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendNewsNumber')); 
		 return Redirect::to('cms/user/add')->withInput();
		}
       
	   $where = array('u.id' => $id);
	   $sql = DB::table('users as u')
		->leftJoin('role_user as ur', 'ur.user_id', '=', 'u.id')
		->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
		->select('u.id','u.name','u.avatar', DB::raw('CONCAT(u.name, " ", u.surname) AS fullName'),
		 'u.surname','u.email','u.gender','u.password','u.status','u.created_at','r.id as roleId','r.name as role','r.label')
		 ->where($where)
		 ->groupBy('u.id')
		 ->first();
			
		return  $sql;
		 
   }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
		$id = session('id');
        $CommonController = new CommonController;
		$data['userStatus'] = $CommonController->userStatus();
		$data['userGroup'] = $CommonController->userGroup();
		$data['results'] = $this->getById($id);
		
		
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('Cms/Profile/profileEdit',$data); 
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
		  
	 $target_dir = config('app.uploads_path');
	 $attachmentName = '';
	 if($_FILES["avatar_logo"]["name"]){
		$target_file = $target_dir . basename($_FILES["avatar_logo"]["name"]);
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		@move_uploaded_file($_FILES["avatar_logo"]["tmp_name"], $target_file);
		$attachmentName = basename($_FILES["avatar_logo"]["name"]); /* file name */
	 }
	 
	   $blanckArray = array('name'=>'',
						'surname'=>'',
						'email'=>'',
						'gender'=>'',
						'password'=>'',
						'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('name' => 'required|max:40', 
	   				  'gender' => 'required',
					  'userGroup' => 'required');

     $id = session('id');
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/editProfile/')->withErrors($validator)->withInput();
		}
		 
		
		    $data = array('name' => $post['name'],
			                  'surname' => $post['surname'],
							   'gender' => $post['gender'],
							  'status' => $post['status'],
							  'updated_at' => $this->createdAt
							  );
		
		if(!empty($attachmentName)){
			
			$sql = DB::table('users')
            ->select('*')
			->where('id',$id)
            ->first();
			$avatar = $sql->avatar;
			if(!empty($avatar)){
			 @unlink($target_dir.'\\'.$avatar);	
			}
			
			 $url = url('uploads/');
	        $avatar = $this->imageCheck($attachmentName,$url);
	        Session::put('avtar', $avatar);
	   
		   $data1 = array('avatar' => @$attachmentName);
		   $data = (array_merge($data,$data1));
		}
			
	    $getProductId =  DB::table('users')->where('id', $id)->update($data);
						
			
		$dataUserGroup = array('role_id' => $post['userGroup']);	
		DB::table('role_user')->where('user_id', $id)->update($dataUserGroup);
			
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/editProfile');
			
		}
		 return Redirect::to('cms/editProfile');
    }
	
	protected function imageCheck($image, $url){
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		
        if(empty($id)){
		  Session::flash('error', trans('common.invalidToken')); 
          return Redirect::to('cms/user');
				
		}
		
		$sql = DB::table('users')
            ->select('*')
			->where('id',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status' => 'Inactive');
			
			DB::table('users')->where('id', $id)->update($data); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/user');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/user');
		
    }

    public function change()
    {
    	$data['title'] = $this->title = 'User';
		$data['user_dashboard'] = 'User Dashboard';
		
		
		$data['results'] = $this->show();
		return view('Cms/User/changePassword',$data); 
    }

    public function updatePassword(Request $request)
    {
     $post = $request->all();
	  if($post){
	  
	   $blanckArray = array(
		   					'oldPassword' =>'',
		   					'confirmPassword'=>'',
							'newPassword'=>'',
							'status'=>'Active'
							);
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('oldPassword' => 'required|min:6|confirmed', 
	   				  'confirmPassword' => 'required|min:6|confirmed',    
                      'newPassword' => 'required|min:6',
					  );

       	$id = session('id');
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/changePassword')->withErrors($validator)->withInput();
		}
		
		    $data = array('password' => bcrypt($post['newPassword']),
						  'status' => $post['status'],
						  'updated_at' => $this->createdAt
						);
						
		$getProductId =  DB::table('users')->where('id', $id)->update($data);
			
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/user');
			
		}
		 return Redirect::to('cms/user');
    }
	
}
