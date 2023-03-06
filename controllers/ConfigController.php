<?php

namespace humhub\modules\social_share\controllers;

use Yii;
use yii\console\Controller;
use yii\web\Request;
use humhub\modules\social_share\models\ConfigureForm;
use humhub\models\Setting;
use yii\helpers\Json;

/**
 * Defines the configure actions.
 *
 * @package humhub.modules.social_share.controllers
 * @author Marjana Pesic
 */
class ConfigController extends \humhub\modules\admin\components\Controller {
	
	public function behaviors(){
		return [
			'acl' => [
				'class' => \humhub\components\behaviors\AccessControl::className(),
				'adminOnly' => true
				]
			];
		}
	
	/**
	 * Configuration Action for Super Admins
	 */
	public function actionConfig(){
		if(Yii::$app->request->get('SocShareDL')){$this->MyDataRequest(); }
		else{
			$SocShareFrom = new ConfigureForm();
			$SocShareFrom->theGroups = Json::decode(Setting::Get('theGroups', 'social_share'));
			$SocShareFrom->ResponsiveTop = Setting::Get('ResponsiveTop', 'social_share');
			$SocShareFrom->SISortOrder = Setting::Get('SISortOrder', 'social_share');
			$SocShareFrom->PreviewMaxRows = Setting::Get('PreviewMaxRows', 'social_share');
			if ($SocShareFrom->load(Yii::$app->request->post()) && $SocShareFrom->validate()) {
				$SocShareFrom->theGroups = Setting::Set('theGroups', Json::encode($SocShareFrom->theGroups), 'social_share');
				$SocShareFrom->ResponsiveTop = Setting::Set('ResponsiveTop', $SocShareFrom->ResponsiveTop, 'social_share');
				$SocShareFrom->SISortOrder = Setting::Set('SISortOrder', $SocShareFrom->SISortOrder, 'social_share');
				$SocShareFrom->PreviewMaxRows = Setting::Set('PreviewMaxRows', $SocShareFrom->PreviewMaxRows, 'social_share');
				return $this->redirect(['/social_share/config/config']);
				}

			return $this->render('config', array('model' => $SocShareFrom));
			}
		}
	
	public function MyDataRequest(){
		if(Yii::$app->request->get('SocShareDL')=='Yes'){
			$MyTabChar="\t"; 
			$dlSocShareFile='originator_email'.$MyTabChar.'username'.$MyTabChar.'originator_ID'.$MyTabChar.'shared_link'.$MyTabChar.'date_created'."\n"; 
			$dlSocShare_cmd=Yii::$app->db->createCommand("SELECT originator_email,username,originator_ID,shared_link,date_created 
				FROM social_share ORDER BY id desc;")->queryAll(); 
			foreach($dlSocShare_cmd as $dlSocShare_row){
				$dlSocShareFile.=$dlSocShare_row['originator_email'].$MyTabChar
				.$dlSocShare_row['username'].$MyTabChar
				.$dlSocShare_row['originator_ID'].$MyTabChar
				.$dlSocShare_row['shared_link'].$MyTabChar
				.$dlSocShare_row['date_created']
				."\n";
				}
			echo $dlSocShareFile; 
			exit;
			}
		}
	
	}

?>
