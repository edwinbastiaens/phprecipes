<?php

require_once('fpdf181/fpdf.php');
require_once('FPDI-1.6.1/fpdi.php');
include_once "GoogleRecaptchya.php";
include_once 'Logger.php';

class PdfMerger
{
    /**
     * Merges all 
     * @param type $path
     */
    public static function mergeDir($sourcepath)
    {
        $files2 = scandir($sourcepath);
        $files = [];
        foreach($files2 as $file){
            if ( strtoupper(pathinfo($file)['extension']) == "PDF"){
                $files[] = $sourcepath . "/" . $file;
            }
        }

        $pageCount = 0;
        // initiate FPDI
        $pdf = new FPDI();

        // iterate through the files
        foreach ($files AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            if ($_POST['modus'] == "1")
                $pc = 1;
            else
                $pc = $pageCount;
            for ($pageNo = 1; $pageNo <= $pc; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                // create a page (landscape or portrait depending on the imported page size)
                if ($size['w'] > $size['h']) {
                    $pdf->AddPage('L', array($size['w'], $size['h']));
                } else {
                    $pdf->AddPage('P', array($size['w'], $size['h']));
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                //$pdf->SetFont('Helvetica');
                //$pdf->SetXY(5, 5);
                //$pdf->Write(8, 'Generated by FPDI');
            }
        }
        $pdf->Output();
    }

    public static function getUploadForm()
    {
        $google =<<<G
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- tolima-leader -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-7137239348401468"
     data-ad-slot="1307243290"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
G;
        $recap = GoogleRecaptchya::getFormField();
        return <<<FRM
<!DOCTYPE html>
<html>
    <head>
        <title>PDF merger</title>
        <META NAME="DESCRIPTION" CONTENT="Merge a number of pdf files into one pdf file with Tolima's free online pdfmerger." />
        <META NAME="KEYWORDS" CONTENT="pdf merger, merge pdf files,merging pdf files" />
        <META charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>
   </head>
    <body>
        <div class='container'>
            <div class='row'>
                <div class='col-md-10 col-md-offset-1'>
                    {$google}
                    <h1>Free online PDF merger</h1>
                    <p>
                    <h4>Merge a number of pdf files into one pdf file.<h4><br/>
                    Do you have to process a whole stack of small pdf documents?  Or maybe you sent a pile of invoices 
                    to your accountant who then has to print them one by one?<br/>
                    You can spare yourself or your accountant a lot of this tedious work by merging all the pdf'files together with this online pdf merger. 
                    </p>
                    <br/>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Select pdf files to merge:</h2>
                        </div>
                        <div class="panel-body">
                            
                            <form method='post' enctype="multipart/form-data">
                                <input type='hidden' name='upl' value='1' />
                                
                                <input type="file" name="upload[]" multiple="" id="fileToUpload" class='btn btn-info form-control' ><br/>
                                <select name='modus' class='form-control' >
                                    <option value='all'>Using all pages of each document (default)</option>
                                    <option value='1'>Only first page of each document</option>
                                </select><br/><br/>
                                {$recap}<br/>
                                <input type='submit' class='btn btn-primary' value='Merge' /><BR/><br/>
                                {$google}
                            </form>
                        </div>
                    </div><br/>
                    <a class="twitter-share-button"
                        href="https://twitter.com/intent/tweet?text=Merge a number of pdf files into one pdf file with Tolima's online pdf merger - ">
                      Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                </div>
            </div>
        </div>
    </body>
</html>
FRM;
    }

    public static function processUploadForm()
    {
        if (! isset($_POST['upl'])){
            return false;
        }
        
//        if (!GoogleRecaptchya::processForm()){
//            echo "<body style='background-color:red'><h1 style='font-size:20em'>HELLO ROBOT!</h1></body>";
//            exit;
//        }

        //upload files
        $target_map = "dir" . time();
        mkdir($target_map);
        $target_dir = $target_map . "/";
        $total = count($_FILES['upload']['name']);
        for($i=0; $i<$total; $i++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            if ( strtoupper(pathinfo($_FILES['upload']['name'][$i])['extension']) !== "PDF") {
                echo "<body style='background-color:red'><h1 style='font-size:20em'>ONLY PDF FILES!</h1></body>";
                exit;
            }
            if ($tmpFilePath != ""){
                $newFilePath = $target_dir . $_FILES['upload']['name'][$i];
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                }
            }
        }
        //do the merge
        PdfMerger::mergeDir($target_map);
        return true;
    }
}

if (!PdfMerger::processUploadForm()){
    Logger::log("open");
    echo PdfMerger::getUploadForm();
} else {
    Logger::log("merge");
}
