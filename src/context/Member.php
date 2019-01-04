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
use xto\membership\core\UserHelper;

class Member extends IUser{
	public $splittins=0;//资金余额
	public $points=0;//积分余额
	public $wallets=0;//钱包余额
	public $realname;//真实姓名

	public $refer_userid;//推荐人id
	public $refer_username;//推荐用户名
	public $trade_password;//安全密码
	public $trade_password_format='md5';//默认md5加密

	public $top_region_id;//所在省份
	public $region_id; //所在城市
	public $nickname;//呢称
	public $identify_card;//身份证
	public $address;//地址
	public $zipcode;//邮编
	public $phone;//电话
	public $mobile; //手机
	public $qq;
	public $wangwang;
	public $wechat;
	public $alipay;
	public $headimg;

	public function __construct(){
		parent::__construct();
		$this->userrole=UserRole::Member; 
	}  

	/**
     * 读取默认为会员角色，系统决定
     * @access public 
     * @return $UserRole UserRole::Member
     */ 
	public function getRole(){
		return UserRole::Member;
	}

	/**
     * 修改登录密码
     * @access public
     * @param $password 新密码
     * @return $bool 1或0
     */
	public function changeLoginPassword($password){
		return UserHelper::changeLoginPassword($this->userid,$password);
	}

	/**
     * 修改安全密码
     * @access public
     * @param $password 新密码
     * @return $bool 1或0
     */
	public function changeSafePassword($password){
		return MemberFactory::instance()->changeSafePassword($this->userid,$password);
	}

	public function validateMemberTrade($pwd){
		switch ($this->trade_password_format) {
			case 'md5':
				$pwd=md5($pwd);
				break; 
			default:
				$pwd=md5($pwd);
				break;
		}  
		return MemberFactory::instance()->validateMemberTrade($this->userid,$pwd);
	}
}