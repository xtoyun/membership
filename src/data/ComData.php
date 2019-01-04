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

use xto\membership\context\ComProvider;
use xto\membership\context\Manager;
use xto\membership\context\Member;
use xto\Util;
use think\Db;

//管理员和会员实现类
class ComData extends ComProvider{
	/**
     * 创建管理员
     * @access public
     * @param Manager $user 管理员对象
     * @return 1或0
     */
	function createManager(Manager $user){
		try {
			$data = [
			'appid'				=> $this->app->appid,
			'description'		=> $user->description,
			'userid' 			=> $user->userid];
			$d=Db::table('xto_managers')->insert($data);  
			if($d>0) return true;
		} catch (Exception $e) {
			
		} 
		return false;
	}

	/**
     * 读取管理员
     * @access public
     * @param $user 用户信息
     * @return $Manager 管理员信息
     */
	function getManager($user){  
		$manager=new Manager();
		if($user->username=='admin'){
			$users=Db::table('xto_managers')
				->where('userid',$user->userid) 
				->select();
		}else{
			$users=Db::table('xto_managers')
				->where('userid',$user->userid)
				->where('appid',$this->app->appid)
				->select();
		}
		
		$item=$users[0];
		$manager->userid			= $user->userid;
		$manager->username			= $user->username;
		$manager->email				= $user->email;
		$manager->is_approved		= $user->is_approved;
		$manager->is_locked			= $user->is_locked;
		$manager->password			= $user->password;
		$manager->password_format	= $user->password_format;
		$manager->password_question	= $user->password_question;
		$manager->password_answer	= $user->password_answer;
		$manager->createdate 		= $user->createdate;
		$manager->appid				= $user->password_question;
		$manager->password_question	= $user->appid;
		$manager->userrole			= $user->userrole;
		$manager->funrole			= $user->funrole;

		$manager->last_login_date 	=$user->last_login_date; 
		$manager->last_password_changeddate =$user->last_password_changeddate; 
		$manager->last_lockeddate 	=$user->last_lockeddate; 
		$manager->comment 			=$user->comment; 
		$manager->gender 			=$user->gender; 
		$manager->birthdate 		=$user->birthdate; 
		$manager->session_id 		=$user->session_id; 
		$manager->token 			=$user->token; 
		$manager->is_admin 			=$user->is_admin; 
		$manager->mobilein 			=$user->mobilein; 

		$manager->description 		= $item['description'];
		return $manager;
	}

	/**
     * 更新管理员
     * @access public
     * @param $Manager 管理员信息
     * @return 1或0
     */
	function updateManager(Manager $user){
		$data = [  
			'description' 		=> $user->description]; 
		if(Db::table('xto_managers')
		    ->where('userid', $user->userid)
		    ->where('appid', $this->app->appid)
		    ->update($data)>0) return true;
		else{
			return false;
		}
	}

	function deleteManager($userid){
		return Db::table('xto_managers')
			->where('userid',$userid)
			->where('appid', $this->app->appid)
			->delete(); 
	}

	/**
     * 创建会员
     * @access public
     * @param Member $user 管理员对象
     * @return 1或0
     */
	function createMember(Member $user){
		try {
			if(!empty($user->trade_password)){
				switch ($user->trade_password_format) {
					case 'md5':
						$pwd=md5($user->trade_password);
					default:
						$user->trade_password_format='md5';
						$pwd=md5($user->trade_password);
				}
			}
			
			$data = [
			'appid'					=> $this->app->appid,
			'refer_userid'			=> $user->refer_userid,
			'refer_username'		=> $user->refer_username,
			'refer_date'			=> Util::getdate(),
			'trade_password'		=> $pwd,
			'trade_password_format' => $user->trade_password_format,
			'top_region_id'			=> $user->top_region_id,
			'region_id'				=> $user->region_id,
			'realname'				=> $user->realname,
			'nickname'				=> $user->nickname,
			'identify_card'			=> $user->identify_card,
			'address'				=> $user->address,
			'zipcode'				=> $user->zipcode,
			'phone'					=> $user->phone,
			'mobile'				=> $user->mobile,
			'qq'					=> $user->qq,
			'wangwang'				=> $user->wangwang,
			'wechat'				=> $user->wechat,
			'alipay'				=> $user->alipay, 
			'headimg' 				=> $user->headimg,
			'userid' 				=> $user->userid];
			$d=Db::table('xto_members')->insert($data);  
			if($d>0) return true;
		} catch (Exception $e) {
			
		} 
		return false;
	}

	/**
     * 更新会员
     * @access public
     * @param $Member 会员信息
     * @return 1或0
     */
	function updateMember(Member $user){
		$data = [  
			'realname' 		=> $user->realname,
			'nickname' 		=> $user->nickname,
			'identify_card' => $user->identify_card,
			'address' 		=> $user->address,
			'zipcode' 		=> $user->zipcode,
			'phone' 		=> $user->phone,
			'mobile' 		=> $user->mobile,
			'qq' 			=> $user->qq,
			'wangwang' 		=> $user->wangwang,
			'wechat' 		=> $user->wechat,
			'headimg' 		=> $user->headimg,
			'alipay' 		=> $user->alipay, 
			'address' 		=> $user->address, 
			];   
		$c = Db::table('xto_members')
		    ->where('userid', $user->userid)
		    ->where('appid', $this->app->appid)
		    ->update(array_filter($data));
		if($c>0){ 
			return true;
		}
		else{
			return false;
		}
	}

	/**
     * 读取会员
     * @access public
     * @param $user 用户信息
     * @return $Member 会员信息
     */
	function getMember($user){  
		$member=new Member();
		$item=Db::table('xto_members')
			->where('userid',$user->userid)
			->where('appid',$this->app->appid)
			->find(); 
		$member->userid			= $user->userid;
		$member->username			= $user->username;
		$member->email				= $user->email;
		$member->is_approved		= $user->is_approved;
		$member->is_locked			= $user->is_locked;
		$member->password			= $user->password;
		$member->password_format	= $user->password_format;
		$member->password_question	= $user->password_question;
		$member->password_answer	= $user->password_answer;
		$member->createdate 		= $user->createdate;
		$member->appid				= $user->password_question;
		$member->password_question	= $user->appid;
		$member->userrole			= $user->userrole;
		$member->funrole			= $user->funrole;
		$member->last_login_date 	=$user->last_login_date; 
		$member->last_password_changeddate =$user->last_password_changeddate; 
		$member->last_lockeddate 	=$user->last_lockeddate; 
		$member->comment 			=$user->comment; 
		$member->gender 			=$user->gender; 
		$member->birthdate 			=$user->birthdate; 
		$member->session_id 		=$user->session_id; 
		$member->token 				=$user->token; 
		$member->is_admin 				=$user->is_admin;

		$member->realname 			= $item['realname'];
		$member->address 			= $item['address'];
		$member->wallets 			= $item['wallets'];  
		$member->splittins 			= $item['splittins'];  
		$member->points 			= $item['points'];  
		$member->trade_password 	= $item['trade_password'];  
		$member->trade_password_format 		= $item['trade_password_format'];  
		$member->top_region_id 		= $item['top_region_id'];  
		$member->region_id 			= $item['region_id'];  
		$member->nickname 			= $item['nickname'];  
		$member->identify_card 		= $item['identify_card'];  
		$member->address 			= $item['address'];  
		$member->zipcode 			= $item['zipcode'];  
		$member->phone 				= $item['phone'];  
		$member->mobile 			= $item['mobile'];  
		$member->qq 				= $item['qq'];  
		$member->wangwang 			= $item['wangwang'];  
		$member->wechat 			= $item['wechat'];  
		$member->alipay 			= $item['alipay'];  
		$member->headimg 			= empty($item['headimg'])?'/public/static/images/user1.jpg':$item['headimg'];
		return $member;
	}

	function deleteMember($userid){
		return Db::table('xto_members')
			->where('userid',$userid)
			->where('appid', $this->app->appid)
			->delete(); 
	}
 
	function changeMemberSafePassword($userid,$newpwd){
		if($userid>0){
			$users=Db::table('xto_members')
				->where('userid',$userid) 
				->where('appid',$this->app->appid)
				->select();
			if(count($users)>0){
				$user= $users[0];
				$newpwd=md5($newpwd);
				$data = [
					'trade_password' => $newpwd
					];
				try {
					$c=Db::table('xto_members')
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

	function validateMemberTrade($userid,$password){
		$c=Db::table('xto_members')
			->where('userid',$userid)
			->where('trade_password',$password)
			->where('appid',$this->app->appid)
			->find();
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
}