<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/URLBBCode.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/URLParser.class.php');

/**
 * Adds title to external urls
 * 
 * @author 		Sebastian "Tr3kk3r" Teumert
 * @copyright	(c) 2010-2011 tr3kk3r.net
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		net.tr3kk3r.data.message.bbcode.autoLinkname
 * @subpackage	system.event.listener
 * @category 	Community Framework (third party)
 * @see 		http://www.mywbb.de/board/database.php?action=view&entryid=4840
 */
class URLParserLinknameListener extends URLBBCode implements EventListener {
	/*	extending the url bbcode is ugly, but sadly neccesary here in order to avoid 
		redundant code (need URLBBCode::isInternalURL(), but that is protected) */
	
	private static $cachedURLs = array();
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (empty(URLParser::$text)) return;	
		
		// search pattern for finding all urls without title
		$urlPattern = "~\[url\](.*?)\[/url\]~i";		
		
		URLParser::$text = preg_replace_callback($urlPattern, array($this, 'makeURLTitle'), URLParser::$text);		
	}
	
	/**
	 * callback function for adding the title to external urls
	 * 
	 * @param	array		$matches	
	 */	
	protected function makeURLTitle($matches) {		
		$url = $matches[1];
		// add protocol if necessary
		if (!preg_match("/[a-z]:\/\//si", $url)) $url = 'http://'.$url;				
			
		if(!$this->isInternalURL($url)) { // found an external url (using url bbocdes detection method) 			 
			if (!isset(self::$cachedURLs[$url])) {
				self::$cachedURLs[$url] = $url;
				
				try {
					@ini_set("default_socket_timeout ", 10); //set timeout if possible
					
					/* Get the MIME type and character set */
					$headers = get_headers($url, 1);
					$contentType = $headers['Content-Type'];					
					preg_match('@([\w/+]+)(;\s+charset=(\S+))?@i', $contentType, $matches);
					$mime = '';
					$charset = 'auto';
					if (isset($matches[1]))
						$mime = $matches[1];
					if (isset($matches[3]))
						$charset = $matches[3];	
						
					if($mime = 'text/html') {						
						$filePath = FileUtil::downloadFileFromHttp($url,'fetch_hyperlink');				
						$fileContent = file_get_contents($filePath);									
						@unlink($filePath);												
		   				if (preg_match("/<title>(.*?)<\/title>/is", $fileContent, $titleMatches)) // cache title
		   			 		self::$cachedURLs[$url] = self::normalizeTitle($titleMatches[1], $charset);
					}	   								
				}
				catch (Exception $e) { /* if fetching the title fails for whatever reason, simply do nothing */ }					 
			}			
			return '[url=\''.$url.'\']'.self::$cachedURLs[$url].'[/url]';
		}		
		return '[url]'.$url.'[/url]';			
	}	
	
	/**
	 * Converts the encoding of an title into the charset used from the database
	 * 
	 * @param string $title			the title to be converted
	 * @param string $fromCharset	the charset of the title, using 'auto' if not set
	 */
	protected static function normalizeTitle($title, $fromCharset = 'auto'){
		//remove uneccessary empty characters und convert html special chars
		$title = StringUtil::convertEncoding($fromCharset, WCF::getDB()->getCharset(), $title);
		
		//return title converted to the used charset.
		return StringUtil::decodeHTML(StringUtil::trim($title)); 			
	}
}

?>