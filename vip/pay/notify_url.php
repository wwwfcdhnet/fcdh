<?php
/* *
 * 功能：彩虹易支付异步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 */

require_once("epay.config.php");
require_once("lib/epay_notify.class.php");
include '../../mysql_mydb.php';
include'../../functionOpen.php';
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//彩虹易支付交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];
	
	//支付金额
	$money = $_GET['money'];

	$_GET['name']='购买会员#VIP1#mnfnnf@163.com';

	$name = explode('#',$_GET['name']);

	$email=$name['2'];
	$vrank=intval(substr($name['1'],3,1));

	
	if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
		$rand=mt_rand();
		$vcode=substr(md5($time.$_KEY.$rand),0, 18);
		$sql='INSERT INTO vipcode SET vcode="'.$vcode.'",vrank='.$vrank.',price='.$money.',email="'.$email.'"';
		
		$result=mysqli_query($mydb,$sql);
		//echo$sql;
		if (!$result) {
			$msg='<strong class="c8">数据重复: '.$vcode.'</strong>';
		}else{
			require_once "../Smtp.class.php";
			$smtpserver = "ssl://smtp.qq.com";              //SMTP服务器
			$smtpserverport =465;                      //SMTP服务器端口
			$smtpusermail = "2281195245@qq.com";      //SMTP服务器的用户邮箱
			$smtpuser = "2281195245@qq.com";         //SMTP服务器的用户帐号
			$smtppass = "fyrxpgolqiurdhie";			  //SMTP服务器的用户密码
			$title='粉美人';

			$smtpemailto = $email;		    //发送给谁 | 收件人  

			$mailsubject = $title." - 邀请码/升级码";        //邮件主题
			$mailbody = "<h1> 邀请码/升级码: <span style='color:blue'>$vcode</span> </h1>";//邮件内容  
			$mailtype = "HTML";                      //邮件格式（HTML/TXT）,TXT为文本邮件
			$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
			$smtp->debug = false;                     //是否显示发送的调试信息
			$state=$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype, $title);
			if($state==""){  
				$msg= "<b>验证码发送失败。</b>";  
			}else{
				$msg= "<b>验证码发送成功。</b>";  
			}
		}

    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
 
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    $msg= "fail";
}

echo  $msg;
?>