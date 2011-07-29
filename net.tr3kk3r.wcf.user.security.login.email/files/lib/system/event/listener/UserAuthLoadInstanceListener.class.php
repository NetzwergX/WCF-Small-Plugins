<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/system/auth/UserAuthEmail.class.php');
/**
 * Sets the user auth to email
 *
 * @author		Sebastian Teumert
 * @copyright	(c) 2011 tr3kk3r.net	
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		net.tr3kk3r.wcf.user.security.login.email
 * @subpackage	system.event.listener
 * @category 	Community Framework (third party)
 */
class UserAuthLoadInstanceListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(ALLOW_LOGIN_EMAIL) {
			UserAuthEmail::useEMailAuth();
		}
	}
}
?>