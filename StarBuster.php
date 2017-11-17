<?php
class Starbuster {
    private $idName;
    private $nrOfStars=5;
    public function __construct($idName, $nrOfStars=5){
        $this->idName = $idName;
        $this->nrOfStars = $nrOfStars;
    }
    
    public function html($checkedValue=0){
        $stars = "";
        for ($i=$this->nrOfStars;$i>0;$i--){
            $n = $i *2;
            $h = $n - 1;
            $imin1 = $i - 1;
            if ($imin1 == 0) $imin1 = "";
            $stars .= <<<STR
                <input type="radio" id="{$this->idName}star{$i}" name="{$this->idName}" value="{$n}" />
                <label class = "full" for="{$this->idName}star{$i}" title="{$n}"></label>
                <input type="radio" id="{$this->idName}star{$imin1}half" name="{$this->idName}" value="{$h}" />
                <label class = "half" for="{$this->idName}star{$imin1}half" title="{$h}"></label>

STR;
        }
        return <<<R
        <fieldset class="rating">
{$stars}
        </fieldset>
R;
    }
    public function process(){
        if (! isset($_POST[$this->idName])) { return 0;}
        return $_POST[$this->idName];
    }
    
    public static function style(){
        return <<<STL
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

fieldset, label { margin: 0; padding: 0; }
body{ margin: 20px; }
h1 { font-size: 1.5em; margin: 10px; }

/****** Style Star Rating Widget *****/

.rating { 
  border: none;
  float: left;
}

.rating > input { display: none; } 
.rating > label:before { 
  margin: 5px;
  font-size: 1.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\\f005";
}

.rating > .half:before { 
  content: "\\f089";
  position: absolute;
}

.rating > label { 
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

STL;
    }
}
?>
<style>
<?php 
$star1 = new Starbuster("aap");
$star2 = new Starbuster("noot",7);
echo Starbuster::style(); ?>
</style>
<?php echo $star1->process(); ?><br/>
<?php echo $star2->process(); ?><br/>
<form method='post'>
    <?php echo $star1->html(); ?><br/><br/><br/><br/>
    <?php echo $star2->html(); ?><br/><br/>
    <input type='submit'>
</form>