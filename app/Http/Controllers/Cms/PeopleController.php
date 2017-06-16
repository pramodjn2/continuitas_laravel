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

class PeopleController extends Controller
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
      $this->title = trans('common.ourPeople');
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
		$data['dashboard'] = trans('common.ourPeople');
		$data['results'] = $this->show();
		
		return view('Cms/People/people',$data); 
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
		$data['peopleDirectie'] = $CommonController->peopleDirectie();
		
        return view('Cms/People/peopleAdd',$data); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	$CommonController = new CommonController;
      $post = $request->all();
      
	  if($post){
	 
	 $target_dir = config('app.people_path'); 
	 $attachmentName = '';
	 // if($_FILES["avatar_logo"]["name"]){
		// $target_file = $target_dir . basename($_FILES["avatar_logo"]["name"]);
		// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// @move_uploaded_file($_FILES["avatar_logo"]["tmp_name"], $target_file);
		// $attachmentName = basename($_FILES["avatar_logo"]["name"]); /* file name */
	 // }
	 	if(Session::has('ourPeople')) 
            {
                $attachmentName =   "thumb_".Session::get('ourPeople'); 
            }
	 		
	
	   $blanckArray = array('email_mens'=>'',
						   'naam_mens'=>'',
						   'tel_mens'=>'',
						   'tekst_mens'=>'',
						   'status'=>'Active');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('email_mens' => 'required|email|max:40', 
	   				  'naam_mens' =>   'required|max:40',    
                      'tel_mens' => 'required',
					  'tekst_mens' => 'required');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/people/add')->withErrors($validator)->withInput();
		}

		        if(!isset($post['naam_mens']))
		        {
		        	$post['naam_mens'] = '';
		        }	
		        if(!isset($post['tekst_mens']))
		        {
		        	$post['tekst_mens'] = '';
		        } 
		        if(!isset($post['tel_mens']))
		        {
		        	$post['tel_mens'] = '';
		        }
		        if(!isset($post['email_mens']))
		        {
		        	$post['email_mens'] = '';
		        }
		        if(!isset($post['Directie']))
		        {
		        	$post['Directie'] = '';
		        }
		        if(!isset($post['Relatiebeheer']))
		        {
		        	$post['Relatiebeheer'] = '';
		        }
		        if(!isset($post['Samenstel']))
		        {
		        	$post['Samenstel'] = '';
		        }
		        if(!isset($post['Audit']))
		        {
		        	$post['Audit'] = '';
		        }
		        if(!isset($post['Ondersteuning']))
		        {
		        	$post['Ondersteuning'] = '';
		        }
		        if(!isset($post['Fiscaal']))
		        {
		        	$post['Fiscaal'] = '';
		        }
		        if(!isset($post['HRM']))
		        {
		        	$post['HRM'] = '';
		        }

		        $tekst_mens = str_ireplace("<p>","",$post['tekst_mens']);
		        $tekst_mens = str_ireplace("</p>","",$tekst_mens);
	            $data = array('naam_mens' => $post['naam_mens'],
			                  'tel_mens' => $post['tel_mens'],
							  'email_mens' => $post['email_mens'],  
							  'directie_mens' => $post['Directie'],
							  'relatiebeheer_mens' => $post['Relatiebeheer'],
							  'samenstel_mens' => $post['Samenstel'],
							  'audit_mens' => $post['Audit'], 
							  'ondersteuning_mens' => $post['Ondersteuning'],
							  'fiscaal_mens' => $post['Fiscaal'], 
							  'hrm_mens' => $post['HRM'],
							  'tekst_mens' => $tekst_mens, 
							  'status' => strtolower($post['status']), 
							   );
							  
			 	if(!empty($attachmentName)){
			
		$data1 = array('foto_mens' => @$attachmentName);
		   $data = (array_merge($data,$data1));
		}
			  
						
		$insertId =  DB::table('onze_mensen')->insertGetId($data);
		
		$request->session()->forget('ourPeople');
				
		Session::flash('success', trans('common.userCreateSucessfully')); 
        return Redirect::to('cms/people')->withInput();
			
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
	   $sql = DB::table('onze_mensen')
	   ->select('id_mens','naam_mens','tel_mens','email_mens','tekst_mens','foto_mens','status','directie_mens', 'relatiebeheer_mens', 'ondersteuning_mens', 'fiscaal_mens', 'hrm_mens', 'samenstel_mens', 'audit_mens')
		->get();

		return  $sql;
    }


    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendPeopleNumber')); 
		 return Redirect::to('cms/people/add')->withInput();
		}
       
	   $where = array('id_mens' => $id);
	   
	   $sql = DB::table('onze_mensen')
		 ->select('id_mens','naam_mens','tel_mens','email_mens','tekst_mens','foto_mens','directie_mens','relatiebeheer_mens','ondersteuning_mens','fiscaal_mens','hrm_mens','samenstel_mens','audit_mens','status')
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
		$data['peopleDirectie'] = $CommonController->peopleDirectie();
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
       return view('Cms/People/peopleEdit',$data); 
    }

    public function crop_image(Request $request)
    {  
        $post = $request->all();
       
        $file_formats = array("jpg","jpeg", "png", "gif", "bmp");

        $filepath = config('app.people_path');
        $preview_width = "400";
        $preview_height = "300";

      if(isset($_FILES['imagefile']))
      {  
        if ($_FILES['imagefile']) {

         $name = $_FILES['imagefile']['name']; // filename to get file's extension
         $size = $_FILES['imagefile']['size'];

         if (strlen($name)) {
          $extension = substr($name, strrpos($name, '.')+1);
          if (in_array($extension, $file_formats)) { // check it if it's a valid format or not
            if ($size < (2048 * 1024)) { // check it if it's bigger than 2 mb or no
              $imagename = "People_".md5(uniqid() . time()) . "." . $extension;
              $tmp = $_FILES['imagefile']['tmp_name'];
                if (move_uploaded_file($tmp, $filepath . $imagename)) {
                  return $imagename;
                } else {
                  return "Could not move the file";
                }
            } else {
              return "Your image size is bigger than 2MB";
            }
          } else {
              return "Invalid file format";
          }
         } else {
          return "Please select image!";
         }
         exit();
        }
         
      }
    }

    public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        switch($imageType) {
          case "image/gif":
            $source=imagecreatefromgif($image); 
            break;
            case "image/pjpeg":
          case "image/jpeg":
          case "image/jpg":
            $source=imagecreatefromjpeg($image); 
            break;
            case "image/png":
          case "image/x-png":
            $source=imagecreatefrompng($image); 
            break;
          }
        imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
        switch($imageType) {
          case "image/gif":
              imagegif($newImage,$thumb_image_name); 
            break;
              case "image/pjpeg":
          case "image/jpeg":
          case "image/jpg":
              imagejpeg($newImage,$thumb_image_name,100); 
            break;
          case "image/png":
          case "image/x-png":
            imagepng($newImage,$thumb_image_name);  
            break;
          }
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }
    public function save_thumbnail(Request $request)
    {    
        $upload_path = config('app.people_path');
        $thumb_width = "150";           
        $thumb_height = "150";
         $post = $request->all();

        if (isset($post["x1"])) { 

            $filename = $post['filename'];

            $large_image_location = $upload_path.$post['filename'];
            $thumb_image_location = $upload_path."thumb_".$post['filename'];

            $x1 = $post["x1"];
            $y1 = $post["y1"];
            $x2 = $post["x2"];
            $y2 = $post["y2"];
            $w = $post["w"];
            $h = $post["h"];
            
            $scale = $thumb_width/$w;
            $cropped = $this->resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
            Session::put('ourPeople', $filename);
           return $filename;
        }

    } 

     /**
     * Show the page for view the specified resource.
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
       return view('Cms/People/peopleView',$data); 
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
		$CommonController = new CommonController;
      $post = $request->all();
	  if($post){
	  

      $target_dir = config('app.people_path');
	 $attachmentName = '';
	 // if($_FILES["avatar_logo"]["name"]){
		// $target_file = $target_dir . basename($_FILES["avatar_logo"]["name"]);
		// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// @move_uploaded_file($_FILES["avatar_logo"]["tmp_name"], $target_file);
		// $attachmentName = basename($_FILES["avatar_logo"]["name"]); /* file name */
	 // }
	 
	 if(Session::has('ourPeople')) 
            {
                $attachmentName =   "thumb_".Session::get('ourPeople'); 
            }
	  $blanckArray = array('email_mens'=>'',
						   'naam_mens'=>'',
						   'tel_mens'=>'',
						   'tekst_mens'=>'');
	   $post = (array_merge($blanckArray,$post));				
       $rules = array('email_mens' => 'required|email|max:40', 
	   				  'naam_mens' =>   'required|max:40',    
                      'tel_mens' => 'required|max:40',
					  'tekst_mens' => 'required|max:500');

        $id = $post['id_mens'];
    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		  $messages = $validator->messages();
		  Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
		  return Redirect::to('cms/people/edit/'.$id)->withErrors($validator)->withInput();
		}
		 
		   if(!isset($post['naam_mens']))
		        {
		        	$post['naam_mens'] = '';
		        }	
		        if(!isset($post['tekst_mens']))
		        {
		        	$post['tekst_mens'] = '';
		        }
		        if(!isset($post['tel_mens']))
		        {
		        	$post['tel_mens'] = '';
		        }
		        if(!isset($post['email_mens']))
		        {
		        	$post['email_mens'] = '';
		        }
		        if(!isset($post['Directie']))
		        {
		        	$post['Directie'] = '';
		        }
		        if(!isset($post['Relatiebeheer']))
		        {
		        	$post['Relatiebeheer'] = '';
		        }
		        if(!isset($post['Samenstel']))
		        {
		        	$post['Samenstel'] = '';
		        }
		        if(!isset($post['Audit']))
		        {
		        	$post['Audit'] = '';
		        }
		        if(!isset($post['Ondersteuning']))
		        {
		        	$post['Ondersteuning'] = '';
		        }
		        if(!isset($post['Fiscaal']))
		        {
		        	$post['Fiscaal'] = '';
		        }
		        if(!isset($post['HRM']))
		        {
		        	$post['HRM'] = '';
		        }

		        $tekst_mens = str_ireplace("<p>","",$post['tekst_mens']);
		        $tekst_mens = str_ireplace("</p>","",$tekst_mens);
	            $data = array('naam_mens' => $post['naam_mens'],
			                  'tel_mens' => $post['tel_mens'],
							  'email_mens' => $post['email_mens'],  
							  'directie_mens' => $post['Directie'],
							  'relatiebeheer_mens' => $post['Relatiebeheer'],
							  'samenstel_mens' => $post['Samenstel'],
							  'audit_mens' => $post['Audit'], 
							  'ondersteuning_mens' => $post['Ondersteuning'],
							  'fiscaal_mens' => $post['Fiscaal'], 
							  'hrm_mens' => $post['HRM'],
							  'tekst_mens' => $tekst_mens,  
							  'status' => strtolower($post['status']),
							   );
							   
				
		if(!empty($attachmentName)){
			
			$sql = DB::table('onze_mensen')
            ->select('*')
			->where('id_mens',$id)
            ->first();
			$avatar = $sql->foto_mens;
			if(!empty($avatar)){
			 @unlink($target_dir.'\\'.$avatar);	
			}
			
			$url = url('ourPeople/');
	        $avatar = $CommonController->imageCheck($attachmentName,$url);
	       
	   
		   $data1 = array('foto_mens' => @$attachmentName);
		   $data = (array_merge($data,$data1));
		}
		
							  
							  
	    $getProductId =  DB::table('onze_mensen')->where('id_mens', $id)->update($data);
		Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
        return Redirect::to('cms/people');
		}
		 return Redirect::to('cms/people');
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
          return Redirect::to('cms/people');
				
		}
		
		$sql = DB::table('onze_mensen')
            ->select('*')
			->where('id_mens',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('status' => 'Inactive');
			
			DB::table('onze_mensen')->where('id_mens', $id)->delete(); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/people');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/people');
		
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
          return Redirect::to('cms/people');
                
        }
        
     
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unPublish');
            
            DB::table('onze_mensen')->whereIn('id_mens', $post['ids'])->delete(); 
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
				$header = array(trans('common.name'),trans('common.telephoneNumber'), trans('common.email'), trans('common.status'),trans('common.tekst'), "Directie", "Relatiebeheer", "Ondersteuning", "Fiscaal", "HRM", "Samenstel", "Audit");
	 
				// Write header to csv
				fputcsv($fp, $header);
               
				foreach ($result['open_quotation'] as $row) 
				{
					$naam_mens 	= $row->naam_mens;
					$tel_mens 	= $row->tel_mens;
					$email_mens 	= $row->email_mens;
					$tekst_mens 	= $row->tekst_mens;
					$status 	= $row->status;
					$directie_mens	= $row->directie_mens;
					$relatiebeheer_mens = $row->relatiebeheer_mens;
					$ondersteuning_mens 	= $row->ondersteuning_mens;
					$fiscaal_mens 	= $row->fiscaal_mens;
					$hrm_mens	= $row->hrm_mens;
					$samenstel_mens 	= $row->samenstel_mens;
					$audit_mens	= $row->audit_mens;

					// Creating array of values and writing it on csv
					$one_row = [$naam_mens, $tel_mens, $email_mens,  $status, $tekst_mens, $directie_mens, $relatiebeheer_mens, $ondersteuning_mens, $fiscaal_mens, $hrm_mens, $samenstel_mens, $audit_mens];
					fputcsv($fp, $one_row);
				}

				// creating filename for csv
				$file_name = 'Mensen '.date("Y-m-d");

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
          return Redirect::to('cms/people');
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
        return view('Cms/People/peopleListImport',$data);
    }

    public function forceDownload()
    {    
        $root = 'sample_csv/People.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'People.csv';
       
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=People.csv');
        header('Pragma: no-cache');
        readfile("sample_csv/People.csv");
       
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
                return Redirect::to('cms/people/import');
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
                    	 if(count($row) !=12)
						{
							Session::flash('error', trans('common.fileFormatInvalid')); 
							return Redirect::to('cms/people/import');
						}
                         
                         if($row[0] !=trans('common.name') && $row[1] != trans('common.telephoneNumber') && $row[2] != trans('common.email') && $row[3] != trans('common.status') && $row[4] != trans('common.tekst') && $row[5] != "Directie" && $row[6] != "Relatiebeheer" && $row[7] != "Ondersteuning" && $row[8] != "Fiscaal" && $row[9] != "HRM" && $row[10] != "Samenstel" && $row[11] != "Audit")
                         {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/people/import');
                         }  
                    }   
                     $ww++;
                   
                       $naam_mens = $row[0];
                       $tel_mens = $row[1];
                       $email_mens = $row[2];
                       $tekst_mens = $row[3];
                       $status = $row[4];
                       $directie_mens = $row[5];
                       $relatiebeheer_mens = $row[6];
                       $ondersteuning_mens = $row[7];
                       $fiscaal_mens = $row[8];
                       $hrm_mens = $row[9];
                       $samenstel_mens = $row[10];
                       $audit_mens = $row[11];
                  
                       $one_row = array(              
                                            'naam_mens'          => $naam_mens,                       
                                            'tel_mens'       => $tel_mens,
                                            'email_mens'         => $email_mens,
                                            'tekst_mens'        => $tekst_mens,
                                            'status'      => $status,
                                            'directie_mens'          => $directie_mens,
                                            'relatiebeheer_mens'          => $relatiebeheer_mens,                       
                                            'ondersteuning_mens'       => $ondersteuning_mens,
                                            'fiscaal_mens'         => $fiscaal_mens,
                                            'hrm_mens'        => $hrm_mens,
                                            'samenstel_mens'      => $samenstel_mens,
                                            'audit_mens'        => $audit_mens,
                                           
                                            
                                            
                                        );

                        // pushing one_row array to file_data_array
                        array_push($file_data_array, $one_row);
                     
                }
                    foreach ($file_data_array as $key => $row) 
                    {  
                        $save_data = array();
                        if($counter > 1 && $row['naam_mens'] != '' 
                                        && $row['tel_mens'] != '' 
                                        && $row['email_mens'] != '' 
                                        && $row['tekst_mens'] != '' 
                                        && $row['status'] != ''
                                        )
                        { 

                        $count1 = DB::table('onze_mensen')
						 					->select('id_mens')
											->where('naam_mens', $row['naam_mens'])
											->where('tel_mens', $row['tel_mens'])
											->where('email_mens', $row['email_mens'])
											->where('tekst_mens', $row['tekst_mens'])
											->where('status', strtolower($row['status']))
			                                ->count();	
			            if($count1>0)
			            { 
			            	$error_message .= '<li>'.$row['naam_mens'].' | '.$row['tel_mens'].' | '.$row['email_mens'].' | '.$row['tekst_mens'].' Dubbele gegevens niet toegestaan</li>';
			            } else{

                        $blanckArray = array('naam_mens'=>'',
                            'tel_mens'=>'',
                            'email_mens'=>'',
                            'tekst_mens'=>'',
                            'status'=>'',
                            'directie_mens'=>'',
                            'relatiebeheer_mens'=>'',
                            'ondersteuning_mens'=>'',
                            'fiscaal_mens'=>'',
                            'hrm_mens'=>'',
                            'samenstel_mens'=>'',
                            'audit_mens'=>'');
                        $post = (array_merge($blanckArray,$row));               
                        $rules = array('naam_mens' => 'required', 
                            'tel_mens' =>   'required',    
                            'email_mens' => 'required',
                            'tekst_mens' => 'required'
                            ); 

                        $error = 0;
                        $validator = Validator::make($post, $rules);
                            if ($validator->fails()) {
                            $messages = $validator->messages();
                            $error_message .= '<li>'.$row['naam_mens'].' | '.$row['tel_mens'].' | '.$row['email_mens'].' | Validation Error '.$messages.' </li>';
                            $error = 1;
                            }
                            else
                            {
                                    $save_data['naam_mens']  = $row['naam_mens'];
                                    $save_data['tel_mens']  = $row['tel_mens'];
                                    $save_data['email_mens']     = $row['email_mens'];
                                    $save_data['tekst_mens']    = $row['tekst_mens'];
                                    $save_data['status']  = strtolower($row['status']);
                                    $save_data['directie_mens']  = $row['directie_mens'];
                                    $save_data['relatiebeheer_mens']  = $row['relatiebeheer_mens'];
                                    $save_data['ondersteuning_mens']     = $row['ondersteuning_mens'];
                                    $save_data['fiscaal_mens']    = $row['fiscaal_mens'];
                                    $save_data['hrm_mens']  = $row['hrm_mens'];
                                    $save_data['samenstel_mens']    = $row['samenstel_mens'];
                                    $save_data['audit_mens']  = $row['audit_mens'];                                 
                    
                                    //inserting data into account table
                                    $insertId =  DB::table('onze_mensen')->insertGetId($save_data);

                                    //creating list of successfully inserted records
                                    $success_message .= '<li>'.$row['naam_mens'].' | '.$row['tel_mens'].' | '.$row['email_mens'].' </li>';
                                
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
                    Session::flash('error', '<big>'.trans('common.peopleAlreadyExist').'</big><br/>'.$error_message);
                }

                // Settting success message into the session variables //   
                if($success_message != "<ol></ol>")
                {   
                    Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
                }
                return Redirect::to('cms/people/import');
            }   
            else
            {     
                Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
                return Redirect::to('cms/people/import');
            }
    
        }
    }
	
}
