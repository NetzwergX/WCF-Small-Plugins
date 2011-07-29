<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 *
 * Forces the user to change his password when it has become invalid 
 *
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright 	(c) 2011 tr3kk3r.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.user.security.password.renew
 * @subpackage 	event.listener
 * @category 	Community Framework (third party)
 */
class StructuredTemplatePasswordRenewListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(!PASSWORD_RENEW_TIME) return;
		if(!WCF::getUser()->userID) return;
		if(isset($_REQUEST['form']) && $_REQUEST['form'] == 'AccountManagement') return;
				
		
		if((WCF::getUser()->lastPasswordChange + (PASSWORD_RENEW_TIME * 86400)) < TIME_NOW) 
			HeaderUtil::redirect('index.php?form=AccountManagement'.SID_ARG_2ND_NOT_ENCODED);
			
	}
}
?>