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

/**
 * 管理员类，扩展描述等字段
 */ 
class Manager extends IUser{
	public $description;

	public function __construct(){
		parent::__construct();
		$this->userrole=UserRole::Manager;
	}

	/**
     * 读取默认为管理员角色，系统决定
     * @access public 
     * @return $UserRole UserRole::Manager
     */ 
	public function getRole(){
		return UserRole::Manager;
	}
}