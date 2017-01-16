<?php 
function getCarInfoByCarTypeID($id)
{
	return D('CarInfoView')->where('car_type.id='.$id)->find();
}

function getOilInfo($id)
{
	return M('car_oil')->find($id);
}

function totalPrice($data)
{
	return $data['total']+F('service_price')-$data['card_price'];
}

function jsonToHtml($json)
{
	$array = objectToArray(json_decode($json));
	$temp = array();

	if(array_key_exists('car_oil', $array)){
		$oil = getOilInfo($array['car_oil']);
		echo "<tr><td>机油</td><td>{$oil['title']} {$oil['price']}元</td></tr>";
	}
	if(array_key_exists('jilv', $array)){
		echo "<tr><td>机油滤清器</td><td>{$array['jilv']}元</td></tr>";
	}
	if(array_key_exists('qilv', $array)){
		echo "<tr><td>空气滤清器</td><td>{$array['qilv']}元</td></tr>";
	}
	if(array_key_exists('konglv', $array)){
		echo "<tr><td>空调滤清器</td><td>{$array['konglv']}元</td></tr>";
	}
	if(array_key_exists('front_pian', $array)){
		echo "<tr><td>前刹车片</td><td>{$array['front_pian']}元</td></tr>";
	}
	if(array_key_exists('back_pian', $array)){
		echo "<tr><td>后刹车片</td><td>{$array['back_pian']}元</td></tr>";
	}
	if(array_key_exists('front_pan', $array)){
		echo "<tr><td>前刹车盘</td><td>{$array['front_pan']}元</td></tr>";
	}
	if(array_key_exists('back_pan', $array)){
		echo "<tr><td>后刹车盘</td><td>{$array['back_pan']}元</td></tr>";
	}
	if(array_key_exists('huohuasai', $array)){
		echo "<tr><td>火花塞</td><td>{$array['huohuasai']}元</td></tr>";
	}
	if(array_key_exists('shacheyou', $array)){
		echo "<tr><td>刹车油</td><td>{$array['shacheyou']}元</td></tr>";
	}
	if(array_key_exists('neishi', $array)){
		echo "<tr><td>内饰清洗</td><td>{$array['neishi']}元</td></tr>";
	}
	if(array_key_exists('kt', $array)){
		echo "<tr><td>空调清洗</td><td>{$array['kt']}元</td></tr>";
	}
	if(array_key_exists('jicang', $array)){
		echo "<tr><td>发动机舱清洗</td><td>{$array['jicang']}元</td></tr>";
	}
	if(array_key_exists('lungu', $array)){
		echo "<tr><td>轮毂清洗</td><td>{$array['lungu']}元</td></tr>";
	}
}

/**
 * 对象转数组
 * @param  [type] $obj [description]
 * @return [type]      [description]
 */
function objectToArray($obj){
    $arr = is_object($obj) ? get_object_vars($obj) : $obj;
    if(is_array($arr)){
        return array_map(__FUNCTION__, $arr);
    }else{
        return $arr;
    }
}

/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content)
{

    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
    $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
    $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
    $mail->Password = C('MAIL_PASSWORD'); //邮箱密码
    $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
    $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
    $mail->AddAddress($to, "尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet = C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject = $title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    echo($mail->ErrorInfo);
    return ($mail->Send());
}

/**
 * 以M的形式显示压缩包大小
 */
function statuSize ($size) {
	$size = round($size/1024/1024,1);
	if ($size == 0) {
		$size = 0.1;
	}
	return $size;
}
 
 ?>