<?
/**
 * ============================================================================
 * * 版权所有 2013-2018 xtoyun.net，并保留所有权利。
 * 网站地址: http://www.xtoyun.net； 
 * ============================================================================
 * $Author: xtoyun $ 
*/
namespace xto\membership\context;

use xto\App;

class Context{
	private static $current;
	private $user;
	private $app; 
    private $manager;

	public static function current(){
		if (is_null ( self::$current ) || isset ( self::$current )) {
            self::$current = new self (); 
            self::$current->app=App::instance(); 
        }
        return self::$current;
	}

    /**
     * 读取用户
     * @access public
     * @return $IUser
     */
	public function getUser(){
		if(empty($this->user)){
			$this->user=Users::getLoginUser();
		}
		return $this->user;
    } 

    /**
     * 读取管理员
     * @access public
     * @return $IUser
     */
    public function getManager(){
        if(empty($this->manager)){
            $this->manager=Users::getLoginManager();
        }
        return $this->manager;
    }

    /**
     * 读取全局类App
     * @access public
     * @return $IUser
     */
    public function getApp(){
        if(empty($this->app)){
            $this->app=App::instance();
        }
        return $this->app;
    }

	public function __get($name)              // 这里$name是属性名
    {
        $getter = 'get' . $name;              // getter函数的函数名
        if (method_exists($this, $getter)) {
            return $this->$getter();          // 调用了getter函数
        } else {
            return $this->$name; 
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;             // setter函数的函数名
        if (method_exists($this, $setter)) {
            $this->$setter($value);          // 调用setter函数
        } else {
            $this->$name = $value; 
        }
    } 
}