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
namespace xto\membership\context;

use xto\membership\core\UserLoginStatus;
use xto\membership\core\UserCreateStatus;
use xto\membership\core\UserHelper;
use xto\membership\core\IUser;
use xto\membership\core\UserRole;
use xto\membership\core\AnonymousUser;
use xto\core\HashTable;
use xto\App;
use think\Cache;
use think\Session;

class Users{ 
	/**
     * 创建用户
     * @access public
     * @param $user 用户信息
     * @return $status CreateUserStatus
     */ 
	static function createUser($user){
		$UserCreateStatus = UserHelper::Create($user);//创建基础用户
		//创建状态为Created，表示成功
		if($UserCreateStatus==UserCreateStatus::Created){
			//创建用户扩展
			$factory=UserFactory::createInstance($user->getRole());
			$status=$factory->createUser($user);
			if(!$status){
				//如果创建不成功，则删除用户所有信息
				UserHelper::deleteUser($user->userid);
				return UserCreateStatus::Failer;
			}
		}
		return $UserCreateStatus; 
	}

	/**
     * 更新用户
     * @access public
     * @param $user 用户信息
     * @return 1或0
     */
	static function updateUser($user){
		$result = UserHelper::updateUser($user);//创建基础用户 
		if($result){ 
			//更新用户扩展信息
			$factory=UserFactory::createInstance($user->userrole);
			if(!$factory->updateUser($user)){
				return $result;
			}
		}
		return $result;   
	}

	/**
     * 删除用户
     * @access public
     * @param $userid 用户id
     * @return 1或0
     */
	static function deleteUser($userid){
		$user=Users::getUser($userid, '', false);//先读取用户
		$result = UserHelper::deleteUser($userid);
		if($result){ 
			$factory=UserFactory::createInstance($user->userrole);
			$result=$factory->deleteUser($userid);
		}
		return $result;
	}

	/**
     * 获取用户
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	static function getLoginUser(){
		$username=Users::getLoginUsername();
		if(empty($username)){
			return null;
		}
		$user=Users::getUser(0, $username, true);
		if (empty($user)) {
			return null;
		}
		return $user;
	}

	/**
     * 获取管理员用户
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	static function getLoginManager(){
		$username=Users::getLoginUserNameByManager();
		if(empty($username)){
			return null;
		}
		$user=Users::getUser(0, $username, true);

		if (empty($user)) {
			return null;
		}
		return $user;
	}

	/**
     * 获取用户
     * @access public
     * @param $userid 用户ID
     * @param $username 用户名
     * @param $iscache 启用缓存(1或0)
     * @return $IUser
     */
	static function getUser($userid=0,$username='',$iscache=false){
		$appid=App::instance()->appid;//全局appid
		$result=null;//返回结果
		$hashtable = Users::userCache(); //读取hasttable和数组功能一样，只是键值的关系，便于管理和性能
		$key = Users::UserKey($username."_".$appid);//读取会员名的缓存key
		if($userid>0){
			$key = Users::UserKey($userid."_".$appid);//如果用户名为空，则以userid缓存key
		}
		//是否读取缓存用户
		if ($iscache) { 
			$result = $hashtable->find($key); //查找hasttable
			if (!empty($result)) {  
				return $result;//直接返回结果
			}
		}
		//读取基础user
		$result=UserHelper::getUser($userid,$username);
 

		if(!is_null($result) && $result->userrole!=UserRole::Anonymous){
			//读取会员或管理员的扩展信息
			$factory=UserFactory::createInstance($result->userrole);
			$result= $factory->getUser($result); 
			if($iscache){
				//写入缓存
				$hashtable->insert(Users::UserKey($result->userid."_".$appid),$result); 
				$hashtable->insert(Users::UserKey($result->username."_".$appid),$result);  
				Cache::set('DataCache-UserLookuptable',$hashtable,3600);
			}   
		} 

		return $result;
	}

	/**
     * 检查权限
     * @access public
     * @param $funid 页面地址，例/admin/index/index
     * @return $bool 1或0
     */
	static function checkfun($funid){
		$user=Users::getLoginManager();
		$funs=UserHelper::getUserFunctions($user->userid);
		if($user->is_admin){
			return true;//如果是管理员直接通过
		}
		if(in_array($funid,$funs)){
			return true;
		}
		return false;
	}

	/**
     * 验证用户
     * @access public
     * @param $user 用户名，根据用户名先读取用户
     * @return $UserLoginStatus UserLoginStatus
     */
	static function validateUser($user){
		$result= UserHelper::validateUser($user);
		if($result==UserLoginStatus::Success){
			//写入登录日志
			Session::set(App::instance()->user_auth_name,$user->username);
		}
		return $result;
	}

	/**
     * 清除用户缓存
     * @access public
     * @param $user 用户名，根据用户名先读取用户
     * @return $bool 1或0
     */
	static function clearUserCache($user){
		$appid=App::instance()->appid();
		$c=Users::userCache();
		$c->remove(Users::userkey($user->userid."_".$appid)); 
		$c->remove(Users::userkey($user->username."_".$appid));
		Cache::set('DataCache-UserLookuptable',$c,3600);//写入缓存
	}

	/**
     * 读取用户缓存hasttable
     * @access public 
     * @return $HashTable
     */
	private static function userCache(){ 
		$c=Cache::get('DataCache-UserLookuptable'); 
		if (empty($c)) { 
			$c=new HashTable();
			Cache::set('DataCache-UserLookuptable',$c,3600);
		} 
		return $c;
	}

	/**
     * 读取用户缓存key，私有方法，仅限此类使用
     * @access public 
     * @param $key key组合
     * @return $string
     */
	private static function userKey($key){
		return "user-".$key;
	}

	/**
     * 读取用户名
     * @access public
     * @return $string
     */
	public static function getLoginUsername(){
		return Session::get(App::instance()->user_auth_name);
	}

	/**
     * 读取管理员登录用户名
     * @access public
     * @return $string
     */
	public static function getLoginUserNameByManager(){
		return Session::get(App::instance()->manager_auth_name);
	}
}