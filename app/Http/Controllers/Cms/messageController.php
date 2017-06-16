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
 
class MessageController extends Controller
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
      $this->title = trans('common.message');
    }
	
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	
		// $CommonController = new CommonController;
		// $access = $CommonController->checkFunctionPermission($this->permision,$this->className,'read');
		// if(empty($access)){
		// 	Session::flash('error', 'You do not have permission to access this page!.'); 
	 //        return Redirect::to('cms/dashboard');
		// }
		 
		
		$data['className'] = $this->className;
        $data['title'] = $this->title;
		$data['dashboard'] = trans('common.message').' '.trans('common.dashboard');
		
		$data['results'] = $this->show();
		return view('Cms/Message/message',$data); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
         $sql = DB::table('apns_messages as r')
         ->leftJoin('klant as ur', 'ur.id_kl', '=', 'r.clientid')
		 ->select('r.pid','r.clientid', 'r.fk_device','r.subject','r.message','r.delivery','r.status','r.created','r.modified','ur.naam_kl','ur.email_kl','ur.gebrnm_kl','r.read')
		->get();
     	
		return  $sql;
		 return  json_encode($sql);
    }

    public function detail($id)
    {
		// $CommonController = new CommonController;
		// $access = $CommonController->checkFunctionPermission($this->permision,$this->className,$this->methodName);
		// if(empty($access)){
		// 	Session::flash('error', 'You do not have permission to access this page!.'); 
	 //        return Redirect::to('cms/dashboard');
		// }
		
		$data['userStatus'] = $CommonController->userStatus();
		$data['premium'] = $CommonController->premium();
		$data['company'] = $CommonController->company();
		$data['results'] = $this->getById($id);
		
	    $data['title'] = $this->title;
		$data['className'] = $this->className;
        return view('cms/message/detail',$data); 
    }

    public function getById($id)
    {
	 
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendMessageNumber')); 
		 return Redirect::to('cms/message/')->withInput();
		}
       
	   $where = array('c.id_kl' => $id);
	   
	    $sql = DB::table('klant as c')
		->leftJoin('bedrijf as comp', 'comp.id_bd', '=', 'c.id_bd')
		->leftJoin('relatiemanager as r', 'r.id_rm', '=', 'comp.id_rm')
		->select('c.*','c.id_kl as id','comp.nm_bd as company')
		->where($where)
		->first();
		
		return  $sql;
		 
   }

   public function getDataById(Request $request)
    {
	   $data = $request->all();
	    $id = $data['id'];
       if(empty($id)){
		 Session::flash('error', trans('common.pleaseSendMessageNumber')); 
		 return Redirect::to('cms/message/')->withInput();
		}
       
	   $where = array('r.pid' => $id);
	   
	    $sql = DB::table('apns_messages as r')
         ->leftJoin('klant as ur', 'ur.id_kl', '=', 'r.clientid')
		 ->select('r.pid','r.clientid', 'r.fk_device','r.subject','r.message','r.delivery','r.status','r.created','r.modified','ur.naam_kl','ur.email_kl','r.read')
		 ->where($where)
		 ->first();
        $sql = (array)$sql;

		$dt = '2016-12-16 19:37:24';
		$dt = strtotime(str_replace('', '', $dt));
		$d = date('F d Y',$dt);
		$t = date('h:i A',$dt);


		$sql['date'] = $d;
		$sql['time'] =$t;	
		
		$data = array('read' => 1);
		DB::table('apns_messages as r')->where($where)->update($data);
		return  $sql;
		 
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
        // $access = $CommonController->checkFunctionPermission($this->permision,$this->className,'delete');
        // if(empty($access)){ 
        //     Session::flash('error', 'You do not have permission to access this page!.'); 
        //     return Redirect::to('cms/dashboard');
        // }
        
         if(empty($post)){ 
          Session::flash('error', trans('common.invalidToken')); 
          return Redirect::to('cms/message');
                
        }
        
     
            
       
            $deleted_at = date("Y-m-d H:i:s");
            $data = array('stat-nw' => 'unpublish');
            
            DB::table('apns_messages')->whereIn('pid', $post['ids'])->delete(); 
             Session::flash('success', trans('common.deletedSuccesfully'));
            
              return 1;

    }
}
?>    