{if $this->user->userID}
	{if $this->language->countAvailableLanguages() == 2}	
		<li id="userMenuLanguage" class="languagePicker options">		
			{foreach from=$this->language->getAvailableLanguageCodes() item=userLanguageCode key=userLanguageID}
				{if $userLanguageID != $this->language->getLanguageID()}
					<a rel="nofollow" href="index.php?action=UserLanguageSwitch&amp;languageID={@$userLanguageID}{if $this->session->requestURI}&amp;url={$this->session->requestURI|urlencode}{/if}{@SID_ARG_2ND}"><img src="{icon}language{@$userLanguageCode|ucfirst}S.png{/icon}" alt="" /> <span>{lang}wcf.global.language.{@$userLanguageCode}{/lang}</span></a>
				{/if}
			{/foreach}				
		</li>
	{/if}
	
	{if $this->language->countAvailableLanguages() > 2}
		<li id="userMenuLanguage" class="languagePicker options"><a id="changeLanguage" class="hidden"><img src="{icon}language{@$this->language->getLanguageCode()|ucfirst}S.png{/icon}" alt="" /> <span>{lang}wcf.global.language.{@$this->language->getLanguageCode()}{/lang}</span></a>
			<div class="hidden" id="changeLanguageMenu">
				<ul>
					{foreach from=$this->language->getAvailableLanguageCodes() item=userLanguageCode key=userLanguageID}
						<li{if $userLanguageID == $this->language->getLanguageID()} class="active"{/if}>
							<a rel="nofollow" href="index.php?action=UserLanguageSwitch{if $this->session->requestURI}&amp;url={$this->session->requestURI|urlencode}{/if}&amp;languageID={$userLanguageID}{@SID_ARG_2ND}">
								<img src="{icon}language{@$userLanguageCode|ucfirst}S.png{/icon}" alt="" /> 
								<span>{lang}wcf.global.language.{@$userLanguageCode}{/lang}</span>
							</a>
						</li>
					{/foreach}
				</ul>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				onloadEvents.push(function() { document.getElementById('changeLanguage').className=''; });
				popupMenuList.register('changeLanguage');
				//]]>
			</script>
			<noscript>
				<form method="get" action="index.php">
					<div>
						<label><img src="{icon}language{@$this->language->getLanguageCode()|ucfirst}S.png{/icon}" alt="" />
							<select name="lamguageID">
								{htmloptions options=$this->language->getLanguages() selected=$this->language->getLanguageID() disableEncoding=true}
							</select>
						</label>
						<input type="hidden" name="action" value="UserLanguageSwitch" />
						{if $this->session->requestURI}<input type="hidden" name="url" value="{$this->session->requestURI|urlencode}" />{/if}
						{@SID_INPUT_TAG}
						<input type="image" class="inputImage" src="{icon}submitS.png{/icon}" alt="{lang}wcf.global.button.submit{/lang}" />
					</div>
				</form>
			</noscript>
		</li>
	{/if}
{/if}