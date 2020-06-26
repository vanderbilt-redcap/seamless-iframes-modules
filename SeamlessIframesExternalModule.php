<?php
namespace Vanderbilt\SeamlessIframesExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class SeamlessIframesExternalModule extends AbstractExternalModule
{
	function redcap_every_page_top()
	{
		$config = $this->getConfig();
		$moduleName = $config['name'];

		?>
		<script>
			// Taken from: https://stackoverflow.com/questions/326069/how-to-identify-if-a-webpage-is-being-loaded-inside-an-iframe-or-directly-into-t
			// We tried the window.frameElement method first, but that returns null in iframes when the origins are not the same.
			function inIframe () {
			    try {
			        return window.self !== window.top;
			    } catch (e) {
			        return true;
			    }
			}

			if(inIframe()){
				// This object used to initialize the iframe resizer object loaded via CDN below.
				window.iFrameResizer = {
					messageCallback: function(data){
						if(data.message === 'load resources'){
							var allowedUrlPrefixes = <?=json_encode($this->getProjectSetting('allowed-url-prefixes'))?>;
							if(allowedUrlPrefixes === null){
								allowedUrlPrefixes = []
							}

							data.resources.forEach(function(url){
								var allowed = allowedUrlPrefixes.some(function(prefix){
									return url.indexOf(prefix) === 0
								})

								if(!allowed){
									console.error('The following URL could not be loaded because it is not allowed in the configuration for the <?=$moduleName?> module: ', url)
									return
								}

								var parts = url.split('?')
								var urlWithoutParams = parts[0]
								parts = urlWithoutParams.split('/')
								var filename = parts.pop()
								parts = filename.split('.')
								var extension = parts.pop().toLowerCase()

								var element
								if(extension === 'css'){
									element = '<link rel="stylesheet" type="text/css" href="' + url + '">'
								}
								else if(extension === 'js'){
									element = '<script src="' + url + '"><\/script>'
								}
								else{
									console.log('Unsupported extension for resource: ' + url)
								}

								if(element){
									$('head').append(element)
								}
							})

							if(<?=json_encode($this->isSurveyPage())?>){
								// Fix a problem with the required fields popup inside iframes.
								var requiredPopup = $('#reqPopup')
								if(requiredPopup.length === 1){
									requiredPopup = requiredPopup.closest('.ui-dialog')
									requiredPopup.show()
									requiredPopup.css({
										top: '100px',
										left: parseInt(requiredPopup.css('left'))/2 + 'px'
									})
								}
							}
						}
					}
				}
			}
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.2/iframeResizer.contentWindow.js" integrity="sha256-GiVOlVInnsReP+g7/SinFGKrkwiDewfPP5kCpXhBdUU=" crossorigin="anonymous"></script>
		<?php
	}

	function redcap_survey_complete()
	{
		?>
		<script>
			if(inIframe()){
				// We're in an iframe.
				// Hide the close survey button since it can't close the window in an iframe anyway.
				$('button').each(function() {
					var button = $(this)
					if(button.text() === 'Close survey'){
						button.hide()
					}
				})
			}
		</script>
		<?php
	}
}
