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

class UserController extends Controller
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
	 // dd($this->permision);
	 $this->title = trans('common.userDashboard');
    }
	
	 
	
	 public function profile()
     {
		
		$data['className'] = $this->className;		
        $data['title'] = $this->title;
		$data['dashboard'] = trans('common.userDashboard');
		$data['results'] = $this->show();
		return view('Cms/User/userProfile',$data); 
		
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
		$data['dashboard'] = trans('common.userDashboard');
		$data['results'] = $this->show();

		//return view('Cms/User/userProfile',$data); 


		return view('Cms/User/user',$data); 
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
						'status'=>'');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('name' => 'required|max:40', 
	   				  'email' =>   'required|email|max:40',    
                      'password' => 'required|min:6',
					  'gender' => 'required',
					  'userGroup' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/user/add')->withErrors($validator)->withInput();
		}
		
		     $getEmailIdCount = DB::table('users')
			 					->select('*')
								->where('email', $post['email'])
                                ->count();
								
								
            if($getEmailIdCount > 0)
            {
               Session::flash('error', trans('common.emailAlreadyExixts')); 
		       return Redirect::to('cms/user/add')->withInput();
            }   
			
	
	    $data = array('name' => $post['name'],
			                  'surname' => $post['surname'],
							  'email' => $post['email'],  
							  'password' => bcrypt($post['password']),
							  'gender' => strtolower($post['gender']),
							  'status' => strtolower($post['status']),
							  'created_at' => $this->createdAt,
							  'updated_at' => $this->createdAt
							  );
							  
		
		
		if(!empty($attachmentName)){
			
		$data1 = array('avatar' => @$attachmentName);
		   $data = (array_merge($data,$data1));
		}
								
		$insertId =  DB::table('users')->insertGetId($data);
		
		$data = array('role_id' => $post['userGroup'],
			 		  'user_id' => $insertId);		
		 DB::table('role_user')->insertGetId($data);
			
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/user')->withInput();
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
	 
       
	   $sql = DB::table('users as u')
		->leftJoin('role_user as ur', 'ur.user_id', '=', 'u.id')
		->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
		->select('u.id','u.name', DB::raw('CONCAT(u.name, " ", u.surname) AS fullName'),
		 'u.surname','u.email','u.gender','u.password','u.avatar','u.status','u.created_at','r.name as role','r.label')
		->get();
		return  $sql;
		 
   }


    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendUserNumber')); 
		 return Redirect::to('cms/user/add')->withInput();
		}
       
	   $where = array('u.id' => $id);
	   $sql = DB::table('users as u')
		->leftJoin('role_user as ur', 'ur.user_id', '=', 'u.id')
		->leftJoin('roles as r', 'r.id', '=', 'ur.role_id')
		->select('u.id','u.name', DB::raw('CONCAT(u.name, " ", u.surname) AS fullName'),
		 'u.surname','u.email','u.gender','u.password','u.avatar','u.status','u.created_at','u.updated_at', 'r.id as roleId','r.name as role','r.label')
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
    public function edit($id)
    {
       $CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
				
		$data['userStatus'] = $CommonController->userStatus();
		$data['userGroup'] = $CommonController->userGroup();
		
		 
		$data['results'] = $this->getById($id);
		
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('Cms/User/userEdit',$data); 
    }

    /**
     * Show the form for View the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,'read');
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
				
		$data['results'] = $this->getById($id);
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('Cms/User/userView',$data); 
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
						'status'=>'');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('name' => 'required|max:40', 
	   				  'gender' => 'required',
					  'userGroup' => 'required');

       $id = $post['id'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/user/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		
		    $data = array('name' => $post['name'],
			                  'surname' => $post['surname'],
							   'gender' => strtolower($post['gender']),
							  'status' => strtolower($post['status']),
							  'updated_at' => $this->createdAt
							  );
		if(!empty($post['password'])){		  
		  $dataPass = array('password' => bcrypt($post['password']));
		  $data = (array_merge($data,$dataPass));	
		}
			
			
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
	       
	   
		   $data1 = array('avatar' => @$attachmentName);
		   $data = (array_merge($data,$data1));
		}
		
	    $getProductId =  DB::table('users')->where('id', $id)->update($data);
						
			
		$dataUserGroup = array('role_id' => $post['userGroup']);	
		DB::table('role_user')->where('user_id', $id)->update($dataUserGroup);
			
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/user');
			
		}
		 return Redirect::to('cms/user');
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
		
		$CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
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
			
			DB::table('users')->where('id', $id)->delete(); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/user');
		} 
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/user');
		
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMultiple(Request $request)
    { 
        $post = $request->all();
        
        // $CommonController = new CommonController;
        // $access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
        // if(empty($access)){ 
        //     Session::flash('error', 'You do not have permission to access this page!.'); 
        //     return Redirect::to('cms/dashboard');
        // }
        
         if(empty($post)){ 
          Session::flash('error', trans('common.invalidToken')); 
          return Redirect::to('cms/users');
                
        }
        
     
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unPublish');
            
            DB::table('users')->whereIn('id', $post['ids'])->delete(); 
             Session::flash('success', trans('common.deletedSuccesfully'));
            
              return 1;

    }
    public function change()
    {
    	$data['title'] = $this->title = 'User';
		$data['user_dashboard'] = trans('common.userDashboard');
		$data['className'] = $this->className;
		
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
		  Session::flash('error', trans('common.pleaseFillMandatoryFields.')); 
		  return Redirect::to('cms/user/change/'.$id)->withErrors($validator)->withInput();
		}
		 
		
		    $data = array(	
		    				'password' => $post['newPassword'],
							'status' => strtolower($post['status']),
							'updated_at' => $this->createdAt
							  
						);
		if(!empty($post['newPassword'])){		  
		  $dataPass = array('password' => bcrypt($post['newPassword']));
		  $data = (array_merge($data,$dataPass));	
		}
			
	    $getProductId =  DB::table('users')->where('id', $id)->update($data);
			
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/user');
			
		}
		 return Redirect::to('cms/user');
    }



    public function exportCsv()
	 {     
	 
	 $CommonController = new CommonController;
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
	   	$result['open_quotation'] = $this->show();
			if(!empty($result['open_quotation']))
			{
				# Opening file memory stream in write mode
				$fp = fopen('php://output', 'w');
	
				// Creating heading row for csv	
				$header = array(trans('common.firstName'),trans('common.lastName'), trans('common.email'), trans('common.gender'),trans('common.status'), trans('common.userGroup'));
	
				// Write header to csv
				fputcsv($fp, $header);
               
				foreach ($result['open_quotation'] as $row) 
				{
					$name 	= $row->name;
					$surname 	= $row->surname;
					$email 	= $row->email;
					$gender 	= $row->gender;
					$status	= $row->status;
					$role = $row->role;

					// Creating array of values and writing it on csv
					$one_row = [$name, $surname, $email, $gender, $status, $role];
					fputcsv($fp, $one_row);
				}

				// creating filename for csv
				$file_name = trans('common.users').date("Y-m-d");

				/*** Modify headers for download csv file ***/
				header('Content-Encoding: UTF-8');
		       	header('Content-Type: text/csv; charset=utf-8');
				header("Content-Disposition: attachment; filename=\"$file_name.csv\";");

				/** Send file to browser for download **/
				fpassthru($fp);
				//return Redirect::to('cms/user');
				exit;
			}
			else
			{
				Session::flash('error', trans('common.thereIsNoDataExport')); 
          return Redirect::to('cms/user');
			}
		
	}
 
	public function import()
	{  
	
	$CommonController = new CommonController;
	
	
		$access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		if(empty($access)){
			Session::flash('error', trans('common.permissionNot')); 
	        return Redirect::to('cms/dashboard');
		}
		
	 $data['className'] = $this->className;		
        $data['title'] = $this->title;
		$data['dashboard'] = trans('common.bulkImport');
		return view('Cms/User/userListImport',$data);
	}

	public function forceDownload()
    {    
        $root = 'sample_csv/sample.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'sample.csv';
       
        header('Content-Type: application/csv');
      	header('Content-Disposition: attachment; filename=gebruikerslijst.csv');
		header('Pragma: no-cache');
		readfile("sample_csv/sample.csv");
       
	}
 
	public function import_csv(Request $request)
	{ 
		if(isset($_FILES['userfile']))
		{	
			  // dd($_FILES['userfile']['tmp_name']);
	        $imageFileType = pathinfo($_FILES['userfile']['name'],PATHINFO_EXTENSION);
	        if ($_FILES["userfile"]["size"] > 3000) 
			{
			    Session::flash('error', trans('common.fileTooLarge')); 
			    return Redirect::to('cms/user/import');
			}
			if($imageFileType=='csv')
			{   
				$counter = 1;
				$error_message = "<ol>";
			    $success_message = "<ol>";
				$upload_url = '/uploads/'; 
				$path =$_FILES['userfile']['tmp_name']; 
				$file = fopen($path,"r");  
				$file_data_array = array();
				$ww = 1;
				while(!feof($file))
				{
					$row = fgetcsv($file);
					if($ww == 1)
					{
                         
                         if($row[0] != trans('common.firstName') && $row[1] != trans('common.lastName') && $row[2] != trans('common.email') && $row[3] != trans('common.gender') && $row[4] != trans('common.password') && $row[5] != trans('common.status') && $row[6] != trans('common.userGroup'))
                         {
							Session::flash('error', trans('common.fileFormatInvalid')); 
							return Redirect::to('cms/user/import');
                         }	
					}	
                     $ww++;
	                
	                   $Name = $row[0];
	                   $Surname = $row[1];
	                   $Email = $row[2];
	                   $Gender = $row[3];
	                   $Password = $row[4];
	                   $Status = $row[5];
	                   $Role_id = $row[6];
                      // $Role_id = 8;
                       $role_id = array();
                       $rd = 0;
                       if($Role_id && $Role_id !=trans('common.userGroup'))
                       {

                       		$role_id = DB::table('roles')
					 					->select('id')
										->where('name', $Role_id)
		                                ->first();
 
		                    $role_id = (array)$role_id;  

		                    if(!$role_id)
		                    {
                             	$error_message .= '<li>'.$row[0].' | '.$row[1].' | '.$row[2].' | '.$row[5].' | Validation Error: Role Name is  incorrect.  </li>';
		                    } else{$role_id = $role_id['id'];}
                       }
                       // else
                       // { 
                       		
                       // 		$error_message .= '<li>'.$row[0].' | '.$row[1].' | '.$row[2].' | '.$row[5].' | Validation Error: Role Name is blank.  </li>';
                       // }

	                   $one_row = array(              
			                				'name' 			=> $Name,          				
											'surname'		=> $Surname,
											'email'			=> $Email,
											'gender' 		=> $Gender,
											'password'		=> $Password,
											'status'		=> $Status,
											'role_id'       => $role_id,
										);

		                // pushing one_row array to file_data_array
		                array_push($file_data_array, $one_row);

	                
											
				}
                    
				if($rd == 0)
				{	
					foreach ($file_data_array as $key => $row) 
		            {
			        	$save_data = array();
			        	if($counter > 1 && $row['name'] != '' 
			        					&& $row['surname'] != '' 
			        					&& $row['email'] != '' 
			        					&& $row['gender'] != '' 
			        					&& $row['password'] != '' 
			        					&& $row['status'] != '' 
			        					&& $row['role_id'] != '')
			        	{ 

						$blanckArray = array('name'=>'',
							'surname'=>'',
							'email'=>'',
							'gender'=>'',
							'password'=>'',
							'status'=>'Active');
						$post = (array_merge($blanckArray,$row));				
						$rules = array('name' => 'required|max:40', 
							'email' =>   'required|email|max:40',    
							'password' => 'required|min:6',
							'gender' => 'required',
							'role_id' => 'required');

						$error = 0;
						$validator = Validator::make($post, $rules);
							if ($validator->fails()) {
							$messages = $validator->messages();
							$error_message .= '<li>'.$row['name'].' | '.$row['surname'].' | '.$row['email'].' | '.$row['status'].' | Validation Error '.$messages.' </li>';
							$error = 1;
							}
							else
							{
		                     

				       			$count = DB::table('users')
						 					->select('id')
											->where('email', $row['email'])
			                                ->count();

								if($count == 0)
								{

				            		$save_data['name'] 	= $row['name'];
									$save_data['surname']  = $row['surname'];
									$save_data['email'] 	= $row['email'];
									$save_data['gender']	= strtolower($row['gender']);
									$save_data['password'] 	= bcrypt($row['password']);
									$save_data['status'] 	= strtolower($row['status']);
									$save_data['created_at'] = $this->createdAt;
									$save_data['updated_at'] = $this->createdAt;
									
					
									//inserting data into account table
									$insertId =  DB::table('users')->insertGetId($save_data);

									$data1 = array('role_id' => $row['role_id'],
									'user_id' => $insertId);		
									DB::table('role_user')->insertGetId($data1);

									//creating list of successfully inserted records
									$success_message .= '<li>'.$row['name'].' | '.$row['surname'].' | '.$row['email'].' | '.$row['status'].'</li>';
								}
								else
								{
									//creating list of records that is not inserted
									if($error == 0){
									$error_message .= '<li>'.$row['name'].' | '.$row['surname'].' | '.$row['email'].' | '.$row['status'].'</li>';
								    }
								}
							}
			        	} 
			        	$counter++;		            	
		            }
	        	}

				$error_message 	.= "</ol>";
				$success_message .= "</ol>";
				
				fclose($file);

				if($error_message != "<ol></ol>")
				{				
					Session::flash('error', '<big>'.trans('common.emailAlreadyExixts').'</big><br/>'.$error_message);
				}

				// Settting success message into the session variables // 	
				if($success_message != "<ol></ol>")
				{   
					Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
				}
				return Redirect::to('cms/user/import');
			}	
			else
			{     
				Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
			    return Redirect::to('cms/user/import');
			}
	
		}
    }
	
}
