<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Adds an option to force the user to change his password to the UserAdd/UserEditForm
 *
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright 	(c) 2011 tr3kk3r.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.user.security.password.renew
 * @subpackage 	event.listener
 * @category 	Community Framework (third party)
 */
class UserAddFormPasswordRenewListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(!PASSWORD_RENEW_TIME) {
			return;
		}
		
		if($eventName == 'show') {
			WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('userAddPasswordRenew'));
		}
		else if($eventName == 'save') {				
			if(isset($_POST['passwordRenew'])) {
				$eventObj->additionalFields['lastPasswordChange'] = 0;
			}			
			else if(!empty($eventObj->password)) {
				$eventObj->additionalFields['lastPasswordChange'] = TIME_NOW;
			}										
		}
	}
}
?>