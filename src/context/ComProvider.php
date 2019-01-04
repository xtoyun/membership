<?
/**
 * ============================================================================
 * * 版权所有 2013-2014 xtoyun.net，并保留所有权利。
 * 网站地址: http://www.xtoyun.net；
 * ============================================================================
 * $Author: xtoyun $ 
*/
namespace xto\membership\context;

use xto\membership\data\ComData;
use xto\App;

abstract class ComProvider{
	public $app;
	private static $_instance;

	/**
     * 实例化当前对象
     * @access public
     * @return BizActorProvider
     */
	public static function instance(){ 
		if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
            //工厂实现类
            self::$_instance = new ComData();//数据实现类
            //全局APP信息
            self::$_instance->app=App::instance();//加入全局app
        }
        return self::$_instance;
	}

	/**
     * 创建管理员
     * @access public
     * @param Manager $user 管理员对象
     * @return 1或0
     */
	abstract function createManager(Manager $user);

	/**
     * 读取管理员
     * @access public
     * @param $user 用户信息
     * @return $Manager 管理员信息
     */
	abstract function getManager($user);

	/**
     * 更新管理员
     * @access public
     * @param $Manager 管理员信息
     * @return 1或0
     */
	abstract function updateManager(Manager $user);

    /**
     * 删除管理员
     * @access public
     * @param $userid 会员id
     * @return 1或0
     */
    abstract function deleteManager($userid);

	/**
     * 创建会员
     * @access public
     * @param Member $user 管理员对象
     * @return 1或0
     */
	abstract function createMember(Member $user);

	/**
     * 读取会员
     * @access public
     * @param $user 用户信息
     * @return $Member 会员信息
     */
	abstract function getMember($user);

	/**
     * 更新会员
     * @access public
     * @param $Member 会员信息
     * @return 1或0
     */
	abstract function updateMember(Member $user);

    /**
     * 删除会员
     * @access public
     * @param $userid 会员id
     * @return 1或0
     */
    abstract function deleteMember($userid);

    /**
     * 修改会员安全密码
     * @access public
     * @param $userid 会员id
     * @return 1或0
     */
    abstract function changeMemberSafePassword($userid,$newpwd);

    /**
     * 验证安全密码
     * @access public
     * @param $userid 会员id
     * @return 1或0
     */
    abstract function validateMemberTrade($userid,$pwd);
}