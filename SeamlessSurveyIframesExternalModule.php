<?php
namespace Vanderbilt\SeamlessSurveyIframesExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

class SeamlessSurveyIframesExternalModule extends AbstractExternalModule
{
	function hook_survey_page($project_id)
	{
		?>
		<script type='text/javascript'>
			$(document).ready(function() {
				var fontContainer = document.getElementById('changeFont');
				var footerContainer = document.getElementById('footer');
				var pageContainer = document.getElementById('pagecontainer');
				fontContainer.style.display = 'none';
				footerContainer.style.display = 'none';
				pageContainer.style.paddingBottom = '0';
				document.getElementById('container').style.border = 'none';

				// Set overflow hidden to get rid of the scrollbar.
				document.body.style.overflow = 'hidden';

				$('#return_corner').css({
					border: '1px solid #b7b7b7',
					'margin-top': '5px',
					'margin-right': '15px',
					'padding-right': '0px',
					'padding-top': '5px'
				});

				var sendHeightToParent = function(height){
					if(!height){
						height = $('body').height();
					}

					parent.postMessage({
						redcapSurveyHeight: height
					},'*');
				};

				var sendHeightBeforePageLoad = function(height){
					$(window).off('resize');
					parent.postMessage('scrollToTop','*');
					sendHeightToParent(height);
				};

				sendHeightToParent();
				$(window).resize(function(){
					sendHeightToParent();
				});

				$('#dpop button').mouseup(function(){
					sendHeightBeforePageLoad(350);
				});
				
				$('button[name=submit-btn-savereturnlater]').mouseup(function(){
					sendHeightBeforePageLoad(750);
				});
			});
		</script>
		<?php
	}

	function hook_survey_complete($project_id)
	{
		?>
		<script type='text/javascript'>
			$(document).ready(function() {
				var footerContainer = document.getElementById('footer');
				var pageContainer = document.getElementById('pagecontainer');
				footerContainer.style.display = 'none';
				pageContainer.style.paddingBottom = '0';
				document.getElementById('container').style.border = 'none';

				// Set overflow hidden to get rid of the scrollbar.
				document.body.style.overflow = 'hidden';

				// Hide the close survey button.
				$('button').hide();

				// This gapHeight prevents a tiny gap below the iframe content in the parent window.
				var gapHeight = 50;
				document.getElementById('surveyacknowledgment').style['padding-bottom'] = gapHeight + 'px';

				parent.postMessage('scrollToTop', '*');

				var sendHeightToParent = function(){
					parent.postMessage({
						redcapSurveyHeight: $('body').height() - gapHeight
					},'*');
				};

				sendHeightToParent();
				$(window).resize(function(){
					sendHeightToParent();
				});
			});
		</script>
		<?php
	}
}
