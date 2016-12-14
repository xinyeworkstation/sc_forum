<?php 
function dd($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

/**
 * 获取排序后的分类
 * @param  [type]  $data  [description]
 * @param  integer $pid   [description]
 * @param  string  $html  [description]
 * @param  integer $level [description]
 * @return [type]         [description]
 */
function getSortedCategory($data,$pid=0,$html="|---",$level=0)
{
	$temp = array();
	foreach ($data as $k => $v) {
		if($v['pid'] == $pid){
	
			$str = str_repeat($html, $level);
			$v['html'] = $str;
			$temp[] = $v;

			$temp = array_merge($temp,getSortedCategory($data,$v['id'],'|---',$level+1));
		}
		
	}
	return $temp;
}

/**
 * 根据key，返回当前行的所有数据
 * @param  string  $key  字段key
 * @return array         当前行的所有数据
 */
function getSettingValueDataByKey($key)
{
	return M('setting')->getByKey($key);
}

/**
 * 根据key返回field字段
 * @param  string $key   [description]
 * @param  string $field [description]
 * @return string        [description]
 */
function getSettingValueFieldByKey($key,$field)
{
	return M('setting')->getFieldByKey($key,$field);
}

/**
 * 上传函数（上传图片和压缩包）
 * @param  [type] $size  [description]
 * @param  [type] $array [description]
 * @param  [type] $path  [description]
 * @return [type]        [description]
 */
function upload($config,$file){
	$upload = new \Think\Upload($config);	//实例化上传类
	//$upload->maxSize = $size;	//设置文件上传大小
	//$upload->exts = $array;		//设置文件上传类型
	//$upload->savePath = $path;	//设置文件上传目录
	$info = $upload->upload($file);
	if(!$info){
		$error = $upload->getError();
		return $error;		//返回错误信息
	}else{
		return $info;		//return true
	}
}


function get_url($str){
	$url = explode('@and',$str);
	return $url;
}


function uncompress($compress){
	//获取文件后缀名 $info['extension'],文件名$info['filename']
	$info = pathinfo($compress);
	//return $info['extension'];
	$arr = explode('/',$compress);
	$num = count($arr);
	for($i=0;$i<$num-1;$i++){
		$url .= '/'.$arr[$i];
	}
	$url = ltrim($url,'/');
	//return $url;
	if($info['extension'] == 'rar'){
		$rar_file = rar_open($compress) or die("Can't open Rar archive"); 
		/*example.rar换成其他档桉即可*/ 
		$entries = rar_list($rar_file); 
		foreach ($entries as $entry) { 
			$entry->extract($url.'/tmp/'); //解压到指定路径 
		} 
		rar_close($rar_file);
	}else{
		get_zip_originalsize($compress,$url.'/tmp/');
	}
	$url = $url.'/tmp/'.$info['filename'];
	
	//获取也就是扫描文件夹内的文件及文件夹名存入数组 $filesnames
	$image = array();
	$filesnames = scandir($url);
	foreach ($filesnames as $name) {
		if($name=='.' || $name=='..')
			continue;
		$image[] = $url.'/'.$name;
	}
	return $image;
}


function get_zip_originalsize($filename, $path) {
	 //先判断待解压的文件是否存在
	 if(!file_exists($filename)){
	  die("文件 $filename 不存在！");
	 } 
	 $starttime = explode(' ',microtime()); //解压开始的时间

	 //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
	 $filename = iconv("utf-8","gb2312",$filename);
	 $path = iconv("utf-8","gb2312",$path);
	 //打开压缩包
	 $resource = zip_open($filename);
	 $i = 1;
	 //遍历读取压缩包里面的一个个文件
	 while ($dir_resource = zip_read($resource)) {
	  //如果能打开则继续
	  if (zip_entry_open($resource,$dir_resource)) {
	   //获取当前项目的名称,即压缩包里面当前对应的文件名
	   $file_name = $path.zip_entry_name($dir_resource);
	   //以最后一个“/”分割,再用字符串截取出路径部分
	   $file_path = substr($file_name,0,strrpos($file_name, "/"));
	   //如果路径不存在，则创建一个目录，true表示可以创建多级目录
	   if(!is_dir($file_path)){
	    mkdir($file_path,0777,true);
	   }
	   //如果不是目录，则写入文件
	   if(!is_dir($file_name)){
	    //读取这个文件
	    $file_size = zip_entry_filesize($dir_resource);
	    //最大读取6M，如果文件过大，跳过解压，继续下一个
	    if($file_size<(1024*1024*6)){
	     $file_content = zip_entry_read($dir_resource,$file_size);
	     file_put_contents($file_name,$file_content);
	    }else{
	     echo "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312","utf-8",$file_name)." </p>";
	    }
	   }
	   //关闭当前
	   zip_entry_close($dir_resource);
	  }
	 }
	 //关闭压缩包
	 zip_close($resource); 
}