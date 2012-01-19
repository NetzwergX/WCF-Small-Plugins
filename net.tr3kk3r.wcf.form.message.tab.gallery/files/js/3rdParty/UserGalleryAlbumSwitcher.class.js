function UserGalleryAlbumSwitcher(userGalleryAlbums) {	
	this.userGalleryAlbums = userGalleryAlbums;
	this.selectedUserGalleryAlbumID = 0;
	this.init();
}

UserGalleryAlbumSwitcher.prototype.init = function() {	
	if (this.userGalleryAlbums.keys().length > 1) {
		var switcherObj = this;
		// add event listener
		var tabMenuList = document.getElementById('tabMenuList');
		for (var i = 0; i < tabMenuList.childNodes.length; i++) {
			if (tabMenuList.childNodes[i].nodeName.toLowerCase() == 'li') {
				if (tabMenuList.childNodes[i].id == 'userGalleryTab') {
					tabMenuList.childNodes[i].onclick = function() {
						switcherObj.showUserGalleryAlbums.apply(switcherObj);
					}
				}
				else {
					tabMenuList.childNodes[i].onclick = function() { 
						if (this.className.indexOf('disabled') == -1) {
							switcherObj.hideUserGalleryAlbums.apply(switcherObj);
						}
					}
				}
			}
		}
	}
}

UserGalleryAlbumSwitcher.prototype.showUserGalleryAlbums = function() {
	if (this.userGalleryAlbums.keys().length > 1) {
		this.hideUserGalleryAlbums();
		var switcherObj = this;
		var subTabMenu = document.getElementById('subTabMenu');
		
		// create ul
		var ul = document.createElement('ul');
		subTabMenu.firstChild.appendChild(ul);
		
		var ids = this.userGalleryAlbums.keys();
		for (var i = 0; i < ids.length; i++) {
			var id = ids[i];
			// create li
			var li = document.createElement('li');
			ul.appendChild(li);
			li.id = 'userGalleryAlbumLink-'+id;
			li.name = id;
			li.onclick = function() {
				switcherObj.showUserGalleryAlbum.apply(switcherObj, [this.name]);
			}
			if (this.selectedUserGalleryAlbumID == id) li.className = 'activeSubTabMenu';
			
			// create a
			var a = document.createElement('a');
			li.appendChild(a);
			
			// create span
			var span = document.createElement('span');
			a.appendChild(span);
			
			var text = document.createTextNode(this.userGalleryAlbums.get(id).unescapeHTML());
			span.appendChild(text);
		}
	}
}

UserGalleryAlbumSwitcher.prototype.hideUserGalleryAlbums = function() {
	var subTabMenu = document.getElementById('subTabMenu');
	if (subTabMenu.firstChild) {
		for (var i = subTabMenu.firstChild.childNodes.length - 1; i >= 0; i--) {
			subTabMenu.firstChild.removeChild(subTabMenu.firstChild.childNodes[i]);
		}
	}
}
	
UserGalleryAlbumSwitcher.prototype.showUserGalleryAlbum = function(newSelectedID) {	
	var switcherObj = this;
	var ids = this.userGalleryAlbums.keys();
	for (var i = 0; i < ids.length; i++) {
		var id = ids[i];
		// set active status
		var userGalleryAlbumLink = document.getElementById('userGalleryAlbumLink-'+id);
		if (newSelectedID == id) userGalleryAlbumLink.className = 'activeSubTabMenu';
		else userGalleryAlbumLink.className = '';
		
		// show userGalleryAlbum
		var userGalleryAlbum = document.getElementById('userGalleryAlbum-'+id);
		if (newSelectedID == id) {
			if (userGalleryAlbum) {
				userGalleryAlbum.className = 'userGalleryAlbum floatContainer';				
			}
			else {
				// create elements				
				var userGalleryContainer = document.getElementById('userGalleryContainer');
				var ul = document.createElement('ul');
				userGalleryContainer.appendChild(ul);
				ul.id = 'userGalleryAlbum-'+id;
				ul.className = 'userGalleryAlbum floatContainer';
			
				// load userGalleryAlbum
				var requestedUserGalleryAlbumID = id;
				var ajaxRequest = new AjaxRequest();				
				ajaxRequest.openPost('index.php?page=UserGalleryPhotosXMLList', 'albumID='+encodeURIComponent(id), function() {
					switcherObj.receiveUserGalleryAlbum.apply(switcherObj, [ajaxRequest.xmlHttpRequest, requestedUserGalleryAlbumID]);
				});
			}
		}
		else {
			if (userGalleryAlbum) userGalleryAlbum.className = 'hidden';
		}
	}
	
	this.selectedUserGalleryAlbumID = newSelectedID;
}
	
UserGalleryAlbumSwitcher.prototype.receiveUserGalleryAlbum = function(request, id) {	
	if (request.readyState == 4 && request.status == 200 && request.responseXML) {			
		var userGalleryAlbum = document.getElementById('userGalleryAlbum-'+id);
		var userGalleryPhotos = request.responseXML.getElementsByTagName('photos');
		if (userGalleryPhotos.length > 0) {
			for (var i = 0; i < userGalleryPhotos[0].childNodes.length; i++) {
				if (userGalleryPhotos[0].childNodes[i].childNodes.length > 0) {
					var path = '';
					var title = '';
					var link = '';
					var thumbnail = '';
					for (var j = 0; j < userGalleryPhotos[0].childNodes[i].childNodes.length; j++) {
						if (userGalleryPhotos[0].childNodes[i].childNodes[j].nodeName == 'path') {
							path = userGalleryPhotos[0].childNodes[i].childNodes[j].childNodes[0].nodeValue;
						}
						if (userGalleryPhotos[0].childNodes[i].childNodes[j].nodeName == 'title') {
							title = userGalleryPhotos[0].childNodes[i].childNodes[j].childNodes[0].nodeValue;
						}
						if (userGalleryPhotos[0].childNodes[i].childNodes[j].nodeName == 'link') {
							link = userGalleryPhotos[0].childNodes[i].childNodes[j].childNodes[0].nodeValue;
						}
						if (userGalleryPhotos[0].childNodes[i].childNodes[j].nodeName == 'thumbnail') {
							thumbnail = userGalleryPhotos[0].childNodes[i].childNodes[j].childNodes[0].nodeValue;
						}
					}
					
					// create element
					var li = document.createElement('li');
					li.className = "floatedElement"
					userGalleryAlbum.appendChild(li);
					var img = document.createElement('img');
					li.appendChild(img);
					img.src = thumbnail;
					img.onmouseover = function() { this.style.cursor='pointer'; };
					img.title = title;
					img.onclick = this.getUserGalleryInsertFunction(path, title, link);				
				}
			}
		}
		
		request.abort();
	}
}
	
UserGalleryAlbumSwitcher.prototype.getUserGalleryInsertFunction = function(path, title, link) {	
	return function() {tinyMCE.insertBBCodes('[url=\''+link+'\'][img]'+path+'[/img][/url]'); };
}