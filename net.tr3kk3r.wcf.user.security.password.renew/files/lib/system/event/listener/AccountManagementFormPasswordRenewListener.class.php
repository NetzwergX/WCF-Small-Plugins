<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Adds the hints for forced passwords to the AccountManagementForm and handles the password change.
 *
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright 	(c) 2011 tr3kk3r.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.user.security.password.renew
 * @subpackage 	event.listener
 * @category 	Community Framework (third party)
 */
class AccountManagementFormPasswordRenewListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if($eventName == 'show') {
			if((WCF::getUser()->lastPasswordChange + (PASSWORD_RENEW_TIME * 86400)) < TIME_NOW) 				
				WCF::getTPL()->append('userMessages', '<p class="warning">'.WCF::getLanguage()->get('wcf.user.passwordChange.forced', array("PASSWORD_RENEW_TIME" => PASSWORD_RENEW_TIME)).'</p>');			
			if($eventObj->errorField = 'newPassword' && $eventObj->errorType == 'notChanged')
				WCF::getTPL()->append('userMessages', '<p class="error">'.WCF::getLanguage()->get('wcf.user.passwordChange.similar').'</p>');
		}
		else if($eventName == 'validate') {					
			if(WCF::getUser()->checkPassword($eventObj->newPassword))
				throw new UserInputException('newPassword', 'notChanged');
		}
		else if($eventName == 'saved'){
			if (!empty($eventObj->newPassword) || !empty($eventObj->confirmNewPassword))
				WCF::getUser()->getEditor()->updateFields(array('lastPasswordChange' => TIME_NOW));				
		}
	}
}
?>