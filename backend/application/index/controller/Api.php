<?php
/**
 * Created by PhpStorm.
 * User: w
 * Date: 2019/1/23
 * Time: 11:19
 */

namespace app\index\controller;

use app\common\Base;
use app\index\model\CrmMobile;
use app\index\model\CrmUseranswer;
use app\task\crontab\profitdata;
use app\index\model\Task;
use app\index\model\Number;
use app\index\model\User;
use app\index\model\Seat;
use app\index\model\SeatWx;
use app\index\model\CrmUsertype;
use Cookie;
use Config;
use Cache;
use Env;
use tools\Aes;
use tools\NetWork;
use tools\ShortTextCompare;
use PHPExcel_IOFactory;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
use PHPExcel_CachedObjectStorageFactory;
use PHPExcel_Settings;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;

class Api extends Base
{
    // 生成报表excel
    public function exlcountdata()
    {
//        $aid = Cookie::get('aid');
//        $aid = input("post.aid");
        $uid = $_GET["aid"];
        $time = $_GET["time"];

        // 设置缓存方式，减少对内存的占用
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('cacheTime' => 300);
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $filename = date("Ymdhis") . '.xlsx';

        $date = date("m:d");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "白宾机器人数据(" . $time . ")");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', "序号")
            ->setCellValue('B2', "部门")
            ->setCellValue('C2', "业务姓名")
            ->setCellValue('D2', "拨打数")
            ->setCellValue('E2', "接通数")
            ->setCellValue('F2', "接通率")
            ->setCellValue('G2', "分配数")
            ->setCellValue('H2', "成功添加数")
            ->setCellValue('I2', "添加率");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $j = 3;
        $q = $j;
        $res['rows'] = array();
        $robot = Task::where("uid", $uid)->select();
        $rs = User::where("id", $uid)->find();
        $countcallnum = 0;
        $countbillnum = 0;
        $countbilllv = 0;
        $countfenpeinum = 0;
        $countaddnum = 0;
        $countaddlv = 0;
        $i = 0;

        if (empty($time)) {
            $time = 'today';
        }
        $rs2 = SeatWx::where("aid",$uid)->whereBetweenTime('dateline', $time)->find();

        foreach ($robot as $v => $k) {
            $res['rows'][$v]["bumen"] = $rs["username"];
//            $rs2 = SeatWx::where("id", $k["id"])->whereTime('dateline', $time)->find();
            $num = new Number();
            $num->setid($k["uuid"]);

            $res['rows'][$v]["username"] = $k["name"];
            $res['rows'][$v]["callnum"] = $num->whereTime('calldate', $time)->count();
            $countcallnum += $res['rows'][$v]["callnum"];

            //接通率
            $res['rows'][$v]["billnum"] = $num->whereTime('calldate', $time)->where('bill', '>', 0)->count();
            $countbillnum += $res['rows'][$v]["billnum"];
            if ($res['rows'][$v]["callnum"] > 0) {
                $res['rows'][$v]["billlv"] = ceil(($res['rows'][$v]["billnum"] / $res['rows'][$v]["callnum"]) * 100) . "%";
                $countbilllv += ceil(($res['rows'][$v]["billnum"] / $res['rows'][$v]["callnum"]) * 100);
            } else {
                $res['rows'][$v]["billlv"] = (0) . '%';
            }

            //A类客户
//            $a = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')
//                ->whereTime('a.calldate', $time)->where("b.type", "a")->where('a.state', 10)->where('b.zid', $k["zid"])
//                ->count();
            $a = CrmUsertype::where("tid",$k["id"])->where("type", "a")->whereBetweenTime("dateline",$time)->count();
            //B类客户
//            $b = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')
//                ->whereTime('a.calldate', $time)->where("b.type", "b")->where('a.state', 10)->where('b.zid', $k["zid"])->count();
            $b = CrmUsertype::where("tid",$k["id"])->where("type", "b")->whereBetweenTime("dateline",$time)->count();
            $res['rows'][$v]["fenpeinum"] = $a + $b;
            $countfenpeinum += $res['rows'][$v]["fenpeinum"];

            if (empty($rs2)) {
                $res['rows'][$v]["addnum"] = 0;
            } else {
                $res['rows'][$v]["addnum"] = $rs2["todaywx"];
                $countaddnum = $rs2["todaywx"];
            }

            if ($res['rows'][$v]["addnum"] > 0 && $res['rows'][$v]["fenpeinum"] > 0) {
                $res['rows'][$v]["addlv"] = ceil(($res['rows'][$v]["addnum"] / $res['rows'][$v]["fenpeinum"]) * 100) . "%";
                $countaddlv += ceil(($res['rows'][$v]["addnum"] / $res['rows'][$v]["fenpeinum"]) * 100);
            } else {
                $res['rows'][$v]["addlv"] = (0) . '%';
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $j, $i)
                ->setCellValue('B' . $j, $res['rows'][$v]["bumen"])
                ->setCellValue('C' . $j, $res['rows'][$v]["username"])
                ->setCellValue('D' . $j, $res['rows'][$v]["callnum"])
                ->setCellValue('E' . $j, $res['rows'][$v]["billnum"])
                ->setCellValue('F' . $j, $res['rows'][$v]["billlv"])
                ->setCellValue('G' . $j, $res['rows'][$v]["fenpeinum"])
                ->setCellValue('H' . $j, $res['rows'][$v]["addnum"])
                ->setCellValue('I' . $j, $res['rows'][$v]["addlv"]);

            //设置边框
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');
            $j++;
            $i++;
        }

        $z = $j - 1;

        if ($countcallnum > 0 && $countbillnum > 0) {
            $callnum = ceil(($countbillnum / $countcallnum) * 100);
        } else {
            $callnum = 0;
        }

        if ($countaddnum > 0 && $countfenpeinum > 0) {
            $fenpeinum = ceil(($countaddnum / $countfenpeinum) * 100);
        } else {
            $fenpeinum = 0;
        }

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $q . ':B' . $z);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $j, "合计");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $j . ':C' . $j);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D' . $j, $countcallnum)
            ->setCellValue('E' . $j, $countbillnum)
            ->setCellValue('F' . $j, $callnum . "%")
            ->setCellValue('G' . $j, $countfenpeinum)
            ->setCellValue('H' . $j, $countaddnum)
            ->setCellValue('I' . $j, $fenpeinum . "%");

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H' . $j)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $j)->getFont()->setBold(true);

        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('D' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('G' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('H' . $j)->getFill()->getStartColor()->setARGB('fff072');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $j)->getFill()->getStartColor()->setARGB('fff072');

        //设置边框
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getTop()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getLeft()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getBottom()->getColor()->setARGB('FF000000');
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $j)->getBorders()->getRight()->getColor()->setARGB('FF000000');

        $filePath = Env::get('runtime_path') . "exldown/" . $filename;
        $objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);

        //发出301头部
        header('HTTP/1.1 301 Moved Permanently');
        //跳转到你希望的地址格式
        header('Location: https://sai.bbxxjs.com/exldown/' . $filename);
    }

    // 生成号码簿excel
    public function extoexl()
    {
        $token = input('token');
        $uid = Cache::get($token);
        $t = input("t");
        $starttime = input("st");
        $endtime = input("et");
        $uids = User::where("sid", $uid)->column("id");

        // 设置缓存方式，减少对内存的占用
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('cacheTime' => 300);
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $filename = date("Ymdhis") . '.xlsx';
        $j = 2;
        $vacant_array = ['does not exist', 'not in service', 'barring of incoming', 'call reminder', 'forwarded', 'number change', 'line fault'];
        date_default_timezone_set('PRC');
        $str1 = $starttime." 00:00:00";
        $str2 = $endtime." 23:59:59";
        $start = strtotime($str1);
        $end = strtotime($str2);
        $today = date('Y-m-d');
        $str3 = $today." 00:00:00";
        $str4 = $today." 23:59:59";
        $today_start = strtotime($str3);
        $today_end = strtotime($str4);
        foreach ($uids as $uid) {
            $task = Task::where("uid", $uid)->select();
            foreach ($task as $k => $v) {
                $num = new Number();
                $num->setid($v['uuid']);

                if (!empty($starttime) && !empty($endtime) && $starttime == $endtime) {
                    if ($t == "d") {
                        $cha = $num->whereBetweenTime('calldate', $starttime)->whereIn("status", $vacant_array)->select();
                    } elseif ($t == "e") {
                        $cha = $num->whereBetweenTime('calldate', $starttime)->where("bill", 0)->select();
                    } elseif ($t == "f") {
                        $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->whereBetweenTime('a.calldate', $starttime)->where("b.type", "<>", "a")->where("b.type", "<>", "b")->where('a.state', 10)->where('b.tid', $v["id"])->select();
                    } else {
//                         $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->whereBetweenTime('a.calldate', $starttime)->where("b.type", $t)->where('a.state', 10)->where('b.zid', $v["id"])->select();
                         $cha = CrmUsertype::where("tid",$v["id"])->where("type", $t)->whereBetweenTime("dateline",$start,$end)->select();
                    }
                } else if (!empty($starttime) || !empty($endtime)) {
                    if ($starttime) {
                        $where[] = ['calldate', '>=', $starttime];
                        $where2[] = ['a.calldate', '>=', $starttime];
                    }
                    if ($endtime) {
                        $where[] = ['calldate', '<=', $endtime];
                        $where2[] = ['a.calldate', '<=', $endtime];
                    }

                    if ($t == "d") {
                        $cha = $num->where($where)->whereIn("status", $vacant_array)->select();
                    } elseif ($t == "e") {
                        $cha = $num->where($where)->where("bill", 0)->select();
                    } elseif ($t == "f") {
                        $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->where($where2)->where("b.type", "<>", "a")->where("b.type", "<>", "b")->where('a.state', 10)->where('b.tid', $v["id"])->select();
                    } else {
//                        $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->where($where2)->where("b.type", $t)->where('a.state', 10)->where('b.zid', $v["id"])->select();
                        $cha = CrmUsertype::where("tid", $v["id"])->where("type",$t)->whereBetweenTime("dateline",$start,$end)->select();
                    }
                } else {
                    if ($t == "d") {
                        $cha = $num->whereTime('calldate', 'today')->whereIn("status", $vacant_array)->select();
                    } elseif ($t == "e") {
                        $cha = $num->whereTime('calldate', 'today')->where("bill", 0)->select();
                    } elseif ($t == "f") {
                        $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->whereTime('a.calldate', 'today')->where("b.type", "<>", "a")->where("b.type", "<>", "b")->where('a.state', 10)->where('b.tid', $v["id"])->select();
                    } else {
//                        $cha = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')->whereTime('a.calldate', 'today')->where("b.type", $t)->where('a.state', 10)->where('b.zid', $v["id"])->select();
                        $cha = CrmUsertype::where("tid", $v["id"])->where("type",$t)->whereBetweenTime("dateline",$today_start,$today_end)->select();
                    }
                }

                foreach ($cha as $v => $k) {
                    if(!empty($k["number"])) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $k["number"]);
                        $j++;
                    }
                }
            }
        }

        $filePath = Env::get('runtime_path') . "exldown/" . $filename;
        $objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);

        //发出301头部
        header('HTTP/1.1 301 Moved Permanently');
        //跳转到你希望的地址格式
        header('Location: https://sai.bbxxjs.com/exldown/' . $filename);

    }

    // 上传并提取待转换的文件
    public function transformexl()
    {
        $file = request()->file('file');

        if ($file) {
            $info = $file->move('../uploads/trans/');

            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                $filename = $info->getSaveName();

            } else {
                // 上传失败获取错误信息
                $filename = $file->getError();

            }
        }

        //上传文件的地址
        $filename = Env::get('root_path') . 'uploads' . DIRECTORY_SEPARATOR . 'trans' . DIRECTORY_SEPARATOR . $filename;

        //提取号码
        file_put_contents(Env::get('runtime_path') . "log/test.txt", "exltomysql@" . $filename, FILE_APPEND);

        //判断截取文件
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        //file_put_contents(Env::get('runtime_path')."log/test.txt", "exltomysql@".$extension, FILE_APPEND);

        // 设置缓存方式，减少对内存的占用
//        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
//        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
//        $cacheSettings = array('cacheTime' => 300);
//        $cacheSettings = array( 'memoryCacheSize' => '8MB' );
//        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        //区分上传文件格式
        if ($extension == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        } else if ($extension == 'xls') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }

        $excel_array = $objPHPExcel->getsheet(0)->toArray();   //转换为数组格式
        array_shift($excel_array);  //删除第一个数组(标题);
        array_shift($excel_array);  //删除第二个数组(行名);
        //file_put_contents(Env::get('runtime_path')."log/test.txt", "exltomysql@".json_encode($excel_array), FILE_APPEND);

        //file_put_contents(Env::get('runtime_path')."log/test.txt", json_encode($excel_array), FILE_APPEND);

        // 释放excel读取占用内存
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        $objPHPExcel = null;
        unset($objReader);
        $objReader = null;

        Ajson('提取成功!', '0000',$filename);


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $filename = date("Ymdhis") . '.xlsx';
        $objPHPExcel->setActiveSheetIndex(0)
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A' . 1, "企业联系电话");
        // 文件行数
        $j = 2;
        $colnum = count($excel_array[1]);

        if($colnum >= 11) {
            foreach ($excel_array as $k => $v) {
                $num = trim($v[10]);
                if (!empty($num)) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        //Excel的第A列，uid是你查出数组的键值，下面以此类推
                        ->setCellValue('A' . $j, $num);
                    $j++;
                }
                $mobile = trim($v[11]);
                if (!empty($mobile)) {
                    if (mb_substr_count($mobile, ';') == 1) {
                        $mobile = str_replace(";", "", $mobile);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile);
                        $j++;
                    } else if (mb_substr_count($mobile, ';') == 2) {
                        $pstart = strpos($mobile, ';');
                        $mobile1 = substr($mobile, 0, $pstart);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile1);
                        $j++;
                        $mobile2 = substr($mobile, $pstart + 1, -1);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile2);
                        $j++;
                    } else if (mb_substr_count($mobile, ';') == 3) {
                        $pstart = strpos($mobile, ';');
                        $mobile1 = substr($mobile, 0, $pstart);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile1);
                        $j++;

                        $mobile1 = substr($mobile, $pstart + 1);

                        $pstart = strpos($mobile1, ';');
                        $mobile2 = substr($mobile1, 0, $pstart);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile2);
                        $j++;;

                        $mobile3 = substr($mobile1, $pstart + 1, -1);
                        $objPHPExcel->setActiveSheetIndex(0)
                            //Excel的第A列，uid是你查出数组的键值，下面以此类推
                            ->setCellValue('A' . $j, $mobile3);
                        $j++;
                    }
                }
            }
            $filePath = Env::get('runtime_path') . "transdown/" . $filename;
            $objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($filePath);
            Ajson('提取成功!', '0000',$filename);
        }else{
            Ajson('提取失败!', '0001');
        }

    }

    // 删除空目录及空子目录
    function rm_empty_dir($path)
    {
        if (is_dir($path) && ($handle = opendir($path)) !== false) {
            while (($file = readdir($handle)) !== false) {     // 遍历文件夹
                if ($file != '.' && $file != '..') {
                    $curfile = $path . '/' . $file;          // 当前目录
                    if (is_dir($curfile)) {                // 目录
                        rm_empty_dir($curfile);          // 如果是目录则继续遍历
                        if (count(scandir($curfile)) == 2) { // 目录为空,=2是因为. 和 ..存在
                            rmdir($curfile);             // 删除空目录
                        }
                    }
                }
            }
            closedir($handle);
        }
    }


//    public function deldir(){
//        $path = input('path');
//        date_default_timezone_set('PRC');
//        if(is_dir($path)){  // 如果是目录则继续
//            $p = scandir($path);    // 扫描一个文件夹内的所有文件夹和文件并返回数组
//            foreach($p as $val){
//                if($val !="." && $val !=".."){  // 排除目录中的.和..
//                    if(is_dir($path.$val)){     // 如果是目录则递归子目录，继续操作
//                        $str = str_replace('-','',$path.$val);
//                        $oneweekage = date("Ymd", strtotime("-1 week"));  // 获取格式为20181230
//                        if($str<$oneweekage) {
//                            deldir($path . $val . '/');  // 子目录中操作删除文件夹和文件
//                            @rmdir($path . $val . '/');  // 目录清空后删除空文件夹
//                        }
//                    }else{
//                        unlink($path.$val);     // 如果是文件直接删除
//                    }
//                }
//            }
//        }
//    }

    function findDelDir($path)
    {
        $ret = [];
        if (is_dir($path)) {  // 如果是目录则继续
            $p = scandir($path);    // 扫描一个文件夹内的所有文件夹和文件并返回数组
            foreach ($p as $val) {
                if ($val != "." && $val != "..") {  // 排除目录中的.和..
                    if (is_dir($path . $val)) {     // 如果是目录则递归子目录，继续操作
                        //因为格式固定，最好不这样改目录名字，可以直接把目录名字转时间戳，之前应该做正则匹配，再比较
                        $pattern1 = '/\d{4}-\d{2}-\d{2}/';
                        $pattern2 = '/\d{8}/';
                        //符合格式就处理
                        if (preg_match($pattern1, $val) || preg_match($pattern2, $val)) {
                            //获取一周前的获取时间戳
                            $mytime = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
                            if (strtotime($val) < $mytime) {
                                //这边直接返回
                                array_push($ret, $path . $val);
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }

    // 清空文件夹函数和清空文件夹后删除空文件夹函数的处理
    public function deldir($path)
    {
        date_default_timezone_set('PRC');
        if (is_dir($path)) {  // 如果是目录则继续
            $p = scandir($path);    // 扫描一个文件夹内的所有文件夹和文件并返回数组
            foreach ($p as $val) {
                if ($val != "." && $val != "..") {  // 排除目录中的.和..
                    if (is_dir($path . $val)) {     // 如果是目录则递归子目录，继续操作
                        $this->deldir($path . $val . '/');  // 子目录中操作删除文件夹和文件
                        rmdir($path . $val . '/');  //目录清空后删除空文件夹  加@屏蔽错误
                    } else {
                        unlink($path . '/' . $val);     // 如果是文件直接删除
                    }
                }
            }
            rmdir($path . '/');
        }
    }

//        public function deleteTest(){
//        $path = input('path');
//        $del_arr = $this->findDelDir($path);
//        foreach ($del_arr as $del_file) {
//            $this->deldir($del_file);
//        }
//    }

    // 删除拨打记录数据 用于定时任务
    public function deleteRecord()
    {
//        date_default_timezone_set('PRC');
//        $path1 = "/usr/local/freeswitch/recordings/";
//        $del_arr = $this->findDelDir($path1);
//        foreach ($del_arr as $del_file) {
//            $this->deldir($del_file); // 删除一周前的录音文件夹  格式：2018-12-11
//        }
//
//        $path2 = "/home/asrdir/";
//        $del_arr = $this->findDelDir($path2);
//        foreach ($del_arr as $del_file) {
//            $this->deldir($del_file); // 删除一周前的录音片段文件夹  格式：20180926
//        }
//
//        $oneweekage1 = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));   // 获取时间戳
//        $oneweekage2 = date("Y-m-d 0:0:0", strtotime("-7 day"));  // 获取格式为2018-12-30 13:26:13
//        CrmUseranswer::where("dateline", '<=', $oneweekage1)->delete();
//        CrmUsertype::where("dateline", '<=', $oneweekage1)->delete();
////        CrmMobile::where("isdel", 1)->delete();
////        CrmUsertype::where("dateline", 'null')->delete();
//        $aids = User::where("id", ">", 0)->column("id");
//        foreach ($aids as $aid) {
//            $zids = Seat::where("aid", $aid)->column("id");
//            foreach ($zids as $zid) {
//                $num = new Number();
//                $num->setid($aid, $zid);
//                $num->whereTime('calldate', '<=', $oneweekage2)->delete();
////                foreach ($callids as $callid) {
////                    $paths = CrmUseranswer::where("callid", $callid)->column("recordfile");
////                    if (!empty($paths)) {
////                        foreach ($paths as $path) {
////                            unlink($path);
////                        }
////                    }
////                    CrmUseranswer::where("callid", $callid)->delete();
////                    $path = $num->where("callid", $callid)->column("recordfile");
////                    var_dump($path);
////                    if (!empty($path)) {
////                        unlink($path);
////                    }
////                    $num->where("callid", $callid)->delete();
////                    CrmUsertype::where("callid", $callid)->delete();
////                }
//            }
//        }
        Ajson('过时数据删除成功!', '0000');
    }

}