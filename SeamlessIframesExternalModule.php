<?php
namespace Vanderbilt\SeamlessIframesExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class SeamlessIframesExternalModule extends AbstractExternalModule
{
	function redcap_every_page_top()
	{
		?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.2/iframeResizer.contentWindow.js" integrity="sha256-GiVOlVInnsReP+g7/SinFGKrkwiDewfPP5kCpXhBdUU=" crossorigin="anonymous"></script>
		<?php
	}

	function redcap_survey_complete()
	{
		?>
		<script>
			if(window.frameElement) {
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
