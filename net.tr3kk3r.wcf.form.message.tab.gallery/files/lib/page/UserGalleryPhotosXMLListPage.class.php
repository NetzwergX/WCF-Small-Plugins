<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/album/UserGalleryAlbum.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/UserGalleryPhotoList.class.php');

/**
 * Outputs an XML document with a list of photos from a specified, non-private album.
 *
 * @author		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2011 tr3kk3r.net
 * @license 	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package 	net.tr3kk3r.wcf.form.message.tab.gallery
 * @subpackage	data.page
 * @category 	Community Framework & Community Gallery (third party)		
 */
class UserGalleryPhotosXMLListPage extends AbstractPage {
	/**
	 * album object
	 * 
	 * @var	UserGalleryAlbum
	 */
	public $album = null;
	
	/**
	 * list of gallery photos
	 *
	 * @var UserGalleryPhotoList
	 */
	public $photoList = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get album
		if (isset($_REQUEST['albumID'])) {
			$this->albumID = intval($_REQUEST['albumID']);
			$this->album = new UserGalleryAlbum($this->albumID);			
			if (!$this->album->albumID || $this->album->isPrivate) {
				exit;
			}
		}
		else exit;		
	}
	
	public function readData() {
		parent::readData();
		$this->photoList = new UserGalleryPhotoList();
		$this->photoList->sqlConditions .= 'user_gallery.albumID = '.intval($this->album->albumID);
		$this->photoList->readObjects();
	}
	
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();

		$photos = $this->photoList->getObjects();
		
		// get photos	
		if (count($photos)) {
			header('Content-type: text/xml');
			echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n<photos>\n";
			
			foreach ($photos as $photo) {
				echo "\t<photo>\n";
				echo "\t\t<path><![CDATA[".StringUtil::escapeCDATA(PAGE_URL.'/'.$photo->getPhoto(self::convertSize(INLINE_IMAGE_MAX_WIDTH)))."]]></path>\n";									
				echo "\t\t<title><![CDATA[".StringUtil::escapeCDATA($photo->title)."]]></title>\n";	
				echo "\t\t<link><![CDATA[".PAGE_URL."/index.php?page=UserGalleryPhoto&photoID=".$photo->photoID."]]></link>\n";	
				echo "\t\t<thumbnail><![CDATA[".StringUtil::escapeCDATA(PAGE_URL.'/'.$photo->getPhoto('quadratic'))."]]></thumbnail>\n";								
				echo "\t</photo>\n";
			}
						
			echo '</photos>';
		}
		
		exit;
	}
	
	public static function convertSize($size, $forceThumb = true) {
		if(is_numeric($size)){
			if($size >= 75 && $size < 150) return 'quadratic';
			else if($size >= 150 && $size < 240) return 'tiny';
			else if($size >= 240 && $size < 500) return 'small';
			else if($size >= 500 && $size < 1024) return 'medium';
			else if($size >= 1024 && $forceThumb) return 'large';			
			else return '';
		}			
		else 
			switch ($size) {
			case 'quadratic': 
				return 75;
			case 'tiny':
				return 150;
			case 'small': 
				return 240;
			case 'medium':
				return 500;
			case 'large': 
				return 1024;
			default:
				return -1;
			}		
	}
}
?>