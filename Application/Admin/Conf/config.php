<?php
return array(
	'VIEW_PATH'          => './Template/Admin/',

	'menus' => [
		'Index' => ['name' => '首页', 'icon' => 'shouye'],
		'Order' => ['name' => '订单管理', 'icon' => 'dingdanguanli'],
		'System' => ['name' => '系统设置', 'icon' => 'shezhi'],
	],


	'roles' => [
		0 => ['name' => '超级管理员', 'menus' => []],
		1 => ['name' => '员工', 'menus' => []],
	],
);