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

class FamilyController extends Controller
{
    
	public $title = '';
	public $result = '';
	public $createdAt = '';
	public $className = '';
	public $methodName = '';
	public $news = '';
	
	public function __construct(Route $route) 
    {
	  $this->createdAt = date("Y-m-d H:i:s");
      $CommonController = new CommonController; 
	  $url = $route->uri();
	  $url = @explode('/',$url);
	  $this->className = @$url['1'];
	  $this->methodName = @$url['2'];
	  $this->permision = $CommonController->userPermission($this->className);
    $this->title = trans('common.familiestatuut');
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
		$data['dashboard'] = trans('common.familiestatuut');
		$data['results'] = $this->show();
		
		return view('Cms/Family/family',$data); 
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
		
		$CommonController = new CommonController;
		$data['userStatus'] = $CommonController->userStatus();
		$data['company'] = $CommonController->company();
        $data['premium'] = $CommonController->premium();
        return view('Cms/Family/familyAdd',$data); 
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
      
       $blanckArray = array('dateOfBirth'=>'',
                        'owner'=>'',
                        'company_id'=>'',
                        'relation_family'=>'',
                        'generation'=>'',
                        'leader'=>'',
                        'say'=>'');
       $post = (array_merge($blanckArray,$post));               
       $rules = array('owner' => 'required|max:40', 
                      'company_id' =>   'required|max:40',    
                      'relation_family' => 'required',
                      'generation' => 'required',
                      'leader' => 'required',
                      'say' => 'required'
                      );

        $validator = Validator::make($post, $rules);
        if ($validator->fails()) {
         $messages = $validator->messages();
          Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
          return Redirect::to('cms/family/add')->withErrors($validator)->withInput();
        }
        
            
            
    
        $data = array('dateOfBirth' => $post['dateOfBirth'],
                              'company_id' => $post['company_id'],
                               'owner' => $post['owner'],
                               'relation_family' => $post['relation_family'],
                               'generation' => $post['generation'],
                               'leader' => $post['leader'],
                               'say' => $post['say'],
                               'created_at' => $this->createdAt
                              );
                              
                      
                        
        $insertId =  DB::table('familiestatuut')->insertGetId($data);
        
        Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/family')->withInput();
            
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
         $sql = DB::table('familiestatuut')
         ->select('id','dateOfBirth', 'company_id','owner','relation_family','generation','leader','say','created_at')
        ->get();
        return  $sql;
         return  json_encode($sql);
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
        return view('Cms/Family/familyEdit',$data); 
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
        return view('Cms/Family/familyView',$data); 
    }

    public function getById($id)
    {
     
       if(empty($id)){
         Session::flash('error', trans('common.pleaseSendFamilyNumber')); 
         return Redirect::to('cms/family/add')->withInput();
        }
       
       $where = array('c.id' => $id);
       
        $sql = DB::table('familiestatuut as c')
        ->select('id', 'dateOfBirth', 'owner', 'company_id', 'relation_family', 'generation', 'leader', 'say', 'created_at')
        ->where($where)
        ->first();
        
        return  $sql;
         
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
      
       $blanckArray = array('dateOfBirth'=>'',
                        'owner'=>'',
                        'company_id'=>'',
                        'relation_family'=>'',
                        'generation'=>'',
                        'leader'=>'',
                        'say'=>'');
       $post = (array_merge($blanckArray,$post));               
       $rules = array('owner' => 'required|max:40', 
                      'company_id' =>   'required|max:40',    
                      'relation_family' => 'required',
                      'generation' => 'required',
                      'leader' => 'required',
                      'say' => 'required'
                      );
        $id = $post['id'];
        $validator = Validator::make($post, $rules);
        if ($validator->fails()) {
         $messages = $validator->messages();
          Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
          return Redirect::to('cms/family/edit'.$id)->withErrors($validator)->withInput();
        }
        
            
            
    
        $data = array('dateOfBirth' => $post['dateOfBirth'],
                              'company_id' => $post['company_id'],
                               'owner' => $post['owner'],
                               'relation_family' => $post['relation_family'],
                               'generation' => $post['generation'],
                               'leader' => $post['leader'],
                               'say' => $post['say'],
                               'created_at' => $this->createdAt
                              );
                              
                      
                        
        $insertId =  DB::table('familiestatuut')->where('id', $id)->update($data);
        
        Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/family')->withInput();
            
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
          return Redirect::to('cms/family');
				
		}
		
		$sql = DB::table('familiestatuut')
            ->select('*')
			->where('id',$id)
            ->get();
		
			
		if(!empty($sql)){
			
			
			DB::table('familiestatuut')->where('id', $id)->delete(); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			return Redirect::to('cms/family');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/family');
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
          return Redirect::to('cms/family');
                
        }
        
    
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unPublish');
            
            DB::table('familiestatuut')->whereIn('id', $post['ids'])->delete(); 
             Session::flash('success', trans('common.deletedSuccesfully'));
            
              return 1;
       
        Session::flash('error', trans('common.invalidToken')); 
        return 0; 
        
        
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
        $header = array(trans('common.dateOfBirth'),trans('common.owner'), trans('common.workInCompany'), trans('common.relationToFamily'),trans('common.generation'), trans('common.leader'), trans('common.say'));
  
        // Write header to csv
        fputcsv($fp, $header);
                
        foreach ($result['open_quotation'] as $row) 
        {
          $id_rm  = $row->dateOfBirth;
          $nm_bd  = $row->owner;
          $relnr_bd   = $row->company_id;
          $ondvorm_bd   = $row->relation_family;
          $generation = $row->generation;
          $vl_rm = $row->leader;
          $nm_rm  = $row->say;
          

          // Creating array of values and writing it on csv
          $one_row = [$id_rm, $nm_bd, $relnr_bd, $ondvorm_bd, $generation, $vl_rm, $nm_rm];
          fputcsv($fp, $one_row);
        }

        // creating filename for csv
        $file_name = 'FamilyRelation '.date("Y-m-d");

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
          return Redirect::to('cms/family');
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
    return view('Cms/Family/familyListImport',$data);
  } 
 
  public function forceDownload()
    {    
        $root = 'sample_csv/FamilyRelation.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'FamilyRelation.csv';
       
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=FamilyRelation.csv');
    header('Pragma: no-cache');
    readfile("sample_csv/FamilyRelation.csv");
       
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
          return Redirect::to('cms/family/import');
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
                         

             if($row[0] != trans('common.dateOfBirth') && $row[1] != trans('common.owner') && $row[2] != trans('common.workInCompany') && $row[3] != trans('common.relation_family') && $row[4] != trans('common.generation') && $row[5] != trans('common.leader') && $row[6] != trans('common.say'))
             {
                Session::flash('error', trans('common.fileFormatInvalid')); 
                return Redirect::to('cms/family/import');
             }  
          } 
                     $ww++;
                  
                     $dateOfBirth = $row[0];
                     $owner = $row[1];
                     $company_id = $row[2];
                     $relation_family = $row[3];
                     $generation = $row[4];
                     $leader = $row[5];
                     $say = $row[6];
                      // $Role_id = 8;
                       $role_id = '';
                       $rd = 0;
                      
                   
                     $one_row = array(              
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
                if($counter > 1 && $row['dateOfBirth'] != '' 
                        && $row['owner'] != '' 
                        && $row['company_id'] != '' 
                        && $row['relation_family'] != '' 
                        && $row['generation'] != '' 
                        && $row['leader'] != '' 
                        && $row['say'] != '')
                { 

             $count1 = DB::table('familiestatuut')
                      ->select('id')
                      ->where('dateOfBirth', $row['dateOfBirth'])
                      ->where('company_id', $row['company_id'])
                      ->where('relation_family', $row['relation_family'])
                      ->where('generation', $row['generation'])
                      ->where('leader', $row['leader'])
                      ->where('say', $row['say'])
                      ->count();  
                  if($count1>0)
                  { 
                    $error_message .= '<li>'.$row['dateOfBirth'].' | '.$row['company_id'].' | '.$row['relation_family'].' | '.$row['generation'].' Dubbele gegevens niet toegestaan</li>';
                  } else{     

            $blanckArray = array('dateOfBirth'=>'',
              'owner'=>'',
              'company_id'=>'',
              'relation_family'=>'',
              'generation'=>'',
              'leader'=>'',
              'say'=>'');
            $post = (array_merge($blanckArray,$row));       
            $rules = array('dateOfBirth' => 'required', 
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
              $error_message .= '<li>'.$row['owner'].' | '.$row['dateOfBirth'].' | '.$row['company_id'].' | Validation Error '.$messages.' </li>';
              $error = 1;
              }
              else
              {
                  $save_data['dateOfBirth']  = $row['dateOfBirth'];
                  $save_data['owner']  = $row['owner'];
                  $save_data['company_id']   = $row['company_id'];
                  $save_data['relation_family']  = $row['relation_family'];
                  $save_data['generation']  = $row['generation'];
                  $save_data['leader']  = $row['leader'];
                  $save_data['say']  = $row['say'];
                  $save_data['created_at'] = $this->createdAt;
                  
                  
          
                  //inserting data into account table
                  $insertId =  DB::table('familiestatuut')->insertGetId($save_data);

                  //creating list of successfully inserted records
                  $success_message .= '<li>'.$row['owner'].' | '.$row['dateOfBirth'].' | '.$row['company_id'].'</li>';
                
              }

               }
                }
                
                $counter++;                 
                }
            }

        $error_message  .= "</ol>";
        $success_message .= "</ol>";
        
        fclose($file);

        if($error_message != "<ol></ol>")
        {       
          Session::flash('error', '<big>'.trans('common.familyAlreadyExist').'</big><br/>'.$error_message);
        }

        // Settting success message into the session variables //   
        if($success_message != "<ol></ol>")
        {   
          Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
        }
        return Redirect::to('cms/family/import');
      } 
      else
      {     
        Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
          return Redirect::to('cms/family/import');
      }
  
    }
    }
}
