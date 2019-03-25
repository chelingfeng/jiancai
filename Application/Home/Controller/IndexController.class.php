<?php

namespace Home\Controller;

class IndexController extends CommonController {

    public function index(){
        if (empty(session('openid'))) {
            exit();
        }
        $user = M('user')->where(['openid' => session('openid')])->find();
        $this->assign('user', $user);
        $this->display();
    }

    public function order()
    {
        $this->assign('system', setting('system'));
        $this->display();
    }

    public function addOrder()
    {
        if (IS_AJAX) {
            $_POST['admin_id'] = session('admin')['adminid'];
            $_POST['create_time'] = date('Y-m-d H:i:s');
            $_POST['update_time'] = date('Y-m-d H:i:s');
            $_POST['scqg'] = setting('system')['scqg'];
            $_POST['dcmb'] = setting('system')['dcmb'];
            $_POST['scmb'] = setting('system')['scmb'];
            $_POST['gjjg'] = setting('system')['gjjg'];
            M('order')->add($_POST);
            $this->ajaxReturn(codeReturn(0));
        }
    }

    public function today()
    {
        $where = "date > '".date('Y-m-d 00:00:00')."' AND date < '".date('Y-m-d 23:59:59')."'";
        if (session('admin')['role'] != '0') {
            $where .= ' AND admin_id = '. session('admin')['adminid'];
        }
        $data = M('order')->where($where)->order('id DESC')->select();
        $this->assign('data', $data);
        $this->display();
    }

    public function detail()
    {
        $data = M('order')->where(['id' => $_GET['id']])->find();
        $this->assign('data', $data);
        $this->assign('system', setting('system'));
        $this->display();
    }

    public function upload()
    {
        $base64_image_content  = $_POST['src'];
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = './Public/Uploads/';
           
            $new_file = $new_file.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                $this->ajaxReturn(['src' => $new_file]);
            }else{
                return false;
            }
        }
    }

    public function print()
    {
        $data = M('order')->where(['id' => $_GET['id']])->find();
        $this->assign('data', $data);
        $this->assign('system', setting('system'));
        $this->display();
    }

    public function searchOrder()
    {
        $keyword = $_POST['keyword'];
        $where = "(name LIKE '%{$keyword}%' OR village LIKE '%{$keyword}%' OR phone LIKE '%{$keyword}%' OR address LIKE '%{$keyword}%')"; 
        if (session('admin')['role'] != '0') {
            $where .= ' AND admin_id = '. session('admin')['adminid'];
        }
        $data = M('order')->where($where)->order('id DESC')->select();
        $this->ajaxReturn(codeReturn(0, $data));
    }
}