<?php

namespace Admin\Controller;

class IndexController extends CommonController
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
        $this->display();
    }

    public function index2()
    {
        if (!$_POST['amount_month']) {
            $_POST['amount_month'] = date('Y-m');
        }

        if (!$_POST['order_month']) {
            $_POST[ 'order_month'] = date('Y-m');
        }

        $thisDayOrderNum = M('order')->where("date > '".date('Y-m-d')."'")->count();
        $lastDayOrderNum = M('order')->where("date > '".date('Y-m-d', strtotime('-1 day'))."' AND date < '".date('Y-m-d')."'")->count();
        $thisDayAmount = M('order')->where("date > '".date('Y-m-d')."'")->sum('amount');
        $lastDayAmount = M('order')->where("date > '".date('Y-m-d', strtotime('-1 day'))."' AND date < '".date('Y-m-d')."'")->sum('amount');

        $this->assign('data', [
            'thisDayOrderNum' => $thisDayOrderNum ?? 0,
            'lastDayOrderNum' => $lastDayOrderNum ?? 0,
            'thisDayAmount' => $thisDayAmount ?? 0,
            'lastDayAmount' => $lastDayAmount ?? 0,
        ]);
        $this->assign('orderData', $this->getOrderData($_POST['order_month']));
        $this->assign('amountData', $this->getAmountData($_POST['amount_month']));
        $this->assign('y', []);
        $this->assign('months', $this->getMonths());
        $this->display();
    }

    private function getOrderData($month)
    {
        $days = date('t', strtotime($month.'-01'));
        $start = strtotime($month.'-01');
        $x = [];
        $y = [];
        for ($i = 1; $i <= $days; $i++) {
            $x[] = date('Y-m-d', ($i - 1) * 86400 + $start);
        }
        foreach ($x as $day) {
            $y[] = M('order')->where("date > '".$day."' AND date < '".$day ." 23:59:59'")->count();
        }
        return [
            'x' => json_encode($x),
            'y' => json_encode($y),
        ];
    }
    
    private function getAmountData($month)
    {
        $days = date('t', strtotime($month.'-01'));
        $start = strtotime($month.'-01');
        $x = [];
        $y = [];
        for ($i = 1; $i <= $days; $i++) {
            $x[] = date('Y-m-d', ($i - 1) * 86400 + $start);
        }
        foreach ($x as $day) {
            $y[] = M('order')->where("date > '".$day."' AND date < '".$day ." 23:59:59'")->sum('amount') ?? 0;
        }
        return [
            'x' => json_encode($x),
            'y' => json_encode($y),
        ];
    }

    private function getMonths()
    {
        $month = '2019-02';
        $months = [];
        while (true) {
            $months[] = $month;
            if ($month == date('Y-m')) {
                break;
            }
            $time = strtotime($month.'-01');
            $month = date('Y-m', strtotime('+1 month', $time));
        }
        return $months;
    }

    public function upload(){
        if(!empty($_FILES['img']['name'])){
            require ('ThinkPHP/Extend/Library/ORG/Util/UploadFile.class.php');
            $upload = new \UploadFile();
            $upload->maxSize   = 3292200;
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            $upload->savePath  = './Public/Uploads/';
            //设置上传文件规则
            $upload->saveRule = 'uniqid';
            if(!$upload->upload()){
                $error = $upload->getErrorMsg();
                $this->ajaxReturn(array('code' => 1, 'msg' => $error));
            }else{
                $uploadList = $upload->getUploadFileInfo();//取得成功上传的文件信息
                $imgUrl = __ROOT__.'/Public/Uploads/'.$uploadList[0]['savename'];
                $this->ajaxReturn(codeReturn(0, array('imgurl' => $imgUrl)));
            }
        }
    }

}

