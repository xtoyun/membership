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
class UserCreateStatus{
  const __default = self::UnknownFailure;
  const UnknownFailure = 1;
  const Created = 2;
  const DuplicateUsername = 3;
  const DuplicateEmailAddress = 4;
  const InvalidFirstCharacter = 5;
  const DisallowedUsername = 6;
  const Updated = 7;
  const Deleted = 8;
  const InvalidQuestionAnswer = 9;
  const InvalidPassword = 10;
  const InvalidEmail = 11;
  const InvalidUserName = 12;
  const DuplicateUser = 13; 
  const Failer=14;
}