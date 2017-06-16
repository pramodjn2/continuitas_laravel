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

class RelationshipmanagerController_fm extends Controller
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
      $this->title = trans('common.relationManager');    
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
		
		$data['title'] = $this->title;
		$data['className'] = $this->className;
		$data['dashboard'] = trans('common.relationManager');
		$data['results'] = $this->show();
		
		return view('Cms/Rsmanager_fm/rsmanager_fm',$data); 
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
		
		$data['title'] = $this->title;
		$data['className'] = $this->className;
		
		
		$data['userStatus'] = $CommonController->userStatus();
		
        return view('Cms/Rsmanager_fm/rsmanagerAdd_fm',$data); 
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
	 
	   $blanckArray = array('initials'=>'',
						   'managerName'=>'',
						   'email'=>'',
						   'gender'=>'',
						   'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('initials' => 'required|max:40', 
	   				  'managerName' =>   'required|max:40',    
                      'email' => 'required',
					  'gender' => 'required',
					  'status' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/manager/add')->withErrors($validator)->withInput();
		}
		
	            $data = array('vl_rm' => $post['initials'],
			                  'nm_rm' => $post['managerName'],
							  'email_rm' => $post['email'],  
							  'sexe_rm' => strtolower($post['gender']),
							  'status_rm' => strtolower($post['status']),
							   );
							  
			 		  
						
		$insertId =  DB::table('relatiemanager_fm')->insertGetId($data);
		
				
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/manager_fm')->withInput();
			
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
	   $sql = DB::table('relatiemanager_fm as r')
	   ->select('r.*', 'r.id_rm as id')
		->get();
		return  $sql;
		 
   }


    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendCompanyNumber')); 
		 return Redirect::to('cms/manager_fm/add')->withInput();
		}
       
	   $where = array('r.id_rm' => $id);
	   
	   $sql = DB::table('relatiemanager_fm as r')
		 ->select('r.*', 'r.id_rm as id')
		->where($where)
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
        return view('Cms/Rsmanager_fm/rsmanagerEdit_fm',$data); 
    }

    /**
     * Show the form for editing the specified resource.
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
        return view('Cms/Rsmanager_fm/rsmanagerView_fm',$data); 
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
	  
	  $blanckArray = array('initials'=>'',
						   'managerName'=>'',
						   'email'=>'',
						   'gender'=>'',
						   'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('initials' => 'required|max:40', 
	   				  'managerName' =>   'required|max:40',    
                      'email' => 'required',
					  'gender' => 'required',
					  'status' => 'required');

        $id = $post['id'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		  $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/manager_fm/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		   $data = array('vl_rm' => $post['initials'],
			                  'nm_rm' => $post['managerName'],
							  'email_rm' => $post['email'],  
							  'sexe_rm' => strtolower($post['gender']),
							  'status_rm' => strtolower($post['status']),
							   );
							  
							  
	    $getProductId =  DB::table('relatiemanager_fm')->where('id_rm', $id)->update($data);
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/manager_fm');
		}
		 return Redirect::to('cms/manager_fm');
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
          return Redirect::to('cms/manager_fm');
				
		}
		
		$sql = DB::table('relatiemanager_fm')
            ->select('*')
			->where('id_rm',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status_rm' => 'Inactive');
			
			DB::table('relatiemanager_fm')->where('id_rm', $id)->update($data); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/manager_fm');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/manager_fm');
		
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
				$header = array(trans('common.initials'), trans('common.name'), trans('common.gender'), trans('common.email'), trans('common.status'));
	
				// Write header to csv
				fputcsv($fp, $header);
               
				foreach ($result['open_quotation'] as $row) 
				{
					
					$vl_rm = $row->vl_rm;
					$nm_rm 	= $row->nm_rm;
					$sexe_rm 	= $row->sexe_rm;
					$email_rm	= $row->email_rm;
					$status_rm	= $row->status_rm;


					// Creating array of values and writing it on csv
					$one_row = [$vl_rm, $nm_rm, $sexe_rm, $email_rm, $status_rm];
					fputcsv($fp, $one_row);
				}

				// creating filename for csv
				$file_name = 'RelatieManager_fm '.date("Y-m-d");

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
          return Redirect::to('cms/manager_fm');
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
        return view('Cms/Rsmanager_fm/rsmanagerListImport_fm',$data);
    }
    public function forceDownload()
    {    
        $root = 'sample_csv/RelatieManager_fm.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'RelatieManager_fm.csv';
       
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Rsmanager_fm.csv');
        header('Pragma: no-cache');
        readfile("sample_csv/RelatieManager_fm.csv");
       
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
                return Redirect::to('cms/user_fm/import');
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
                        if(count($row) != 5)
                        {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/manager_fm/import');
                        }
                         
                         if($row[0] != trans('common.initials') && $row[1] !=trans('common.name')  && $row[2] != trans('common.gender') && $row[3] != trans('common.email') && $row[4] != trans('common.status'))
                         {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/manager_fm/import');
                         }  
                    }   
                     $ww++;
                    
                       $vl_rm = $row[0];
                       $nm_rm = $row[1];
                       $sexe_rm = $row[2];
                       $email_rm = $row[3];
                       $status_rm = $row[4];
                  
                       $one_row = array(              
                                            'vl_rm'          => $vl_rm,                       
                                            'nm_rm'       => $nm_rm,
                                            'sexe_rm'         => $sexe_rm,
                                            'email_rm'        => $email_rm,
                                            'status_rm'      => $status_rm,
                                            
                                        );

                        // pushing one_row array to file_data_array
                        array_push($file_data_array, $one_row);
                     
                }
                    foreach ($file_data_array as $key => $row) 
                    {
                        $save_data = array();
                        if($counter > 1 && $row['vl_rm'] != '' 
                                        && $row['nm_rm'] != '' 
                                        && $row['sexe_rm'] != '' 
                                        && $row['email_rm'] != '' 
                                        && $row['status_rm'] != '')
                        { 

                        $count1 = DB::table('relatiemanager_fm')
						 					->select('id_rm')
											->where('vl_rm', $row['vl_rm'])
											->where('nm_rm', $row['nm_rm'])
											->where('sexe_rm', strtolower($row['sexe_rm'])
											->where('email_rm', $row['email_rm'])
											->where('status_rm', strtolower($row['status_rm'])
			                                ->count();	
			            if($count1>0)
			            { 
			            	$error_message .= '<li>'.$row['vl_rm'].' | '.$row['nm_rm'].' | '.$row['sexe_rm'].' | '.$row['email_rm'].' Dubbele gegevens niet toegestaan</li>';
			            }else{	

                        $blanckArray = array('vl_rm'=>'',
                            'nm_rm'=>'',
                            'sexe_rm'=>'',
                            'email_rm'=>'',
                            'status_rm'=>'');
                        $post = (array_merge($blanckArray,$row));               
                        $rules = array('vl_rm' => 'required', 
                            'nm_rm' =>   'required',    
                            'sexe_rm' => 'required',
                            'email_rm' => 'required|email|max:40',
                            'status_rm' => 'required');
 
                        $error = 0;
                        $validator = Validator::make($post, $rules);
                            if ($validator->fails()) {
                            $messages = $validator->messages();
                            $error_message .= '<li>'.$row['vl_rm'].' | '.$row['nm_rm'].' | '.$row['sexe_rm'].' | '.$row['email_rm'].' | Validation Error '.$messages.' </li>';
                            $error = 1;
                            }
                            else
                            { 
                            	$count = DB::table('relatiemanager_fm')
						 					->select('id')
											->where('email_rm', $row['email_rm'])
			                                ->count();

			                     if($count == 0)
								{           
                                    $save_data['vl_rm']  = $row['vl_rm'];
                                    $save_data['nm_rm']  = $row['nm_rm'];
                                    $save_data['sexe_rm']     = strtolower($row['sexe_rm']);
                                    $save_data['email_rm']    = $row['email_rm'];
                                    $save_data['status_rm']  = strtolower($row['status_rm']);                                 
                    
                                    //inserting data into account table
                                    $insertId =  DB::table('relatiemanager_fm')->insertGetId($save_data);

                                    //creating list of successfully inserted records
                                    $success_message .= '<li>'.$row['vl_rm'].' | '.$row['nm_rm'].' | '.$row['sexe_rm'].' | '.$row['email_rm'].'</li>';
                                }
                                else
								{
									//creating list of records that is not inserted
									if($error == 0){
									$error_message .= '<li>'.$row['vl_rm'].' | '.$row['nm_rm'].' | '.$row['sexe_rm'].' | '.$row['email_rm'].'</li>';
								    }
								}
                            }
                          }  
                        }
                        $counter++;                     
                    }
                

                $error_message  .= "</ol>";
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
                return Redirect::to('cms/manager_fm/import');
            }   
            else
            {     
                Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
                return Redirect::to('cms/manager_fm/import');
            }
    
        }
    }
	
}
