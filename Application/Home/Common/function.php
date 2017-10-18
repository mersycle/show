<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*发生短信*/
function send_set(){
	set_time_limit(0);
	header("Content-Type: text/html; charset=UTF-8");
	Vendor('Nusoap.Client');
	$gwUrl = C('YM_WGURL');
	$serialNumber = C('YM_SERIAL');
	$password = C('YM_PWD');
	$sessionKey = C('YM_SESSION_KEY');
	$connectTimeOut = 2;
	$readTimeOut = 10;
		$proxyhost = false;
		$proxyport = false;
		$proxyusername = false;
		$proxypassword = false; 
	$client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
	$client->setOutgoingEncoding("UTF-8");
	return $client;
}

function sendSMS($mobile=array(),$content){
	$client = send_set();
	$contents=strpos('start'.$content,'')===false?''.$content:$content;
        
	$statusCode = $client->sendSMS($mobile,$content);
	return $statusCode;
}

function create_xls($data,$filename='购票号码.xls'){
//            echo 111;die;
		ini_set('max_execution_time', '0');
		$filename=str_replace('.xls','', $filename).'.xls';
		require THINK_PATH.'Library/Org/Util/PHPExcel.class.php';
		require THINK_PATH.'Library/Org/Util/PHPExcel/Reader/Excel5.php';
		$phpexcel = new PHPExcel();
		$phpexcel->getProperties()
		->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");
		$phpexcel->setActiveSheetIndex(0)
		->setCellValueExplicit('A1', '姓名')
		->setCellValueExplicit('B1', '电话号码');
		
		$objActSheet=$phpexcel->getActiveSheet();
		foreach($data as $k => $v){
//                    var_Dump($k);die;
			$k=$k+1;
			$num=$k+1;//数据从第二行开始录入
			$objActSheet
			//Excel的第A列，查出数组的键值，下面以此类推
			->setCellValueExplicit('A'.$num, $k,\PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValueExplicit('B'.$num, $v,\PHPExcel_Cell_DataType::TYPE_STRING);
			//设置单元格宽度自动 以下设置宽度可有可无
//			$objActSheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('A1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('B1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('C1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('D1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('E1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('F1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('G1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('H1')->getFill()->getStartColor()->setARGB('FF808080');
//			$objActSheet->getStyle('I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//			$objActSheet->getStyle('I1')->getFill()->getStartColor()->setARGB('FF808080');
			 
//			$objActSheet->getColumnDimension('A')->setWidth(20);
//			$objActSheet->getColumnDimension('B')->setWidth(15);
//			$objActSheet->getColumnDimension('C')->setWidth(10);
//			$objActSheet->getColumnDimension('D')->setWidth(15);
//			$objActSheet->getColumnDimension('E')->setWidth(15);
//			$objActSheet->getColumnDimension('F')->setWidth(15);
//			$objActSheet->getColumnDimension('G')->setWidth(5);
//			$objActSheet->getColumnDimension('H')->setWidth(40);
//			$objActSheet->getColumnDimension('I')->setWidth(10);
//			$objActSheet->getColumnDimension('J')->setWidth(10);
//			$objActSheet->getColumnDimension('K')->setWidth(10);
		}
		$phpexcel->setActiveSheetIndex(0);
		header('Content-Type: application/vnd.ms-excel');
		header("Content-Disposition: attachment;filename=$filename");
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objwriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
//                var_dump($objwriter);die;
		$objwriter->save('php://output');
//		$objwriter->save();
		exit;
	}
     
function curl_post($url, $post) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $data = curl_exec($ch);
    if($data)
    {
        curl_close($ch);
        return $data;
    }else {
//        $errorstr = curl_error($ch);
//        $error = curl_errno($ch);
//        saveFile(UP_ROOT.'/log/curl_post_error.log',$error);
        curl_close($ch);
        return false;
    }
}


function curl_get($url){
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    // curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);

    if($data)
    {
        curl_close($curl);
        return $data;
    }else {
//        $errorstr = curl_error($curl);
//        $error = curl_errno($curl);
//         saveFile(UP_ROOT.'/log/curl_get_error.log',$error);
        curl_close($curl);
        return false;
    }
   
}
