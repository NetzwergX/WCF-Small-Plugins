<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * 
 *
 * @author 	Sebastian Teumert
 * @copyright 	(c) 2011 teumert.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.teumert.wcf.form.user.nameValidation
 * @subpackage 	event.listener
 * @category 	Community Framework (third party)
 */
class UserAddFormValidationListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(!preg_match(REGISTER_USERNAME_VALIDATION_PATTERN, $eventObj->username)) {
			$eventObj->errorType['username'] = 'notValid';
		}
	}
}
?>