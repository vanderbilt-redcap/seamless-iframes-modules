<?php
namespace Vanderbilt\SeamlessIframesExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class SeamlessIframesExternalModule extends AbstractExternalModule
{
	function redcap_every_page_top()
	{
		?>
		<script>
			if(window.frameElement){
				// This object used to intialize the iframe resizer object loaded via CDN below.
				var iFrameResizer = {
					messageCallback: function(data){
						if(data.message === 'load resources'){
							data.resources.forEach(function(url){
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
			if(window.frameElement){
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
