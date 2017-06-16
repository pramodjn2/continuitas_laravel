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


class NewsController extends Controller
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
      $this->title = trans('common.news');  
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
		$data['dashboard'] = trans('common.news');
		$data['results'] = $this->show(); 
        
		return view('Cms/News/news',$data); 
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
		
        return view('Cms/News/newsAdd',$data); 
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
        if($post)
        {
            $target_dir = config('app.uploads_path').'News/';
            $attachmentName = '';
            $foto_nw = '';
            $foto2_nw = '';
            
            $blanckArray = array('kop_nw'=>'',
                    'kort_nw'=>'',
                    'hfd_nw'=>'',
                    'foto_nw'=>'',
                    'foto2_nw'=>'',
                    'dat_nw'=>'',
                    'stat_nw'=>'');
            $post = (array_merge($blanckArray,$post));               
            $rules = array('kop_nw' => 'required|max:40', 
                    'kort_nw' =>   'required',    
                    'hfd_nw' => 'required');
            $validator = Validator::make($post, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages();
                Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
                return Redirect::to('cms/news/add')->withErrors($validator)->withInput();
            }


            if(Session::has('fotoVierkant')) 
            {
                $foto_nw =  "thumb_".Session::get('fotoVierkant'); 
            }
             
            if(Session::has('fotoBreed')) 
            {
               $foto2_nw =  "thumb_".Session::get('fotoBreed'); 
            }  
            $hfd_nw = str_ireplace("<p>","",$post['hfd_nw']);
            $hfd_nw = str_ireplace("</p>","",$hfd_nw);        
            $data = array('kop_nw' => $post['kop_nw'],
                    'kort_nw' => $post['kort_nw'],
                    'hfd_nw' => $hfd_nw,  
                    'foto_nw' => $foto_nw,
                    'foto2_nw' => $foto2_nw,
                    'dat-nw' => $post['dat_nw'],
                    'stat-nw' => strtolower($post['stat_nw'])
            ); 
            $insertId =  DB::table('nieuws')->insertGetId($data);
            $request->session()->forget('fotoVierkant');
            $request->session()->forget('fotoBreed');
            Session::flash('success', trans('common.userCreateSucessfully')); 
            return Redirect::to('cms/news')->withInput();
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
        $sql = DB::table('nieuws')
        ->select('id_nw', 'kop_nw', 'kort_nw', 'hfd_nw', 'foto_nw', 'foto2_nw', 'dat-nw as dat_nw', 'stat-nw as stat_nw')
        ->get();
        return  $sql;
    }
 

    public function crop_image(Request $request)
    {  
        $post = $request->all();
        
        $file_formats = array("jpg","jpeg", "png", "gif", "bmp");

        $filepath = config('app.uploads_path').'News/';
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
              $imagename = "vierkant_".md5(uniqid() . time()) . "." . $extension;
              $tmp = $_FILES['imagefile']['tmp_name'];
                if (move_uploaded_file($tmp, $filepath.$imagename)) {
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
    public function crop_image2(Request $request)
    {  
        $post = $request->all();
        
        $file_formats = array("jpg","jpeg", "png", "gif", "bmp");

        $filepath = config('app.uploads_path').'News/';
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
              $imagename = "breed_".md5(uniqid() . time()) . "." . $extension;
              $tmp = $_FILES['imagefile']['tmp_name'];
                if(move_uploaded_file($tmp, $filepath.$imagename)) {
                  
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
        $upload_path = config('app.uploads_path').'News/';
        $thumb_width = "500";           
        $thumb_height = "500";
         $post = $request->all();

        if (isset($post["x1"])) {

            $filename = $post['filename'];

            $large_image_location = $upload_path.$post['filename'];
            $thumb_image_location = $upload_path."thumb_".$post['filename'];

            $x1 = $post["x1"];
            $y1 = $post["y1"];
            $x2 = $post["x2"];
            $y2 = $post["y2"];
            $w = $post["w"]=500;
            $h = $post["h"]=500;
            
            $scale = $thumb_width/$w;
            $cropped = $this->resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
            Session::put('fotoVierkant', $filename);
           return $filename;
        }

    } 
    
    public function save_thumbnail2(Request $request)
    {      
        $upload_path = config('app.uploads_path').'News/';
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
            Session::put('fotoBreed', $filename);
           return $filename;
        }

    } 

    public function getById($id)
    {
     
       if(empty($id)){
         Session::flash('error', trans('common.pleaseSendNewsNumber')); 
         return Redirect::to('cms/news/add')->withInput();
        }
       
       $where = array('u.id_nw' => $id);
       $sql = DB::table('nieuws as u')
        ->select('id_nw', 'kop_nw', 'kort_nw', 'hfd_nw', 'foto_nw', 'foto2_nw', 'dat-nw as dat_nw', 'stat-nw as stat_nw')
         ->where($where)
         ->groupBy('u.id_nw')
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
        
         
        $data['results'] = $this->getById($id);
        
        
        $data['title'] = $this->title;
        $data['className'] = $this->className;
        return view('Cms/News/newsEdit',$data); 
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
        return view('Cms/News/newsView',$data); 
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
        
        if($post) 
        {
            $target_dir = config('app.uploads_path').'News/';
            $attachmentName = '';
            $foto_nw = '';
            $foto2_nw = '';
            $foto_nw_hd = '';
            $foto2_nw_hd = '';
            
            $blanckArray = array('kop_nw'=>'',
                    'kort_nw'=>'',
                    'hfd_nw'=>'',
                    'foto_nw'=>'',
                    'foto2_nw'=>'',
                    'dat_nw'=>'',
                    'stat_nw'=>'');
            $post = (array_merge($blanckArray,$post));               
            $rules = array('kop_nw' => 'required|max:40', 
                    'kort_nw' =>   'required',    
                    'hfd_nw' => 'required');
            $validator = Validator::make($post, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages();
                Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
                return Redirect::to('cms/news/add')->withErrors($validator)->withInput();
            }

            
            if(Session::has('fotoVierkant')) 
            {
               echo "1".$foto_nw =  "thumb_".Session::get('fotoVierkant'); 
            }
             
            if(Session::has('fotoBreed')) 
            {
              echo "2".$foto2_nw =  "thumb_".Session::get('fotoBreed'); 
            }  
            
            $hfd_nw = str_ireplace("<p>","",$post['hfd_nw']);
            $hfd_nw = str_ireplace("</p>","",$hfd_nw); 
            $id = $post['id_nw'];        
            $data = array('kop_nw' => $post['kop_nw'],
                    'kort_nw' => $post['kort_nw'],
                    'hfd_nw' => $hfd_nw,  
                    'dat-nw' => $post['dat_nw'],
                    'stat-nw' => strtolower($post['stat_nw'])
            );
 
            if($foto_nw != '') 
            {
                $data1 = array('foto_nw' => $foto_nw); 
            }
            else{ if(isset($post['foto_nw_hd'])){$foto_nw_hd = $post['foto_nw_hd']; } $data1 = array('foto_nw' => $foto_nw_hd);  } 
            if($foto2_nw != '') 
            {
                $data2 = array('foto2_nw' => $foto2_nw);         
            }
            else{ if(isset($post['foto2_nw_hd'])){$foto2_nw_hd = $post['foto2_nw_hd'];  } $data2 = array('foto2_nw' => $foto2_nw_hd); } 

            $data = array_merge($data,$data1,$data2);   
            $getProductId =  DB::table('nieuws')->where('id_nw', $id)->update($data);
            $request->session()->forget('fotoVierkant');
            $request->session()->forget('fotoBreed');
            Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
            return Redirect::to('cms/news')->withInput();
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
          return Redirect::to('cms/news');
				
		}
		 
		/*nieuws')->where('id_nw', $id)->update($data);*/
		
		$sql = DB::table('nieuws')
            ->select('*')
			->where('id_nw',$id)
            ->get();
		
			
		if(!empty($sql)){
			$deleted_at = date("Y-m-d H:i:s");
			$data = array('stat-nw' => 'unPublish');
			
			DB::table('nieuws')->where('id_nw', $id)->delete(); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/news');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/news');
		
		
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
          return Redirect::to('cms/news');
                
        }
        
    
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unPublish');
            
            DB::table('nieuws')->whereIn('id_nw', $post['ids'])->delete(); 
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
                $header = array(trans('common.header'),trans('common.textShort'), trans('common.textLong'), trans('common.datum'), trans('common.status'));
    
                // Write header to csv
                fputcsv($fp, $header);
               
                foreach ($result['open_quotation'] as $row) 
                {
                    $kop_nw  = $row->kop_nw;
                    $kort_nw  = $row->kort_nw;
                    $hfd_nw   = $row->hfd_nw;
                    $dat_nw  = $row->dat_nw;
                    $stat_nw = $row->stat_nw;
            

                    // Creating array of values and writing it on csv
                    $one_row = [$kop_nw, $kort_nw, $hfd_nw, $dat_nw, $stat_nw];
                    fputcsv($fp, $one_row);
                }

                // creating filename for csv
                $file_name = 'nieuws '.date("Y-m-d");

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
          return Redirect::to('cms/news');
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
        return view('Cms/News/newsListImport',$data);
    }

    public function forceDownload()
    {    
        $root = 'sample_csv/News.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'News.csv';
       
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Nieuws.csv');
        header('Pragma: no-cache');
        readfile("sample_csv/News.csv");
       
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
                         
                         if($row[0] !=trans('common.header') && $row[1] != trans('common.textShort') && $row[2] != trans('common.textLong') && $row[3] != trans('common.datum') && $row[4] != trans('common.status'))
                         {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/news/import');
                         }  
                    }   
                     $ww++;
                    
                       $kop_nw = $row[0];
                       $kort_nw = $row[1];
                       $hfd_nw = $row[2];
                       $dat_nw = $row[3];
                       $stat_nw = $row[4];
                  
                       $one_row = array(              
                                            'kop_nw'          => $kop_nw,                       
                                            'kort_nw'       => $kort_nw,
                                            'hfd_nw'         => $hfd_nw,
                                            'dat_nw'        => $dat_nw,
                                            'stat_nw'      => $stat_nw,
                                            
                                        );

                        // pushing one_row array to file_data_array
                        array_push($file_data_array, $one_row);
                     
                }
                    foreach ($file_data_array as $key => $row) 
                    {
                        $save_data = array();
                        if($counter > 1 && $row['kop_nw'] != '' 
                                        && $row['kort_nw'] != '' 
                                        && $row['hfd_nw'] != '' 
                                        && $row['dat_nw'] != '' 
                                        && $row['stat_nw'] != '')
                        { 

                         $count1 = DB::table('nieuws')
                      ->select('id_nw')
                      ->where('kop_nw', $row['kop_nw'])
                      ->where('kort_nw', $row['kort_nw'])
                      ->where('hfd_nw', $row['hfd_nw'])
                      ->where('dat-nw', $row['dat_nw'])
                      ->where('stat-nw', strtolower($row['stat_nw']))
                                      ->count();  
                  if($count1>0)
                  { 
                    $error_message .= '<li>'.$row['kop_nw'].' | '.$row['kort_nw'].' | '.$row['hfd_nw'].' | '.$row['dat_nw'].' Dubbele gegevens niet toegestaan</li>';
                  }  else{

                        $blanckArray = array('kop_nw'=>'',
                            'kort_nw'=>'',
                            'hfd_nw'=>'',
                            'dat_nw'=>'',
                            'stat_nw'=>'');
                        $post = (array_merge($blanckArray,$row));               
                        $rules = array('kop_nw' => 'required', 
                            'kort_nw' =>   'required',    
                            'hfd_nw' => 'required',
                            'dat_nw' => 'required',
                            'stat_nw' => 'required');

                        $error = 0;
                        $validator = Validator::make($post, $rules);
                            if ($validator->fails()) {
                            $messages = $validator->messages();
                            $error_message .= '<li>'.$row['kop_nw'].' | '.$row['kort_nw'].' | '.$row['hfd_nw'].' | '.$row['dat_nw'].' | Validation Error '.$messages.' </li>';
                            $error = 1;
                            }
                            else
                            {
                                    $save_data['kop_nw']  = $row['kop_nw'];
                                    $save_data['kort_nw']  = $row['kort_nw'];
                                    $save_data['hfd_nw']     = $row['hfd_nw'];
                                    $save_data['dat-nw']    = $row['dat_nw'];
                                    $save_data['stat-nw']  = strtolower($row['stat_nw']);                                 
                    
                                    //inserting data into account table
                                    $insertId =  DB::table('nieuws')->insertGetId($save_data);

                                    //creating list of successfully inserted records
                                    $success_message .= '<li>'.$row['kop_nw'].' | '.$row['kort_nw'].' | '.$row['hfd_nw'].' | '.$row['dat_nw'].'</li>';
                                
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
                    Session::flash('error', '<big>'.trans('common.newsAlreadyExist').'</big><br/>'.$error_message);
                }

                // Settting success message into the session variables //   
                if($success_message != "<ol></ol>")
                {   
                    Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
                }
                return Redirect::to('cms/news/import');
            }   
            else
            {     
                Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
                return Redirect::to('cms/news/import');
            }
    
        }
    }
}
