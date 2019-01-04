<?
/**
 * ============================================================================
 * * 版权所有 2013-2017 xtoyun.net，并保留所有权利。
 * 网站地址: http://www.xtoyun.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xtoyun $ 
*/
namespace xto\membership\data;

use xto\membership\core\UserProvider;
use xto\membership\core\UserCreateStatus;
use xto\membership\core\App;
use think\Db;

class UserData extends UserProvider {

	/**
     * 创建用户
     * @access public
     * @param $user 用户信息
     * @return $status UserCreateStatus
     */
	function createUser($user){ 
		if($user->userrole==0){ 
			return UserCreateStatus::Failer;
		}
		$status=UserCreateStatus::UnknownFailure;
		//有些注册是以手机，邮件，用户名，需要区别一下
		$c=Db::table('xto_users')
			->where('username',$user->username)
			->where('appid',$this->app->appid)
			->count('userid');
		//如果用户名
		//如果是手机
		if(preg_match("/^1[34578]\d{9}$/", $user->username)){

		}

		if(!preg_match("/^[\w\_]{4,20}$/", $user->username)){
			return UserCreateStatus::Failer;
		} 


		if($c>0){
			$status = UserCreateStatus::DuplicateUsername; 
		}else{
			$pwd=$user->password;
			switch ($user->password_format) {
				case 'md5':
					$pwd=md5($user->password);
				default:
			}
			$data = [
			'userid' 			=> $user->userid,
			'username' 			=> $user->username, 
			'lower_username' 	=> strtolower($user->username), 
			'email'				=> $user->email,
			'is_approved'		=> $user->is_approved,
			'is_locked'			=> $user->is_locked,
			'password'			=> $pwd,
			'password_format'	=> $user->password_format,
			'password_question'	=> $user->password_question,
			'password_answer'	=> $user->password_answer,
			'createdate'		=> $user->createdate,
			'appid'				=> $this->app->appid,
			'is_admin'			=> empty($user->is_admin)?0:$user->is_admin,
			'funrole' 			=> $user->funrole,//应用角色
			'userrole' 			=> $user->getrole()];
			$d=Db::table('xto_users')->insert($data); 
			if($d>0){
				$user->userid=Db::table('xto_users')->getLastInsID();
				$status=UserCreateStatus::Created;
			}else{
				$status=UserCreateStatus::Failer;
			}
		}  
		return $status;
	}

	/**
     * 获取用户
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	function getUser($userid=0,$username=''){

		if($userid>0){
			return Db::table('xto_users')
				->where('userid',$userid) 
				->where('appid',$this->app->appid)
				->find(); 
		}
		if ($username<>'') {
	 
			if(in_array($username,config('admins'))){
				return Db::table('xto_users')
				->where('username',$username) 
				->find();
			}
			return Db::table('xto_users')
				->where('username',$username)
				->where('appid',$this->app->appid)
				->find(); 
		} 
		return null;
	}

	/**
     * 更新用户
     * @access public
     * @param $user 用户信息
     * @return 1或0
     */
	function updateUser($user){ 
		if($user->userrole==0){ 
			return false;
		}  
		$data = [
			//'username' 			=> $user->username, 
			'email'				=> $user->email,
			'mobilein'			=> $user->mobilein,
			'is_approved'		=> $user->is_approved,
			'is_locked'			=> $user->is_locked,
			'is_admin'			=> $user->is_admin,
			'funrole'			=> $user->funrole,
			//'password'			=> $pwd,
			//'password_format'	=> $user->password_format,
			'password_question'	=> $user->password_question,
			'password_answer'	=> $user->password_answer,
			//'userid'			=> $user->userid,
			//'appid'				=> $this->app->appid(),
			//'userrole' 			=> $user->userrole
			];
		try {
			$c=Db::table('xto_users')
		    ->where('userid', $user->userid)
		    ->where('appid', $this->app->appid)
		    ->update($data);
		    return true;
		} catch (Exception $e) {
			return false;
		} 
	}

	/**
     * 删除用户
     * @access public
     * @param $userid 用户id
     * @return 1或0
     */
	function deleteUser($userid){
		if(Db::table('xto_users')
			->where('userid',$userid)
			->where('appid',$this->app->appid)
			->delete()>0){
			return true;
		}
		return false;
	}

	/**
     * 验证用户
     * @access public
     * @param $username 用户名
     * @param $password 密码
     * @return 1或0
     */
	function validateUser($username,$password){
		if(in_array($username,config('admins'))){
			$c=Db::table('xto_users')
				->where('username',$username)
				->where('password',$password)
				->find();
		}else{
			$c=Db::table('xto_users')
				->where('username',$username)
				->where('password',$password)
				->where('appid',$this->app->appid)
				->find();
		}
		if (!empty($c)) { 
			$data = [  
				'last_login_date'		=> date("Y-m-d H:i:s"), 
			]; 
			Db::table('xto_users')
		    ->where('userid', $c['userid'])
		    ->where('appid', $this->app->appid)
		    ->update($data);//更新日志
			return true;
		}
		return false;
	}

	function changeLoginPassword($userid,$newpwd){
		if($userid>0){
			$users=Db::table('xto_users')
				->where('userid',$userid) 
				->where('appid',$this->app->appid)
				->select();
			if(count($users)>0){
				$user= $users[0];
				$newpwd=md5($newpwd);
				$data = [
					'password' => $newpwd
					];
				try {
					$c=Db::table('xto_users')
				    ->where('userid', $userid)
				    ->where('appid', $this->app->appid)
				    ->update($data);
				    return true;
				} catch (Exception $e) {
					
				} 
			} 
		}
		return false;
	}

	/**
     * 读取所有权限功能
     * @access public 
     * @return 1或0
     */
	public function getUserFunctions($userid){
		//读取所有角色
		$u=array();
		$u[]=Db::table('xto_users')->where('userid',$userid)->where('appid',$this->app->appid)->value('funrole'); 
		$roles=Db::table('xto_usersinroles')->where('userid',$userid)->where('appid',$this->app->appid)->column('roleid');
		foreach ($roles as $key => $value) {
			$u[]=$value;
		} 
		return Db::table('xto_rolefunction')
				->where('roleid','in',$u) 
				->where('appid',$this->app->appid)
				->column('funid');
	}

	public function deleteUserRoles($userid){
		$c=Db::table('xto_usersinroles')
			->where('userid',$userid)
			->where('appid',$this->app->appid)
			->delete();
		if($c>0){
			return true;
		}
		return false;
	}
}