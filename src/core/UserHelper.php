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
namespace xto\membership\core;

class UserHelper{

	/**
     * 创建用户
     * @access public
     * @param $user 用户信息
     * @return $status CreateUserStatus
     */
	static function create($user){ 
		$userProvider=UserProvider::instance();
		$createStatus=$userProvider->createUser($user);
		if($createStatus==UserCreateStatus::Created){
			$roleProvider=RoleProvider::instance();
			if(!$roleProvider->addUserToRole($user->userid,$user->userrole)){
				$userProvider::deleteUser($user->userid);
				return UserCreateStatus::Deleted;
			}
		}
		return $createStatus;
	}

	/**
     * 更新用户
     * @access public
     * @param $user 用户信息
     * @return 1或0
     */
	static function updateUser($user){
		$result=false;
		if(empty($user)){
			return false;
		}
		$userProvider=UserProvider::instance();
		$result=$userProvider->updateUser($user); 
		return $result;
	}

	/**
     * 删除用户
     * @access public
     * @param $userid 用户id
     * @return 1或0
     */
	static function deleteUser($userid){
		$userProvider=UserProvider::instance();
		return $userProvider->deleteUser($userid);
	}

	/**
     * 获取用户
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	static function getUser($userid=0,$username=''){
		$userProvider=UserProvider::instance();
		$user= $userProvider->getUser($userid,$username); 
		if(!is_null($user)){  
			$n=new IUser();
			$n->userid 				=$user['userid'];
			$n->username 			=$user['username'];
			$n->userrole 			=$user['userrole'];
			$n->funrole 			=$user['funrole'];
			$n->email 				=$user['email'];
			$n->is_approved 		=$user['is_approved'];
			$n->is_locked 			=$user['is_locked'];
			$n->password 			=$user['password'];
			$n->password_format		=$user['password_format'];
			$n->password_question	=$user['password_question'];
			$n->password_answer 	=$user['password_answer'];
			$n->createdate 			=$user['createdate'];
			$n->appid 				=$user['appid']; 

			$n->last_login_date 	=$user['last_login_date']; 
			$n->last_password_changeddate =$user['last_password_changeddate']; 
			$n->last_lockeddate 	=$user['last_lockeddate']; 
			$n->comment 			=$user['comment']; 
			$n->gender 				=$user['gender']; 
			$n->birthdate 			=$user['birthdate']; 
			$n->session_id 			=$user['session_id']; 
			$n->token 				=$user['token'];  
			$n->mobilein 			=$user['mobilein'];  
			$n->is_admin 			=$user['is_admin']==1?true:false;  
			return $n;
		}
		return new AnonymousUser();//匿名用户
	}

	/**
     * 验证用户
     * @access public
     * @param $user 用户
     * @return $LoginUserStatus 
     */
	static function validateUser($user){
		$result=UserLoginStatus::UnknownError;
		if(!$user->is_approved){
			return UserLoginStatus::AccountPending;
		}else{
			if($user->is_locked){
				return UserLoginStatus::AccountLockedOut;
			}else{
				$userProvider=UserProvider::instance();
				$pwd=$user->password;
				switch ($user->password_format) {
					case 'md5':
						$pwd=md5($user->password);
					default:
				}
				if($userProvider->validateUser($user->username,$pwd)){
					$result=UserLoginStatus::Success;
				}else{
					return UserLoginStatus::InvalidCredentials;
				}
			}
		}
		return $result;
	}

	static function changeLoginPassword($userid,$newpwd){
		$userProvider=UserProvider::instance();
		return $userProvider->changeLoginPassword($userid,$newpwd);
	}

	static function getUserFunctions($userid){
		$userProvider=UserProvider::instance();
		return $userProvider->getUserFunctions($userid);
	}

	static function deleteUserRoles($userid){
		$userProvider=UserProvider::instance();
		return $userProvider->deleteUserRoles($userid);
	}
}