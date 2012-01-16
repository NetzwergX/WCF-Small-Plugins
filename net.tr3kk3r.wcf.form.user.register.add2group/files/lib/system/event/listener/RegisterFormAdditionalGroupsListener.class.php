<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Automatically adds user to user groups upon registration specified by acp option 
 *
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2010 tr3kk3r.net | Sebasian Teumert
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.form.user.register.add2group
 * @subpackage 	system.event.listener
 * @category 	Community Framework (third party)
 */
class RegisterFormAdditionalGroupsListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		//check non-emptyness
		$additionalGroupIDs = explode(",", REGISTER_ADDITIONAL_GROUPS);
		if(count($additionalGroupIDs) > 0)
			$eventObj->user->addToGroups($additionalGroupIDs, false, false);		
	}
}
?>