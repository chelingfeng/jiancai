<?php

namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('system_config', json_encode(setting('system')));
    }
    public function index()
    {
        if (empty(session('openid'))) {
            exit();
        }
        $this->display();
    }

    public function login()
    {
        if (IS_POST) {
            $admin = M('admin')->where(['del' => 0, 'username' => $_POST['username'], 'password' => md5($_POST['password'])])->find();
            if ($admin) {
                M('admin')->where(['adminid' => $admin['adminid']])->save(['openid' => session('openid')]);
            } else {
                $this->ajaxReturn(codeReturn(10007));
            }
            $this->ajaxReturn(codeReturn(0));
        }
    }

}

