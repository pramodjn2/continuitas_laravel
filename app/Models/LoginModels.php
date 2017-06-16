<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use Hash;



class LoginModels extends Model
{
   // protected $fillable = ['user_id','name', 'email', 'lastLoginDate'];
	protected $table = 'users';	
	protected $db;
	
   public function __construct(){
		$this->db = DB::table('users');
		}
	
   public function validate_user($post){	
	   $this->db->where('email', $post['email']);
	   /*$this->db->where('status', 'Active');*/
		$result = $this->db->first();
		if($result){
			if ( Hash::check($post['password'], $result->password)) {
			    
				
				if($result->status != 'Actief'){
			      return 2;
			    }
			
				$this->set_session($result);
				return true;
			}
		}else{
		  return false;	
		}
		 return false;	
    }
	
   protected function set_session($userData){
	   
	   session()->regenerate();
	   $where = array('r.user_id' => $userData->id);
	   $sql = DB::table('role_user as r')
		      ->select('r.role_id')
		      ->where($where)
		      ->first();
	   if(!empty($sql)){
		 Session::put('role_id', $sql->role_id);  
	   }
		 
	   Session::put('id', $userData->id);
	   Session::put('email', $userData->email);
	   Session::put('name', ucwords($userData->name));
	   Session::put('surName', $userData->surname);
	   Session::put('fullName', ucwords($userData->name.' '.$userData->surname));
	  
	   $url = url('uploads/');
	   $avatar = $this->imageCheck($userData->avatar,$url);
	   Session::put('avtar', $avatar);
	   
	    //$date = array('lastLoginDate'=> date('Y-m-d H:i:s'));
		//$post = $this->db->where('user_id', $userData[0]->user_id)->update($date);
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
	
	 public function validate_email($post){	
	   $this->db->where('email', $post['email']);
	   $result = $this->db->first();
		if($result){
				return $result;
		}else{
		  return false;	
		}
		 return false;	
    }
  
}
