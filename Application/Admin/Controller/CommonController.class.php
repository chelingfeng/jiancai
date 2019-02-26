<?php

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller {

    function __construct(){
		parent::__construct();
		$_GET['epage'] || $_GET['epage'] = 1;
		if(!session('account')){
			$this->error('请先登陆', U('Admin/Login/index'));
		}
		$account = session('account');
		$account['menu'] = C('roles')[$account['role']]['menus'];
		session('account', $account);
	}

	public function _empty($name){
        $this->error('访问的方法不存在');
    }
	
}