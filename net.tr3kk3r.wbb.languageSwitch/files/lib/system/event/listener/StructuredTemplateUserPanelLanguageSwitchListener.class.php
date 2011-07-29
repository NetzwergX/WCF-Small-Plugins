<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Show the language switch in the user panel
 * 
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2010-2011 tr3kk3r.net
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		net.tr3kk3r.wbb.languageSwitch
 * @subpackage	system.event.listener
 * @category 	Burning Board (third party)
 */
class StructuredTemplateUserPanelLanguageSwitchListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if(WCF::getUser()->userID)		
			WCF::getTPL()->append('additionalUserMenuItems', WCF::getTPL()->fetch('userMenuLanguageSwitch'));		
	}
}
?>