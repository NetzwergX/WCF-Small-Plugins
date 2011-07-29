<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Removes inactive oder locked users from the members list.
 *
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2010 tr3kk3r.net | Sebasian Teumert
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.user.profile.memberslist.remove
 * @subpackage 	system.event.listener
 * @category 	Community Framework (third party)
 */
class MembersListRemoveInactiveListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(!empty($eventObj->sqlConditions))
			$eventObj->sqlConditions .= " AND ";
		$eventObj->sqlConditions .= "wcf_user.banned = 0 AND wcf_user.activationCode = 0";
		
	}
}
?>