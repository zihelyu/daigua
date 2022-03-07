<?php
error_reporting(0);
//error_reporting(E_ALL); ini_set("display_errors", 1);
$mysql = require_once __DIR__ . '/../application/index/database.php';
require_once __DIR__ . '/../application/functions.php';
include 'info.inc.php';
try {
    $pdo = new PDO("mysql:host={$mysql['hostname']};dbname={$mysql['database']};port={$mysql['hostport']}",$mysql['username'],$mysql['password']);
}catch(Exception $e){
    exit('链接数据库失败:'.$e->getMessage());
}
$pdo->exec("set names utf8");

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;
$url=daddslashes($_GET['url']);
$authcode=daddslashes($_GET['authcode']);

if($url){
	if(strlen($authcode)!=32)exit('您可能是盗版软件的受害者。购买正版请联系QQ：1277180438');
	if($auth=get_curl('http://auth.cccyun.cc/apicheck.php?v=2&url='.$url.'&authcode='.$authcode)){
		if ($auth==1) {
		} else {
			exit('您可能是盗版软件的受害者。购买正版请联系QQ：1277180438');
		}
	}
}

function convert($func,$a='DECODE'){
	if($a=='DECODE'){
		if(is_array($func)){
			for($i=0;$i<6;$i++){
				$func[$i]=convert($func[$i],'DECODE');
			}
		}else{
			if($func==0)$func=2;
			elseif($func==1)$func=0;
		}
	}else{
		if(is_array($func)){
			for($i=0;$i<6;$i++){
				$func[$i]=convert($func[$i],'ENCODE');
			}
		}else{
			if($func==2)$func=0;
			elseif($func==0)$func=1;
		}
	}
	return $func;
}
$funcarr=array('4'=>'电脑管家代挂','2'=>'电脑ＱＱ代挂','3'=>'手机ＱＱ代挂','7'=>'QQ勋章墙代挂','5'=>'ＱＱ音乐代挂','6'=>'ＱＱ手游代挂');

if($act=='add')
{
	$id=daddslashes($_GET['id']);
	$km=daddslashes($_GET['km']);
	$uin=daddslashes($_GET['uin']);
	$pwd=daddslashes($_GET['pwd']);
	$pwd=authcode($pwd,'DECODE','CLOUDKEY');
	$pwd=daddslashes($pwd);
	$func=daddslashes($_GET['func']);
	$func=convert(explode(',',$func),'DECODE');

	if(!$uin||!$func)exit('确保各项不能为空');

	if($id!=''){
		$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
		if(!$row){
			//找回QQ
			$row=$pdo->query("SELECT * FROM dg_qqs WHERE uin='{$uin}' limit 1")->fetch();
			if($row){
				$kmrow=$pdo->query("select * from dg_dgkms where km='$km' and user='$uin' limit 1")->fetch();
				if($kmrow || $pwd==$row['pwd']){
					if(!$row['id']){
						$pdo->exec("update dg_qqs set id='{$id}' where uin='{$uin}' limit 1");
					}
				}else{
					$noverify=true;//验证不通过
				}
			}else{
				$noverify=true;//验证不通过
			}
		}
	}else{
		//找回QQ
		$row=$pdo->query("SELECT * FROM dg_qqs WHERE uin='{$uin}' limit 1")->fetch();
		if($row){
			$kmrow=$pdo->query("select * from dg_dgkms where km='$km' and user='$uin' limit 1")->fetch();
			if($kmrow || $pwd==$row['pwd']){
				if(!$row['id']){
					$id=strtoupper(substr(md5($uin.time()),0,8).'-'.uniqid());
					$pdo->exec("update dg_qqs set id='{$id}' where uin='{$uin}' limit 1");
				}else{
					$id=$row['id'];
				}
			}else{
				$noverify=true;//验证不通过
			}
		}else{
			$noverify=true;//验证不通过
		}
	}
	$myrow=$pdo->query("select * from dg_dgkms where km='$km' limit 1")->fetch();

	if(!$noverify && $km=='')
	{
		$pdo->exec("update `dg_qqs` set `uin`='{$uin}',`pwd` ='{$pwd}',`cookiezt`='0',`zt`='0' where `id`='{$row['id']}'");
		$pdo->exec("update dg_orders set zt='{$func[0]}' where qid='{$row['qid']}' and tid=4 limit 1");
		$pdo->exec("update dg_orders set zt='{$func[1]}' where qid='{$row['qid']}' and tid=2 limit 1");
		$pdo->exec("update dg_orders set zt='{$func[2]}' where qid='{$row['qid']}' and tid=3 limit 1");
		$pdo->exec("update dg_orders set zt='{$func[3]}' where qid='{$row['qid']}' and tid=7 limit 1");
		$pdo->exec("update dg_orders set zt='{$func[4]}' where qid='{$row['qid']}' and tid=5 limit 1");
		$pdo->exec("update dg_orders set zt='{$func[5]}' where qid='{$row['qid']}' and tid=6 limit 1");
		$result=array("code"=>1,"msg"=>"添加/修改代挂成功！","id"=>$id,"uin"=>$uin);
	}
	elseif(!$myrow)
	{
		$result=array("code"=>-1,"msg"=>"此激活码不存在");
	}
	elseif($myrow['user']!=0){
		$result=array("code"=>-1,"msg"=>"此激活码已被使用");
	}
	else
	{
		if($row['uin']) {
			$orderrow=$pdo->query("SELECT * FROM dg_orders WHERE qid='{$row['qid']}' and tid=4 limit 1")->fetch();
			if($orderrow['endtime']>$date) $endtime = date("Y-m-d H:i:s", strtotime("+ {$myrow['value']} months", strtotime($orderrow['endtime'])));
			else $endtime = date("Y-m-d H:i:s", strtotime("+ {$myrow['value']} months"));
			if($twice_free>0){
				$endtime = date("Y-m-d H:i:s", strtotime("+ {$twice_free} days", strtotime($endtime)));
				$addstr = '，同时赠送给您'.$twice_free.'天代挂配额';
			}
			$pdo->exec("update `dg_qqs` set `uin`='{$uin}',`pwd` ='{$pwd}',`cookiezt` =0,`zt`=0 where `id`='{$row['id']}' limit 1");
			$pdo->exec("update dg_orders set zt='{$func[0]}',endtime='$endtime' where qid='{$row['qid']}' and tid=4 limit 1");
			$pdo->exec("update dg_orders set zt='{$func[1]}',endtime='$endtime' where qid='{$row['qid']}' and tid=2 limit 1");
			$pdo->exec("update dg_orders set zt='{$func[2]}',endtime='$endtime' where qid='{$row['qid']}' and tid=3 limit 1");
			$pdo->exec("update dg_orders set zt='{$func[3]}',endtime='$endtime' where qid='{$row['qid']}' and tid=7 limit 1");
			$pdo->exec("update dg_orders set zt='{$func[4]}',endtime='$endtime' where qid='{$row['qid']}' and tid=5 limit 1");
			$sds=$pdo->exec("update dg_orders set zt='{$func[5]}',endtime='$endtime' where qid='{$row['qid']}' and tid=6 limit 1");
		}
		else
		{
			$endtime = date("Y-m-d H:i:s", strtotime("+ {$myrow['value']} months"));
			$id=strtoupper(substr(md5($uin.time()),0,8).'-'.uniqid());
			
			$sds=$pdo->exec("INSERT INTO `dg_qqs` (`uid`, `uin`, `pwd`, `skey`, `p_skey`, `id`) VALUES ('{$myrow['uid']}', '{$uin}', '{$pwd}', 'o', 'o', '{$id}')");
			$qid=$pdo->lastInsertId();
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('4', '{$qid}', NOW(),'{$endtime}','{$func[0]}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('2', '{$qid}', NOW(),'{$endtime}','{$func[1]}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('3', '{$qid}', NOW(),'{$endtime}','{$func[2]}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('7', '{$qid}', NOW(),'{$endtime}','{$func[3]}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('5', '{$qid}', NOW(),'{$endtime}','{$func[4]}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`, `zt`) VALUES ('6', '{$qid}', NOW(),'{$endtime}','{$func[5]}')");
		}
		if($sds){
			$pdo->exec("update `dg_dgkms` set `user` ='$uin',`usetime`=NOW() where `kid`='{$myrow['kid']}'");
			$result=array("code"=>1,"msg"=>"添加/修改代挂成功！".$addstr,"id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
		}else{
			$result=array("code"=>-2,"msg"=>"添加/修改代挂失败！".$addstr,"id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
		}
		
	}
}
elseif($act=='switch')
{
	$id=daddslashes($_GET['id']);
	$func=daddslashes($_GET['func']);
	$uin=daddslashes($_GET['uin']);
	$star=convert(daddslashes($_GET['star']),'DECODE');
	$func_tid=array('guanjia'=>4,'pcqq'=>2,'mqq'=>3,'xunzhang'=>7,'qqmusic'=>5,'qqgame'=>6);

	$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();

	if($row['uin']==$uin) {
		$sql="update dg_orders set zt='{$star}' where qid='{$row['qid']}' and tid='{$func_tid[$func]}' limit 1";

		if($pdo->exec($sql)){
			$result=array("code"=>1,"msg"=>"修改代挂成功","id"=>$id,"uin"=>$uin);
		}else{
			$result=array("code"=>-2,"msg"=>"修改代挂失败","id"=>$id,"uin"=>$uin);
		}
	}
	else
	{
		$result=array("code"=>-1,"msg"=>"没有此记录");
	}
}
elseif($act=='fill')
{
	//if(date("H")>18 && date("i")>30){
	//	$result=array("code"=>-2,"msg"=>"亲爱的用户，今天补挂时间已经结束，请明天在规定时间内补单，谢谢合作！");
	//	exit(json_encode($result));
	//}

	$id=daddslashes($_GET['id']);
	$func=daddslashes($_GET['func']);
	$func=explode(',',$func);
	$uin=daddslashes($_GET['uin']);

	$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();

	if($row['uin']==$uin) {
		if(in_array('guanjia',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=4 limit 1");
		if(in_array('pcqq',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=2 limit 1");
		if(in_array('mqq',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=3 limit 1");
		if(in_array('xunzhang',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=7 limit 1");
		if(in_array('qqmusic',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=5 limit 1");
		if(in_array('qqgame',$func))
			$sds=$pdo->exec("update dg_orders set zt='1' where qid='{$row['qid']}' and tid=6 limit 1");

		if($sds){
			$result=array("code"=>1,"msg"=>"补挂操作成功","id"=>$id,"uin"=>$uin);
		}else{
			$result=array("code"=>-2,"msg"=>"补挂操作失败","id"=>$id,"uin"=>$uin);
		}
	}
	else
	{
		$result=array("code"=>-1,"msg"=>"没有此记录");
	}
}
elseif($act=='active')
{
	$id=daddslashes($_GET['id']);
	$km=daddslashes($_GET['km']);
	$uin=daddslashes($_GET['uin']);
	$pwd=daddslashes($_GET['pwd']);
	$pwd=authcode($pwd,'DECODE','CLOUDKEY');
	$pwd=daddslashes($pwd);

	if(!$km)exit('确保各项不能为空');

	$myrow=$pdo->query("select * from dg_dgkms where km='$km' limit 1")->fetch();
	if(!$myrow)
	{
		$result=array("code"=>-1,"msg"=>"此激活码不存在");
	}
	elseif($myrow['user']!=0){
		$result=array("code"=>-1,"msg"=>"此激活码已被使用");
	}
	else
	{
		if($id)
			$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
		else{
			$row=$pdo->query("SELECT * FROM dg_qqs WHERE uin='{$uin}' limit 1")->fetch();
			if(!$row['id']){
				$id=strtoupper(substr(md5($uin.time()),0,8).'-'.uniqid());
				$pdo->exec("update dg_qqs set id='{$id}' where uin='{$uin}' limit 1");
			}else{
				$id=$row['id'];
			}
		}

		if($row['uin']) {
			$orderrow=$pdo->query("SELECT * FROM dg_orders WHERE qid='{$row['qid']}' and tid=4 limit 1")->fetch();
			if($orderrow['endtime']>$date) $endtime = date("Y-m-d", strtotime("+ {$myrow['value']} months", strtotime($orderrow['endtime'])));
			else $endtime = date("Y-m-d H:i:s", strtotime("+ {$myrow['value']} months"));
			if($twice_free>0){
				$endtime = date("Y-m-d H:i:s", strtotime("+ {$twice_free} days", strtotime($endtime)));
				$addstr = '，同时赠送给您'.$twice_free.'天代挂配额';
			}
			$pdo->exec("update `dg_qqs` set `uin`='{$uin}',`pwd` ='{$pwd}',`cookiezt` =0,`zt`=0 where `id`='{$row['id']}'");
			$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=4 limit 1");
			$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=2 limit 1");
			$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=3 limit 1");
			$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=7 limit 1");
			$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=5 limit 1");
			$sds=$pdo->exec("update dg_orders set endtime='$endtime' where qid='{$row['qid']}' and tid=6 limit 1");
			if($sds){
				$pdo->exec("update `dg_dgkms` set `user` ='$uin',`usetime` =NOW() where `kid`='{$myrow['kid']}'");
				$result=array("code"=>1,"msg"=>"续期代挂成功".$addstr,"id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
			}else{
				$result=array("code"=>-2,"msg"=>"续期代挂失败".$addstr,"id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
			}
		}
		else
		{
			$endtime = date("Y-m-d H:i:s", strtotime("+ {$myrow['value']} months"));
			$id=strtoupper(substr(md5($uin.time()),0,8).'-'.uniqid());
			
			$sds=$pdo->exec("INSERT INTO `dg_qqs` (`uid`, `uin`, `pwd`, `skey`, `p_skey`, `id`) VALUES ('{$myrow['uid']}', '{$uin}', '{$pwd}', 'o', 'o', '{$id}')");
			$qid=$pdo->lastInsertId();
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('4', '{$qid}', NOW(),'{$endtime}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('2', '{$qid}', NOW(),'{$endtime}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('3', '{$qid}', NOW(),'{$endtime}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('7', '{$qid}', NOW(),'{$endtime}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('5', '{$qid}', NOW(),'{$endtime}')");
			$pdo->exec("INSERT INTO `dg_orders` (`tid`, `qid`, `addtime`, `endtime`) VALUES ('6', '{$qid}', NOW(),'{$endtime}')");
			if($sds){
				$pdo->exec("update `dg_dgkms` set `user` ='$uin',`usetime` =NOW() where `kid`='{$myrow['kid']}'");
				$result=array("code"=>1,"msg"=>"开通代挂成功","id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
			}else{
				$result=array("code"=>-2,"msg"=>"开通代挂失败","id"=>$id,"uin"=>$uin,"enddate"=>$endtime);
			}
		}
	}
}
elseif($act=='close')
{
	$id=daddslashes($_GET['id']);
	$uin=daddslashes($_GET['uin']);

	if(!$id||!$uin)exit('确保各项不能为空');

	$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();

	if($row['uin']==$uin) {
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=4");
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=2");
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=3");
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=7");
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=5");
		$pdo->exec("update dg_orders set zt='2' where qid='{$row['qid']}' and tid=6");
		$result=array("code"=>1,"msg"=>"关闭代挂成功","id"=>$id,"uin"=>$uin);
	}
	else
	{
		$result=array("code"=>-1,"msg"=>"没有此记录");
	}
}
elseif($act=='delete')
{
	$id=daddslashes($_GET['id']);
	$uin=daddslashes($_GET['uin']);

	if(!$id||!$uin)exit('确保各项不能为空');

	$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
	if($row['uin']==$uin) {
		$sql=$pdo->exec("delete dg_qqs where `qid`='{$row['qid']}' limit 1");
		$pdo->exec("delete dg_orders where `qid`='{$row['qid']}' limit 1");

		if($sql){
			$result=array("code"=>1,"msg"=>"删除代挂成功","id"=>$id,"uin"=>$uin);
		}else{
			$result=array("code"=>-2,"msg"=>"删除代挂失败","id"=>$id,"uin"=>$uin);
		}
	}
	else
	{
		$result=array("code"=>-1,"msg"=>"没有此记录");
	}
}
elseif($act=='list')
{
	$ids=daddslashes($_GET['ids']);
	$ids=explode(',',$ids);
	foreach($ids as $id) {
		$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
		if($row['id'])
		$data[]=array('id'=>$id,'qq'=>$row['qq'],'adddate'=>$row['addtime'],'enddate'=>$row['endtime']);
	}
	$result=array("code"=>1,"msg"=>"","data"=>$data);
}
elseif($act=='query')
{
	if($_GET['id']){
		$id=daddslashes($_GET['id']);
		$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
	}else{
		$qq=daddslashes($_GET['qq']);
		$pwd=daddslashes($_GET['pwd']);
		$row=$pdo->query("SELECT * FROM dg_qqs WHERE uin='{$qq}' and pwd='{$pwd}' limit 1")->fetch();
	}
	if($row['id']){
		$rs=$pdo->query("SELECT * FROM dg_orders WHERE qid='{$row['qid']}'");
		while($rows=$rs->fetch()){
			if($rows['tid']==4){$addtime=$rows['addtime'];$endtime=$rows['endtime'];}
			$func[$rows['tid']]=convert($rows['zt'],'ENCODE');
		}
		$data=$func[4].','.$func[2].','.$func[3].','.$func[7].','.$func[5].','.$func[6];
		$result=array('code'=>1,'id'=>$row['id'],'qq'=>$row['uin'],'data'=>$data,'black'=>$row['zt'],'adddate'=>$addtime,'enddate'=>$endtime);
	}
	else
	{
		$result=array('code'=>-1,'id'=>$row['id'],'qq'=>$row['uin'],'data'=>null,"msg"=>"没有此记录");
	}
}
/*elseif($act=='findqq')
{
	$uin=daddslashes($_GET['uin']);
	$skey=daddslashes($_GET['skey']);
	$pskey=daddslashes($_GET['pskey']);
	$cookie='pt2gguin=o0'.$uin.'; uin=o0'.$uin.'; skey='.$skey.'; p_skey='.$pskey.'; p_uin=o0'.$uin.';';
	$url='http://r.qzone.qq.com/cgi-bin/user/qzone_cgi_msg_getcnt2?uin='.$uin.'&bm=0800950000008001&v=1&g_tk='.getGTK($pskey).'&g=0.291287'.time();
	$data=get_curl($url,0,'http://cnc.qzs.qq.com/qzone/v6/setting/profile/profile.html',$cookie);
	preg_match('/\_Callback\((.*?)\);/is',$data,$json);
	$arr=json_decode($json[1], true);

	if(!$data || $arr['error']==4004){
		$result=array('code'=>-2,'data'=>null,"msg"=>"SKEY验证失败");
	}else{
		$row=$pdo->get_row("SELECT * FROM ".dbchart." WHERE qq='{$uin}' limit 1");
		if($row['id']){
			$data=$row['guanjia'].','.$row['pcqq'].','.$row['mqq'].','.$row['xunzhang'].','.$row['qqmusic'].','.$row['qqgame'];
			$result=array('code'=>1,'id'=>$row['id'],'qq'=>$row['qq'],'data'=>$data,'black'=>$row['black'],'adddate'=>$row['adddate'],'enddate'=>$row['enddate']);
		}
		else
		{
			$result=array('code'=>-1,'id'=>$row['id'],'qq'=>$row['qq'],'data'=>null,"msg"=>"没有此记录");
		}
	}
	
}*/
elseif($act=='update')
{
	$id=daddslashes($_GET['id']);
	$pwd=daddslashes($_GET['pwd']);
	$pwd=authcode($pwd,'DECODE','CLOUDKEY');
	$pwd=daddslashes($pwd);

	$row=$pdo->query("SELECT * FROM dg_qqs WHERE id='{$id}' limit 1")->fetch();
	if($row['id'])
	{
		if($pwd!=$row['pwd']){
			$sql="update `dg_qqs` set `pwd` ='{$pwd}',`zt`='0' where `id`='{$id}'";
			if($pdo->query($sql)){
				$result=array("code"=>1,"msg"=>"更新密码成功！","id"=>$row['id'],"uin"=>$row['uin'],"enddate"=>$row['endtime']);
			}else{
				$result=array("code"=>-2,"msg"=>"更新密码失败！","id"=>$row['id'],"uin"=>$row['uin'],"enddate"=>$row['endtime']);
			}
		}
	}
	else
	{
		$result=array("code"=>-1,"msg"=>"没有此记录");
	}
}
elseif($act=='black')
{
	$uin=daddslashes($_GET['uin']);
	$row=$pdo->query("SELECT * FROM dg_qqs WHERE uin='{$uin}' limit 1")->fetch();
	if($row['uin']){
		if($row['black']==1)$reason="密码错误";
		if($row['black']==2)$reason="ＱＱ冻结";
		if($row['black']==3)$reason="请关闭设备锁";
		else $reason="请关闭设备锁";
		$result=array('code'=>1,'uin'=>$uin,'black'=>$row['zt'],'reason'=>$reason);
	}
	else
	{
		$result=array('code'=>-1,'uin'=>$uin,"msg"=>"没有此记录");
	}
}
elseif($act=='buy')
{
	$result=array('code'=>1,'msg'=>$info_buy,'qq'=>$info_qq);
}
else
{
	$result=array("code"=>-5,"msg"=>"No Act!");
}

echo json_encode($result);

?>