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

use xto\membership\data\RoleData;
use xto\App;

abstract class RoleProvider{
	//实例对象
	private static $_instance; 

	/**
     * 全局
     * @access public
     * @return 1或0
     */
	protected $app;

	/**
     * 获取实例对象
     * @access public
     * @return 1或0
     */
	public static function instance(){ 
		if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new RoleData(); 
            self::$_instance->app=App::instance();
        }
        return self::$_instance;
	}

	/**
     * 添加角色
     * @access public
     * @return 1或0
     */
	abstract function createRole($role);

	/**
     * 更新角色
     * @access public
     * @return 1或0
     */
	abstract function updateRole($role);

	/**
     * 删除角色
     * @access public
     * @return 1或0
     */
	abstract function deleteRole($roleid);

	/**
     * 查找角色
     * @access public
     * @return $IRole
     */
	abstract function findRole($name);

    abstract function getRole($id);

    abstract function getRoles();

	/**
     * 添加用户角色s
     * @access public
     * @return 1或0
     */
	abstract function addUserToRoles($userid,array $roles);

    abstract function addRoleFunctions($roleid,array $funs);

    


    abstract function getRoleFunctions($roleid);

	/**
     * 添加用户角色
     * @access public
     * @return 1或0
     */
	abstract function addUserToRole($userid,$role);
}