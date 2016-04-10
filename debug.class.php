<?php

class Debug{
    private static $DEBUG_PRINT_ON  = false;
    private static $DEBUG_LEVEL = 1; //only print for level >= DEBUG_LEVEL.
    private static $passed = null;
    
    private static function getObjectContentsInternal($obj){ 
        if (! isset($obj)) return "undefined";
        if (is_null($obj)) return "null";
        
        //prevent selfcontainment loops
        if (in_array($obj, self::$passed))
            return;
        self::$passed[] = $obj;
        
        if (is_object($obj) || is_array($obj)){
            if (is_object($obj)) $p = "->"; else $p = "[] = ";
            $out = "<ul>";
            foreach($obj as $k => $v)
                $out .= "<li>" . $k . $p . self::getObjectContentsInternal($v) . "</li>";
            return $out . "</ul>";
        }
        if ($obj === false)
            return "false";
        if ($obj === true)
            return "true";        
        return $obj;
    }
    
    public static function getObjectContents($obj){
        self::$passed = array();
        return self::getObjectContentsInternal($obj);
    }
    
    public static function eObjectContents($obj,$debugLevel=1){
        if ($debugLevel < self::$DEBUG_LEVEL) return;
        echo self::getObjectContents($obj);
    }
    
    public static function e($str,$debugLevel=1,$newLine = true){
        if ($debugLevel < self::$DEBUG_LEVEL) return;
        if (self::$DEBUG_PRINT_ON)
            echo $str . ($newLine)? "<br/>" : "";
    }
    
    public static function g($str,$newLine = true){
        if ($debugLevel < self::$DEBUG_LEVEL) return "";
        if (self::$DEBUG_PRINT_ON)
            return $str . ($newLine)?  "<br/>" : "";
        return "";
    }
    
    public static function startDebugging($debugLevel=1){
        if (is_numeric($debugLevel))
            self::$DEBUG_LEVEL = $debugLevel;
        self::$DEBUG_PRINT_ON = true;
    }
    
    public static function stopDebugging(){
        self::$DEBUG_PRINT_ON = false;
    }
    
    public static function abc($parm=1){
        $parm +=4;
        return $parm;
    }
}

//end if file