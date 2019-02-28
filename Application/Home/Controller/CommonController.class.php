<?php

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller {
	public function __construct()
	{
		parent::__construct();
        $openid = I('get.openid');
		if ($openid) {
            session('openid', $openid);
		}
		if (empty(session('openid'))) {
			exit();
        }
        $admin = M('admin')->where(['openid' => session('openid')])->find();
        session('admin', $admin);
		if (empty(session('admin'))) {
			$this->redirect('Home/Login/index');
			exit();
		}
		$this->assign('system_config', json_encode(setting('system')));
	}
}