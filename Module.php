<?php

namespace humhub\modules\social_share;

use Yii;
use yii\helpers\Url;
use humhub\models\Setting;

use humhub\modules\ui\menu\MenuLink;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\admin\permissions\ManageModules;

class Module extends \humhub\components\Module
{

	/**
	 * On build of the dashboard sidebar widget, add the social_share widget if module is enabled.
	 *
	 * @param type $event			
	 */
	
	public static function onSidebarInit($event) {
		if (Yii::$app->hasModule('social_share')) {
			
			$SISortOrderSet=800; 
			$social_share=Yii::$app->getModule('social_share'); 
			if ($social_share->settings->get('SISortOrder') >= 0) {
				$SISortOrderSet = $social_share->settings->get('SISortOrder'); 
				}
			
			$event->sender->addWidget(widgets\Sidebar::className(), array(), array('sortOrder' => intval($SISortOrderSet)));
			}
		}

	public function getConfigUrl() {
		return Url::to(['/social_share/config/config']);
		}

	/**
	 * Enables this module
	 */
	public function enable()
	{
		parent::enable();
		
		$social_share=Yii::$app->getModule('social_share'); 

		if ($social_share->settings->get('theGroups') == '') {
			$social_share->settings->set('theGroups', 0); 
			}
		if ($social_share->settings->get('ResponsiveTop') == '') {
			$social_share->settings->set('ResponsiveTop', 0); 
			}
		if ($social_share->settings->get('SISortOrder') == '') {
			$social_share->settings->set('SISortOrder', 160); 
			}
		if ($social_share->settings->get('PreviewMaxRows') == '') {
			$social_share->settings->set('PreviewMaxRows', 50); 
			}
		}
	
	public static function onAdminMenuInit($event){
		
		if (!Yii::$app->user->can(ManageModules::class)) {
			return;
			}
		
		/** @var AdminMenu $menu */
		$menu = $event->sender;
		$menu->addEntry(new MenuLink([
			'label' => 'Social Share',
			'url' => Url::to(['/social_share/config/config']),
			//'group' => 'manage',
			'icon' => 'commenting-o',
			'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'social_share' && Yii::$app->controller->id == 'admin'),
			'sortOrder' => 700,
			]));
		
		}

}

?>
