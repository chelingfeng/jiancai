<?php

namespace Admin\Controller;

class OrderController extends CommonController
{
	public function __construct()
	{
 		parent::__construct();
	}

    public function index()
    {
       
        $this->assign('page', page($count));
        $this->assign('data', $data);
        $this->display();
    }

    public function findOrder()
    {
        $order = M('order')->where(['id' => $_POST['id']])->find();
        $order['detail'] = json_decode($order['detail'], true);
        $order['user'] = M("user")->where(['id' => $order['user_id']])->find();
        $order['payment'] = C('payment')[$order['payment']];
        $this->ajaxReturn(codeReturn(0, $order));
    }

    public function successOrder()
    {
        M('order')->where(['id' => $_POST['id']])->save(['status' => 'success']);
        $this->ajaxReturn(codeReturn(0));
    }
}

