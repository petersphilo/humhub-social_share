<?php

use Yii;

use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

use yii\db; 

use humhub\models\Setting;

/**
 * @var $model \humhub\modules\social_share\models\ConfigureForm
 */

$ReadtheGroups=Json::decode(Setting::Get('theGroups', 'social_share'));
if(is_int($ReadtheGroups)){$ReadtheGroups=[$ReadtheGroups]; }
$GettheGroupName_cmd=Yii::$app->db->createCommand("SELECT name FROM `group` WHERE id=:Gid;"); 

$BlankGroupName=Yii::t('SocialShareModule.base','Empty Group (to select no group)'); 

$ConcernedGroups=''; 
foreach($ReadtheGroups as $ReadtheGroup){
	if($ReadtheGroup==0){$ConcernedGroups.=$BlankGroupName."; "; }
	else{$ConcernedGroups.=$GettheGroupName_cmd->bindValue(':Gid',$ReadtheGroup)->queryScalar()." (id: $ReadtheGroup); "; }
	}

$MyGroupsFull=[]; 
$MyGroupsFull+=[0=>$BlankGroupName]; 
$ListAllGroups_cmd=Yii::$app->db->createCommand("SELECT id,name FROM `group`;")->queryAll(); 
foreach($ListAllGroups_cmd as $ListAllGroups_row){
	$GroupName=$ListAllGroups_row['id'].' -- '.$ListAllGroups_row['name']; 
	$MyGroupsFull+=[$ListAllGroups_row['id']=>$GroupName]; 
	}

$MyPreviewMaxRows=Setting::Get('PreviewMaxRows', 'social_share'); 
if($MyPreviewMaxRows==''){$MyPreviewMaxRows=50; }
?>

<div class="panel panel-default">
	<div class="panel-heading">
		Social Share Module Configuration
	</div>
	<div class="panel-body">
		<p>
			<?php 
				echo Yii::t('SocialShareModule.base','The Current Selected Groups are').": <strong>$ConcernedGroups</strong><br>"; 
			?>
		</p>
		<br/>

		<?php $SocShareFrom = ActiveForm::begin(); ?>

		<div class="form-group">
			<?php
				echo $SocShareFrom->field($model, 'theGroups')->checkboxList($MyGroupsFull);
				echo $SocShareFrom->field($model, 'ResponsiveTop')->dropdownList([0=>'No',1=>'Yes']);  
				echo $SocShareFrom->field($model, 'SISortOrder')->textInput(); 
				echo $SocShareFrom->field($model, 'PreviewMaxRows')->textInput(); 
			?>
		</div>
		<span id='MyCurrentGetURL'></span>
		<span id='MyNewGetURL'></span>

		<br>

		<?php echo Html::submitButton('Save', ['class' => 'btn btn-primary']); ?>

		<a class="btn btn-default" href="<?php echo Url::to(['/admin/module']); ?>">
			Back to modules
		</a>
		<?php $SocShareFrom::end(); ?>
		
		<br>
		<hr>
	</div>
	<div class="panel-heading">
		<?php 
			echo Yii::t('SocialShareModule.base','Most Recent Shares')." -- $MyPreviewMaxRows:"; 
		?>
		<div style='float: right; '>
			<span class="btn btn-info btn-sm" id='SocShare-DL'><?php echo Yii::t('SocialShareModule.base','Download Share List'); ?></span>
		</div>
	</div>
	<div class="panel-body">
<?php			
	$GetTheLastFiftyShares_cmd=Yii::$app->db->createCommand("SELECT shared_link,originator_ID,originator_email,username,date_created FROM social_share ORDER BY id desc LIMIT $MyPreviewMaxRows;")->queryAll(); 
	
?>
		<style>
			table.MyRecentShares{width:100%; }
			.MyRecentShares tr:first-of-type td{font-weight:500; background-color:#777; color:#fff; }
			.MyRecentShares td{border:1px solid #ddd; padding:0.25em; }
			.NoWrapLines {white-space: nowrap; }
		</style>
		<table class='MyRecentShares'>
			<tr>
				<td class='NoWrapLines'>
					<?php echo Yii::t('SocialShareModule.base','User eMail'); ?>
				</td>
				<td class='NoWrapLines'>
					<?php echo Yii::t('SocialShareModule.base','User ID'); ?>
				</td>
				<td>
					<?php echo Yii::t('SocialShareModule.base','Shared Link'); ?>
				</td>
				<td class='NoWrapLines'>
					<?php echo Yii::t('SocialShareModule.base','Date Created'); ?>
				</td>
			</tr>
<?php			
	foreach($GetTheLastFiftyShares_cmd as $LastFiftyShares_row){
		$SharedLinkForShow=''; 
		if(strlen($LastFiftyShares_row['shared_link'])>64){$SharedLinkForShow=substr($LastFiftyShares_row['shared_link'],0,64).'...'; }
		else{$SharedLinkForShow=$LastFiftyShares_row['shared_link']; }
		$BuildTableRow='<tr>'
			."<td class='NoWrapLines'><a target='_blank' href='/u/".$LastFiftyShares_row['username']."/'>".$LastFiftyShares_row['originator_email'].'</a></td>'
			.'<td>'.$LastFiftyShares_row['originator_ID'].'</td>'
			."<td><a target='_blank' href='https://href.li/?".$LastFiftyShares_row['shared_link']."'>".$SharedLinkForShow.'</a></td>'
			."<td class='NoWrapLines'>".substr($LastFiftyShares_row['date_created'],0,10).'</td>'
			//.'<td>'.$LastFiftyShares_row['date_created'].'</td>'
			.'</tr>'; 
		echo $BuildTableRow; 
		}
	
?>
		</table>
	</div>
</div>
<script>
	$(function(){
		var MyNewGetURL='',
			MyCurrentGetURL=window.location.search; 
		$('#SocShare-DL').on('click',function(){
			if(MyCurrentGetURL.length){MyNewGetURL=MyCurrentGetURL+'&SocShareDL=Yes'; }
			else{MyNewGetURL='?SocShareDL=Yes'; }
			/* $('#MyCurrentGetURL').text(MyCurrentGetURL);  */
			/* $('#MyNewGetURL').text(MyNewGetURL);  */
			fetch(MyNewGetURL)
				.then(resp => resp.blob())
				.then(blob => {
					var Myurl = window.URL.createObjectURL(blob);
					const TempdlLink = document.createElement('a');
					TempdlLink.style.display = 'none';
					TempdlLink.href = Myurl;
					TempdlLink.download = 'SocialShareList.csv';
					document.body.appendChild(TempdlLink);
					TempdlLink.click();
					window.URL.revokeObjectURL(Myurl);
					TempdlLink.remove();
					})
				.catch(() => alert('something went wrong..'));
			}); 
		}); 
</script>

