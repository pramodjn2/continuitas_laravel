<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;
use Auth;
use Session;
use Redirect;
use Illuminate\Routing\Route;

class CommonController extends Controller
{

/**
Image Resize
**/
function resize_images($path,$rs_width,$rs_height,$destinationFolder='') {

 $folder_path = dirname($path);
 $thumb_folder = $destinationFolder;
 $percent = 0.5;
 
 if (!is_dir($thumb_folder)) {

    mkdir($thumb_folder, 0777, true);
 }
 
 $name = basename($path);

 $x = getimagesize($path);            

 $width  = $x['0'];
 
 $height = $x['1'];

 switch ($x['mime']){

              case "image/gif":

                 $img = imagecreatefromgif($path);

                 break;

              case "image/jpeg":

                 $img = imagecreatefromjpeg($path);

                 break;
			 case "image/jpg":

                 $img = imagecreatefromjpeg($path);

                 break;

              case "image/png":

                 $img = imagecreatefrompng($path);

                 break;

  }

    $img_base = imagecreatetruecolor($rs_width, $rs_height);

    $white = imagecolorallocate($img_base, 255, 255, 255);

    imagefill($img_base, 0, 0, $white);
    
	imagecopyresized($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);

    imagecopyresampled($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);

    $path_info = pathinfo($path);   

    $dest = $thumb_folder.$name;

           switch ($path_info['extension']) {

              case "gif":

                 imagegif($img_base, $dest);  

                 break;

              case "jpg":

                 return imagejpeg($img_base, $dest);  

                 break;
			  case "jpeg":

                 return imagejpeg($img_base, $dest);  

                 break;

              case "png":

                return imagepng($img_base, $dest);  

                 break;

           }
}



/**
   IMAGE CHECK
**/

public function imageCheck($image, $url){
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
  get front user permission 
*/
function userPermission($class){
	
	$role_id = session('role_id');
	 if(empty($class)) 
	    return false;
		
		 if(empty($role_id)) 
	    return false;
	 
	 $class =  trim(strtolower($class));
	 $role_id = trim(session('role_id'));
	
	    $sql = DB::table('cms_permissions as p')
		->leftJoin('permissions_class as c', 'c.id', '=', 'p.class_id')
		->leftJoin('permissions_actions as a', 'a.id', '=', 'p.action_id')
		->select('a.action_name')
	    ->where("c.class_name", $class)
		->where("p.role_id", $role_id)
		->get();
		return $sql; 
	}  
	
/**
 check function permission
*/
function checkFunctionPermission($array, $className = NULL, $methodName){
	if(empty($array))
		return false;
	
	$methodName = trim(strtolower($methodName));
	
	foreach($array as $val){
		if($val->action_name == $methodName){
			return true;
		}
	}
	 $className = $className ? $className : 'dashboard';   
	$className = 'dashboard';   
	Session::flash('error', 'You do not have permission to access this page!.'); 
	return false;
	//return Redirect::to('cms/'.$className);
} 
	
	
/**
    * user status
    * @param  {[type]} $id [string selected id]
    * @return {[type]}       [array]
*/
public function userStatus(){
	//$id = '';
	$sql = array(array('id'=>'Actief',		'name'=>'Actief'),
				 array('id'=>'Inactief',	'name'=>'Inactief'),
				 );
		return $sql;
}
/* end */



   public function gender(){
	$sql = array(array('id'=>'heer',		'name'=>'Heer'),
				 array('id'=>'mevrouw',		'name'=>'Mevrouw'),
				 );
	return $sql;
   }
   
    public function premium(){
	$sql = array(array('id'=>'ja',		'name'=>'ja'),
				 array('id'=>'nee',		'name'=>'Nee')
				 );
	return $sql;
   }
   
   
   /* kind of company */
    public function kindOfCompany(){
	$sql = array(array('id'=>'BV',				'name'=>'BV'),
				 array('id'=>'Eenmanszaak',		'name'=>'Eenmanszaak'),
				 array('id'=>'VOF',				'name'=>'VOF'),
				 array('id'=>'PersonalHolding', 'name'=>'Personal Holding'),
				 array('id'=>'Stichting',		'name'=>'Stichting'),
				 array('id'=>'Overig',			'name'=>'Overig'),
				 );
	return $sql;
   }
   
   
  /* user group */
   public function userGroup(){
   	
   	if(Session::has('role_id')) 
    {
       $role_id =  Session::get('role_id'); 
    }

	$sql = DB::table('roles')
	->where('id','>=',$role_id)
	->get();
	return $sql;
   }
  
  /* list of company */
   public function company(){
	$sql = DB::table('bedrijf')->get();
	return $sql;
   }

   /* list of company */
   public function company_fm(){
	$sql = DB::table('bedrijf_fm')->get();
	return $sql;
   }
   
   /* relation manager */
    public function relationshipManager(){
	$sql = DB::table('relatiemanager')->get();
	return $sql;
   }
   
    /* relation manager */
    public function relationshipManager_fm(){
	$sql = DB::table('relatiemanager_fm')->get();
	return $sql;
   } 
   
   /* permissions class */
   public function permissionsClass(){
	$sql = DB::table('permissions_class')->get();
	return $sql;
   }
   
    
   /* permissions actions */
   public function permissionsActions(){
	$sql = DB::table('permissions_actions')->get();
	return $sql;
   }
  
  
     /* permissions  */
   public function cmsPermissions($id){
	 
	 $where = array('role_id' => $id);
	$sql = DB::table('cms_permissions')->where($where)->get();
	return $sql;
   }
   
    /* kind of company */
    public function peopleDirectie(){
	$sql = array(array('id'=>'Directie',		'name'=>'Directie'),
				 array('id'=>'Relatiebeheer',	'name'=>'Relatiebeheer'),
				 array('id'=>'Samenstel',		'name'=>'Samenstel'),
				 array('id'=>'Audit', 			'name'=>'Audit'),
				 array('id'=>'Ondersteuning',	'name'=>'Ondersteuning'),
				 array('id'=>'Fiscaal',			'name'=>'Fiscaal'),
				  array('id'=>'HRM',			'name'=>'HRM'),
				 );
	return $sql;
   }
/* Year list */
    public function getYear(){
	$sql = array(array('id'=>2016,		'name'=>2016),
				 array('id'=>2017,	    'name'=>2017),
				 array('id'=>2018,		'name'=>2018),
				 array('id'=>2019, 		'name'=>2019),
				 array('id'=>2020,	    'name'=>2020),
				 array('id'=>2021,		'name'=>2021),
				  array('id'=>2022,		'name'=>2022),
				 );
	return $sql;
   }

}