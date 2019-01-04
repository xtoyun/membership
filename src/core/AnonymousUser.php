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

class AnonymousUser extends IUser{
	public function __construct(){
		parent::__construct();
		$this->userrole=UserRole::Anonymous;
	}

	function getRole(){
		return UserRole::Anonymous;
	}
}