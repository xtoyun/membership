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

use xto\membership\data\UserData;
use xto\App;

abstract class UserProvider{
	private static $_instance;
	public $app;

	public static function instance(){ 
		if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            self::$_instance = new UserData(); 
            self::$_instance->app=App::instance();
        }
        return self::$_instance;
	}

	abstract function createUser($user);

	abstract function getUser($userid=0,$username='');

	abstract function updateUser($user);

	abstract function deleteUser($userid);  

	abstract function validateUser($username,$password);

	abstract function changeLoginPassword($userid,$newpwd);

	abstract function getUserFunctions($userid);

	abstract function deleteUserRoles($userid);
}