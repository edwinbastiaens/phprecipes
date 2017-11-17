<?php
class MatchRecaptchya
{
    private static $txt = [
        "placeholder" => [
            "nl" => "ben jij een mens? Vul hier de oplossing van de som in.",
            "fr" => "Tapez ici la solution."]
    ];

    private static $lang = "nl";
    protected static function text($keyword)
    {
        if (isset(self::$txt[$keyword])){
            if (isset(self::$txt[$keyword][self::$lang]))
                return self::$txt[$keyword][self::$lang];
        }
        return "";
    }

    public static function lang($lng='nl')
    {
        self::$lang = $lng;
    }

    public static function cleanOldImages()
    {
        if (rand(0,10) < 9) return;
        $t = time();
        $iterator = new FilesystemIterator($_SERVER['DOCUMENT_ROOT'] . "/images/rcp/");
        foreach($iterator as $fileInfo){
            if(! $fileInfo->isFile()) continue;
            $ext = strtoupper(pathinfo($fileInfo->getFileName(), PATHINFO_EXTENSION));
            if ($ext != "JPG") continue;
            $diff =  $t - $fileInfo->getCTime();
            if ($diff > 100){
                unlink($_SERVER['DOCUMENT_ROOT'] . "/images/rcp/". $fileInfo->getFileName());
            }
        }
    }
    public static function getFormField()
    {
        $im = imagecreatetruecolor(180, 40);
        $col1 = imagecolorallocate($im, rand(180,255), rand(180,255), rand(180,255));
        $col2 = imagecolorallocate($im, rand(0,150), rand(0,150), rand(0,150));
        for ($i=0;$i<5;$i++){
            imageline ( $im , rand(0,180) , rand(0,40) , rand(0,180) , rand(0,40) ,  $col2);
        }
        $a = rand(1,9);
        $b = rand(1,9);
        $txt = $a. "  +  ".$b." = ?";
        imagettftext ( $im, 18, 0, 10, 30, $col1, 'fonts/tahoma.ttf' , $txt);
        $filename = uniqid() . 'chktxt.jpg';
        imagejpeg($im, $_SERVER['DOCUMENT_ROOT'] . "/images/rcp/" . $filename);

        $sum = $a + $b;
        $chk = md5($sum . date("ymd"));
        
        // Free up memory
        imagedestroy($im);

        return "<img src='http://www.freelancenetwork.be/images/rcp/".$filename."?a=".rand(1,1000)."' />"
        . "<input type='hidden' name='mchky' value='".$chk."' />"
        . "<input name='mathcap' value='' placeholder='".self::text('placeholder')."' />";
    }

    public static function getEmptyFormField()
    {
        return "<input type='hidden' name='nocap' value='1' />";
    }

    public static function processForm()
    {
        self::cleanOldImages();
        if (isset($_POST['nocap'])) return true;
        if(!isset($_POST['mathcap'])) return false;
        $captcha = $_POST['mathcap'];
        if (! $captcha) return false;
        if (!is_numeric($captcha)) return false;
        $val = md5($captcha . date("ymd"));
        if ($val == $_POST['mchky']) return true;
        return false;
    }   
}

class GoogleRecaptchya
{     public static function getFormField()
    {
        return GoogleRecaptchya2::getFormField();
    }

    public static function processForm()
    {
        return GoogleRecaptchya2::processForm();
    }

    public static function getEmptyFormField()
    {
        return GoogleRecaptchya2::getEmptyFormField();
    }
}

class GoogleRecaptchya2
{ //ref: http://stackoverflow.com/questions/27274157/new-google-recaptcha-with-checkbox-server-side-php
    private static $sitekey = "6LcMkxwUAAAAAL_zYC0N0UnvRydARe_e0pVRph0B";
    private static $secretKey = "6LcMkxwUAAAAADOi9cfItLtUbbmkR-n5HJwIQVAK";
    public static function getFormField()
    {
        return "<div class='g-recaptcha' data-sitekey='".self::$sitekey."'></div>";
    }

    public static function processForm()
    {
        if(!isset($_POST['g-recaptcha-response'])) return false;
        $captcha = $_POST['g-recaptcha-response'];
        if (! $captcha) return false;
        $response = json_decode(
            file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".self::$secretKey."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']),
            true
        );

        if($response['success'] == false) return false;//spammer
        return true;
    }
}
