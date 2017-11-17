<?php 
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
?><!DOCTYPE html>
<html>
    <head>
        <title>My IP</title>
        <META NAME="DESCRIPTION" CONTENT="Find your Ip address." />
        <META NAME="KEYWORDS" CONTENT="My IP address,IP address, what is my IP address" />
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
                    <?=$google?>
                    <h1>What is my IP address?</h1>
                    <p>
                        See here below your current IP address. Notice that Ip addresses of most devices change over time.
                        It could very well be that tomorrow, or in a couple of hours, your device will be assigned another IP address.
                    </p>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>My current IP address : </h2>
                        </div>
                        <div class="panel-body">
                            <h2><?=$_SERVER['REMOTE_ADDR'] ?></h2>
                        </div>
                    </div>
                    <br/>
                    <?=$google?><br/><br/>
                    <a class="twitter-share-button"
                        href="https://twitter.com/intent/tweet?text=Merge a number of pdf files into one pdf file with Tolima's online pdf merger - ">
                      Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    <a href='http://www.tolima.be' >Back</a>
                </div>
            </div>
        </div>
    </body>
</html>