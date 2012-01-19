<!-- Start user gallery tab -->
<div class="hidden" id="userGallery">
	<fieldset class="noJavaScript attachmentPreview">
		<legend class="noJavaScript">{lang}wcf.user.gallery{/lang}</legend>
		
		<div id="userGalleryContainer" class="gallery">
			<ul class="userGalleryAlbum floatContainer" id="userGalleryAlbum-0">
			{foreach from=$uncategorized item=photo}
				<li class="floatedElement">					
					<img onmouseover="this.style.cursor='pointer'" onclick="tinyMCE.insertBBCodes('[url={PAGE_URL}/index.php?page=UserGalleryPhoto&photoID={$photo->photoID}][img]{PAGE_URL}/{$photo->getPhoto($convertedSize)}[/img][/url]')" src="{$photo->getPhoto('quadratic')}" alt="" title="{$photo->title}" />
				</li>
			{/foreach}
			</ul>
		</div>
		<p class="copyright smallFont">{lang}net.tr3kk3r.copyright.form.message.gallery{/lang}</p>
	</fieldset>
</div>
<!-- Javascript embedding -->
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/3rdParty/UserGalleryAlbumSwitcher.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	tabbedPane.addTab('userGallery', false);
	
	// define albums
	var userGalleryAlbums = new Hash();
	userGalleryAlbums.set(0, '{lang}wcf.user.gallery.photo.notInAlbum{/lang} ({#$uncategorized|count})');
	{foreach from=$albums item=album}
		userGalleryAlbums.set({@$album->albumID}, '{$album->title} ({#$album->photos})');
	{/foreach}
		
	// init album switcher
	var userGalleryAlbumSwitcher = new UserGalleryAlbumSwitcher(userGalleryAlbums);
	{if $activeTab == 'userGallery'}
		document.observe("dom:loaded", function() {
			userGalleryAlbumSwitcher.showUserGalleryAlbums();
		});
	{/if}
	//]]>
</script>
<!-- End user gallery tab -->
