<?php
namespace app\index\controller;
use think\db;
use think\Loader;
use think\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class Index extends Controller
{
    public function index()
    {
        return "<a href='".url('excel_list')."'>导出</a>";
    }
	//导出
	 public function excel_list()
    {
        Loader::import('PHPExcel.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
        $phpexcel = new \PHPExcel();
        $class = db('iclass')->select();
        $column  = db()->query('show full columns from wx_users');
        $letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $k = 0;
        foreach ($class as $v) {
            $stuinfo = db('users')->where("iclass = {$v['id']}")->select();
            if(!$stuinfo) {
                continue;
            }
            $phpexcel->createSheet();
            $phpexcel->setActiveSheetIndex($k);
            $sheet = $phpexcel->getActiveSheet();
            $sheet->setTitle($v['classname']);
            foreach ($column as $ckey=>$cval) {    //设置第一行
                $comment = $cval['Comment'] ? $cval['Comment'] : $cval['Field'];
                $sheet->setCellValue("{$letter[$ckey]}1",$comment);
            }

            $line = 2;
            foreach ($stuinfo as $skey=>$sval) { //设置下面几行
                $row = 0;
                foreach($sval as $sskey=>$ssval) {
                    $sheet->setCellValue("{$letter[$row]}{$line}",$ssval);
                    $row++;
                }
                $line++;
            }
            $k++;
        }
		$PHPWriter = \PHPExcel_IOFactory::createWriter($phpexcel,'Excel2007'); //创建生成的格式
        header('Content-Disposition: attachment;filename="学生列表.xlsx"'); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
    }
	public function excelImport()
	{
		 return $this->fetch();
	}
	public function do_excelImport()
	{
		$file = request()->file('file');
        $pathinfo = pathinfo($file->getInfo()['name']);
        $extension = $pathinfo['extension'];
        $savename = time().'.'.$extension;
        if($upload = $file->move('./upload',$savename)) {
            $savename = './upload/'.$upload->getSaveName();
            Loader::import('PHPExcel.PHPExcel');
            Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($savename,$encode = 'utf8');
            $sheetCount = $objPHPExcel->getSheetCount();
            for($i=0 ; $i<$sheetCount ; $i++) {    //循环每一个sheet
                $sheet = $objPHPExcel->getSheet($i)->toArray();
                unset($sheet[0]);
                foreach ($sheet as $v) {
                    $data['id'] = $v[0];
                    $data['username'] = $v[1];
                    $data['sex'] = $v[2];
                    $data['idcate'] = $v[3];
                    $data['dorm_id'] = $v[4];
                    $data['iclass'] = $v[5];
                    $data['adress'] = $v[6];
                    $data['nation'] = $v[7];
                    $data['major'] = $v[8];
                    $data['birthday'] = $v[9];
                    $data['photo'] = $v[10];
                    $data['famname'] = $v[11];
                    $data['hujiadress'] = $v[12];
                    $data['stutel'] = $v[13];
                    $data['weixin'] = $v[14];
                    $data['qq'] = $v[15];
                    $data['email'] = $v[16];
                    $data['famtel'] = $v[17];
                    $data['pro'] = $v[18];
                    $data['city'] = $v[19];
                    $data['area'] = $v[20];
                    $data['rili'] = $v[21];
                    $data['bed'] = $v[22];
                    $data['openid'] = $v[23];
                    $data['status'] = $v[24];
                    try {
                        db('users1')->insert($data);
                    } catch(\Exception $e) {
                        return '插入失败';
                    }

                }
            }
            echo "succ";
        } else {
            return $upload->getError();
        }

	}
	public function yu()
	{
		return $this->fetch();
	}
	public function reg()
	{
		$email=input('post.email');
		$username=input('post.username');
		$title="你好,".$username.'欢迎注册相亲网';
		$body="你好，".$username.',相亲网欢迎你的加入，以下是激活链接：http://localhost/tp1';
		sendmail($email,$title,$body);
	}
    public function excel()
    {
        $path = dirname(__FILE__); //找到当前脚本所在路径
        Loader::import('PHPExcel.PHPExcel'); //手动引入PHPExcel.php
        Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory'); //引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
        $PHPExcel = new \PHPExcel(); //实例化
        $iclasslist=db('iclass')->select();		
		// echo "<pre>";
		// print_r($iclasslist);exit;								
        foreach($iclasslist as $key=> $v){
            $PHPExcel->createSheet();
            $PHPExcel->setactivesheetindex($key);
            $PHPSheet = $PHPExcel->getActiveSheet();
            $PHPSheet->setTitle($v['classname']); //给当前活动sheet设置名称
            $PHPSheet->setCellValue("A1", "编号")
                     ->setCellValue("B1", "姓名")
                     ->setCellValue("C1", "性别")
                     ->setCellValue("D1", "身份证号")
                     ->setCellValue("E1", "宿舍编号")
                     ->setCellValue("F1", "班级");//表格数据
            $userlist=db('users')->where("iclass=".$v['id'])->select();
			// echo "<pre>";
			// print_r($userlist);exit;
            //echo db('users')->getLastSql();
            $i=2;
            foreach($userlist as $t)
            {
				// echo "<pre>";
				// print_r($t);exit;
                $PHPSheet->setCellValue("A".$i, $t['id'])
                         ->setCellValue("B".$i, $t['username'])
                         ->setCellValue("C".$i, $t['sex'])
                         ->setCellValue("D".$i, $t['idcate'])
                         ->setCellValue("E".$i, $t['dorm_id'])
                         ->setCellValue("F".$i, $t['iclass']);
                        //表格数据
                $i++;
            }

        }
       // exit;
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007"); //创建生成的格式
        header('Content-Disposition: attachment;filename="学生列表.xlsx"'); //下载下来的表格名
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
    }
}
