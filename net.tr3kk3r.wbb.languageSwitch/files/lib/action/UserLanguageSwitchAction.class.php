<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Switches the users language to the given language id
 * 
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2010-2011 tr3kk3r.net
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		net.tr3kk3r.wbb.languageSwitch
 * @subpackage	lib.action
 * @category 	Burning Board (third party)
 */
class UserLanguageSwitchAction extends AbstractAction {
	
	/**
	 * The language id to be switched to
	 * 
	 * @var int
	 */
	public $languageID = 0;	
	
	/**
	 * Redirect to given URL if set, otherwise to index page
	 * 
	 * @var	string
	 */
	public $url = '';
	
	/**
	 * @see	Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['languageID'])) $this->languageID = intval($_GET['languageID']);
		if (isset($_GET['url'])) $this->url = StringUtil::trim(urldecode($_GET['url']));		
	}	
		
	/**
	 * @see	Action::execute()
	 */
	public function execute() {
		parent::execute();		
		
		if (!empty($this->languageID)) {
				//switch language
				$editor = WCF::getUser()->getEditor();
				$editor->update('', '', '', null, null, array('languageID' => $this->languageID));				
				
				// call executed event
				$this->executed();				
				
				//redirect
				if (empty($this->url)) {					
					HeaderUtil::redirect('index.php?page=Index'.SID_ARG_2ND_NOT_ENCODED);			
					exit;
				}
				else {
					HeaderUtil::redirect($this->url.SID_ARG_2ND_NOT_ENCODED, false);
					exit;
				}			
		}		
		
		throw new IllegalLinkException();
	}
}
?>