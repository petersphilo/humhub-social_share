<?php

use yii\helpers\Html;

// humhub\modules\social_share\Assets::register($this);
use humhub\models\Setting;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Json;

$theGroupsEcho=Json::decode(Setting::Get('theGroups', 'social_share'));
$ResponsiveTopEcho=Setting::Get('ResponsiveTop', 'social_share');


?>
<div class="panel panel-default" id="social_share-panel">

	<!-- Display panel menu widget -->
	<?php humhub\widgets\PanelMenu::widget(array('id' => 'social_share-panel')); ?>
	<ul data-ui-widget="ui.panel.PanelMenu" data-ui-init="" class="nav nav-pills preferences">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-label="Toggle" aria-haspopup="true"><i class="fa fa-angle-down" aria-hidden="true"></i></a>
			<ul class="dropdown-menu pull-right">
				<li>
					<a class="panel-collapse panel-collapsed" data-action-click="toggle" data-ui-loader=""><i class="fa fa-minus-square" aria-hidden="true"></i>Collapse</a>
				</li>
			</ul>
		</li>
	</ul>
	<div class="panel-heading">
		<strong><?php echo Yii::t('SocialShareModule.base','Show You Share!'); ?> &#x1F92A;</strong>
	</div>
	
	<div class="panel-body">
		<?php
			echo Yii::t('SocialShareModule.base','Paste a link to a post in which you shared the WIA website, or some other content from Women In Africa..'); 
			echo '<br><br>';
			echo Yii::t('SocialShareModule.base','And get points for each new link you share!'); 
		
			echo '<br><br>'; 
			
		?>
		<form id='social_share-ShareForm' action='/social_share/response/response' method='get' data-target="#globalModal">
			<div class='form-group required'>
				<label class="control-label" for="ShareForm-theURL"><?php echo Yii::t('SocialShareModule.base','Paste the link below'); ?>:</label>
				<input type='url' id='ShareForm-theURL' class='form-control required' >
				<p class="help-block help-block-error"></p>
			</div>
			<button type="submit" class="btn btn-info btn-sm" id='ShareForm-Submit'><?php echo Yii::t('SocialShareModule.base','Submit'); ?></button>
			<a href='' style='display:none; ' id='ShareForm-SubmitLink' data-target='#globalModal'></a>
			<script>
				$(function(){
					/**/
					function isValidHttpUrl(str) {
						try {
							const newUrl = new URL(str);
							return newUrl.protocol === 'http:' || newUrl.protocol === 'https:';
							} 
						catch (err) {
							return false;
							}
						}
					
					$('#social_share-ShareForm').on('submit',function(e){
						e.preventDefault(); 
						
						var SharedURLEL=$('#ShareForm-theURL'), 
							MyFromGroup=$('#social_share-ShareForm .form-group'), 
							SharedURL=SharedURLEL.val(), 
							theErrorText="<?php echo Yii::t('SocialShareModule.base','Please provide a valid url'); ?>", 
							TheNewhref='/social_share/response/response?TheSharedURL='+encodeURIComponent(SharedURL);
						
						MyFromGroup.removeClass('has-error'); 
						
						if(isValidHttpUrl(SharedURL)){
							//console.log(/^([^@\s]+@[^@\s]+\.[^@\s]+)$/.test(SharedURL)); 
							MyFromGroup.removeClass('has-error'); 
							$('#ShareForm-SubmitLink').attr({href:TheNewhref}).click(); 
							$('#ShareForm-SubmitLink').attr({href:''}); 
							setTimeout(function(){SharedURLEL.val('');}, 1000); 
							}
						else{
							MyFromGroup.addClass('has-error'); 
							$('.help-block-error').text(theErrorText); 
							}
						})
					
					}); 
			</script>
		</form>
		<?php
			if($ResponsiveTopEcho==1){
		?>
		<script>
			$(function(){
	
				var myVar=new Object(); 
					myVar.DataStream=$('.data-stream-content'); 
					myVar.WallEntry=$('.wall-entry'); 
					myVar.DashBtn=$('[data-menu-id="dashboard"]'); 
	
				function DumpScrollOther(){
					myVar.WinWidth=$(window).width(); 
					myVar.zero=0; 
					if($('#social_share-panel').length && $('.layout-sidebar-container > #social_share-panel').length && myVar.WinWidth < 992 && $('#layout-content > .container > .row > .layout-content-container').length && !$('#layout-content > .container > .row > .layout-content-container > #social_share-panel:first-child').length){
						$('#social_share-panel').detach().prependTo('.layout-content-container');
						//console.log('timing 1 New fired'); 
						}
					if($('#social_share-panel').length && $('.layout-content-container > #social_share-panel').length && myVar.WinWidth > 991 && $('#layout-content > .container > .row > .layout-content-container').length){
						$('#social_share-panel').detach().prependTo('.layout-sidebar-container');
						//console.log('timing 2 New fired'); 
						}
					}
	
				function DumpScrollOtherOnATimer(){
					if($('html').hasClass('nprogress-busy')){setTimeout(function(){DumpScrollOtherOnATimer(); },10); return; }
					else{
						DumpScrollOther(); 
						setTimeout(function(){DumpScrollOther(); },600);
						}
					}
	
				DumpScrollOther();
				DumpScrollOtherOnATimer(); 
	
				$(window).on('scroll resize load', function(){DumpScrollOther(); }); 
	
				}); 
		</script>
		<?php
			}
		?>
	</div>
</div>

