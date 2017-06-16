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

class CompanyController extends Controller
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
      $this->title = trans('common.company');
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
		
		$data['dashboard'] = trans('common.company');
		$data['results'] = $this->show();
		
		return view('Cms/Company/company',$data); 
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
		$data['kindOfCompany'] = $CommonController->kindOfCompany();
		$data['relationship'] = $CommonController->relationshipManager();
		
		
        return view('Cms/Company/companyAdd',$data); 
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
	 
	   $blanckArray = array('companyName'=>'',
						   'companyNumber'=>'',
						   'kindOfCompany'=>'',
						   'relationManager'=>'',
						   'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('companyName' => 'required|max:40', 
	   				  'companyNumber' =>   'required|max:40',    
                      'kindOfCompany' => 'required',
					  'relationManager' => 'required',
					  'status' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/company/add')->withErrors($validator)->withInput();
		}
		
		   $getEmailIdCount = DB::table('bedrijf')
			 					->select('*')
								->where('relnr_bd', $post['companyNumber'])
                                ->count();
								
								
            if($getEmailIdCount > 0)
            {
               Session::flash('error', trans('common.companyNumberAlreadyExixts')); 
		       return Redirect::to('cms/company/add')->withInput();
            }   
			
	
	     $data = array('nm_bd' => $post['companyName'],
			                  'relnr_bd' => $post['companyNumber'],
							  'ondvorm_bd' => $post['kindOfCompany'],  
							  'id_rm' => $post['relationManager'],
							  'status' => strtolower($post['status']),
							   'created_at' => $this->createdAt
							  );
							  
			 		  
						
		$insertId =  DB::table('bedrijf')->insertGetId($data);
		
				
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/company')->withInput();
			
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
       /*
	    klant :-  client
		bedrijf :- company 
		relatiemanager :- relationship manager
	   */
	   $sql = DB::table('bedrijf as comp')
		->leftJoin('relatiemanager as r', 'r.id_rm', '=', 'comp.id_rm')
		->select('comp.*', 'comp.id_bd as id','r.nm_rm','r.vl_rm','r.sexe_rm','r.email_rm')
		->get();
		return  $sql;
		 
   }


    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendCompanyNumber')); 
		 return Redirect::to('cms/company/add')->withInput();
		}
       
	   $where = array('comp.id_bd' => $id);
	   
	   $sql = DB::table('bedrijf as comp')
		->leftJoin('relatiemanager as r', 'r.id_rm', '=', 'comp.id_rm')
		->select('comp.*', 'comp.id_bd as id','r.nm_rm')
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
		$data['kindOfCompany'] = $CommonController->kindOfCompany();
		$data['relationship'] = $CommonController->relationshipManager();
		$data['results'] = $this->getById($id);
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('Cms/Company/companyEdit',$data); 
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
        return view('Cms/Company/companyView',$data); 
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
	  
	  $blanckArray = array('companyName'=>'',
						   'companyNumber'=>'',
						   'kindOfCompany'=>'',
						   'relationManager'=>'',
						   'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('companyName' => 'required|max:40', 
	   				  'companyNumber' =>   'required|max:40',    
                      'kindOfCompany' => 'required',
					  'relationManager' => 'required',
					  'status' => 'required');

        $id = $post['id'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		  $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/company/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		   $data = array('nm_bd' => $post['companyName'],
			                  'relnr_bd' => $post['companyNumber'],
							  'ondvorm_bd' => $post['kindOfCompany'],  
							  'id_rm' => $post['relationManager'],
							  'status' => strtolower($post['status']),
							  'updated_at' => $this->createdAt
							  );
							  
	    $getProductId =  DB::table('bedrijf')->where('id_bd', $id)->update($data);
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/company');
		}
		 return Redirect::to('cms/company');
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
          return Redirect::to('cms/company');
				
		}
		
		$sql = DB::table('bedrijf')
            ->select('*')
			->where('id_bd',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status' => 'Inactief');
			
			DB::table('bedrijf')->where('id_bd', $id)->update($data); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/company'); 
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/company');
		
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
				$header = array( trans('common.nameCompany'), trans('common.companyNumber'), trans('common.relationName'),trans('common.status'), trans('common.initials'), trans('common.kindOfCompany'), trans('common.gender'), trans('common.email'));
	
				// Write header to csv
				fputcsv($fp, $header); 
               
				foreach ($result['open_quotation'] as $row) 
				{
					$id_rm 	= $row->id_rm;
					$nm_bd 	= $row->nm_bd;
					$relnr_bd 	= $row->relnr_bd;
					$ondvorm_bd 	= $row->ondvorm_bd;
					$status	= $row->status;
					$vl_rm = $row->vl_rm;
					$nm_rm 	= $row->nm_rm;
					$sexe_rm 	= $row->sexe_rm;
					$email_rm	= $row->email_rm;

					// Creating array of values and writing it on csv
					$one_row = [ $nm_bd, $relnr_bd,$nm_rm,  $status, $vl_rm, $ondvorm_bd, $sexe_rm, $email_rm];
					fputcsv($fp, $one_row);
				}

				// creating filename for csv

				$file_name = trans('common.company').date("Y-m-d");

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
          return Redirect::to('cms/company');
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
		return view('Cms/Company/companyListImport',$data);
	}

	public function forceDownload()
    {    
        $root = 'sample_csv/Company.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'Company.csv';
       
        header('Content-Type: application/csv');
      	header('Content-Disposition: attachment; filename=Company.csv');
		header('Pragma: no-cache');
		readfile("sample_csv/Company.csv");
       
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
			    return Redirect::to('cms/company/import');
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
				$bd = 0;
				while(!feof($file)) 
				{
					$row = fgetcsv($file);
					if($ww == 1)
					{   
						if(count($row) !=5)
						{
							Session::flash('error', trans('common.fileFormatInvalid')); 
							return Redirect::to('cms/company/import');
						}
                         
                         if($row[0] != trans('common.nameCompany') && $row[1] != trans('common.companyNumber') && $row[2] != trans('common.kindOfCompany') && $row[3] != trans('common.relationName') && $row[4] != trans('common.status'))
                         {
							Session::flash('error', trans('common.fileFormatInvalid')); 
							return Redirect::to('cms/company/import');
                         }	
					}	
                     $ww++;
	                
	                   $nameCompany = $row[0]; 
	                   $companyNumber = $row[1];
	                   $kindOfCompany = $row[2];
	                   $relationManager = $row[3];
	                   $status = $row[4];
	                   $id_rm = '';
	                   
                      if($relationManager && $relationManager !=trans('common.relationName'))
                       {

                       		$id_rm = DB::table('relatiemanager')
					 					->select('id_rm')
										->where('nm_rm', $relationManager)
		                                ->first();

		                    $id_rm = (array)$id_rm;  

		                    if(!$id_rm)
		                    { 
		                    	$bd = 1;
                             	$error_message .= '<li>'.$row[0].' | '.$row[1].' | '.$row[2].' | '.$row[3].' | Validation Error: Bedrijfsnaam is onjuist.  </li>';
		                    } else{$id_rm = $id_rm['id_rm'];}
                       }

	                   $one_row = array(              
			                				'nm_bd' 	   => $nameCompany,          				
											'relnr_bd'	   => $companyNumber,
											'ondvorm_bd'   => $kindOfCompany,
											'id_rm' 	   => $id_rm,
											'status'	   => $status,
										);
		                // pushing one_row array to file_data_array
		                array_push($file_data_array, $one_row);
						
				}
				if($bd == 0)
				{
					foreach ($file_data_array as $key => $row) 
		            {
			        	$save_data = array();
			        	if($counter > 1 && $row['nm_bd'] != '' 
			        					&& $row['relnr_bd'] != '' 
			        					&& $row['ondvorm_bd'] != '' 
			        					&& $row['id_rm'] != '' 
			        					&& $row['status'] != '' )
			        	{ 


			        	$count1 = DB::table('bedrijf')
						 					->select('id')
											->where('nm_bd', $row['nm_bd'])
											->where('relnr_bd', $row['relnr_bd'])
											->where('ondvorm_bd', $row['ondvorm_bd'])
											->where('id_rm', $row['id_rm'])
											->where('status', $row['status'])
			                                ->count();	
			            if($count1>0)
			            { 
			            	$error_message .= '<li>'.$row['nm_bd'].' | '.$row['relnr_bd'].' | '.$row['ondvorm_bd'].' | '.$row['id_rm'].' Dubbele gegevens niet toegestaan</li>';
			            } 	else{

						   $blanckArray = array('nm_bd'=>'',
						   'relnr_bd'=>'',
						   'ondvorm_bd'=>'',
						   'id_rm'=>'',
						   'status'=>'');
					   $post = (array_merge($blanckArray,$row));				
				       $rules = array('nm_bd' => 'required|max:40', 
	   				  'relnr_bd' =>   'required|max:40',    
                      'ondvorm_bd' => 'required',
					  'id_rm' => 'required',
					  'status' => 'required');

						$error = 0;
						$validator = Validator::make($post, $rules);
							if ($validator->fails()) {
							$messages = $validator->messages();
							$error_message .= '<li>'.$row['nm_bd'].' | '.$row['relnr_bd'].' | '.$row['ondvorm_bd'].' | '.$row['status'].' | Validation Error '.$messages.' </li>';
							$error = 1;
							}
							else
							{

				            		$save_data['nm_bd']  = $row['nm_bd'];
									$save_data['relnr_bd']    = $row['relnr_bd'];
									$save_data['ondvorm_bd'] 	 = $row['ondvorm_bd'];
									$save_data['id_rm']	 = $row['id_rm'];
									$save_data['status'] 	 = strtolower($row['status']);
									$save_data['created_at'] 	 = $this->createdAt;
									 
									
					
									//inserting data into klant table
									$insertId =  DB::table('bedrijf')->insertGetId($save_data);

									//creating list of successfully inserted records
									$success_message .= '<li>'.$row['nm_bd'].' | '.$row['relnr_bd'].' | '.$row['ondvorm_bd'].' | '.$row['status'].'</li>';
								
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
					Session::flash('error', '<big>'.trans('common.companyNumberAlreadyExixts').'</big><br/>'.$error_message);
				}

				// Settting success message into the session variables // 	
				if($success_message != "<ol></ol>")
				{   
					Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
				}
				return Redirect::to('cms/company/import');
			}	
			else
			{     
				Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
			    return Redirect::to('cms/company/import');
			}
	
		}
    }
	
}
