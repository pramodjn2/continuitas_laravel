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

class ClientController_fm extends Controller
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
      $this->title = trans('common.clients');	
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
		$data['dashboard'] = trans('common.clients');
		$data['results'] = $this->show();
		
		return view('Cms/Client_fm/client_fm',$data); 
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
		$data['premium'] = $CommonController->premium();
		$data['company'] = $CommonController->company_fm();
		
		
        return view('Cms/Client_fm/clientAdd_fm',$data); 
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
	 
	   $blanckArray = array('username'=>'',
						'surname'=>'',
						'initials'=>'',
						'email'=>'',
						'password'=>'',
						'gender'=>'',
						'telephone'=>'',
						'company'=>'',
						'premiumClient'=>'',
						'status'=>'Active',
						'dateOfBirth'=>'',
                        'owner'=>'',
                        'company_id'=>'',
                        'relation_family'=>'',
                        'generation'=>'',
                        'leader'=>'',
                        'say'=>'');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('username' => 'required|max:40', 
	   				  'gender' =>   'required',    
                      'company' => 'required',
					  'premiumClient' => 'required',
					  'status' => 'required',
					  'owner' => 'required|max:40', 
                      'company_id' =>   'required|max:40',    
                      'relation_family' => 'required',
                      'generation' => 'required',
                      'leader' => 'required',
                      'say' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/client_fm/add')->withErrors($validator)->withInput();
		} 
		
		     $getEmailIdCount = DB::table('klant_fm')
			 					->select('*')
								->where('email_kl', $post['email'])
                                ->count();
								
								
            if($getEmailIdCount > 0)
            {
               Session::flash('error', trans('common.emailAlreadyExixts')); 
		       return Redirect::to('cms/client_fm/add')->withInput();
            }   
			
			

	
	     $data = array('gebrnm_kl' => $post['username'],
			                  'naam_kl' => $post['surname'],
							  'vl_kl' => $post['initials'],  
							  'pw_kl' => $post['password'],
							  'email_kl' => $post['email'],
							  'sexe_kl' => strtolower($post['gender']),
							  'tel_kl' => $post['telephone'],
							  'id_bd' => $post['company'],
							  'premium_kl' => $post['premiumClient'],
							  'status' => strtolower($post['status']),
							  'dateOfBirth' => $post['dateOfBirth'],
                              'company_id' => $post['company_id'],
                               'owner' => $post['owner'],
                               'relation_family' => $post['relation_family'],
                               'generation' => $post['generation'],
                               'leader' => $post['leader'],
                               'say' => $post['say'],
							  'created_at' => $this->createdAt
							  );
							  
			 		  
						
		$insertId =  DB::table('klant_fm')->insertGetId($data);
		
				
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/client_fm')->withInput();
			
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
	   $sql = DB::table('klant_fm as c')
		->leftJoin('bedrijf_fm as comp', 'comp.id_bd', '=', 'c.id_bd')
		->leftJoin('relatiemanager_fm as r', 'r.id_rm', '=', 'comp.id_rm')
		->select('c.*','c.id_kl as id','comp.nm_bd as company')
		->where('c.status', 'Actief')
		->get();
		
		return  $sql;
		 
   }


    public function getById($id)
    { 
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendUserNumber')); 
		 return Redirect::to('cms/client_fm/add')->withInput();
		}
       
	   $where = array('c.id_kl' => $id);
	   
	    $sql = DB::table('klant_fm as c')
		->leftJoin('bedrijf_fm as comp', 'comp.id_bd', '=', 'c.id_bd')
		->leftJoin('relatiemanager_fm as r', 'r.id_rm', '=', 'comp.id_rm')
		->select('c.*','c.id_kl as id','comp.nm_bd as company')
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
		$data['premium'] = $CommonController->premium();
		$data['company'] = $CommonController->company_fm();
		$data['results'] = $this->getById($id);
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('Cms/Client_fm/clientEdit_fm',$data); 
    }

     /**
     * Show the Page for View the specified resource.
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
        return view('Cms/Client_fm/clientView_fm',$data); 
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
	  
	 
	   $blanckArray = array('username'=>'',
						'surname'=>'',
						'initials'=>'',
						'email'=>'',
						'password'=>'',
						'gender'=>'',
						'telephone'=>'',
						'company'=>'',
						'premiumClient'=>'',
						'status'=>'Active',
						'dateOfBirth'=>'',
                        'owner'=>'',
                        'company_id'=>'',
                        'relation_family'=>'',
                        'generation'=>'',
                        'leader'=>'',
                        'say'=>'');
	   $post = (array_merge($blanckArray,$post));				
        $rules = array('username' => 'required|max:40', 
	   				  'gender' =>   'required',    
                      'company' => 'required',
					  'premiumClient' => 'required',
					  'status' => 'required',
					  'owner' => 'required|max:40', 
                      'company_id' =>   'required|max:40',    
                      'relation_family' => 'required',
                      'generation' => 'required',
                      'leader' => 'required',
                      'say' => 'required');

        $id = $post['id'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		  $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/client_fm/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		
		  $data = array('gebrnm_kl' => $post['username'],
			                  'naam_kl' => $post['surname'],
							  'vl_kl' => $post['initials'],  
							  'sexe_kl' => strtolower($post['gender']),
							  'tel_kl' => $post['telephone'],
							  'id_bd' => $post['company'],
							  'premium_kl' => $post['premiumClient'],
							  'status' => strtolower($post['status']),
							  'dateOfBirth' => $post['dateOfBirth'],
                              'company_id' => $post['company_id'],
                               'owner' => $post['owner'],
                               'relation_family' => $post['relation_family'],
                               'generation' => $post['generation'],
                               'leader' => $post['leader'],
                               'say' => $post['say'],
							  'updated_at' => $this->createdAt
							  );
							  
		if(!empty($post['password'])){		  
		  $dataPass = array('pw_kl' => $post['password']);
		  $data = (array_merge($data,$dataPass));	
		}
	    $getProductId =  DB::table('klant_fm')->where('id_kl', $id)->update($data);
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/client_fm');
		}
		 return Redirect::to('cms/client_fm');
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
          return Redirect::to('cms/client_fm');
				
		}
		
		$sql = DB::table('klant_fm')
            ->select('*')
			->where('id_kl',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status' => 'Inactive');
			
			DB::table('klant_fm')->where('id_kl', $id)->update($data); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/client_fm');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/client_fm');
		
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
          return Redirect::to('cms/client_fm');
                
        }
        
     
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('status' => 'unPublish');
            
            DB::table('klant_fm')->whereIn('id_kl', $post['ids'])->update($data); 
             Session::flash('success', trans('common.deletedSuccesfully'));
            
              return 1;

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
				$header = array(trans('common.firstName'),trans('common.lastName'), trans('common.initials'), trans('common.email'), trans('common.telephoneNumber'), trans('common.gender'), trans('common.nameCompany'), trans('common.premium'), trans('common.status'), trans('common.dateOfBirth'),'Eigendom in het bedrijf', trans('common.company_id'), trans('common.relationToFamily'),trans('common.generation'), 'Leiding in het bedrijf', 'Zeggenschap in het bedrijf');
	
				// Write header to csv
				fputcsv($fp, $header);
               
				foreach ($result['open_quotation'] as $row) 
				{ 
					$gebrnm_kl 	= $row->gebrnm_kl;
					$naam_kl 	= $row->naam_kl;
					$vl_kl 	= $row->vl_kl;
					$email_kl	= $row->email_kl;
					$tel_kl = $row->tel_kl;
					$sexe_kl = $row->sexe_kl;
					$company = $row->company;
					$premium_kl = $row->premium_kl;
					$status = $row->status;
					$id_rm  = $row->dateOfBirth;
		          $nm_bd  = $row->owner;
		          $relnr_bd   = $row->company_id;
		          $ondvorm_bd   = $row->relation_family;
		          $generation = $row->generation;
		          $vl_rm = $row->leader;
		          $nm_rm  = $row->say;

					// Creating array of values and writing it on csv
					$one_row = [$gebrnm_kl, $naam_kl, $vl_kl,   $email_kl, $tel_kl, $sexe_kl, $company, $premium_kl, $status, $id_rm, $nm_bd, $relnr_bd, $ondvorm_bd, $generation, $vl_rm, $nm_rm];
					fputcsv($fp, $one_row);
				}

				// creating filename for csv
				$file_name = trans('common.clients').'fm'.date("Y-m-d");

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
          return Redirect::to('cms/client_fm');
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
		return view('Cms/Client_fm/clientListImport_fm',$data);
	}

	public function forceDownload()
    {    
        $root = 'sample_csv/Client_fm.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'Client_fm.csv';
       
        header('Content-Type: application/csv');
      	header('Content-Disposition: attachment; filename=Client_fm.csv');
		header('Pragma: no-cache');
		readfile("sample_csv/Client_fm.csv");
       
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
			    return Redirect::to('cms/client_fm/import');
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
						if(count($row) !=16)
						{
							Session::flash('error', trans('common.fileFormatInvalid'));  
							return Redirect::to('cms/client_fm/import');
						}
                         
                         if($row[0] != trans('common.username') && $row[1] != trans('common.lastname') && $row[2] != trans('common.initial') && $row[3] != trans('common.email') && $row[4] != trans('common.gender') && $row[5] != trans('common.telephoneNumber') && $row[6] != trans('common.nameCompany') && $row[7] != trans('common.premium')  && $row[8] != trans('common.password') && $row[9] != trans('common.dateOfBirth') && $row[10] != 'Eigendom in het bedrijf' && $row[11] != trans('common.workInCompany') && $row[12] != trans('common.relationToFamily') && $row[13] != trans('common.generation') && $row[14] != 'Leiding in het bedrijf' && $row[15] != 'Zeggenschap in het bedrijf')
                         {
							Session::flash('error', trans('common.fileFormatInvalid')); 
							return Redirect::to('cms/client_fm/import');
                         }	
					}	
                     $ww++;
	                
	                   $gebrnm_kl = $row[0];
	                   $naam_kl = $row[1];
	                   $vl_kl = $row[2];
	                   $email_kl = $row[3];
	                   $sexe_kl = $row[4];
	                   $tel_kl = $row[5];
	                   $id_bd = $row[6];
	                   $premium_kl = $row[7];
	                   $status = 'Actief';
	                   $password = $row[8];
	                   $dateOfBirth = $row[9];
                     $owner = $row[10];
                     $company_id = $row[11];
                     $relation_family = $row[12];
                     $generation = $row[13];
                     $leader = $row[14];
                     $say = $row[15];
                      $id_bd1 = '';
                       $rd = 0;
                      if($id_bd && $id_bd != trans('common.nameCompany'))
                       {
                            
                       		$id_bd1 = DB::table('bedrijf_fm')
					 					->select('id_bd')
										->where('nm_bd', $id_bd)
		                                ->first();

		                    $id_bd1 = $id_bd1; 
                            
		                    if(!$id_bd1)
		                    { 
		                    	$rd = 1; 
                             	$error_message .= '<li>'.$row[0].' | '.$row[1].' | '.$row[2].' | '.$row[5].' | Validation Error: Bedrijfsnaam is onjuist.  </li>';
		                    } else{$id_bd1 = $id_bd1->id_bd;}
                       }

	                   $one_row = array(              
			                				'gebrnm_kl' 	=> $gebrnm_kl,          				
											'naam_kl'		=> $naam_kl,
											'vl_kl'			=> $vl_kl,
											'email_kl' 		=> $email_kl,
											'sexe_kl'		=> $sexe_kl,
											'tel_kl'		=> $tel_kl,
											'id_bd'         => $id_bd1,
											'premium_kl'	=> $premium_kl,
											'status'        => $status,
											'pw_kl'        => $password,
										  'dateOfBirth'      => $dateOfBirth,                 
					                      'owner'   => $owner,
					                      'company_id'     => $company_id,
					                      'relation_family'    => $relation_family,
					                      'generation'    => $generation,
					                      'leader'    => $leader,
					                      'say'       => $say,
										);

		                // pushing one_row array to file_data_array
		                array_push($file_data_array, $one_row);

	                
											
				}
                 
				if($rd == 0)
				{

					foreach ($file_data_array as $key => $row) 
		            {
			        	$save_data = array();
			        	if($counter > 1 && $row['gebrnm_kl'] != '' 
			        					&& $row['naam_kl'] != '' 
			        					&& $row['vl_kl'] != '' 
			        					&& $row['email_kl'] != '' 
			        					&& $row['sexe_kl'] != '' 
			        					&& $row['tel_kl'] != '' 
			        					&& $row['id_bd'] != ''
			        					&& $row['premium_kl'] != '' 
			        					&& $row['status'] != '' 
			        					&& $row['pw_kl'] != ''
			        					&& $row['dateOfBirth'] != '' 
				                        && $row['owner'] != '' 
				                        && $row['company_id'] != '' 
				                        && $row['relation_family'] != '' 
				                        && $row['generation'] != '' 
				                        && $row['leader'] != '' 
				                        && $row['say'] != '')
			        	{ 

						$blanckArray = array('gebrnm_kl'=>'',
							'naam_kl'=>'',
							'vl_kl'=>'',
							'email_kl'=>'',
							'sexe_kl'=>'',
							'tel_kl'=>'',
							'id_bd'=>'',
							'premium_kl'=>'',
							'status'=>'',
							'pw_kl'=>'',
							'dateOfBirth'=>'',
				              'owner'=>'',
				              'company_id'=>'',
				              'relation_family'=>'',
				              'generation'=>'',
				              'leader'=>'',
				              'say'=>'');
						$post = (array_merge($blanckArray,$row));				
						$rules = array('gebrnm_kl' => 'required|max:40', 
							'naam_kl' =>   'required|max:40',    
							'pw_kl' => 'required|min:6',
							'email_kl' => 'required|email|max:40',
							'sexe_kl' => 'required',
							'dateOfBirth' => 'required', 
				              'owner' =>   'required', 
				              'company_id' =>   'required',   
				              'relation_family' => 'required',
				              'generation' => 'required',
				              'leader' => 'required',
				              'say' => 'required');

						$error = 0;
						$validator = Validator::make($post, $rules);
							if ($validator->fails()) {
							$messages = $validator->messages();
							$error_message .= '<li>'.$row['gebrnm_kl'].' | '.$row['naam_kl'].' | '.$row['email_kl'].' | '.$row['status'].' | Validation Error '.$messages.' </li>';
							$error = 1;
							}
							else
							{
		                     

				       			$count = DB::table('klant_fm')
						 					->select('id_kl')
											->where('email_kl', $row['email_kl'])
			                                ->count();

								if($count == 0)
								{   
									$dob = explode('/', $row['dateOfBirth']);
									$dateOfBirth = $dob[1].'/'.$dob[0].'/'.$dob[2]; 
									
				            		$save_data['gebrnm_kl']  = $row['gebrnm_kl'];
									$save_data['naam_kl']    = $row['naam_kl'];
									$save_data['vl_kl'] 	 = $row['vl_kl'];
									$save_data['email_kl']	 = $row['email_kl'];
									$save_data['pw_kl'] 	 = $row['pw_kl'];
									$save_data['sexe_kl'] 	 = strtolower($row['sexe_kl']);
									$save_data['tel_kl']     = $row['tel_kl'];
									$save_data['id_bd'] 	 = $row['id_bd'];
									$save_data['premium_kl'] = $row['premium_kl'];
									$save_data['status'] 	 = strtolower($row['status']);
									$save_data['dateOfBirth']  = $dateOfBirth;
					                  $save_data['owner']  = $row['owner'];
					                  $save_data['company_id']   = $row['company_id'];
					                  $save_data['relation_family']  = $row['relation_family'];
					                  $save_data['generation']  = $row['generation'];
					                  $save_data['leader']  = $row['leader'];
					                  $save_data['say']  = $row['say'];
									$save_data['created_at'] = $this->createdAt;
									
									
										
									//inserting data into klant table
									$insertId =  DB::table('klant_fm')->insertGetId($save_data);

									//creating list of successfully inserted records
									$success_message .= '<li>'.$row['gebrnm_kl'].' | '.$row['naam_kl'].' | '.$row['email_kl'].' | '.$row['status'].'</li>';
								}
								else
								{
									//creating list of records that is not inserted
									if($error == 0){
									$error_message .= '<li>'.$row['gebrnm_kl'].' | '.$row['naam_kl'].' | '.$row['email_kl'].' | '.$row['status'].'</li>';
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
				return Redirect::to('cms/client_fm/import');
			}	
			else
			{     
				Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
			    return Redirect::to('cms/client_fm/import');
			}
	
		}
    }
	
}
