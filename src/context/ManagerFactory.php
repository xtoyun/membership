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

class ManagerFactory extends Userfactory{
	private static $instance;
	private $provider; 

	public function __construct(){
		 
	}
	
	static function instance(){
		if (is_null ( self::$instance ) || isset ( self::$instance )) {
            self::$instance = new self ();
            self::$instance->provider=ComProvider::instance();//实例化
        }
        return self::$instance;
	} 

	/**
     * 创建管理员
     * @access public
     * @param $user 用户信息
     * @return $bool 1或0
     */
	function createUser($user){ 
		return $this->provider->createManager($user); 
	}

	/**
     * 更新管理员
     * @access public
     * @param $user 用户信息
     * @return 1或0
     */
	function updateUser($user){ 
		return $this->provider->updateManager($user); 
	}

	/**
     * 读取管理员信息
     * @access public
     * @param $user 用户信息
     * @return $IUser
     */
	function getUser($user){
		return $this->provider->getManager($user); 
	}

	/**
     * 删除管理员
     * @access public
     * @param $userid 用户id
     * @return true或false
     */
	function deleteUser($userid){
		return $this->provider->deleteManager($userid); 
	} 
}