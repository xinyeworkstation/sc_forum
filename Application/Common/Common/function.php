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
function upload($config){
	$upload = new \Think\Upload($config);	//实例化上传类
	//$upload->maxSize = $size;	//设置文件上传大小
	//$upload->exts = $array;		//设置文件上传类型
	//$upload->savePath = $path;	//设置文件上传目录
	$info = $upload->upload();
	//$move = move_uploaded_file($info[1]['savename'], __PUBLIC__.'/Uploads/compress/');
	echo dirname(__FILE__);
	if(!file_exists('./Public/Uploads/compress')){
		mkdir('./Public/Uploads/compress');
	}
	$move = move_uploaded_file("./Public".$info[1]['savepath'].$info[1]['name'],"./Public/Uploads/compress/".$info[1]['savename']);
	if(!$info){
		$error = $upload->getError();
		return $error;		//返回错误信息
	}else{
		return $info;
		//return true
	}
}