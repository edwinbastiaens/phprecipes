<?php
class Starbuster {
    private $idName;
    private $nrOfStars=5;

    /**
     * create starbuster
     * @param type $idName
     * @param type $nrOfStars
     */
    public function __construct($idName, $nrOfStars=5){
        $this->idName = $idName;
        $this->nrOfStars = $nrOfStars;
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
                <label class = "full" for="{$this->idName}star{$i}" title="{$n}"></label>
                <input disabled type="radio" {$checkedh} id="{$this->idName}star{$imin1}half" name="{$this->idName}" value="{$h}" />
                <label class = "half" for="{$this->idName}star{$imin1}half" title="{$h}"></label>

STR;
        }
        return <<<R
        <fieldset class="starry">
{$stars}
        </fieldset>
R;
//        return <<<R
//        <fieldset class="starry">
//                <input disabled type="radio" id="aaapstar5" name="aaap" value="10">
//                <label class="full" for="aaapstar5" title="10"></label>
//                <input disabled type="radio" id="aaapstar4half" name="aaap" value="9">
//                <label class="half" for="aaapstar4half" title="9"></label>
//                <input disabled type="radio" id="aaapstar4" name="aaap" value="8">
//                <label class="full" for="aaapstar4" title="8"></label>
//                <input disabled type="radio" id="aaapstar3half" name="aaap" value="7">
//                <label class="half" for="aaapstar3half" title="7"></label>
//                <input disabledtype="radio" id="aaapstar3" name="aaap" value="6">
//                <label class="full" for="aaapstar3" title="6"></label>
//                <input disabled type="radio" id="aaapstar2half" name="aaap" value="5">
//                <label class="half" for="aaapstar2half" title="5"></label>
//                <input checked type="radio" id="aaapstar2" name="aaap" value="4">
//                <label class="full" for="aaapstar2" title="4"></label>
//                <input disabled type="radio" id="aaapstar1half" name="aaap" value="3">
//                <label class="half" for="aaapstar1half" title="3"></label>
//                <input disabled type="radio" id="aaapstar1" name="aaap" value="2">
//                <label class="full" for="aaapstar1" title="2"></label>
//                <input disabled type="radio" id="aaapstarhalf" name="aaap" value="1">
//                <label class="half" for="aaapstarhalf" title="1"></label>
//        </fieldset>
//R;
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
        <fieldset class="rating">
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
    public static function style(){
        return <<<STL
@import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

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

.starry > input:checked ~ label { color: #FFD700;  }
STL;
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
