<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/album/UserGalleryAlbumList.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/UserGalleryPhotoList.class.php');
require_once(WCF_DIR.'lib/page/UserGalleryPhotosXMLListPage.class.php');

/**
 * Displays the users photos (albums) in the message fom
 *
 * @author		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2011 tr3kk3r.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.form.message.tab.gallery
 * @subpackage	event.listener
 * @category 	Community Framework & Community Gallery (third party)
 */
class MessageFormGalleryListener implements EventListener {
	/**
	 * list of gallery albums
	 * 
	 * @var	UserGalleryAlbumList
	 */
	public $albumList = null;
	
	/**
	 * list of guncategorized photos
	 * 
	 * @var	UserGalleryPhotoList
	 */
	public $photoList = null;
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		//check if user is logged in
		if(WCF::getUser()->userID == 0)
			return;

		if($eventName == "readData") {
			$this->albumList = new UserGalleryAlbumList();
			$this->albumList->sqlConditions .= 'user_gallery_album.ownerID = '.WCF::getUser()->userID;
			$this->albumList->sqlConditions .= " AND user_gallery_album.isPrivate = 0";
			$this->albumList->sqlOrderBy = 'user_gallery_album.showOrder ASC';
			$this->albumList->readObjects();
			
			$this->photoList = new UserGalleryPhotoList();
			$this->photoList->sqlConditions .= 'user_gallery.ownerID = '.WCF::getUser()->userID;;
			$this->photoList->sqlConditions .= ' AND user_gallery.albumID = 0';
			$this->photoList->readObjects();
		}
		else if($eventName == "show") {		
			WCF::getTPL()->assign(array(
				'convertedSize' => UserGalleryPhotosXMLListPage::convertSize(INLINE_IMAGE_MAX_WIDTH),
				'albums' => $this->albumList->getObjects(),
				'uncategorized' => $this->photoList->getObjects()));
			
			WCF::getTPL()->append(array(
						'additionalTabs' => '<li id="userGalleryTab"><a onclick="tabbedPane.openTab(\'userGallery\');"><span>'.WCF::getLanguage()->get('wcf.user.gallery').'</span></a></li>',
						'additionalSubTabs' => WCF::getTPL()->fetch('messageFormGallery')
					));
		}
		
		
	}
}
?>