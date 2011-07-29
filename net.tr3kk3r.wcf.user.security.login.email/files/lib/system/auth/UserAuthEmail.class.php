<?php
// wcf imports
if (!defined('NO_IMPORTS')) {
	require_once(WCF_DIR.'lib/system/auth/UserAuth.class.php');
	require_once(WCF_DIR.'lib/data/user/User.class.php');
}

/**
 * Implementation of the user authentication by email adress instead of username.
 * 
 * @author		Sebastian Teumert
 * @copyright	(c) 2011 tr3kk3r.net	
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		net.tr3kk3r.wcf.user.security.login.email
 * @subpackage	system.auth
 * @category 	Community Framework (third party)
 */
class UserAuthEmail extends UserAuthDefault {

	public static function useEMailAuth() {
		parent::$instance = new UserAuthEmail();
	}	 
	
	/**
	 * @see UserAuth::loginManually()
	 */
	public function loginManually($username, $password, $userClassname = 'UserSession') {
		if(ALLOW_LOGIN_EMAIL) {			
				$user = new $userClassname(null, null, null, $username);
				if ($user->userID == 0) {
					if(FORCE_LOGIN_EMAIL)
						throw new UserInputException('username', 'notFound');
					else {
						$user = new $userClassname(null, null, $username);
						if ($user->userID == 0)
							throw new UserInputException('username', 'notFound'); 
					}
				}
			
				// check password
				if (!$user->checkPassword($password)) {
					throw new UserInputException('password', 'false');				 
				}			
				return $user;
		}
		
		return parent::loginManually();
		
		
	}
}
	