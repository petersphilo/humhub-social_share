<?php

use humhub\modules\dashboard\widgets\Sidebar;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\components\ModuleManager;

return [
	'id' => 'social_share',
	'class' => 'humhub\modules\social_share\Module',
	'namespace' => 'humhub\modules\social_share',
	'events' => [
		['class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => ['humhub\modules\social_share\Module', 'onSidebarInit']],
		['class' => AdminMenu::className(), 'event' => AdminMenu::EVENT_INIT, 'callback' => ['humhub\modules\social_share\Module', 'onAdminMenuInit']],
	],
];
?>
