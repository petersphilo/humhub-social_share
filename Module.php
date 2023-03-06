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
			/**/
			if (Setting::Get('SISortOrder', 'social_share') >= 0) {
				$SISortOrderSet = Setting::Get('SISortOrder', 'social_share'); 
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

		if (Setting::Get('theGroups', 'social_share') == '') {
			Setting::Set('theGroups', 0, 'social_share'); 
			}
		if (Setting::Get('ResponsiveTop', 'social_share') == '') {
			Setting::Set('ResponsiveTop', 0, 'social_share'); 
			}
		if (Setting::Get('SISortOrder', 'social_share') == '') {
			Setting::Set('SISortOrder', 160, 'social_share'); 
			}
		if (Setting::Get('PreviewMaxRows', 'social_share') == '') {
			Setting::Set('PreviewMaxRows', 50, 'social_share'); 
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
