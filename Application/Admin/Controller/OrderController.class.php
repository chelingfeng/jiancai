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
        $where = "id > 0";
        if (session('account')['role'] != '0') {
            $where .= ' AND admin_id = '. session('account')['adminid'];
        }
        if($_POST[ 'keyword']){
            $keyword = $_POST['keyword'];
            $where .= " AND (name LIKE '%{$keyword}%' OR village LIKE '%{$keyword}%' OR phone LIKE '%{$keyword}%' OR address LIKE '%{$keyword}%')";
        }
        if($_POST[ 'start_date']){
            $where .= " AND date > '{$_POST[ 'start_date']}'";
        }
        if($_POST[ 'end_date']){
             $where .= " AND date < '{$_POST[ 'end_date']} 23:59:59'";
        }
        $data   = M('order')->where($where)->order('id DESC')->page($_GET['epage'], C('PAGESIZEADMIN'))->select();
        $count  = M('order')->where($where)->count();
        $this->assign('page', page($count));
        $this->assign('data', $data);
        $this->display();
    }

    public function findOrder()
    {
        $order = M('order')->where(['id' => $_POST['id']])->find();
        $this->ajaxReturn(codeReturn(0, $order));
    }
}

