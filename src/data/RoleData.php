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

use xto\membership\core\RoleProvider;
use xto\membership\core\IRole;
use think\Db;

class RoleData extends RoleProvider{
	public function createRole($role){
		try {
			$data = [
			'appid'				=> $this->app->appid,
			'description'		=> $role->description,
			'name' 				=> $role->name];
			$d=Db::table('xto_roles')->insert($data);  
			
			if($d>0){
				$role->roleid=Db::table('xto_roles')->getLastInsID();
				return true;
			}  
		} catch (Exception $e) {
			
		} 
		return false; 
	}

	public function updateRole($role){
		try {
			$data = [
			'description'		=> $role->description,
			'name' 				=> $role->name];
			$d=Db::table('xto_roles')
						->where('roleid', $role->roleid)
						->where('appid', $this->app->appid)
						->update($data);  
			return true;
		} catch (Exception $e) {
			
		} 
		return false;
	}

	public function deleteRole($roleid){
		$c=Db::table('xto_roles')
			->where('roleid',$roleid)
			->where('appid',$this->app->appid)
			->delete();
		if($c>0){
			return true;
		}
		return false;
	}

	public function findRole($name){
		$c=Db::table('xto_roles')
			->where('name',$name)
			->where('appid',$this->app->appid)
			->count('roleid');

		if($c>0){
			return true;
		}
		return false;
	}

	public function getRole($id){
		$c=Db::table('xto_roles')
			->where('roleid',$id)
			->where('appid',$this->app->appid)
			->find();
		if(!empty($c)){
			$result=new IRole();
			$result->roleid 	=$c['roleid'];
			$result->name 		=$c['name'];
			$result->description=$c['description'];
			$result->appid 		=$c['appid'];
			return $result;
		} 
		return null;
	}

	public function getRoles(){
		return Db::table('xto_roles') 
			->where('appid',$this->app->appid)
			->select();
	}

	public function addUserToRoles($userid,array $roles){
		$result=false;
		foreach ($roles as $key => $value) {
			$data = [
			'appid' 		=> $this->app->appid,
			'roleid'		=> $value,
			'userid'		=> $userid];
			$d=Db::table('xto_usersinroles')
						->insert($data); 
			if($d) $result=true;
		}
		return $result;
	}

	public function addRoleFunctions($roleid,array $funs){
		//先删除所有旧记录
		Db::table('xto_rolefunction')
			->where('roleid',$roleid)
			->where('appid',$this->app->appid)
			->delete();
		$data=[]; 
		foreach ($funs as $key => &$value) {
			if(!empty($value)){
				$data[] = [
					'appid' 		=> $this->app->appid,
					'roleid'		=> $roleid,
					'funid'			=> $value
				];
			} 
		}
		$d=Db::table('xto_rolefunction')->insertAll($data); 
		if($d>0) return true;
		return false;
	}

	public function addUserToRole($userid,$roleid){
		$data = [
			'roleid'		=> $roleid,
			'appid'			=> $this->app->appid,
			'userid'		=> $userid];
			$d=Db::table('xto_usersinroles')
						->insert($data); 
		if($d>0){
			return true;
		}
		return false;
	}

	public function getRoleFunctions($roleid){
		return Db::table('xto_rolefunction')
				->where('roleid',$roleid) 
				->where('appid',$this->app->appid)
				->column('funid');
	}
}