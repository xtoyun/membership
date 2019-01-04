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

use xto\membership\context\Users;

class IUser{
	private $userid;
	private $username;
	private $lower_username;
	private $email;
	private $mobilein;

	private $password_question;
	private $password_answer;
	private $password;
	private $password_format;

	private $is_approved=false;
	private $is_locked=false;
	private $userrole;
	private $funrole;
	private $appid;
	private $createdate;

	private $last_login_date;
	private $last_password_changeddate;
	private $last_lockeddate; 
	private $comment;
	private $gender;
	private $birthdate;
	private $session_id;
	private $token;

	private $is_admin=false;

	public function __construct(){
		//$this->password_format='md5';
		//$this->createdate=date("Y-m-d H:i:s");
		//$this->password_question='';
		//$this->password_answer='';
		//$this->appid=0;
	}

	public function getIs_approved(){
		if(empty($this->is_approved)){
    		return false;//默认情况
    	}
		return $this->is_approved;
	}

	public function getCreatedate(){
		if(empty($this->createdate)){
    		return date("Y-m-d H:i:s");//默认情况
    	}
		return $this->createdate;
	}

    public function getPassword_format(){
    	if(empty($this->password_format)){
    		return 'md5';//默认情况
    	}
		return $this->password_format;
	} 

	public function inrole($roleid){
		$role=explode(',',$this->userrole);
		return in_array($roleid,$role);
	}

	public function clearCache(){
		return Users::clearUserCache($this);
	}


	public function __get($name)              // 这里$name是属性名
    {
        $getter = 'get' . $name;              // getter函数的函数名
        if (method_exists($this, $getter)) {
            return $this->$getter();          // 调用了getter函数
        } else {
        	if (isset($this->$name)) {
        		return $this->$name;
        	} 
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