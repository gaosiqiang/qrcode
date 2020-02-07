<?php
namespace app\controller;

use vendor\base\Application;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;

class ImgController extends Application {

    /**
     * 二维码
     * @return string
     * @throws \Endroid\QrCode\Exception\InvalidPathException
     */
    public function actionQrcode()
    {
        $code = Application::$app->request->get('code', '');
        if (!$code) {
            return ['code' => 100010, 'msg' => 'code not null!'];
        }
        $code_img_size = Application::$app->request->get('code_img_size', 600);
        $logo = Application::$app->request->get('logo', '');
        $logo_rate = Application::$app->request->get('logo_rate', 4); //即logo的大小是 (1/$logo_rate) * $code_img_size
        if (!$code) {
            return '';
        }
        // Create a basic QR code
        $qrCode = new QrCode($code);
        $qrCode->setSize($code_img_size);
        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);

        $local_log = self::getFile($logo, '/data/logos/'.date('Ymd'));
//        $local_log['file_path'] = '/usr/local/nginx/html/qrcode/vendor/endroid/qr-code/assets/images/symfony.png';
        if ($local_log['file_path'] && is_file($local_log['file_path'])) {
            $qrCode->setLogoPath($local_log['file_path']);
            $qrCode->setLogoSize(ceil($code_img_size/$logo_rate), ceil($code_img_size/$logo_rate));
        }
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        // Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
        @unlink($local_log['file_path']);
        die();
    }


    private static function getFile($url, $save_dir='', $filename=''){
        $ext_array = [
            '.gif',
            '.jpg',
            '.png'
        ];
        if(trim($url)==''){
            return array('file_name'=>'','file_path'=>'','error'=>1);
        }
        if(trim($save_dir)==''){
            $save_dir='./';
        }
        if(trim($filename)==''){
            $ext=strrchr($url,'.');
            if(!in_array($ext, $ext_array)){
                return array('file_name'=>'','file_path'=>'','error'=>3);
            }
            $filename=time().$ext;
        }
        if(0!==strrpos($save_dir,'/')){
            $save_dir.='/';
        }
        if(!file_exists($save_dir)&&!mkdir($save_dir,0755,true)){
            return array('file_name'=>'','file_path'=>'','error'=>5);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        file_put_contents($save_dir.$filename, $file_content);
        return array('file_name'=>$filename, 'file_path'=> $save_dir.$filename, 'error'=>0);
    }


}