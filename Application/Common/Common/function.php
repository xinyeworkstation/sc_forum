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




//传递数据以易于阅读的样式格式化后输出
function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}


/**
 * 上传文件类型控制 此方法仅限ajax上传使用
 * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string   $format  文件格式限制
 * @param  integer  $maxSize 允许的上传文件最大值 10485760
 * @return booler   返回ajax的json格式数据
 */
function ajax_upload($path='file',$format='empty',$maxSize='10485760'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,               // 上传文件最大为10M
                'rootPath'  =>  './Public',                   // 文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         // 文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     // 上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   // 自动使用子目录保存上传文件 默认为true
                'exts'      =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // p($_FILES);
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        // p($info);
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            echo json_encode($data);
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                // p($data);
                echo json_encode($data);
            }               
        }
    }
}

/**
 * 在delete表中记录任意其他表中的禁用数据，并定时一周进行删除
 * @param  [String] $tname 表名
 * @param  [int] $id   禁用字段id
 * @param  [String] $flag  表中禁用字段属性名
 * @return [bool]   操作是否成功   
 */
function forbidden ($tname,$id,$flag = 'status') {
	$work = M($tname);
	$arr[$flag] = '0';
	$num = $work->where('id='.$id)->save($arr);
	if ($num) {
		$model = M('delete');
		$data['table'] = $tname;
		$data['t_id'] = $id;
		$data['time'] = time()+7*24*60*60;//设置删除的时间为七天后
		$row = $model->add($data);
		if($row){
			return true;
		}else{
			return false;
		}
	} else {
		return false;
	}
}

/**
 * 在delete表中删除任意其他表中的已启用数据
 * @param  [String] $tname 表名
 * @param  [int] $id   禁用字段id
 * @param  [String] $flag  表中禁用字段属性名
 * @return [bool]   操作是否成功
 */
function allow ($tname,$id,$flag = 'status') {
	$work = M($tname);
	$arr[$flag] = '1';
	$num = $work->where('id='.$id)->save($arr);
	if ($num) {
		$model = M('delete');
		$where = array('table'=>$tname,'t_id'=>$id);
		$row = $model->where($where)->delete();
		if($row){
			return true;
		}else{
			return false;
		}
	} else {
		return false;
	}
}

