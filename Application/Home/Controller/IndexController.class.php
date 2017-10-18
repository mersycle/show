<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    protected function init($db){
        $redis = new \Redis();
        $redis->connect("127.0.0.1",6379);
        $redis->select(0);
        return $redis;
    }
    
    protected function hx(){
       vendor("emchat-server-php.Easemob"); 
       $options['client_id']='YXA62n8RwKTrEee_L7erL4P7Mw';
        $options['client_secret']='YXA6MQjOAFUJ8vYEu1NYkXw3B4KWSws';
        $options['org_name']='attitude';
        $options['app_name']='helloworld';

        $h=new \Easemob($options);
        return $h;
    }
    protected function checkUser(){
        if(empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->error("请登录","/home/index/login"); 
        }
    }
    public function index(){
        $this->checkUser();
        $world = "aaa";
        $this->assign("account",$_SESSION[C("SESS_ACCOUNT")]);
        $this->assign('world',$world);
        $this->display();
    }
    public function reg(){
        if(!empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->success("你已经登录了","/home/index/index");
            
        }else{
            if(empty($_POST)){
                $this->display();
            }else{
                $data["account"] = I("post.account");
                $data["yanzheng"] = I("post.yanzheng");
                $data["pass"] = I("post.pass1");
                $data["pass2"] = I("post.pass2");
                if(empty($data["account"]) || empty($data["yanzheng"]) || empty($data["pass"]) || empty($data["pass2"])){
                    $this->error("信息请填写完整！");
                }elseif($data["pass"] != $data["pass2"]){
                    $this->error("两次密码不一致！");
                }
                
                
                
                
                $redis = $this->init(0);
                $info = $redis->get($data["account"]);

                if(!$info){
                    $res = M("User")->add($data);
                    if(empty($res)){
                        $redis->close();
                        $this->error("注册失败，mysql");
                    }

                    $uid = M("User")->getLastInsID();
                    $hx = $this->hx();
                    $hx_res = $hx->createUser($data["account"],$data["pass"]);
//                    $hx_res = json_decode($hx_res);
//                    print_r($hx_res["entities"]);die;
                    if(empty($hx_res["entities"][0]["uuid"])){
                        M("User")->where(array("id"=>$uid))->delete();
                        $redis->close();
                        $this->error("注册失败，环信");
                    }
                    
                    $resa = $redis->set($data["account"],$uid);
                    if(empty($resa)){
                        M("User")->where(array("id"=>$uid))->delete();
                        $hx->deleteUser($data["account"]);
                        $redis->close();
                        $this->error("注册失败，redis");
                    }
                    $this->success("注册成功","/home/index/login");
                }else{
                    $redis->close();
                    $this->error("注册失败,该账号已存在");
                }
                
            }
        }
        
        
    }
    
    public function login(){
//       echo __Public__;DIE;
        if(!empty($_SESSION[C("SESS_ACCOUNT")])){
            $this->success("你已经登录了","/home/index/index");
        }else{
            if(empty($_POST)){
                $this->display();
            }else{
                $data["account"] = I("post.account");
                $data["pass"] = I("post.pass");
                if(empty($data["account"])){
                    $this->error("账户不能为空");
                }elseif(empty($data["pass"])){
                    $this->error("密码不能为空");
                }
                $redis = $this->init(0);
                $uid = $redis->get($data["account"]);
                $redis->close();
                if($uid){
                    $info = M("User")->where(array("id"=>$uid))->field("pass")->find();
                    if($info["pass"] == $data["pass"]){
                        $_SESSION[C("SESS_ACCOUNT")] = $data["account"];
                        $_SESSION[C("SESS_UID")] = $uid;

                        $this->success("登录成功","/home/index/index");
                    }else{
                        $this->error("密码错误");
                    }  
                }else{
                    $this->error("用户不存在");
                }
            }
        }
    }
    
    public function logout(){
        if(isset($_SESSION[C("SESS_ACCOUNT")])){
            unset($_SESSION[C("SESS_ACCOUNT")]);
            unset($_SESSION[C("SESS_UID")]);
            session('[destroy]');
            $this->success("退出成功","/home/index/login");
        }else{
            $this->success("已经退出","/home/index/login");
        }
    }
    
    public function sendVerify(){
        $number = I("post.number");
        
        $verify=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $content="帅哥告诉你，验证码为".$verify."，15分钟内有效。";
        sendSMS(array($number),$content);
    }
    public function chat(){
        $number = I("post.number");
//        $number = "";
        $res = sendSMS(array($number),"- -！");
        echo true;
    }
    
    
//    excel 导电话号码
    public function demo(){
        
        $oldStr = file_get_contents("http://vim.renxing12306.com/info2.txt");

//        echo $oldStr;
//        die;
        $oldStr=trim($oldStr);
        $numbers = array();
        if(empty($oldStr)){
          return $numbers;
        }
        // 删除86-180640741122，0997-8611222之类的号码中间的减号（-）
        $strArr = explode("-", $oldStr);
        $newStr = $strArr[0];
        for ($i=1; $i < count($strArr); $i++) { 
          if (preg_match("/\d{2}$/", $newStr) && preg_match("/^\d{11}/", $strArr[$i])){
            $newStr .= $strArr[$i]; 
          } elseif (preg_match("/\d{3,4}$/", $newStr) && preg_match("/^\d{7,8}/", $strArr[$i])) {
            $newStr .= $strArr[$i]; 
          } else {
            $newStr .= "-".$strArr[$i]; 
          } 
        }
        // 手机号的获取
        $reg='/\D(?:86)?(\d{11})\D/is';//匹配数字的正则表达式
        preg_match_all($reg,$newStr,$result);
          $nums = array();
        // * 中国移动：China Mobile
        // * 134[0-8],135,136,137,138,139,150,151,157,158,159,182,187,188
        $cm = "/^1(34[0-8]|(3[5-9]|5[017-9]|8[278])\d)\d{7}$/";
        // * 中国联通：China Unicom
        // * 130,131,132,152,155,156,185,186
        $cu = "/^1(3[0-2]|5[256]|8[56])\d{8}$/";
        // * 中国电信：China Telecom
        // * 133,1349,153,180,189
        $ct = "/^1((33|53|8[09])[0-9]|349)\d{7}$/";
        $i = 0;
        $name = "顾客";
        foreach ($result[1] as $key => $value) {
            $nums[$name.$i] = $value;
//            $nums[$name.$i] = $value;
            $i++;
        }
        var_dump($result[1]);die;
        create_xls($result[1]);
    }
    
    
    public function airTicket(){
        $nowtime = date("Y-m-d",time());
        $this->assign("nowtime", $nowtime);
        $this->display();
    }
    
    public function hada(){
        $goid = I("post.goid");
        $coid = I("post.coid");
        $godate = I("post.date");
        $url = "http://123.58.249.133:8866/SkyEchoProduct/FlightSearch";
        $data["appid"] = "CFDE64D8AD187AA8C04C23870CAD5E8D";
        $data["timestamp"] = time();
        $data["sign"]= strtoupper(md5($data["appid"].time()."851EA7B429AB86792C575B587DDECB43"));
        $data["version"] = "1";
//        $data["data"] = array("is_international_city"=>"0","is_hot_city"=>"0");
//        $data["data"] = array("is_international_city"=>"0","is_hot_city"=>"1");
        
        $data["data"] = array("dpt"=>$goid,"arr"=>$coid,"flightDate"=>$godate,"airline"=>"ALL");
        $data = json_encode($data);
        
        $re = curl_post($url, $data);
        echo $re;die;
//        $re = json_decode($re);
//        $re = json_encode($re,true);
//        var_dump($re);die;
//        
//        foreach($re["data"] as $k=>$v){
//
//            print_r($v);
//
//        }
    }
    
    
    public function sendMsg(){
        $mysql_server_name='123.57.74.40';
        $mysql_username='zhangyun'; 
        $mysql_password='zhangyun'; 
        $mysql_database='renxing';
        $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password) or die("链接数据库失败");
        mysql_query("set names 'utf8'");
        mysql_select_db($mysql_database);
        $sql ="select pay_account from rx_withdraw ";
        $result = mysql_query($sql,$conn);
        $num = [];
        while($row = mysql_fetch_row($result)){
            array_push($num,$row[0]);
        }
        $re = sendSMS($num,"thisistest");
        var_dump($num);
    }
}