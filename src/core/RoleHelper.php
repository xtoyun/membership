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

class RoleHelper{
	static function roleExists($rolename){

	}
	
	static function createRole($role){
		$result=RoleProvider::instance();
		return $result->createRole($role);
	}

	static function updateRole($role){
		if ($role->roleid<1) {
			return false;
		}
		$result=RoleProvider::instance();
		return $result->updateRole($role);
	}

	static function findRole($name){
		$result=RoleProvider::instance();
		return $result->findRole($name);
	}

	static function getRole($id){
		$result=RoleProvider::instance();
		return $result->getRole($id);
	}

	static function getRoles(){
		$result=RoleProvider::instance();
		return $result->getRoles();
	}

	static function deleteRole($roleid){
		$result=RoleProvider::instance();
		return $result->deleteRole($roleid);
	}

	static function addUserToRoles($userid,array $roles){
		$result=RoleProvider::instance();
		return $result->addUserToRoles($userid,$roles);
	}

	static function addRoleFunctions($roleid,array $funs){
		$result=RoleProvider::instance();
		return $result->addRoleFunctions($roleid,$funs);
	}

	static function addUserToRole($userid,$roleid){
		$result=RoleProvider::instance();
		return $result->addUserToRole($userid,$roleid);
	}

	static function getRoleFunctions($roleid){
		$result=RoleProvider::instance();
		return $result->getRoleFunctions($roleid);
	}
}