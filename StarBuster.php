<?php

//ref: https://codepen.io/jamesbarnett/pen/vlpkh
class Starbuster {
    private $idName;
    private $nrOfStars=5;
    private $cssClasses = null;

    /**
     * create starbuster
     * @param type $idName
     * @param type $nrOfStars
     */
    public function __construct($idName, $nrOfStars=5){
        $this->idName = $idName;
        $this->nrOfStars = $nrOfStars;
    }

    public function cssClasses($cssClasses){
        $this->cssClasses = $cssClasses;
    }

    /**
     * generate form input field
     * use checkedvalue if you want to pass a predetermined value
     * @param type $checkedValue
     * @return type
     */
    public function html($checkedValue=0){
        $stars = "";
        for ($i=$this->nrOfStars;$i>0;$i--){
            $n = $i *2;
            $h = $n - 1;
            $imin1 = $i - 1;
            if ($imin1 == 0) $imin1 = "";
            $checkedn = ($n == $checkedValue)? " checked " : "";
            $checkedh = ($h == $checkedValue)? " checked " : "";
            $stars .= <<<STR
                <input type="radio" 
                    {$checkedn}
                    id="{$this->idName}star{$i}" 
                    name="{$this->idName}" 
                    value="{$n}" />
                <label class = "full" for="{$this->idName}star{$i}" title="{$n}"></label>
                <input type="radio" {$checkedh} id="{$this->idName}star{$imin1}half" name="{$this->idName}" value="{$h}" />
                <label class = "half" for="{$this->idName}star{$imin1}half" title="{$h}"></label>

STR;
        }
        return <<<R
        <fieldset class="rating {$this->cssClasses}">
{$stars}
        </fieldset>
R;
    }

    /**
     * get the user input after form-submit.
     * returns null in case no form-submit.
     * @param type $method
     * @return type
     */
    public function userInput($method='POST'){
        if ($method == 'POST'){
            if (! isset($_POST[$this->idName])) { return null;}
            return $_POST[$this->idName];
        } else if ($method == 'GET') {
            if (! isset($_GET[$this->idName])) { return null;}
            return $_GET[$this->idName];
        }
    }

    /**
     * Use this static function to provide the necessary styles to make it work.
     * Important:
     * Must do this in order to work
     * Use only once
     * 
     * @return type
     */
    public static function style($includeStyleTags=false,$echo = false){
        $openStyle = ($includeStyleTags)? "<style>":"";
        $closeStyle = ($includeStyleTags)? "</style>":"";
        $txt = <<<STL
{$openStyle}@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

fieldset, label { margin: 0; padding: 0; }
body{ margin: 20px; }
h1 { font-size: 1.5em; margin: 10px; }

/****** Style Star Rating Widget *****/

.starry, .rating { 
  border: none;
  float: left;
}

.starry > input, .rating > input { display: none; } 
.starry > label:before, .rating > label:before { 
  margin: 5px;
  font-size: 1.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\\f005";
}

.starry > .half:before,.rating > .half:before { 
  content: "\\f089";
  position: absolute;
}

.starry > label , .rating > label { 
  color: #ddd; 
 float: right; 
}

/***** CSS Magic to Highlight Stars on Hover *****/


.rating > input:checked ~ label, /* show gold star when clicked */
.rating:not(:checked) > label:hover, /* hover current star */
.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

.rating > input:checked + label:hover, /* hover current star when changing rating */
.rating > input:checked ~ label:hover,
.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
.rating > input:checked ~ label:hover ~ label { color: #FFED85;  }

.starry > input:checked ~ label { color: #FFD700;  }{$closeStyle}
STL;
        if ($echo){
            echo $txt;
        }
        return $txt;
    }

    public function read_only_html($checkedValue= 0){
        $stars = "";
        for ($i=$this->nrOfStars;$i>0;$i--){
            $n = $i *2;
            $h = $n - 1;
            $imin1 = $i - 1;
            if ($imin1 == 0) $imin1 = "";
            $checkedn = ($n == $checkedValue)? " checked " : "";
            $checkedh = ($h == $checkedValue)? " checked " : "";
            $stars .= <<<STR
                <input disabled type="radio" 
                    {$checkedn}
                    id="{$this->idName}star{$i}" 
                    name="{$this->idName}" 
                    value="{$n}" />
                <label class = "full" for="{$this->idName}star{$i}"></label>
                <input disabled type="radio" {$checkedh} id="{$this->idName}star{$imin1}half" name="{$this->idName}" value="{$h}" />
                <label class = "half" for="{$this->idName}star{$imin1}half"></label>

STR;
        }
        return <<<R
        <fieldset class="starry {$this->cssClasses}">
{$stars}
        </fieldset>
R;
    }
}
?>
<style>
<?php echo Starbuster::style(); ?>
</style>
<?php
$star1 = new Starbuster("aap");
$star2 = new Starbuster("noot",7);
?>
<form method='post'>
    <?php echo $star1->html($star1->userInput()); ?><br/><br/><br/><br/>
    <?php echo $star2->html($star2->userInput()); ?><br/><br/>
    <input type='submit'>
</form>

<br/>
<?php echo $star1->read_only_html(4);
