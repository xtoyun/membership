<?
/**
 * ============================================================================
 * * 版权所有 2013-2014 xtoyun.net，并保留所有权利。
 * 网站地址: http://www.xtoyun.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xtoyun $ 
*/
namespace xto\membership\context;

use xto\membership\core\IUser;
use xto\membership\core\UserRole; 

abstract class UserFactory{ 
	/**
     * 创建用户
     * @access public
     * @param $user 用户信息
     * @return $status CreateUserStatus
     */
	abstract function createUser($user);

	/**
     * 更新用户
     * @access public
     * @param $user 用户信息
     * @return 1或0
     */
	abstract function updateUser($user);

	/**
     * 获取用户
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	abstract function getUser($user);

     /**
     * 删除用户
     * @access public
     * @param $user 用户信息
     * @return true或false
     */
     abstract function deleteUser($userid);

	/**
     * 根据角色实例对象
     * @access public
     * @param $role 角色信息
     * @return $MemberFactory 或 ManagerFactory
     */
	public static function createInstance($role){ 
		switch ($role) {
			case UserRole::Manager: 
				return ManagerFactory::instance();
			case UserRole::Member:
				return MemberFactory::instance();
		}
	}
}