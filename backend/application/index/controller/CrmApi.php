<?php
/**
 * Created by PhpStorm.
 * User: w
 * Date: 2019/4/15
 * Time: 16:56
 */

namespace app\index\controller;

use app\index\model\Scene;
use app\index\model\SceneKey;
use app\index\model\Word;
use app\index\model\WordBase;
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
use app\index\model\CallRemarks;
use app\index\model\WxMenu;

use Env;
use tools\Aes;
use tools\NetWork;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class CrmApi extends Base
{
    // 客户信息列表
    public function crmListApi(){

        $uid = input('post.uid');
        $select_type0 = input('post.type0');
        $select_type1 = input('post.type1');
        $start = input('post.starttime');
        $end = input('post.endtime');
        $page = input('post.page');
        $crmcompany = input('post.crmcompany');
        $crmmobile = input('post.crmmobile');

        date_default_timezone_set('PRC');
        if(!empty($select_type0)) {
            if ($select_type0 == "unintentional" || $select_type0 == "unreachable") {
                $select_type = "[" . json_encode($select_type0) . "]";
            } else if ($select_type0 == "autoaddwechat" || $select_type0 == "nodeal") {
                $select_type = $select_type0;
            } else {
                $select_type = "[" . json_encode($select_type0) . "," . json_encode($select_type1) . "]";
            }
        }else{
            $select_type = "";
        }
        if(!empty($start)){
            $start = $start . " 00:00:00";
            $starttime = strtotime($start);
        }else{
            $starttime = 0;
        }
        if(!empty($end)){
            $end = $end . " 23:59:59";
            $endtime = strtotime($end);
        }else{
            $endtime = 0;
        }

        $list = array();
        if($select_type == ""){
            $sql = CallInfo::where("uid", $uid);
            if(!empty($crmcompany)){
                $sql->whereLike("company", "%$crmcompany%");
            }
            if(!empty($crmmobile)){
                $sql->whereLike("mobile", "%$crmmobile%");
            }
            if(!empty($starttime)){
                $sql->whereBetweenTime("dateline", $starttime, $endtime);
            }
            $re = $sql->order("dateline", "Desc")->limit(($page - 1) * 10, 10)->select();
            $count = $sql->count();
            foreach ($re as $v=>$k){
                if(!empty($k["name"])){
                    $list[$v]["username"] = $k["name"];
                }else{
                    $list[$v]["username"] = "未填写";
                }
                if(!empty($k["dateline"])) {
                    $list[$v]["addtime"] = friendlyDate($k["dateline"], 'mohu');
                }
                if($k["id"] != 0) {
                    $type = json_decode($k["type"], true);
                    if (!empty($type)) {
                        if ($type[0] == "unintentional") {
                            $list[$v]["type0"] = "无意向";
                            $list[$v]["type1"] = "";
                        } else if ($type[0] == "unreachable") {
                            $list[$v]["type0"] = "未接通";
                            $list[$v]["type1"] = "";
                        } else if($type[0] == "unrecord"){
                            $list[$v]["type0"] = "未记录";
                            $list[$v]["type1"] = "";
                        }else if ($type[0] == "new_customer") {
                            $list[$v]["type0"] = "新客户";
                            if ($type[1] == "new_levelA") {
                                $list[$v]["type1"] = " - A类高意向";
                            } else if ($type[1] == "new_levelB") {
                                $list[$v]["type1"] = " - B类中等意向";
                            } else {
                                $list[$v]["type1"] = " - C类低意向";
                            }
                        } else if ($list[$v]["type0"] = "old_customer") {
                            $list[$v]["type0"] = "老客户";
                            if(!empty($type[1])) {
                                if ($type[1] == "old_levelA") {
                                    $list[$v]["type1"] = " - 50台以上";
                                } else if ($type[1] == "old_levelB") {
                                    $list[$v]["type1"] = " - 10-20台";
                                } else if ($type[1] == "old_levelC") {
                                    $list[$v]["type1"] = " - 10台以下";
                                } else {
                                    $list[$v]["type1"] = "";
                                }
                            }else{
                                $list[$v]["type1"] = "";
                            }
                        }
                    } else {
                        $list[$v]["type0"] = "未设标签";
                        $list[$v]["type1"] = "";
                    }
                }
                $list[$v]["id"] = $k["id"];
                $list[$v]["gender"] = $k["sex"];
                $list[$v]["mobile"] = $k["mobile"];
                $list[$v]["company"] = $k["company"];
                $list[$v]["source"] = $k["source"];
                $list[$v]["remark"] = $k["bz"];
            }
        }else if($select_type == "nodeal" || $select_type == "autoaddwechat"){
            $sql = CrmUsertype::where("uid", $uid);
            if($select_type == "nodeal"){
                $sql->where("isnodeal", 1);
            }else if($select_type == "autoaddwechat"){
                $sql->where("isaddwx", 1);
            }
            if (!empty($crmmobile)){
                $sql->whereLike("number", "%$crmmobile%");
            }
            if($starttime){
                $sql->whereBetweenTime("dateline", $starttime, $endtime);
            }
            $re = $sql->order("dateline", "Desc")->limit(($page - 1) * 10, 10)->select();
            $count = $sql->count();
            foreach ($re as $v=>$k){
                $list[$v]["id"] = $k["id"] - 2*$k["id"];
                $list[$v]["username"] = "未联系";
                if($select_type == "nodeal"){
                    $list[$v]["type0"] = "无需处理";;
                }else if($select_type == "autoaddwechat"){
                    $list[$v]["type0"] = "自动添加微信";;
                }
                $list[$v]["type1"] = "";
                $list[$v]["contact"] = 1;
                $list[$v]["gender"] = -1;
                $list[$v]["addtime"] = friendlyDate($k["dateline"], 'mohu');
                $list[$v]["mobile"] = $k["number"];
                $list[$v]["company"] = "";
                $list[$v]["remark"] = "";
            }
        }else{
            $sql = CallInfo::where("uid", $uid)->where("type", $select_type);
            if(!empty($starttime)){
                $sql->whereBetweenTime("dateline", $starttime, $endtime);
            }
            if(!empty($crmmobile)){
                $sql->whereLike("mobile", "%$crmmobile%");
            }
            if(!empty($crmcompany)){
                $sql->whereLike("company", "%$crmcompany%");
            }
            $re = $sql->order("dateline", "Desc")->limit(($page - 1) * 10, 10)->select();
            $count = $sql->count();
            foreach($re as $v=>$k) {
                $list[$v]["id"] = $k["id"];
                $list[$v]["username"] = $k["name"];
                $list[$v]["addtime"] = friendlyDate($k["dateline"], 'mohu');
                $type = json_decode($k["type"], true);
                if (!empty($type)) {
                    if ($type[0] == "unintentional") {
                        $list[$v]["type0"] = "无意向";
                        $list[$v]["type1"] = "";
                    }else if($type[0] == "unreachable"){
                        $list[$v]["type0"] = "未接通";
                        $list[$v]["type1"] = "";
                    } else if ($type[0] == "new_customer") {
                        $list[$v]["type0"] = "新客户";
                        if ($type[1] == "new_levelA") {
                            $list[$v]["type1"] = " - A类高意向";
                        } else if ($type[1] == "new_levelB") {
                            $list[$v]["type1"] = " - B类中等意向";
                        } else {
                            $list[$v]["type1"] = " - C类低意向";
                        }
                    } else if($list[$v]["type0"] = "old_customer") {
                        $list[$v]["type0"] = "老客户";
                        if(!empty($type[1])) {
                            if ($type[1] == "old_levelA") {
                                $list[$v]["type1"] = " - 50台以上";
                            } else if ($type[1] == "old_levelB") {
                                $list[$v]["type1"] = " - 10-20台";
                            } else if ($type[1] == "old_levelC") {
                                $list[$v]["type1"] = " - 10台以下";
                            } else {
                                $list[$v]["type1"] = "";
                            }
                        }else{
                            $list[$v]["type1"] = "";
                        }
                    }
                } else {
                    $list[$v]["type0"] = "未设标签";
                    $list[$v]["type1"] = "";
                }
                $list[$v]["gender"] = $k["sex"];
                $list[$v]["mobile"] = $k["mobile"];
                $list[$v]["company"] = $k["company"];
                $list[$v]["source"] = $k["source"];
                $list[$v]["remark"] = $k["bz"];
            }
        }

        $rs["rows"] = $list;
        $rs["count"] = $count;

        Ajson("查询成功！", '0000', $rs);
    }

    // 录入的客户信息详情
    public function getCrmDetails(){
        $id = input("post.id");

        if($id > 0) {
            $rs = CallInfo::where("id", $id)->find();
            $list["id"] = $id;
            $list["username"] = $rs["name"];
            $list["addtime"] = friendlyDate($rs["dateline"], 'mohu');
            $type = json_decode($rs["type"], true);
            $list["type"] = $type;

            $list["gender"] = $rs["sex"];
            $list["contact"] = CallRecord::where("mobile", $rs["mobile"])->count();
            // 详情页面额外显示信息
            $list["isaddwx"] = $rs["isaddwx"];
            $list["stage"] = $rs["stage"];
            $list["company"] = $rs["company"];
            $list["email"] = $rs["email"];
            $list["dizhi"] = $rs["dizhi"];
            $list["isyuyue"] = $rs["isyuyue"];
            $list["yydateline"] = $rs["yydateline"];
            $list["yytxt"] = $rs["yytxt"];
            $list["bz"] = $rs["bz"];
            $list["remarks"] = CallRemarks::where("info_id", $id)->select();
            $list["mobile"] = $rs["mobile"];
            $list["realcall"] = CallRecord::where("mobile", $rs["mobile"])->select();  // 逻辑错误，应在Record表中新增infoid字段
            foreach ($list["realcall"] as $v => $k) {
                $username = User::where("id", $k["uid"])->find();
                $list["realcall"][$v]["username"] = $username["username"];
                $list["realcall"]["$v"]["billsec"] = $k["hanguptime"] - $k["starttime"];
            }
            $tid = CrmUsertype::where("customerid", $rs["id"])->find();
            if (!empty($tid)) {
                $uuid = Task::where("id", $tid["tid"])->find();
                if (!empty($uuid)) {
                    $num = new Number();
                    $num->setid($uuid["uuid"]);
                    $list["robotcall"] = $num->where("callid", $tid["callid"])->find();
                    $list["robotcall"]['type'] = $tid['type'];
                } else {
                    $list["robotcall"] = null;
                }
            } else {
                $list["robotcall"] = null;
            }

            //info表是否存在
            $list["exist"] = 1;
            Ajson("返回值", "0000", $list);
        } else{
            $list["realcall"] = array();
            $id = $id - 2*$id;
            $tid = CrmUsertype::where("id", $id)->find();
            if (!empty($tid)) {
                $uuid = Task::where("id", $tid["tid"])->find();
                if (!empty($uuid)) {
                    $num = new Number();
                    $num->setid($uuid["uuid"]);
                    $list["robotcall"] = $num->where("callid", $tid["callid"])->find();
                    $list["robotcall"]['type'] = $tid['type'];
                } else {
                    $list["robotcall"] = null;
                }
            } else {
                $list["robotcall"] = null;
            }
            //info表是否存在
            $list["id"] = $id;
            $list["exist"] = 0;
            $list["mobile"] = $tid["number"];
            Ajson("返回值", "0000", $list);
        }
    }

    // 审批
    public function approvalApi(){
        $id = input("post.id");
        $index = input("post.index");
        $re = CallInfo::where("id", $id)->find();
        if(!empty($re)) {
            CallInfo::where("id", $id)->update(['approval' => $index]);
            Ajson("审批成功！", "0000");
        }else{
            Ajson("审批失败！", "0001");
        }
    }

    public function wordBaseList(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $page = input('post.page');
        $pagesize = input('post.pagesize');
        $curr = ($page-1)*$pagesize;

        if (empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $re['list'] = WordBase::where("uid", $uid)->limit($curr, $pagesize)->select();
            $re['total'] = WordBase::where("uid", $uid)->count();
            Ajson("查询成功", "0000", $re);
        }
    }

    public function wordBaseAdd(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $name = input('post.name');

        $data = [
            'name' => $name,
            'uid' => $uid,
            'addtime' => time(),
        ];
        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $wid = WordBase::insertGetId($data);
            Ajson("添加成功！", "0000");
        }
    }

    public function wordBaseChange(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');
        $name = input('post.name');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            WordBase::where("id", $id)->update(['name'=>$name, 'updatetime'=>time()]);
            Ajson("修改成功！", "0000");
        }
    }

    public function wordBaseDelete(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            WordBase::where("id", $id)->delete();
            Word::where("bid", $id)->delete();
            $this->clearWordByWordBase($id);
            Ajson("删除成功！", "0000");
        }
    }

    public function wordListApi(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $bid = input('post.bid');
        $page = input('post.page');
        $pagesize = input('post.pagesize');
        $curr = ($page-1)*$pagesize;

        if (empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $re['list'] = Word::where("bid", $bid)->limit($curr, $pagesize)->select();
            $re['total'] = Word::where("bid", $bid)->count();
            Ajson("查询成功", "0000", $re);
        }
    }

    public function wordAdd(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $bid = input('post.bid');
//        $word = input('post.word');
        $word_arr = input('post.word');
        if(strpos($word_arr, '|') !== false){
            $words = explode('|', $word_arr);
        }else if(strpos($word_arr, ',') !== false){
            $words = explode(',', $word_arr);
        }else if(strpos($word_arr, '/') !== false){
            $words = explode('/', $word_arr);
        }else if(strpos($word_arr, '、') !== false){
            $words = explode('、', $word_arr);
        }else if(strpos($word_arr, ' ') !== false){
            $words = explode(' ', $word_arr);
        }else{
            $words = array($word_arr);
        }

        $data = [
//            'word' => $word,
            'bid' => $bid,
            'addtime' => time(),
        ];
        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $flag = 0;
            foreach ($words as $word) {
                if(!empty($word)) {
                    $key = $this->getWordKey($word);
                    $data['keyword'] = $key;
                    $data["word"] = $word;
                    if (isset($data['bid']) && isset($data['keyword']) && isset($data['word'])) {
                        Word::insert($data);
                        $flag = 1;
                        $this->clearWordByWordBase($bid);
                    }
                }
            }
            if($flag == 1){
                Ajson('添加成功!', '0000');
            }else{
                Ajson('添加失败!', '0001');
            }
//            $key = $this->getWordKey($word);
//            $data['keyword'] = $key;
//            Word::insert($data);
//            $this->clearWordByWordBase($bid);
//            Ajson("添加成功！", "0000");
        }
    }

    //获取该句的关键词
    private function getWordKey($text){
        $url = 'http://127.0.0.1:5000/api/keyword';

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = array(
            "text" => $text,
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $data;
    }

    public function wordChange(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');
        $word = input('post.word');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            Word::where("id", $id)->update(['word'=>$word, 'updatetime'=>time()]);
            $bid = Word::where("id", $id)->value("bid");
            $this->clearWordByWordBase($bid);
            Ajson("修改成功！", "0000");
        }
    }

    public function wordDelete(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $bid = Word::where("id", $id)->value("bid");
            $this->clearWordByWordBase($bid);
            Word::where("id", $id)->delete();
            Ajson("删除成功！", "0000");
        }
    }


    private function clearWordByWordBase($wid){
        $pid_arr = PatterWord::where("word_base",$wid)->group("pid")->column("pid");
        foreach ($pid_arr as $pid){
            $this->clearWordTrainArr($pid);
        }

    }


    /**
     * 更改关键词相关信息，清除对应关键词缓存
     * @param $pid
     */
    private function clearWordTrainArr($pid){
        $url = 'http://47.101.192.243:1987/clear_word_train';

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = array(
            "pid" => $pid,
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
    }

    public function sceneListApi(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $page = input('post.page');
        $pagesize = input('post.pagesize');
        $curr = ($page-1)*$pagesize;

        if (empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $re['list'] = Scene::where("uid", $uid)->limit($curr, $pagesize)->select();
            $re['total'] = Scene::where("uid", $uid)->count();
            Ajson("查询成功", "0000", $re);
        }
    }

    public function sceneAdd(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $name = input('post.name');

        $data = [
            'name' => $name,
            'uid' => $uid,
            'addtime' => time(),
        ];
        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            Scene::insert($data);
            Ajson("添加成功！", "0000");
        }
    }

    public function sceneChange(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');
        $name = input('post.name');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            Scene::where("id", $id)->update(['name'=>$name, 'updatetime'=>time()]);
            Ajson("修改成功！", "0000");
        }
    }

    public function sceneDelete(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            Scene::where("id", $id)->delete();
            SceneKey::where("sid", $id)->delete();
            Ajson("删除成功！", "0000");
        }
    }

    public function sceneKeyList(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $sid = input('post.sid');
        $page = input('post.page');
        $pagesize = input('post.pagesize');
        $curr = ($page-1)*$pagesize;

        if (empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            $re['list'] = SceneKey::where("sid", $sid)->limit($curr, $pagesize)->select();
            $re['total'] = SceneKey::where("sid", $sid)->count();
            Ajson("查询成功", "0000", $re);
        }
    }

    public function sceneKeyAdd(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $sid = input('post.sid');
        $key = input('post.key');

        $data = [
            'key' => $key,
            'sid' => $sid,
            'addtime' => time(),
        ];
        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            SceneKey::insert($data);
            Ajson("添加成功！", "0000");
        }
    }

    public function sceneKeyChange(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');
        $key = input('post.key');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            SceneKey::where("id", $id)->update(['key'=>$key, 'updatetime'=>time()]);
            Ajson("修改成功！", "0000");
        }
    }

    public function sceneKeyDelete(){
        $token = input('post.token');
        $uid = Cache::get($token);
        $id = input('post.id');

        if(empty($uid)){
            Ajson("无权限！", "0001");
        }else{
            SceneKey::where("id", $id)->delete();
            Ajson("删除成功！", "0000");
        }
    }

    // 修改话术
    public function changePatter(){
        $tid = input('post.tid');
        $pid = input('post.pid');

        if(empty($tid)){
            Ajson("任务id不能为空", "0001");
        }else{
            if(empty($pid)){
                Ajson("话术id不能为空", "0001");
            }else{
                $speech = Patter::where("id", $pid)->find();
                if($speech["version"] == 0){
                    Task::where("id", $tid)->update(["alter_datetime"=>date('Y-m-d H:i:s'), "destination_extension"=>8888, "pid"=>$pid]);
                }else if($speech["version"] == 1){
                    Task::where("id", $tid)->update(["alter_datetime"=>date('Y-m-d H:i:s'), "destination_extension"=>9527, "pid"=>$pid]);
                }
                Ajson("修改成功", "0000");
            }
        }
    }
}