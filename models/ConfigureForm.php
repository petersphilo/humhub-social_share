<?php

namespace humhub\modules\social_share\models;

use Yii;

class ConfigureForm extends \yii\base\Model
{

	public $theGroups;
	public $ResponsiveTop;
	public $SISortOrder;
	public $PreviewMaxRows;
	
	public function rules()
	{
		return array(
			array('theGroups', 'required'),
			array('theGroups', 'safe'),
			array('ResponsiveTop', 'required'),
			array('ResponsiveTop', 'integer', 'min' => 0, 'max' => 1),
			array('SISortOrder', 'required'),
			array('SISortOrder', 'integer', 'min' => 0, 'max' => 1000),
			array('PreviewMaxRows', 'required'),
			array('PreviewMaxRows', 'integer', 'min' => 0, 'max' => 10000),
		);
	}
	
	
	public function attributeLabels()
	{
		
		$theGroups_title=Yii::t('SocialShareModule.base','The group IDs Allowed to Share');
		$ResponsiveTop_title=Yii::t('SocialShareModule.base','Put the Widget on Top when Responsive');
		$SISortOrder_title=Yii::t('SocialShareModule.base','Set the sortOrder - Between 0 and 1000');
		$PreviewMaxRows_title=Yii::t('SocialShareModule.base','Set the Max number of Preview Rows - Between 0 and 10 000');
		
		return array(
			'theGroups' => $theGroups_title,
			'ResponsiveTop' => $ResponsiveTop_title,
			'SISortOrder' => $SISortOrder_title,
			'PreviewMaxRows' => $PreviewMaxRows_title,
		);
	}

}
