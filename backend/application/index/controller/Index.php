<?php

namespace app\index\controller;

use app\index\model\Scene;
use app\index\model\SpeechRole;
use think\helper\Time;
use think\View;
use think\Db;
use Cookie;
use Config;
use Cache;
use app\common\Base;
use app\index\model\User;
use app\index\model\UserInfo;
use app\index\model\UserBuy;
use app\index\model\Product;
use app\index\model\Number;
use app\index\model\Numberpool;
use app\index\model\CrmMobile;
use app\index\model\CrmMobilePc;
use app\index\model\CrmUsertype;
use app\index\model\RechargeRemarks;
use app\index\model\Seat;
use app\index\model\SeatWx;
use app\index\model\Task;
use app\index\model\Patter;
use app\index\model\Voice;
use app\index\model\PatterWord;
use app\index\model\UserGroup;
use app\index\model\CallRecord;
use app\index\model\CallInfo;
use app\index\model\WxMenu;
use app\index\model\WordBase;

use Env;
use tools\Aes;
use tools\NetWork;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        $username = input("post.username");
        $password = input("post.password");
        $uuid1 = Uuid::uuid1();

        $rs = User::where("username", $username)->find();
        $token = $uuid1->toString();

        //Cookie::set($token, $rs["id"]);

        if (empty($rs)) {
            Ajson('用户名密码错误!', '0001');
        } else {
            if (getmd5($password) != $rs["password"]) {
                Ajson('用户名密码错误!', '0001');
            } else {
                Cache::store('redis')->set($token, $rs["id"]);
                Ajson('登陆成功!', '0000', $token);
            }
        }


    }

    public function logout()
    {
        cookie::set("Admin-Token", "",['domain'=> '.bbxxjs.com']);
        Ajson('退出成功!', '0000');
    }

    public function userinfo()
    {
        $token = input("post.token");
        $id = Cache::get($token);
        $rs = User::where("id", $id)->find();
        $g = UserGroup::where("id", $rs["gid"])->find();

        $res = array(
            "roles" => array($g["role"]),
            "token" => $token,
            "id" => $id,
            "userinfo" => array(
                "introduction" => $g["name"],
                "avatar" => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                "name" => $rs["username"]
            )
        );

        Ajson('登陆成功!', '0000', $res);
    }

    // 导入号码表
    public function uploadexl()
    {
        $file = request()->file('file');
        $token = input("post.token");
        $uid = Cache::get($token);

        if ($file) {
            $info = $file->move('../uploads/exl/');

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
        $filename = Env::get('root_path') . 'uploads' . DIRECTORY_SEPARATOR . 'exl' . DIRECTORY_SEPARATOR . $filename;

        //判断截取文件
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        //file_put_contents(Env::get('runtime_path')."log/test.txt", "exltomysql@".$extension, FILE_APPEND);

        //区分上传文件格式
        if ($extension == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        } else if ($extension == 'xls') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }

        $excel_array = $objPHPExcel->getsheet(0)->toArray();   //转换为数组格式
        if ($excel_array[0][0] == "号码" || $excel_array[0][0] == "企业公示的联系电话") {
            $this->doRequest('sai.bbxxjs.com', '/exltomysql', array(
                    'filename' => $filename,
                    'name' => $_FILES["file"]["name"],
                    'uid' => $uid,
                )
            );
            //echo $fp;
            Ajson('导入成功!', '0000');
        } else {
            Ajson('格式不正确!', '0001');
        }
    }


    public function doRequest($host, $path, $param = array())
    {
        $query = isset($param) ? http_build_query($param) : '';

        $port = 80;
        $errno = 0;
        $errstr = '';
        $timeout = 10;

        //$fp = fsockopen($host, $port, $errno, $errstr, $timeout);
        $fp = fsockopen('ssl://' . $host, 443, $errno, $errstr, 20);

        $out = "POST " . $path . " HTTP/1.1\r\n";
        $out .= "host:" . $host . "\r\n";
        $out .= "content-length:" . strlen($query) . "\r\n";
        $out .= "content-type:application/x-www-form-urlencoded\r\n";
        $out .= "connection:close\r\n\r\n";
        $out .= $query;

        fputs($fp, $out);

        fclose($fp);

    }

    // 文件写入数据库
    public function exltomysql()
    {
        function isTel($tel, $type = '')
        {
            $tel = trim($tel);
            if(empty($tel)){
                return false;
            }
            $regxArr = array(
//                'sj' => '/^(\+?86-?)?\d{11}$/',
//                'tel' => '/^(\d{3,5})-\d{7,9}(-\d+)?$/',
//                '400' => '/^400(-\d{3,4}){2}$/',
                'sj'=>'/^(\+)?(\d*-?)*$/'
            );
            if ($type && isset($regxArr[$type])) {
                return preg_match($regxArr[$type], $tel) ? true : false;
            }
            foreach ($regxArr as $regx) {
                if (preg_match($regx, $tel)) {
                    return true;
                }
            }
            return false;
        }

        ignore_user_abort(true);
        set_time_limit(0);
        $filename = trim(input('post.filename'));
        $name = trim(input('post.name'));
        $uid = trim(input('post.uid'));
        $t = explode("/", $filename);

        file_put_contents(Env::get('runtime_path') . "log/test.txt", "exltomysql@" . $filename, FILE_APPEND);

        //判断截取文件
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        //file_put_contents(Env::get('runtime_path')."log/test.txt", "exltomysql@".$extension, FILE_APPEND);

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
        //file_put_contents(Env::get('runtime_path')."log/test.txt", "exltomysql@".json_encode($excel_array), FILE_APPEND);
        $data["dateline"] = time();
        $data['name'] = $name;
        $data['filename'] = $filename;
        $data['uid'] = $uid;
        $pid = CrmMobilePc::insertGetId($data);

        $city = [];
        $count = 0;
        //file_put_contents(Env::get('runtime_path')."log/test.txt", json_encode($excel_array), FILE_APPEND);
        foreach ($excel_array as $k => $v) {
            $num = trim($v[0]);
            if (isTel($num)) {
                $city['mobile'] = $num;
                $city['pid'] = $pid;
                $city['uid'] = $uid;
                CrmMobile::insert($city);
		        $count++;
            }
        }
	    CrmMobilePc::where("id", $pid)->update(['znum'=>$count, 'snum'=>$count]);
    }

    // 文件列表显示
    public function mobilelist()
    {
//        $s = input('post.s');
//        $p = input('post.p');
        $name = trim(input('post.name'));
        $token = input('post.token');
        $uid = Cache::get($token);

//        if ($s == 1){
//            $s = 0;
//        }else{
//            $s = ($s-1) * $p;
//        }


        if (!isset($name) || empty($name)) {
            $rs = CrmMobilePc::where("uid", $uid)->where("isdel", 0)->order("id", "desc")->select();
//            $re['total'] = CrmMobilePc::where("sid",$sid)->where("isdel",0)->count();
        } else {
            $rs = CrmMobilePc::where("name", "like", "%" . $name . "%")->where("uid", $uid)->where("isdel", 0)->order("id", "desc")->select();
//            $rs = CrmMobilePc::where("name","like", "%" . $name . "%")->where("sid",$sid)->where("isdel",0)->order("id","desc")->limit($s,$p)->select();
//            $re['total'] = CrmMobilePc::where("name",$name)->where("sid",$sid)->where("isdel",0)->count();
        }

//        $ad = Suser::where("id",$sid)->find();
        $ad = User::where("id", $uid)->find();

        if (count($rs->toArray()) > 0) {
            foreach ($rs as $v => $a) {
                $rs[$v]["user"] = $ad["username"];
//                $sy = CrmMobile::where("pid", $a["id"])->where("uid", $uid)->where("isdel", 0)->count();
//                $rs[$v]["snum"] = $sy;
//                $rs[$v]["znum"] = 0;
//                $pids = CrmMobilePc::where("pid", $a["id"])->column("id");
//                foreach ($pids as $pid) {
//                    $yfp = CrmMobile::where("pid", $pid)->count();
//                    $rs[$v]["znum"] += $yfp;
//                }
//                $rs[$v]["znum"] += $sy;
                $rs[$v]["dateline"] = friendlyDate($a["dateline"], 'mohu');
            }
            $re['rows'] = $rs;
        } else {
            $re['rows'] = [];
        }

        Ajson('查询成功!', '0000', $re);
    }

    // 用户列表显示
    public function userlist()
    {
//        $s = input('post.s');
//        $p = input('post.p');
        $name = input('post.name');
        $token = input('post.token');
        $sid = Cache::get($token);

//        if ($s == 1){
//            $s = 0;
//        }else{
//            $s = ($s-1) * $p;
//        }


        if (empty($name)) {
            $rs = User::where("sid", $sid)->select();
//            $rs = User::where("sid",$sid)->limit($s,$p)->select();
//            $re['total'] = User::where("sid",$sid)->count();
        } else {
            $rs = User::where('username', 'like', '%' . $name . '%')->where("sid", $sid)->select();
//            $rs = User::where('username', 'like', '%' . $name . '%')->where("sid",$sid)->limit($s,$p)->select();
//            $re['total'] = User::where('username', 'like', '%' . $name . '%')->where("sid",$sid)->count();
        }

//        $ad = Suser::where("id",$sid)->find();

        if (count($rs->toArray()) > 0) {
//            foreach ($rs as $v=>$a){
//                $rs[$v]["nme"] = $ad["username"];
//                $rs[$v]["name"] = $rs["username"];
//                $rs[$v]["mobile"] = $rs["mobile"];
//                $rs[$v]["isdown"] = $rs["isdown"];
//            }
            $re['rows'] = $rs;
        } else {
            $re['rows'] = [];
        }

        Ajson('查询成功!', '0000', $re);
    }

    // 下载权限更改
    public function downchange()
    {
        $id = input("post.id");
        $tag = input("post.tag");

        if ($tag == 1) {
            User::where('id', $id)->setField('isdown', $tag);
            Ajson('执行成功!', '0000');
        } else if ($tag == 0) {
            User::where('id', $id)->setField('isdown', $tag);
            Ajson('执行成功!', '0000');
        } else {
            Ajson('执行失败!', '0001');
        }
    }

    // 号码全显权限更改
    public function showchange()
    {
        $id = input("post.id");
        $flag = input("post.flag");

        if ($flag == 1) {
            User::where('id', $id)->setField('notshow', $flag);
            Ajson('执行成功!', '0000');
        } else if ($flag == 0) {
            User::where('id', $id)->setField('notshow', $flag);
            Ajson('执行成功!', '0000');
        } else {
            Ajson('执行失败!', '0001');
        }
    }

//    // 获取话费余额
//    public function getcallmoney()
//    {
//        $sip = input("post.sip");
//        if (!empty($sip) && isset($sip)){
//            $money = getcallmoney($sip);
//        }else{
//            $money = getcallmoney();
//        }
//
//        Ajson('查询成功!', '0000', $money);
//    }

    // 分配号码列表
    public function ywuserlist()
    {
//        $s = input('post.s');
//        $p = input('post.p');
        $username = trim(input('post.name'));
        $token = input('post.token');
        $uid = Cache::get($token);

//        if ($s == 1) {
//            $s = 0;
//        } else {
//            $s = ($s - 1) * $p;
//        }


        if (empty($username)) {
            $rs = User::where("sid", $uid)->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->count();
        } else {
            $rs = User::where("sid", $uid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->count();
        }

        if (count($rs->toArray()) > 0) {
            foreach ($rs as $v => $a) {
                $syhm = CrmMobile::where("uid", $a["id"])->where("use", 0)->where("isdel", 0)->count();
                $rs[$v]["syhm"] = $syhm;
            }
            $re['rows'] = $rs;
        } else {
            $re['rows'] = [];
        }

        Ajson('查询成功!', '0000', $re);
    }

    // 余额信息
    public function yeuserlist()
    {
//        $s = input('post.s');
//        $p = input('post.p');
        $username = trim(input('post.name'));
        $token = input('post.token');
        $uid = Cache::get($token);

//        if ($s == 1) {
//            $s = 0;
//        } else {
//            $s = ($s - 1) * $p;
//        }


        if (empty($username)) {
            $rs = User::where("sid", $uid)->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->count();
        } else {
            $rs = User::where("sid", $uid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->count();
        }

        if (count($rs->toArray()) > 0) {
            foreach ($rs as $v => $a) {
//                $info = UserBuy::where("uid",$a["id"])->where("cid",1)->find();
                if (!empty($a['ai_vos'])) {
//                    $sip = json_decode($info["setting"],true);
                    $money = getcallmoney($a['ai_vos']);
                    if (!empty($money)) {
                        $rs[$v]["callmoney"] = $money["callmoney"];
                        $rs[$v]["password"] = $money["password"];
                        $rs[$v]["e164s"] = $money["e164s"];
                    }
                } else {
                    $rs[$v]["callmoney"] = 0;
                }
            }
            $re['rows'] = $rs;
        } else {
            $re['rows'] = [];
        }

        Ajson('查询成功!', '0000', $re);
    }

    // 分配话费列表
    public function hfuserlist()
    {
//        $s = input('post.s');
//        $p = input('post.p');
        $username = trim(input('post.name'));
        $token = input('post.token');
        $sid = Cache::get($token);

//        if ($s == 1) {
//            $s = 0;
//        } else {
//            $s = ($s - 1) * $p;
//        }


        if (empty($username)) {
            $rs = User::where("sid", $sid)->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->count();
        } else {
            $rs = User::where("sid", $sid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->select();
//            $rs = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->order('id', 'asc')->limit($s, $p)->select();
//            $re['total'] = User::where("sid",$sid)->where('username', 'like', '%' . $username . '%')->count();
        }

        if (count($rs->toArray()) > 0) {
            foreach ($rs as $v => $a) {
                $rs[$v]["amount"] = Task::where("uid", $a["id"])->where("del", 0)->count();
                $limits = User::where("id", $a["id"])->value("charge");
                if ($limits == 0) {
                    $limits = "无限制";
                }
                $rs[$v]["limit"] = $limits;
            }
            $re['rows'] = $rs;
        } else {
            $re['rows'] = [];
        }

        Ajson('查询成功!', '0000', $re);
    }

    // 删除
    public function delnumpc()
    {
        $id = input("post.id");

        //CrmMobile::where("pid",$id)->delete();
//        CrmMobile::where("pid",$id)->setField("isdel",1);
        CrmMobilePc::where("id", $id)->setField("isdel", 1);
        //CrmMobilePc::where("id",$id)->delete();

        Ajson('删除成功!', '0000');
    }

    // 分配号码 剩余号码数信息
    public function creckpc()
    {
        $id = input("post.id");
        $token = input('post.token');
        $uid = Cache::get($token);

        $rs = CrmMobilePc::where("id", $id)->find();

        $rs["snum"] = CrmMobile::where("pid", $id)->where("uid", $uid)->where("isdel", 0)->count();
        $rs["dateline"] = $id . "@" . $uid;

        Ajson('查询成功!', '0000', $rs);
    }

    // 分配话费 余额信息
    public function lefthf()
    {

//        Ajson('查询成功!','0000',$rs);
    }

    // 分配号码
    public function mobilefp()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $pid = input("post.pid");
        $nums = input("post.num");
        $aidlist = input("post.aidlist");

        $info = CrmMobilePc::where("id", $pid)->find();

        $in = array(
            "dateline" => time(),
            "name" => $info["name"] . "子批次",
            "pid" => $pid,
        );

        if (strpos($aidlist, ",") > 0) {
            $aidlist = explode(',', $aidlist);

            foreach ($aidlist as $aid) {
                $in["uid"] = $aid;
                $in["znum"] = $nums;
                $in["snum"] = $nums;
                $npid = CrmMobilePc::insertGetId($in);

                $rs = CrmMobile::where("uid", $uid)->where("use", 0)->where("pid", $pid)->where("isdel", 0)->order("id", "Asc")->limit(0, $nums)->select();

                foreach ($rs as $v => $a) {
//                    CrmMobile::where('id', $a["id"])->setField('pid', $npid);
//                    CrmMobile::where('id', $a["id"])->setField('uid', $aid);
                    CrmMobile::where("id", $a["id"])->update(["pid"=>$npid, "uid"=>$aid]);
                }
                CrmMobilePc::where("id", $pid)->dec("snum", $nums)->update();
            }
        } else {
            $in["uid"] = $aidlist;
            $in["znum"] = $nums;
            $in["snum"] = $nums;
            $npid = CrmMobilePc::insertGetId($in);

            $rs = CrmMobile::where("uid", $uid)->where("use", 0)->where("pid", $pid)->where("isdel", 0)->order("id", "Asc")->limit(0, $nums)->select();

            foreach ($rs as $v => $a) {
//                CrmMobile::where('id', $a["id"])->setField('pid', $npid);
//                CrmMobile::where('id', $a["id"])->setField('uid', $aidlist);
                CrmMobile::where("id", $a["id"])->update(["pid"=>$npid, "uid"=>$aidlist]);
            }
            CrmMobilePc::where("id", $pid)->dec("snum", $nums)->update();
        }

        Ajson('分配成功!', '0000');
    }

    // 分配话费
    public function chargefp()
    {
        $nums = input("post.num");
        $aidlist = input("post.aidlist");

        if (strpos($aidlist, ",") > 0) {
            $aidlist = explode(',', $aidlist);

            foreach ($aidlist as $aid) {
//                $count = Task::where("aid",$aid)->where("del",0)->count();
//                $charge = $nums*$count;
                User::where('id', $aid)->setField('charge', $nums);
            }
        } else {
//            $count = Task::where("aid", $aidlist)->where("del", 0)->count();
//            $charge = $nums*$count;
            User::where('id', $aidlist)->setField('charge', $nums);
        }

        Ajson('分配成功!', '0000');
    }

    // 获取报表信息
    public function countdatalist()
    {
//        $aid = Cookie::get('aid');
//        $s = input('post.s');
//        $p = input('post.p');
        $uid = input('post.aid');
        $time = input('post.time');
//        if ($s == 1) {
//            $s = 0;
//        } else {
//            $s = ($s - 1) * $p;
//        }

        $res['rows'] = array();
        $robot = Task::where("uid", $uid)->select();
        $rs = User::where("id", $uid)->find();
        $rs2 = SeatWx::where("aid", $uid)->whereBetweenTime('dateline', $time)->find();

        $countcallnum = 0;
        $countbillnum = 0;
        $countbilllv = 0;
        $countfenpeinum = 0;
        $countaddnum = 0;
        $countaddlv = 0;
        $i = 0;

        foreach ($robot as $v => $k) {
            $res['rows'][$v]["id"] = $i;
            $res['rows'][$v]["bumen"] = $rs["username"];
//            $rs = Seat::where("id",$k["zid"])->find();

            $num = new Number();
            $num->setid($k["uuid"]);

            $res['rows'][$v]["username"] = $k["name"];
            $res['rows'][$v]["callnum"] = $num->whereBetweenTime('calldate', $time)->count();
            $countcallnum += $res['rows'][$v]["callnum"];

            //接通率
            $res['rows'][$v]["billnum"] = $num->whereBetweenTime('calldate', $time)->where('bill', '>', 0)->count();
            $countbillnum += $res['rows'][$v]["billnum"];
            if ($res['rows'][$v]["callnum"] > 0) {
                $res['rows'][$v]["billlv"] = ceil(($res['rows'][$v]["billnum"] / $res['rows'][$v]["callnum"]) * 100) . "%";
                $countbilllv += ceil(($res['rows'][$v]["billnum"] / $res['rows'][$v]["callnum"]) * 100);
            } else {
                $res['rows'][$v]["billlv"] = (0) . '%';
            }

            //A类客户
//            $a = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')
//                ->whereBetweenTime('a.calldate', $time)->where("b.type", "a")->where('a.state', 10)->where('b.zid', $k["zid"])
//                ->count();
            $a = CrmUsertype::where("tid", $k["id"])->where("type", "a")->whereBetweenTime("dateline", $time)->count();
            //B类客户
//            $b = $num->alias('a')->leftJoin('bbxxjs.bb_crm_usertype b', 'a.callid = b.callid')
//                ->whereBetweenTime('a.calldate', $time)->where("b.type", "b")->where('a.state', 10)->where('b.zid', $k["zid"])->count();
            $b = CrmUsertype::where("tid", $k["id"])->where("type", "b")->whereBetweenTime("dateline", $time)->count();
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

            $i++;
        }


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

        $res['rows'][count($res['rows'])] = array(
            "id" => "合计",
            "bumen" => $countcallnum,
            "username" => $countbillnum,
            "callnum" => $callnum . "%",
            "billnum" => $countfenpeinum,
            "billlv" => $countaddnum,
            "fenpeinum" => $fenpeinum . "%",
        );

        $res['total'] = Task::where("uid", $uid)->count() + 1;

        Ajson('查询成功！', '0000', $res);
    }

    // 提交充值备注信息
    public function setremarks()
    {
        $token = input('post.token');
        $sid = Cache::get($token);
        $remarks = input('post.remarks');
        if (!empty($remarks)) {
            $data['sid'] = $sid;
            $data['remarks'] = $remarks;
            $data['dateline'] = time();
            RechargeRemarks::insert($data);
            Ajson('提交成功!', '0000', $remarks);
        } else {
            Ajson('提交失败!', '0001');
        }
    }

    // 语音资料列表
    public function voicelist()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }
        $re['rows'] = Voice::where("uid", $uid)->limit($page, $pagesize)->select();
        foreach ($re["rows"] as $v => $k) {
            $re["rows"][$v]["path"] = "https://voice.bbxxjs.com/static/voice_recording/" . $uid . "/" . $k["path"];
        }
        $re['total'] = Voice::where("uid", $uid)->count();

        Ajson('查询成功!', '0000', $re);
    }

    // 添加语音资料
    public function addvoice()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $word = input('post.word');
        $path = input('post.path');

        $data['uid'] = $uid;
        $data['word'] = $word;
        $data['path'] = $path;
        $data['addtime'] = time();
        Voice::insert($data);

        Ajson('添加成功!', '0000');
    }

    // 修改语音资料
    public function editvoice()
    {
        $id = input('post.id');
        $word = input('post.word');
        $path = input('post.path');

        $data = array(
            "word" => $word,
            "path" => $path,
            "updatetime" => time(),
        );
        Voice::where("id", $id)->update($data);

        Ajson('添加成功!', '0000');
    }

    // 添加或修改角色
    public function changespeechrole()
    {
        $flag = input('post.flag');
        $common = input('post.name');
        $weights = input('post.weights');

        if($flag == 0){
            $pid = input('post.pid');
            $start = SpeechRole::where("pid", $pid)->where("role","like","business%")->count();
            $num = $start + 1;
            $data["role"] = "business-"."$num";
            $data["pid"] = $pid;
            $data["common"] = $common;
            $data["weights"] = $weights;
            SpeechRole::insert($data);
            Ajson('添加成功!', '0000');
        }else if($flag == 1){
            $id = input('post.id');
            SpeechRole::where("id", $id)->update(["common"=>$common,"weights"=>$weights]);
            Ajson('更新成功!', '0000');
        }
    }

    // 删除语音资料
    public function delvoice()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');

        Voice::where("uid", $uid)->where("id", $id)->delete();

        Ajson('删除成功!', '0000');
    }

    // 话术列表
    public function patterlist()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }
        $res = Patter::where("uid", $uid)->where("version",0)->limit($page, $pagesize)->select();
        $re['total'] = Patter::where("uid", $uid)->count();

        foreach ($res as $v => $k) {
            if (!empty($res[$v]["updatetime"])) {
                $res[$v]["time"] = friendlyDate($k["updatetime"]);
            } else {
                $res[$v]["time"] = friendlyDate($k["addtime"]);
            }
        }
        $re['rows'] = $res;

        Ajson('查询成功!', '0000', $re);
    }

    // 添加话术
    public function addpatter()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $title = input('post.title');

            $data['uid'] = $uid;
            $data['title'] = $title;
            $data['addtime'] = time();
            Patter::insert($data);

            Ajson('添加成功!', '0000');
        }
        Ajson('添加失败!', '0001');
    }

    // 编辑话术
    public function editpatter()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $id = input('post.id');
            $title = input('post.title');

            $data = array(
                "title" => $title,
                "updatetime" => time(),
            );
            Patter::where("id", $id)->update($data);

            Ajson('编辑成功!', '0000');
        }
        Ajson('编辑失败!', '0001');
    }

    public function patterwordlist()
    {
        $typeid = input('post.type');
        $pid = input('post.pid');
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }
        if ($typeid == 0) {
            $res = PatterWord::where("pid", $pid)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->count();
        } else if ($typeid == 1) {
            $res = PatterWord::where("pid", $pid)->where("type", "<>", 0)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->where("type", "<>", 0)->count();
        } else {
            $res = PatterWord::where("pid", $pid)->where("type", 0)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->where("type", 0)->count();
        }

        foreach ($res as $v => $k) {
            if (!empty($res[$v]["updatetime"])) {
                $res[$v]["time"] = friendlyDate($k["updatetime"]);
            } else {
                $res[$v]["time"] = friendlyDate($k["addtime"]);
            }
            if ($res[$v]["y_next"] == -1) {
                $res[$v]["y_word"] = "挂机";
            } else {
                $resy_word = PatterWord::where("id", $res[$v]["y_next"])->find();
                $res[$v]["y_word"] = $resy_word["sign"];
            }
            if ($res[$v]["u_next"] == -1) {
                $res[$v]["u_word"] = "挂机";
            } else {
                $resu_word = PatterWord::where("id", $res[$v]["u_next"])->find();
                $res[$v]["u_word"] = $resu_word["sign"];
            }
            if ($res[$v]["n_next"] == -1) {
                $res[$v]["n_word"] = "挂机";
            } else {
                $resn_word = PatterWord::where("id", $res[$v]["n_next"])->find();
                $res[$v]["n_word"] = $resn_word["sign"];
            }
            $resword = Voice::where("id", $res[$v]["voice_id"])->find();
            $res[$v]["word"] = $resword["word"];
        }
        $re['rows'] = $res;

        Ajson('查询成功!', '0000', $re);
    }

    public function delPatterWord()
    {
        $id = input('post.wid');

        PatterWord::where("id", $id)->delete();
        Ajson('删除成功!', '0000');
    }

    public function allvoice()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $re = Voice::where("uid", $uid)->select();

        Ajson('查询成功!', '0000', $re);
    }

    public function wordlist()
    {
        $pid = input('post.pid');

        $res = PatterWord::where("pid", $pid)->where("type", "<>", 0)->select();
        foreach ($res as $v => $k) {
            $resword = Voice::where("id", $res[$v]["voice_id"])->find();
            $res[$v]["word"] = $resword["word"];
        }

        Ajson('查询成功!', '0000', $res);
    }

    public function addpatterword()
    {
        $data['pid'] = input('post.pid');
        $data['sign'] = input('post.sign');
        $data['type'] = input('post.type');
        $data['score'] = input('post.score');
        $data['voice_id'] = input('post.voice_id');
        $data['order'] = input('post.order');
        $data['y_next'] = input('post.y_next');
        $data['n_next'] = input('post.n_next');
        $data['u_next'] = input('post.u_next');
        $data['addtime'] = time();
        $keyword_arr = input('post.keyword');
        if(strpos($keyword_arr, '|') !== false){
            $keywords = explode('|', $keyword_arr);
        }else if(strpos($keyword_arr, ',') !== false){
            $keywords = explode(',', $keyword_arr);
        }else if(strpos($keyword_arr, '/') !== false){
            $keywords = explode('/', $keyword_arr);
        }else if(strpos($keyword_arr, '、') !== false){
            $keywords = explode('、', $keyword_arr);
        }else if(strpos($keyword_arr, ' ') !== false){
            $keywords = explode(' ', $keyword_arr);
        }else{
            $keywords = array($keyword_arr);
        }
        $flag = 0;
        foreach ($keywords as $keyword) {
            if(!empty($keyword)) {
                $data["keyword"] = $keyword;
                if (isset($data['pid']) && isset($data['type']) && isset($data['voice_id'])) {
                    PatterWord::insert($data);
                    $flag = 1;
                }
            }
        }
        if($flag == 1){
            Ajson('添加成功!', '0000');
        }else{
            Ajson('添加失败!', '0001');
        }
    }

//    public function addpatterword()
//    {
//        $data['pid'] = input('post.pid');
//        $data['sign'] = input('post.sign');
//        $data['type'] = input('post.type');
//        $data['keyword'] = input('post.keyword');
//        $data['score'] = input('post.score');
//        $data['voice_id'] = input('post.voice_id');
//        $data['order'] = input('post.order');
//        $data['y_next'] = input('post.y_next');
//        $data['n_next'] = input('post.n_next');
//        $data['u_next'] = input('post.u_next');
//        $data['addtime'] = time();
//
//        if (isset($data['pid']) && isset($data['type']) && isset($data['voice_id'])) {
//            PatterWord::insert($data);
//            Ajson('添加成功!', '0000');
//        } else {
//            Ajson('添加失败!', '0001');
//        }
//    }

    public function editpatterword()
    {
        $id = input('post.id');
        $data['pid'] = input('post.pid');
        $data['sign'] = input('post.sign');
        $data['type'] = input('post.type');
        $data['keyword'] = input('post.keyword');
        $data['score'] = input('post.score');
        $data['voice_id'] = input('post.voice_id');
        $data['order'] = input('post.order');
        $data['y_next'] = input('post.y_next');
        $data['n_next'] = input('post.n_next');
        $data['u_next'] = input('post.u_next');
        $data['updatetime'] = time();

        PatterWord::where("id", $id)->update($data);

        Ajson('修改成功!', '0000');
    }

    public function getPatterWord()
    {
        $wid = input('post.wid');

        $res = PatterWord::where("id", $wid)->find();

        Ajson('查询成功!', '0000', $res);
    }

    //测试使用，获取所有话术
    public function getAllPatter()
    {
        $res = Patter::whereIn("id", '1,9,12,18,19,21,22,23,26,27,28,29,30')->whereOr("version",1)->whereOr("version",2)->select();

        Ajson('查询成功!', '0000', $res);
    }

    //测试使用，获取测试任务队列状态
    public function getCurQueue()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $num = new Number();
        $num->setid('test_'.$uid);
        $reamin = $num->where("state", "<>", 10)->count();
        if ($reamin > 0) {
            $res['status'] = -1;
        } else {
            $res['status'] = 0;
        }

        Ajson('查询成功!', '0000', $res);
    }

    //测试使用，拨打电话
    public function startCall()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $mobile = trim(input("post.mobile"));
        $patter = intval(input("post.patter"));
        if(!empty($mobile) && !empty($patter)){
            //判断话术版本
            $patter_info = Patter::where("id",$patter)->find();
            if(!empty($patter_info)){
                $update_data = array("pid"=>$patter);
                $update_data['alter_datetime'] = date("Y-m-d H:i:s",time());
                if($patter_info['version'] == 1){
                    $update_data['destination_extension'] = "9527";
                }elseif ($patter_info['version'] == 2){
                    $update_data['destination_extension'] = "7521";
                }else{
                    $update_data['destination_extension'] = "8888";
                }
                Task::where("uuid", 'test_'.$uid)->update($update_data);
                $num = new Number();
                $num->setid('test_'.$uid);
                $num->insert(["number"=>$mobile]);
                Ajson('添加队列策成功!', '0000');
            }
        }
        Ajson('添加队列失败!', '0001');
    }

    /*public function linshi(){
        $rs = CrmMobilePc::where(true)->select();
        foreach ($rs as $v=>$a){
            $sy = CrmMobile::where("pid",$a["id"])->where("uid",$a["uid"])->where("isdel",0)->count();
            $snum = $sy;
            $znum = 0;
            $pids = CrmMobilePc::where("pid", $a["id"])->column("id");
            foreach ($pids as $pid){
                $yfp = CrmMobile::where("pid", $pid)->count();
                $znum += $yfp;
            }
            $znum += $sy;
            CrmMobilePc::where("id",$a["id"])->update(['znum'=>$znum,'snum'=>$snum]);
        }
        Ajson('完成！');
    }*/

    //获取所有销售的统计数据
    public function getInformations(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            $user_group = User::where("id",$uid)->value("gid");
            if($user_group && $user_group == 1){
                $ret_arr = array();
                //所有业务员
//                显示的业务员
                $show_sales = array(155,193,222,221);
                $sale_arr = User::where("gid",1)->where('id','in',$show_sales)->order("id","asc")->select();
                $sale_id_arr = array();
                foreach ($sale_arr as $sale){
                    $sale_id_arr[] = $sale['id'];
                    $temp = array(
                        'username'=>$sale['username'],
                        'bill_sum'=>0,
                        'call_count'=>0,
                        'new_count'=>0,
                        'bill_rate'=>'0%',
                        'average'=>0,
                        'wx_count'=>0,
                        'unhandle_count'=>0,
                        'nodeal_count'=>0,
                        'file_rate'=>"0%",
                    );
                    $ret_arr["sale".$sale['id']] = $temp;
                }
                //通话时长 拨打数量 接通率 平均通话时长
                $record_info_arr = CallRecord::where("uid", "in",$sale_id_arr)->whereTime("starttime", "today")->field('uid,count(*) as call_count,count(billsec>0 or null) as bill_count,sum(billsec) as bill_sum')->order("uid","asc")->group('uid')->select();
                foreach ($record_info_arr as $record_info){
                    $ret_arr["sale".$record_info['uid']]['bill_sum'] = $record_info['bill_sum'];
                    $ret_arr["sale".$record_info['uid']]['call_count'] = $record_info['call_count'];
                    if($record_info['call_count']>0){
                        $ret_arr["sale".$record_info['uid']]['bill_rate'] = (round($record_info['bill_count']/$record_info['call_count'],2)*100) . "%";
                    }else{
                        $ret_arr["sale".$record_info['uid']]['bill_rate'] = "0%";
                    }
                    if($record_info['bill_count']>0) {
                        $ret_arr["sale" . $record_info['uid']]['average'] = round($record_info['bill_sum'] / $record_info['bill_count'], 2);
                    }else{
                        $ret_arr["sale" . $record_info['uid']]['average'] = 0;
                    }
                }
                //新增归档
                $new_file_arr = CallInfo::where("uid",'in', $sale_id_arr)->whereTime("dateline", "today")->where("type","<>","")->where("type","<>",'["unreachable"]')->field('uid,count(*) as new_count')->order("uid","asc")->group('uid')->select();
                foreach ($new_file_arr as $new_file){
                    $ret_arr["sale".$new_file['uid']]['new_count'] = $new_file['new_count'];
                }
                //加微信量 剩余任务 已处理量 无需处理量
                $today_start = mktime(0,0,0,date('m'),date('d'),date('Y'));
                $wx_count_arr = CrmUsertype::where("uid", "in",$sale_id_arr)->where("type", "in",array('a','b'))->where("dateline",'>',$today_start)->field("uid,count(isaddwx>0 or null) as wx_count,count(click=0 or null) as unhandle_count,count(click=1 or null) as handle_count,count(isnodeal=1 or null) as nodeal_count")->order("uid","asc")->group('uid')->select();
                foreach ($wx_count_arr as $wx_count){
                    $ret_arr["sale".$wx_count['uid']]['wx_count'] = $wx_count['wx_count'];
                    $ret_arr["sale".$wx_count['uid']]['unhandle_count'] = $wx_count['unhandle_count'];
                    $ret_arr["sale".$wx_count['uid']]['nodeal_count'] = $wx_count['nodeal_count'];
                    if( $ret_arr["sale".$wx_count['uid']]['new_count']>0 && $wx_count['handle_count']>0){
                        $ret_arr["sale".$wx_count['uid']]['file_rate'] = (round($ret_arr["sale".$wx_count['uid']]['new_count']/$wx_count['handle_count'],2)*100)."%";
                    }
                }
                $return  = array();
                foreach ($ret_arr as $ret){
                    $return[] = $ret;
                }
                Ajson('查询成功!1', '0000',$return);
            }
        }
        Ajson('权限不足!', '0001');
    }

    //获取菜单列表
    public function getMenus(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            $gid = User::where("id",$uid)->value("gid");
            if($gid && $gid == 4){
                $menu_list = WxMenu::select();
                if($menu_list && count($menu_list)>0){
                    Ajson('查询成功!', '0000',$menu_list);
                }
            }
        }
        Ajson('权限不足!', '0001');
    }

    //修改菜单权限
    public function editMenu(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            //只有后台管理员才有权限
            $gid = User::where("id",$uid)->value("gid");
            if($gid && $gid == 4){
                $type = input("post.type");
                $id = input("post.id");
                $menu = input("post.menu");
                //统一设置
                if($type == 1){
                    $menus = array("menus"=>implode(",", $menu));
                    User::where("1",1)->update($menus);
                    Ajson('修改成功!', '0000');
                }else{
                    //按ids设置
                    $menus = array("menus"=>implode(",", $menu));
                    User::where("id",'in',$id)->whereOr("sid",'in',$id)->update($menus);
                    Ajson('修改成功!', '0000');
                }

            }
        }
        Ajson('权限不足!', '0001');
    }

    //获取配置菜单用户列表
    public function getMenuer(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            //只有后台管理员才有权限
            $gid = User::where("id",$uid)->value("gid");
            if($gid && $gid == 4){
                $page = intval(input("post.page"))>0?intval(input("post.page")):1;
                $pagesize = intval(input("post.pagesize"))>0?intval(input("post.pagesize")):20;
                $total = User::where("sid",0)->count();
                $start = ($page - 1) * $pagesize;
                if($start>$total){
                    $page = 1;
                    $start = 0;
                }
                $list = User::alias("u")->leftJoin("bbxxjs.bb_user_group g","g.id=u.gid")->where("sid", 0)->field("u.id,u.username,g.name as role,u.menus")->limit($start, $pagesize)->select();
                $ret = array(
                    "page"=>$page,
                    "total"=>$total,
                    "list"=>$list,
                );
                Ajson('查询成功!', '0000',$ret);
            }
        }
        Ajson('权限不足!', '0001');
    }

    //获取所有关键词库列表
    public function getAllWordBase(){
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $res = WordBase::where("uid",$uid)->select();
            Ajson('查询成功!', '0000',$res);
        }
        Ajson('查询失败!', '0001');
    }

    //获取所有情景关键词库列表
    public function getAllScene(){
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $res = Scene::where("uid",$uid)->select();
            Ajson('查询成功!', '0000',$res);
        }
        Ajson('查询失败!', '0001');
    }

    //获取关键词库信息
    public function getwordBaseInfo(){
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $bid = input('post.bid');
            if(!empty($bid)){
                $wordBase= WordBase::where("id",$bid)->find();
                Ajson('查询成功!', '0000',$wordBase);
            }
        }
        Ajson('查询失败!', '0001');
    }

    //获取情景关键词信息
    public function getSceneInfo(){
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $sid = input('post.sid');
            if(!empty($sid)){
                $scene= Scene::where("id",$sid)->find();
                Ajson('查询成功!', '0000',$scene);
            }
        }
        Ajson('查询失败!', '0001');
    }

    // 获取当前任务状态列表
    public function getTaskState(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $re = User::where("sid", $uid)->select();
        $total = count($re);

        $list = array();
        if(!empty($re)){
            foreach ($re as $v=>$k){
                $list[$v]["id"] = $k["id"];
                $list[$v]["username"] = $k["username"];
                $list[$v]["amount"] = Task::where("uid",$k["id"])->count();
                $list[$v]["opened"] = Task::where("uid",$k["id"])->where("start", 1)->count();
            }
            $rs["rows"] = $list;
            $rs["total"] = $total;
            Ajson('查询成功!', "0000", $rs);
        }else{
            Ajson('查询失败!', "0001");
        }
    }

    // 开启任务状态
    public function changeOpenState(){
        $uidlist = input("post.aidlist");
        $updatetime = date('Y-m-d H:i:s',time());

        if (strpos($uidlist, ",") > 0) {
            $uidlist = explode(',', $uidlist);
            foreach ($uidlist as $uid) {
                Task::where('uid', $uid)->update(["start"=>1, "alter_datetime"=>$updatetime]);
            }
            Ajson('开启完成!', "0000");
        } else {
            Task::where('uid', $uidlist)->update(["start"=>1, "alter_datetime"=>$updatetime]);
            Ajson('开启完成!', "0000");
        }
    }

    // 关闭任务状态
    public function changeCloseState(){
        $uidlist = input("post.aidlist");
        $updatetime = date('Y-m-d H:i:s',time());

        if (strpos($uidlist, ",") > 0) {
            $uidlist = explode(',', $uidlist);
            foreach ($uidlist as $uid) {
                Task::where('uid', $uid)->update(["start"=>0, "alter_datetime"=>$updatetime]);
            }
            Ajson('关闭完成!', "0000");
        } else {
            Task::where('uid', $uidlist)->update(["start"=>0, "alter_datetime"=>$updatetime]);
            Ajson('关闭完成!', "0000");
        }
    }


    //测试话术相关接口
    // 话术列表
    public function patter1list()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }
        $res = Patter::where("uid", $uid)->where("version","in",array(1,2))->limit($page, $pagesize)->select();
        $re['total'] = Patter::where("uid", $uid)->count();

        foreach ($res as $v => $k) {
            if (!empty($res[$v]["updatetime"])) {
                $res[$v]["time"] = friendlyDate($k["updatetime"]);
            } else {
                $res[$v]["time"] = friendlyDate($k["addtime"]);
            }
        }
        $re['rows'] = $res;

        Ajson('查询成功!', '0000', $re);
    }

    // 添加话术
    public function addpatter1()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $title = input('post.title');
            $version = input('post.version');
            $score_a= input('post.score_a')?intval(input('post.score_a')):0;
            $score_b =input('post.score_b')?intval(input('post.score_b')):0;
            $bill_a = input('post.bill_a')?intval(input('post.bill_a')):0;
            $bill_b = input('post.bill_b')?intval(input('post.bill_b')):0;

            $data['uid'] = $uid;
            $data['title'] = $title;
            $data['version'] = $version;
            $data['score_a'] = $score_a;
            $data['score_b'] = $score_b;
            $data['bill_a'] = $bill_a;
            $data['bill_b'] = $bill_b;
            $data['addtime'] = time();
            $pid = Patter::insertGetId($data);

            if(!empty($pid)) {
                $data[0] = [
                    'role' => 'hello',
                    'common' => '问候',
                    'weights' => 1
                ];
                $data[1] = [
                    'role' => 'opening',
                    'common' => '开场白',
                    'weights' => 2
                ];
                $data[2] = [
                    'role' => 'finish',
                    'common' => '结束',
                    'weights' => 100
                ];
                $data[3] = [
                    'role' => 'questionnaire',
                    'common' => '调查问卷',
                    'weights' => 0
                ];
                $data[4] = [
                    'role' => 'business-1',
                    'common' => '业务1',
                    'weights' => 3
                ];
                $data[5] = [
                    'role' => 'answer',
                    'common' => '问答',
                    'weights' => 0
                ];
                $data[6] = [
                    'role' => 'noting',
                    'common' => '不作为',
                    'weights' => 0
                ];
                $data[7] = [
                    'role' => 'channel',
                    'common' => '微信',
                    'weights' => 90
                ];
                $data[8] = [
                    'role' => 'busy',
                    'common' => '忙碌',
                    'weights' => 0
                ];
                for($i = 0; $i<9; $i++){
                    $data[$i]['pid'] = $pid;
                    SpeechRole::insert($data[$i]);
                }
                Ajson('添加成功!', '0000');
            }else{
                Ajson('添加失败!', '0001');
            }
        }
        Ajson('添加失败!', '0001');
    }
    // 编辑话术
    public function editpatter1()
    {
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $id = input('post.id');
            $title = input('post.title');
            $version = input('post.version');
            $score_a= input('post.score_a')?intval(input('post.score_a')):0;
            $score_b =input('post.score_b')?intval(input('post.score_b')):0;
            $bill_a = input('post.bill_a')?intval(input('post.bill_a')):0;
            $bill_b = input('post.bill_b')?intval(input('post.bill_b')):0;

            $data = array(
                "title" => $title,
                "version" => $version,
                "updatetime" => time(),
                "score_a" => $score_a,
                "score_b" =>$score_b,
                "bill_a" => $bill_a,
                "bill_b" =>$bill_b,
            );
            Patter::where("id", $id)->update($data);

            Ajson('编辑成功!', '0000');
        }
        Ajson('编辑失败!', '0001');
    }

    //话术详情列表
    public function patter1wordlist()
    {
        $typeid = input('post.type');
        $pid = input('post.pid');
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }
        if ($typeid == 0) {
            $res = PatterWord::where("pid", $pid)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->count();
        } else if ($typeid == 1) {
            $res = PatterWord::where("pid", $pid)->where("type", "<>", 0)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->where("type", "<>", 0)->count();
        } else {
            $res = PatterWord::where("pid", $pid)->where("type", 0)->limit($page, $pagesize)->select();
            $re['total'] = PatterWord::where("pid", $pid)->where("type", 0)->count();
        }

        foreach ($res as $v => $k) {
            if (!empty($res[$v]["updatetime"])) {
                $res[$v]["time"] = friendlyDate($k["updatetime"]);
            } else {
                $res[$v]["time"] = friendlyDate($k["addtime"]);
            }
            if ($res[$v]["y_next"] == -1) {
                $res[$v]["y_word"] = "挂机";
            } else {
                $resy_word = PatterWord::where("id", $res[$v]["y_next"])->find();
                $res[$v]["y_word"] = $resy_word["sign"];
            }
            if ($res[$v]["u_next"] == -1) {
                $res[$v]["u_word"] = "挂机";
            } else {
                $resu_word = PatterWord::where("id", $res[$v]["u_next"])->find();
                $res[$v]["u_word"] = $resu_word["sign"];
            }
            if ($res[$v]["n_next"] == -1) {
                $res[$v]["n_word"] = "挂机";
            } else {
                $resn_word = PatterWord::where("id", $res[$v]["n_next"])->find();
                $res[$v]["n_word"] = $resn_word["sign"];
            }
            $resword = Voice::where("id", $res[$v]["voice_id"])->find();
            $res[$v]["word"] = $resword["word"];
            if($res[$v]["role"] == "hello"){
                $res[$v]["role"] = "问候";
            }else if($res[$v]["role"] == "opening"){
                $res[$v]["role"] = "开场白";
            }else if($res[$v]["role"] == "finish"){
                $res[$v]["role"] = "结束";
            }else if($res[$v]["role"] == "questionnaire"){
                $res[$v]["role"] = "调查问卷";
            }else if($res[$v]["role"] == "answer"){
                $res[$v]["role"] = "问答";
            }else if($res[$v]["role"] == "nothing"){
                $res[$v]["role"] = "不作为";
            }else if($res[$v]["role"] == "channel"){
                $res[$v]["role"] = "微信";
            }else if(strpos($res[$v]["role"],'business')){
                $res[$v]["role"] = "业务";
            }
        }
        $re['rows'] = $res;

        Ajson('查询成功!', '0000', $re);
    }

    // 获取用户管理信息列表
    public function rolemanagelist()
    {
//        $typeid = input('post.type');
        $pid = input('post.pid');
        $page = input('post.page');
        $pagesize = input('post.pagesize');

        if ($page == 1) {
            $page = 0;
        } else {
            $page = ($page - 1) * $pagesize;
        }

        $res = SpeechRole::where("pid", $pid)->limit($page, $pagesize)->select();
        $re['total'] = SpeechRole::where("pid", $pid)->count();

        $re['rows'] = $res;

        Ajson('查询成功!', '0000', $re);
    }

    //删除话术
    public function delPatter1Word()
    {
        $id = input('post.wid');

        PatterWord::where("id", $id)->delete();
        Ajson('删除成功!', '0000');
    }

    //获取话术信息
    public function getPatter1Info(){
        $token = input('post.token');
        $uid = Cache::get($token);
        if(!empty($uid)){
            $pid = input('post.pid');
            if(!empty($pid)){
                $patter = Patter::where("id",$pid)->find();
                Ajson('查询成功!', '0000',$patter);
            }
        }
        Ajson('查询失败!', '0001');
    }

    //添加话术文字
    public function addpatter1word()
    {
        $data['pid'] = input('post.pid');
        $data['sign'] = input('post.sign');
        $data['type'] = input('post.type');
        $data['word_base'] = input('post.word_base');
        $data['word_base_name'] = input('post.word_base_name');
        $data['score'] = input('post.score');
        $data['voice_id'] = input('post.voice_id');
        $data['order'] = input('post.order');
        $data['y_next'] = input('post.y_next');
        $data['n_next'] = input('post.n_next');
        $data['u_next'] = input('post.u_next');
        $data['repeatable'] = input('post.repeatable');
        $data['replace'] = input('post.replace');
        $data['pass_key'] = input('post.pass_key');
        $data['scene_id'] = input('post.scene_id');
        $data['scene_name'] = input('post.scene_name');
        $data['normal_intention'] = input('post.normal_intention');
        $data['go_straight'] = input('post.go_straight');
        $data['label'] = input('post.label');
        $data['waittime'] = input('post.waittime');
        $data['role'] = input('post.role_id');
        $data['addtime'] = time();

        if (isset($data['pid']) && isset($data['type']) && isset($data['voice_id'])) {
            PatterWord::insert($data);
            Ajson('添加成功!', '0000');
        } else {
            Ajson('添加失败!', '0001');
        }
    }

    //编辑话术
    public function editpatter1word()
    {
        $id = input('post.id');
        $data['pid'] = input('post.pid');
        $data['sign'] = input('post.sign');
        $data['type'] = input('post.type');
        $data['word_base'] = input('post.word_base');
        $data['word_base_name'] = input('post.word_base_name');
        $data['score'] = input('post.score');
        $data['voice_id'] = input('post.voice_id');
        $data['order'] = input('post.order');
        $data['y_next'] = input('post.y_next');
        $data['n_next'] = input('post.n_next');
        $data['u_next'] = input('post.u_next');
        $data['repeatable'] = input('post.repeatable');
        $data['replace'] = input('post.replace');
        $data['pass_key'] = input('post.pass_key');
        $data['scene_id'] = input('post.scene_id');
        $data['scene_name'] = input('post.scene_name');
        $data['normal_intention'] = input('post.normal_intention');
        $data['go_straight'] = input('post.go_straight');
        $data['label'] = input('post.label');
        $data['waittime'] = input('post.waittime');
        $data['role'] = input('post.role_id');
        $data['updatetime'] = time();

        PatterWord::where("id", $id)->update($data);

        Ajson('修改成功!', '0000');
    }

    //获取话术
    public function getPatter1Word()
    {
        $wid = input('post.wid');

        $res = PatterWord::where("id", $wid)->find();

        Ajson('查询成功!', '0000', $res);
    }

    //所有话术
    public function word1list()
    {
        $pid = input('post.pid');

        $res = PatterWord::where("pid", $pid)->where("type", "<>", 0)->select();
        foreach ($res as $v => $k) {
            $resword = Voice::where("id", $res[$v]["voice_id"])->find();
            $res[$v]["word"] = $resword["word"];
        }

        Ajson('查询成功!', '0000', $res);
    }

    // 所有角色
    public function allrole()
    {
        $pid = input('post.pid');
        $res = SpeechRole::where("pid", $pid)->select();

        Ajson('查询成功', "0000", $res);
    }

    //客户使用统计信息
    public function company_analyse(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            $user_list = User::where("gid",2)->where("sid",0)->select();
            Ajson('查询成功!', '0000',$user_list);
        }
        Ajson('权限不足!', '0001');
    }

    public function company_analyse_info(){
        $token = $_SERVER['HTTP_X_TOKEN'];
        $uid = Cache::get($token);
        if(!empty($uid)){
            $id = input("post.id");
            if(empty($id)){
                Ajson('参数不足!', '0001');
            }
            $user_list = User::where("sid",$id)->select();
            $ret = array();
            foreach ($user_list as $u){
                //今日数据
                $temp1 = array(
                    "id"=>$u['id'],
                    "username"=>$u['username'],
                );
                //对比数据
                $temp2 = array(
                    "id"=>$u['id'],
                    "username"=>$u['username'],
                );
                if (!empty($u['ai_vos'])) {
                    $money = getcallmoney($u['ai_vos']);
                    if (!empty($money)) {
                        $temp1['fee'] = $money["callmoney"];
                        $temp2['fee'] = $money["callmoney"];
                    }
                    $temp1['fee'] = 0;
                    $temp2['fee'] = 0;
                } else {
                    $temp1['fee'] = 0;
                    $temp2['fee'] = 0;
                }
                $temp1['date'] = "今日数据";
                $temp2['date'] = "本周数据";
                $temp1['call'] = 0;
                $temp1['reply_rate'] = "0%";
                $temp1['reply_num'] = 0;
                $temp1['fee_time'] = 0;
                $temp1['fee_time_rate'] = 0;
                $temp1['a_class'] = 0;
                $temp1['b_class'] = 0;
                $temp1['c_class'] = 0;
                $temp1['remain'] = 0;
                $temp2['call'] = 0;
                $temp2['reply_rate'] = "0%";
                $temp2['reply_num'] = 0;
                $temp2['fee_time'] = 0;
                $temp2['fee_time_rate'] = 0;
                $temp2['a_class'] = 0;
                $temp2['b_class'] = 0;
                $temp2['c_class'] = 0;
                $temp2['remain'] = 0;
                $task_arr = Task::where("uid", $u['id'])->select();
                foreach ($task_arr as $k => $v) {
                    $num = new Number();
                    $num->setid( $v['uuid']);

                    $temp1['call'] += $num->whereTime('calldate', 'today')->count();
                    $temp2['call'] += $num->whereTime('calldate', 'week')->count();
                    $temp1['reply_num'] += $num->whereTime('calldate', 'today')->where("bill",">",0)->count();
                    $temp2['reply_num'] += $num->whereTime('calldate', 'week')->where("bill",">",0)->count();
                    $temp1['fee_time'] += intval($num->whereTime('calldate', 'today')->where("bill",">",0)->sum("bill"));
                    $temp2['fee_time'] += intval($num->whereTime('calldate', 'week')->where("bill",">",0)->sum("bill"));
                    $temp1['remain'] += $num->whereTime('calldate', 'today')->where('state',"<>",10)->count();
                    $temp2['remain'] += $num->whereTime('calldate', 'week')->where('state',"<>",10)->count();
                    }
                if($temp1['reply_num'] >0){
                    $temp1['reply_rate'] ="". (round($temp1['reply_num']/$temp1['call'],4) * 100 )."%";
                    $temp1['fee_time_rate'] = intval($temp1['fee_time']/$temp1['reply_num']);
                }
                if($temp2['reply_num'] >0){
                    $temp2['reply_rate'] ="". (round($temp2['reply_num']/$temp2['call'],4) * 100 )."%";
                    $temp2['fee_time_rate'] = intval($temp2['fee_time']/$temp2['reply_num']);
                }
                $temp1['a_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","today")->where("type","a")->count();
                $temp1['b_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","today")->where("type","b")->count();
                $temp1['c_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","today")->where("type","c")->count();
                $temp2['a_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","week")->where("type","a")->count();
                $temp2['b_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","week")->where("type","b")->count();
                $temp2['c_class'] = CrmUsertype::where("uid",$u['id'])->whereTime("dateline","week")->where("type","c")->count();

                $temp1['fee_time'] = intval($temp1['fee_time']/1000);
                $temp2['fee_time'] = intval($temp2['fee_time']/1000);
                $temp1['fee_time_rate'] = intval($temp1['fee_time_rate']/1000);
                $temp2['fee_time_rate'] = intval($temp2['fee_time_rate']/1000);
                $ret[] = $temp1;
                $ret[] = $temp2;
            }
            Ajson('查询成功!', '0000',$ret);
        }
        Ajson('权限不足!', '0001');
    }


}
