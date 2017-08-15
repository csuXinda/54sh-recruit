<?php
/**
 * @author xinda
 * @modify_time 2017-08-03
 */

namespace xinda\recruit\model;

defined(PATH) OR exit('invalid path');

require_once '../vendor/autoload.php';
require_once '../config/config.php';
require_once 'DB.php';

//命名空间，下文直接throw exception

class Excel
{

    protected $name = '';
    protected $data = array();

    protected $tab = array('A1' => '姓名', 'B1' => '电话', 'C1' => '申报部门', 'D1' => '第一志愿', 'E1' => '第二志愿', 'F1' => '自我简介', 'G1' => '性别', 'H1' => '学院', 'I1' => '班级', 'J1' => '学号', 'K1' => '出生年月', 'L1' => '时间');

    public function __construct($name, $data)
    {
        $this->name = $name;
        $this->data = $data;
        $this->write();
    }
    
    /**
     * 生成排行excel文件
     * @param  array $data 数组
     * @return none
     */
    public function write()
    {
        $objPHPExcel = new PHPExcel();

        foreach ($this->tab as $key => $value) {
            # code...
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($key, $value);
        }
        $objPHPExcel->getActiveSheet()->setTitle($this->name); //设置工作表名称
        // ->setCellValue('A1', '公众号')
        // ->setCellValue('B1', '文章总数')
        // ->setCellValue('C1', '阅读总数')
        // ->setCellValue('D1', '平均阅读数')
        // ->setCellValue('E1', '点赞总数')
        // ->setCellValue('F1', 'WCI')
        // ->setCellValue('G1', '总排名变化');//待解决

        $i = 2;
        foreach ($this->data as $key => $value) {

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $value['phone']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $value['organization']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $value['depart1']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $value['depart2']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $value['introduce']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $value['sex']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $value['college']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $value['class']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $i, $value['student_id']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $value['born']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $value['time']);

            $i++;

        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // $objWriter->save("test2.xls"); //转存的文件
        $this->output($objPHPExcel);
    }

    public function output($objPHPExcel)
    {

        ob_end_clean();
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$this->name");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }
}
