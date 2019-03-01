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

    public function export(){
        require('ThinkPHP/Extend/Library/ORG/Util/PHPExcel/PHPExcel.php');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '姓名');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '电话');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', '小区');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '地址');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', '定金');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '测量日期');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', '测量内容');

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
        $data   = M('order')->where($where)->order('id DESC')->select();

        foreach($data as $key => $val){
             $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A'.($key +2),  $val['name'],  \ PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B'.($key +2),  $val['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C'.($key +2),  $val['village'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D'.($key +2),  $val['address'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E'.($key +2),  $val['amount'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('F'.($key +2),  $val['date'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('G'.($key +2),  $val['content'], \PHPExcel_Cell_DataType::TYPE_STRING);
        }

        $title = '订单导出';
        $objPHPExcel->getActiveSheet()->setTitle($title);

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN, //细边框  
                )
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:G'.(count($data) + 1))->applyFromArray($styleArray);

        ob_end_clean(); //清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename='.$title.'.xls');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
}

    public function findOrder()
    {
        $order = M('order')->where(['id' => $_POST['id']])->find();
        $this->ajaxReturn(codeReturn(0, $order));
    }
}

