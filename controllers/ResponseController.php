<?php

namespace humhub\modules\social_share\controllers;

use humhub\modules\user\components\ActiveQueryUser;
use humhub\modules\user\models\User;

use Yii;

use yii\base\Behavior;
use yii\base\Exception;
use yii\validators\UrlValidator; // https://www.yiiframework.com/doc/api/2.0/yii-validators-urlvalidator

use yii\db; 
use yii\db\Query; 
use yii\db\Command; 

class ResponseController extends \humhub\components\Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors(){
		return [
			'acl' => [
				'class' => \humhub\components\behaviors\AccessControl::className(),
				'guestAllowedActions' => ['response']
				]
			];
		}
	
	
	
	public function actionResponse(){
		$userID=Yii::$app->user->id;
		$CurrentUserName=Yii::$app->user->identity->username;
		$CurrentUsereMail=Yii::$app->user->identity->email;
		$MyBR='<br>'; 
		
		$ResponseMessage=''; 
		
		$TheSharedURL=''; 
		$ActivityLog='';
		$ResponseTitle=Yii::t('SocialShareModule.base','Error'); 
		
		if (Yii::$app->request->get('TheSharedURL')!=''){
			$TheSharedURL=trim(rawurldecode(Yii::$app->request->get('TheSharedURL'))); 
			$ActivityLog='Received the Guest email: '.$TheSharedURL.$MyBR; 
			}else{return $this->renderAjax('response', ['TheSharedURL' => '','TheOriginatorID' => '','ResponseMessage' => 'fatal error; There Was no URL']);}
		
		if ($userID==''){
			return $this->renderAjax('response', ['TheSharedURL' => '','TheOriginatorID' => '','ResponseMessage' => 'fatal error; Problem getting your ID']);
			}
		
		/**/
		$URLSahreOK=1; 
		// Invalid URL
		$validator=new UrlValidator;
		if (!$validator->validate($TheSharedURL)) {
			$URLSahreOK=0; 
			$ResponseMessage.=Yii::t('SocialShareModule.base','The URL you provided could not be validated').$MyBR; 
			//return false;
			}

		// URL already shared
		//$URLExixsts_cmd=Yii::$app->db->createCommand("SELECT id FROM social_share WHERE shared_link='$TheSharedURL';")->queryAll(); 
		$URLExixsts_cmd=Yii::$app->db->createCommand("SELECT id FROM social_share WHERE shared_link=:URL;"); 
		//if ($URLExixsts_cmd != null) {
		if ($URLExixsts_cmd->bindValue(':URL',$TheSharedURL)->queryAll() != null) {
			$URLSahreOK=0; 
			$ResponseMessage.=Yii::t('SocialShareModule.base','The URL you provided <i>has already been shared</i>,<br><b><u>the URL has not been submitted again</u></b>').$MyBR; 
			$ResponseTitle=Yii::t('SocialShareModule.base','URL Not Submitted'); 
			//return false; 
			}
		
		if ($URLSahreOK==1) {
			$InsertTheShare_cmd=Yii::$app->db->createCommand("INSERT INTO social_share (shared_link,originator_ID,originator_email,username) VALUES (:URL,'$userID','$CurrentUsereMail','$CurrentUserName');"); 
			$InsertTheShare_cmd->bindValue(':URL',$TheSharedURL)->query(); 
			
			$ResponseMessage.=Yii::t('SocialShareModule.base','The URL you provided has successfully been recorded!<br>Thank You!').$MyBR; 
			$ResponseTitle=Yii::t('SocialShareModule.base','URL Recorded!'); 
			
			
			/* $userInvite->sendInviteMail(); */
			/* $ResponseMessage.='An email has just been sent to invite your Guest'.$MyBR;  */
			}
		// end
		
		
		return $this->renderAjax('response', [
					'TheSharedURL' => $TheSharedURL,
					'TheOriginatorID' => $userID.'; '.$CurrentUsereMail,
					'ResponseMessage' => $ResponseMessage,
					'ResponseTitle' => $ResponseTitle,
					'ActivityLog' => $ActivityLog
					]);
		}

	}

?>
