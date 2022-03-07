<?php
	header("Access-Control-Request-Method:GET,POST");
	header("Access-Control-Allow-Credentials:true");
	header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept, Authorization');

	$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
	header('Access-Control-Allow-Origin:'.$origin); // 允许单个域名跨域


spl_autoload_register(function ($className) {
    $namespace = 'Minho\\Captcha';
    if (strpos($className, $namespace) === 0) {
        $className = str_replace($namespace, '', $className);
        $fileName = __DIR__ . '/captcha/' . str_replace('\\', '/', $className) . '.php';
        if (file_exists($fileName)) {
            require($fileName);
        }
    }
});

use Minho\Captcha\CaptchaBuilder;

$captch = new CaptchaBuilder();


$captch->initialize([
    'width' => 160,     // 宽度
    'height' => 40,     // 高度
    'line' => false,     // 直线
    'curve' => true,   // 曲线
    'noise' => 1,   // 噪点背景
    'fonts' => []       // 字体
]);
$captch->create();
session_start();
$_SESSION['scode'] = $captch->getText();
$captch->output(1);

/*
//创建一个大小为 100*30 的验证码
$image = imagecreatetruecolor(100, 25);
$bgcolor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgcolor);

$captch_code = '';$count=0;
for ($i = 0; $i < 4; $i++) {
    $fontsize = 5;
    $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
    $data = '2345678923456789ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz!@#$&%*()/-_:;,.';
	$fontcontent = substr($data, rand(0, strlen($data) - 1), 1);
    $captch_code .= $fontcontent;
    $x = ($i * 100 / 4) + rand(2, 12);
    $y = rand(2, 9);
    imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
}
//就生成的验证码保存到session
$_SESSION['scode'] = @strtoupper($captch_code);
 
//在图片上增加点干扰元素
for ($i = 0; $i < 250; $i++) {
    $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
    imagesetpixel($image, rand(1, 99), rand(1, 24), $pointcolor);
}
 
//在图片上增加线干扰元素
for ($i = 0; $i < 4; $i++) {
    $linecolor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
    imageline($image, rand(1, 99), rand(1, 24), rand(1, 99), rand(1, 24), $linecolor);
    //imagelinethick($image, rand(1, 99), rand(1, 24), rand(1, 99), rand(1, 24), $linecolor,2);
}
//设置头
header('content-type:image/png');
imagepng($image);
imagedestroy($image);


function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
    /* 下面两行只在线段直角相交时好使
    imagesetthickness($image, $thick);
    return imageline($image, $x1, $y1, $x2, $y2, $color);
    
    if ($thick == 1) {
        return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
        return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
        round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
        round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
        round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
        round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
}*/