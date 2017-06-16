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

class EventController extends Controller
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
      $this->title = trans('common.event');
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
		$data['dashboard'] = trans('common.event').' '.trans('common.dashboard');
		
		$data['results'] = $this->show();
		return view('Cms/Event/event',$data); 
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
		$data['dashboard'] = trans('common.event').' '.trans('common.dashboard');
		$CommonController = new CommonController;
        $data['results'] = '';
		$data['userStatus'] = $CommonController->userStatus();
		
        return view('Cms/Event/eventAdd',$data); 
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
        
        if($post)
        {
            $target_dir = config('app.uploads_path').'Event/';
            $attachmentName = '';
            $foto_ev = '';
            $foto2_ev = '';
            
            $blanckArray = array('kop_ev'=>'',
                    'kort_ev'=>'',
                    'hfd_ev'=>'',
                    'foto_ev'=>'',
                    'foto2_ev'=>'',
                    'dat_ev'=>'',
                    'stat_ev'=>'',
                    'start_ev' => '',
                    'eind_ev' => '');
            $post = (array_merge($blanckArray,$post));               
            $rules = array('kop_ev' => 'required|max:40', 
                    'kort_ev' =>   'required',    
                    'hfd_ev' => 'required');
            $validator = Validator::make($post, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages();
                Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
                return Redirect::to('cms/event/add')->withErrors($validator)->withInput();
            }

            
            if(Session::has('Event_fotoVierkant')) 
            {
                $foto_ev =  "thumb_".Session::get('Event_fotoVierkant'); 
            }
             
            if(Session::has('Event_fotoBreed')) 
            {
               $foto2_ev =  "thumb_".Session::get('Event_fotoBreed'); 
            }  
            $hfd_ev = str_ireplace("<p>","",$post['hfd_ev']);
            $hfd_ev = str_ireplace("</p>","",$hfd_ev);        
            $data = array('kop_ev' => $post['kop_ev'],
                    'kort_ev' => $post['kort_ev'],
                    'hfd_ev' => $hfd_ev,  
                    'foto_ev' => $foto_ev,
                    'foto2_ev' => $foto2_ev,
                    'dat_ev' => $post['dat_ev'],
                    'stat_ev' => strtolower($post['stat_ev']),
                    'start_ev' => $post['start_ev'],
                    'eind_ev' => $post['eind_ev']
            );
            $insertId =  DB::table('event')->insertGetId($data);
            $request->session()->forget('Event_fotoVierkant');
            $request->session()->forget('Event_fotoBreed');
            Session::flash('success', trans('common.userCreateSucessfully')); 
            return Redirect::to('cms/event')->withInput();
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
        $sql = DB::table('event')
        ->select('id_ev', 'kop_ev', 'kort_ev', 'hfd_ev', 'foto_ev', 'foto2_ev', 'dat_ev', 'stat_ev','start_ev','eind_ev')
        ->get();
        return  $sql;
    }
    public function crop_image(Request $request)
    {  
        $post = $request->all();
        
        $file_formats = array("jpg","jpeg", "png", "gif", "bmp");

        $filepath = config('app.uploads_path').'Event/';
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
    public function crop_image2(Request $request)
    {  
        $post = $request->all();
        
        $file_formats = array("jpg","jpeg", "png", "gif", "bmp");

        $filepath = config('app.uploads_path').'Event/';
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
        $upload_path = config('app.uploads_path').'Event/';
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
            Session::put('Event_fotoVierkant', $filename);
           return $filename;
        }

    }   
    
    public function save_thumbnail2(Request $request)
    {    
        $upload_path = config('app.uploads_path').'Event/';
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
            Session::put('Event_fotoBreed', $filename);
           return $filename;
        }

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
        return view('Cms/Event/eventEdit',$data); 
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
        return view('Cms/Event/eventView',$data); 
    }


    public function getById($id)
    {
     
       if(empty($id)){
         Session::flash('error', trans('common.pleaseSendEventNumber')); 
         return Redirect::to('cms/event/add')->withInput();
        }
       
       $where = array('id_ev' => $id);
       $sql = DB::table('event')
        ->select('id_ev', 'kop_ev', 'kort_ev', 'hfd_ev', 'foto_ev', 'foto2_ev', 'dat_ev', 'stat_ev','start_ev','eind_ev')
         ->where($where)
         ->groupBy('id_ev')
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
        if($post) 
        {
			
			
            $target_dir = config('app.event_path');
            $attachmentName = '';
            $foto_ev = '';
            $foto2_ev = '';
            $foto_ev_hd = '';
            $foto2_ev_hd = '';
            
            $blanckArray = array('kop_ev'=>'',
                    'kort_ev'=>'',
                    'hfd_ev'=>'',
                    'foto_ev'=>'',
                    'foto2_ev'=>'',
                    'dat_ev'=>'',
                    'stat_ev'=>'',
                    'start_ev' => '',
                    'eind_ev' => '');
            $post = (array_merge($blanckArray,$post));               
            $rules = array('kop_ev' => 'required|max:40', 
                    'kort_ev' =>   'required',    
                    'hfd_ev' => 'required');
            $validator = Validator::make($post, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages();
                Session::flash('error', trans('common.pleaseFillMandatoryFields')); 
                return Redirect::to('cms/event/add')->withErrors($validator)->withInput();
            }

            
            if(Session::has('Event_fotoVierkant')) 
            {
                $foto_ev =  "thumb_".Session::get('Event_fotoVierkant'); 
            }
             
            if(Session::has('Event_fotoBreed')) 
            {
               $foto2_ev =  "thumb_".Session::get('Event_fotoBreed'); 
            }  
            $hfd_ev = str_ireplace("<p>","",$post['hfd_ev']);
            $hfd_ev = str_ireplace("</p>","",$hfd_ev);    
            $id = $post['id_ev'];        
            $data = array('kop_ev' => $post['kop_ev'],
                    'kort_ev' => $post['kort_ev'],
                    'hfd_ev' => $hfd_ev,  
                    'dat_ev' => $post['dat_ev'],
                    'stat_ev' => strtolower($post['stat_ev']),
                    'start_ev' => $post['start_ev'],
                    'eind_ev' => $post['eind_ev']
                    );


            if($foto_ev != '')
            {
              $data1 = array('foto_ev' => $foto_ev); 
              $data = array_merge($data,$data1);   
            }else{  if(isset($post['foto_ev_hd'])){$foto_ev_hd = $post['foto_ev_hd'];  }$data1 = array('foto_ev' => $foto_ev_hd);  }
            
            
			if($foto2_ev != '')
            {
                $data2 = array('foto2_ev' => $foto2_ev); 
				$data = array_merge($data,$data2);         
            }else{ if(isset($post['foto2_ev_hd'])){$foto2_ev_hd = $post['foto2_ev_hd']; }$data2 = array('foto2_ev' => $foto2_ev_hd); }
            
			//dd($data);
          // $data = array_merge($data,$data1,$data2);   
           
		    $getProductId =  DB::table('event')->where('id_ev', $id)->update($data);
        $request->session()->forget('Event_fotoVierkant');
            $request->session()->forget('Event_fotoBreed');
            Session::flash('success', trans('common.userInfomationUpdateSucessfully')); 
            return Redirect::to('cms/event')->withInput();
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
          return Redirect::to('cms/event');
				
		}

		$sql = DB::table('event')
            ->select('*')
			->where('id_ev',$id)
            ->get();
		
			
		if(!empty($sql)){
			$data = array('stat_ev' => 'unPublish');
			
			DB::table('event')->where('id_ev', $id)->delete(); 
			Session::flash('success', trans('common.deletedSuccesfully')); 
			
			  return Redirect::to('cms/event');
		}
		Session::flash('error', trans('common.invalidToken')); 
        return Redirect::to('cms/event');
		
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
          return Redirect::to('cms/event');
                
        }
        
    
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unPublish');
            
            DB::table('event')->whereIn('id_ev', $post['ids'])->delete(); 
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
                $header = array(trans('common.header'),trans('common.textShort'), trans('common.textLong'), trans('common.datum'), trans('common.startTime'), trans('common.endTime'), trans('common.status'));
    
                // Write header to csv
                fputcsv($fp, $header);
               
                foreach ($result['open_quotation'] as $row) 
                {
                    $kop_ev    = $row->kop_ev;
                    $kort_ev   = $row->kort_ev;
                    $hfd_ev    = $row->hfd_ev;
                    $dat_ev    = $row->dat_ev;
                    $startTime = $row->start_ev;
                    $endTime   = $row->eind_ev;
                    $stat_ev   = $row->stat_ev;
                    

                    // Creating array of values and writing it on csv
                    $one_row = [$kop_ev, $kort_ev, $hfd_ev, $dat_ev, $startTime, $endTime, $stat_ev];
                    fputcsv($fp, $one_row);
                }

                // creating filename for csv
                $file_name = 'Evenement '.date("Y-m-d");

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
          return Redirect::to('cms/event');
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
        return view('Cms/Event/eventListImport',$data);
    }

    public function forceDownload()
    {    
        $root = 'sample_csv/Event.csv';
        $data = file_get_contents($root); // Read the file's contents
        $name = 'Event.csv';
       
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Evenement.csv');
        header('Pragma: no-cache');
        readfile("sample_csv/Event.csv");
       
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
                        if(count($row) != 7)
                        {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/event/import');
                        }
                         
                         if($row[0] !=trans('common.header') && $row[1] != trans('common.textShort') && $row[2] != trans('common.textLong') && $row[3] != trans('common.datum') && $row[4] != trans('common.Starttijd') && $row[5] != trans('common.endTime') && $row[6] != trans('common.Fase'))
                         {
                            Session::flash('error', trans('common.fileFormatInvalid')); 
                            return Redirect::to('cms/event/import');
                         }  
                    }   
                     $ww++;
                    
                       $kop_ev = $row[0];
                       $kort_ev = $row[1];
                       $hfd_ev = $row[2];
                       $dat_ev = $row[3];
                       $start_ev = $row[4];
                       $eind_ev = $row[5];
                       $stat_ev = $row[6];
                  
                       $one_row = array(              
                                            'kop_ev'       => $kop_ev,                       
                                            'kort_ev'      => $kort_ev,
                                            'hfd_ev'       => $hfd_ev,
                                            'dat_ev'       => $dat_ev,
                                            'stat_ev'      => $stat_ev,
                                            'start_ev'     => $start_ev,
                                            'eind_ev'      => $eind_ev,
                                            
                                        );

                        // pushing one_row array to file_data_array
                        array_push($file_data_array, $one_row);
                     
                } 
                    foreach ($file_data_array as $key => $row) 
                    {
                        $save_data = array();
                        if($counter > 1 && $row['kop_ev'] != '' 
                                        && $row['kort_ev'] != '' 
                                        && $row['hfd_ev'] != '' 
                                        && $row['dat_ev'] != '' 
                                        && $row['stat_ev'] != ''
                                        && $row['start_ev'] != '' 
                                        && $row['eind_ev'] != '')
                        { 

                      $count1 = DB::table('event')
                      ->select('id_ev')
                      ->where('kop_ev', $row['kop_ev'])
                      ->where('kort_ev', $row['kort_ev'])
                      ->where('hfd_ev', $row['hfd_ev'])
                      ->where('dat_ev', $row['dat_ev'])
                      ->where('stat_ev', strtolower($row['stat_ev']))
                      ->where('start_ev', $row['start_ev'])
                      ->where('eind_ev', $row['eind_ev'])
                      ->count();  
                  if($count1>0)
                  { 
                    $error_message .= '<li>'.$row['kop_ev'].' | '.$row['kort_ev'].' | '.$row['dat_ev'].' Dubbele gegevens niet toegestaan</li>';
                  }else{
                        $blanckArray = array('kop_ev'=>'',
                            'kort_ev'=>'',
                            'hfd_ev'=>'',
                            'dat_ev'=>'',
                            'stat_ev'=>'',
                            'start_ev'=>'',
                            'eind_ev'=>'');
                        $post = (array_merge($blanckArray,$row));               
                        $rules = array('kop_ev' => 'required', 
                            'kort_ev' =>   'required',    
                            'hfd_ev' => 'required',
                            'dat_ev' => 'required',
                            'stat_ev' => 'required',);

                        $error = 0;
                        $validator = Validator::make($post, $rules);
                            if ($validator->fails()) {
                            $messages = $validator->messages();
                            $error_message .= '<li>'.$row['kop_ev'].' | '.$row['kort_ev'].' | '.$row['hfd_ev'].' | '.$row['dat_ev'].' | Validation Error '.$messages.' </li>';
                            $error = 1;
                            }
                            else
                            {
                                    $save_data['kop_ev']  = $row['kop_ev'];
                                    $save_data['kort_ev']  = $row['kort_ev'];
                                    $save_data['hfd_ev']     = $row['hfd_ev'];
                                    $save_data['dat_ev']    = $row['dat_ev'];
                                    $save_data['stat_ev']  = $row['stat_ev'];
                                    $save_data['start_ev']    = $row['start_ev'];
                                    $save_data['eind_ev']  = $row['eind_ev'];                                 
                    
                                    //inserting data into account table
                                    $insertId =  DB::table('event')->insertGetId($save_data);

                                    //creating list of successfully inserted records
                                    $success_message .= '<li>'.$row['kop_ev'].' | '.$row['kort_ev'].' | '.$row['hfd_ev'].' | '.$row['dat_ev'].'</li>';
                                
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
                    Session::flash('error', '<big>'.trans('common.eventAlreadyExist').'</big><br/>'.$error_message);
                }

                // Settting success message into the session variables //   
                if($success_message != "<ol></ol>")
                {   
                    Session::flash('success', '<big>'.trans('common.userCreateSucessfully').'</big><br/>'.$success_message);
                }
                return Redirect::to('cms/event/import');
            }   
            else
            {     
                Session::flash('error', trans('common.pleaseUploadOnlyCSVFile')); 
                return Redirect::to('cms/event/import');
            }
    
        }
    }
}
