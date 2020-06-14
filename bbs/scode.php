<?php
session_start();
//创建一个大小为 100*30 的验证码
$image = imagecreatetruecolor(100, 25);
$bgcolor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgcolor);

$captch_code = '';$count=0;
for ($i = 0; $i < 4; $i++) {
    $fontsize = 5;
    $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
    $data = '2345678923456789ABCDEFGHIJKMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz!@#$^&()%+=-';
	$fontcontent = substr($data, rand(0, strlen($data) - 1), 1);
    $captch_code .= $fontcontent;
    $x = ($i * 100 / 4) + rand(6, 11);
    $y = rand(2, 7);
    imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
}
//就生成的验证码保存到session
$_SESSION['scode'] = @strtoupper($captch_code);
 
//在图片上增加点干扰元素
for ($i = 0; $i < 200; $i++) {
    $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
    imagesetpixel($image, rand(1, 99), rand(1, 29), $pointcolor);
}
 
//在图片上增加线干扰元素
for ($i = 0; $i < 3; $i++) {
    $linecolor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
    imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
}
//设置头
header('content-type:image/png');
imagepng($image);
imagedestroy($image);
