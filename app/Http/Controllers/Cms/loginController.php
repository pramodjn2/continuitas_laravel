<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\LoginModels; 
use DB;
use Validator;
use Session;
use Redirect;
use Hash;
use Auth;

use Illuminate\Support\Facades\Mail;



class loginController extends Controller
{
    
	public $title = 'Admin';
	public $createdAt = '';
	public function __construct()
    {
    $this->createdAt = date("Y-m-d H:i:s");
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
	   $data['title'] = $this->title = trans('common.welcomeToLogin');
	   return view('Cms/login',$data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
		$login_models = new LoginModels;
        $post = $request->all();
		if($post){
			
       $rules = array('email' => 'required|email|max:40',    
                      'password' => 'required|min:6');

    	$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
		 $messages = $validator->messages();
		  return Redirect::to('cms')->withErrors($validator)->withInput();
		}
	         
			$loginreturn = $login_models->validate_user($post);
			
			if($loginreturn) { 
				if($loginreturn === 2){
					Session::flash('error', trans('common.yourAccountIsInactive')); 
                    return Redirect::to('cms');
				}
  
				elseif($loginreturn === 3){ 
					Session::flash('error', trans('common.thisRoleInactive')); 
                    return Redirect::to('cms');
				}
				
				return Redirect::to('cms/dashboard');	
			} else {
				// Otherwise show the login screen with an error message.
				Session::flash('error', trans('common.incorrectEmailPsw')); 
                return Redirect::to('cms');
			}	
		}
		$data['title'] = $this->title = trans('common.welcomeToLogin');
	   return view('Cms/login',$data); 
    }
 

     public function forgetPassword(Request $request){
		$login_models = new LoginModels;
		$post = $request->all();
		
		if($post){
			$data['result'] = $login_models->validate_email($post);
			
	    if($data['result']) {
			
			$random_number = mt_rand();
			$update = array('remember_token' => $random_number);
			DB::table('users')->where('id', $data['result']->id)->update($update); 
			$email = $data['result']->email;
			$msg =  '<h2> '.trans('common.welcomeToLogin').ucwords($data['result']->name).',</h2><br/>';
			$msg .= '<p>'.trans('common.weGotRequestToResetYourPassword').'</p>';
			$msg .= '<a href="'.url('cms/emailverify?verifycode='.$random_number).'"><button class="red_button" type="button">'.trans('common.resetPassword').' </button></a>';
			$msg .= '<p></p>';
			$msg .= "<p>".trans('common.ifYouIgnoreThisMessage')."</p>";
			
			$msg .= '<br/><br/>';
			$msg .= '<p>'.trans('common.thanks').' </p>';
			$msg .= '<p>'.trans('common.laapheerTeam').'</p>';
			
			$subject = trans('common.resetPassword');
			$headers = "From: laapheer@trustedaccountantapp.nl" . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			mail($email,$subject,$msg,$headers);
			Session::flash('success', trans('common.checkYourMail')); 
            return Redirect::to('cms');
		}else{
		    Session::flash('error', trans('common.noAccountWithThis')); 
            return Redirect::to('cms');
		}
	
    }
	  Session::flash('error', trans('common.incorrectUrl')); 
      return Redirect::to('cms');
		
	}
	
	public function emailverify(Request $request){
		 $data['title'] = 'Email login';
	  	   $post = $request->all();
		  
		   $data['verifycode'] =  $post['verifycode'];
		   if(empty($data['verifycode'])){
			 Session::flash('error', 'Incorrect url'); 
              return Redirect::to('cms');   
		   }
		   
		   $sql = DB::table('users')
            ->select('*')
			->where('remember_token',$post['verifycode'])
            ->first();
		 
		 if ( @$post['password']) {
		
			$data = array('password' => bcrypt($post['password']),
			 			 'remember_token' => '',
						  'updated_at' => $this->createdAt);
			$getProductId =  DB::table('users')->where('remember_token', $post['verifycode'])->update($data);
			
			Session::flash('success', trans('common.passwordChanged')); 
			return Redirect::to('cms');
		 }
		
		
		  /* if(@$post['password']){
			   
			   $password = $post['password'];
			    $confirmPassword = $post['confirmPassword'];
			 }*/
		   
		   return view('Cms/emailverify',$data);  
	}
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function logout(){
		session()->flush();
		Session::flash('success', trans('common.logoutSucessfully')); 
        return Redirect::to('cms'); //redirect back to login
	}
}
