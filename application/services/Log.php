<?php
class Log {
    static private $log;

    static function init(Zend_Log $logger) {
        self::$log = $logger;
    }

    static function logger($object, $message, $param = null, $priority = null) {
        $text = '';
        if ($object != null)
            $text .= get_class($object) . ':';
        $text .= $message;
        if ($param != null)
            $text .= " - Param:[" . self::param_to_str($param) . "]";

        if ($priority == null) $priority = Zend_Log::DEBUG;
        self::$log->log($text, $priority);
      //  var_dump($text);
    }


    static function debug($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::DEBUG);
    }

    static function info($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::INFO);
    }
	
    static function warn($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::WARN);
    }

    static function err($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::ERR);
    }

    static function crit($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::CRIT);
    }

    static function alert($object, $message, $param = null) {
        self::logger($object, $message, $param, Zend_Log::ALERT);
    }

    static function path($role,$requestUri,$route,$level){
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $refer = null;
            if(is_bot($agent)){
                $log="robot|$ip:$requestUri [$route] ".$agent;
            }else{
                if(!empty($_SERVER[HTTP_REFERER])){
                    $refer = urldecode($_SERVER[HTTP_REFERER]);
                    $refer = '<:{'.str_replace('http://rif.name','',$refer).'} ';
                }
                $log="$role|$ip:$requestUri [$route] ".$refer.$agent;
            }
            self::$log->log($log, $level);
    }


    static public function param_to_str($param) {
        $text=null;
        if(is_string($param))
            $text = $param;
        else if($param==null)
            $text = 'null';
        else if (is_array($param) ||
                (is_object($param) && get_class($param) == 'stdClass') ||
                (is_object($param) && get_class($param) == 'ArrayObject'))
        {
            $text = "array(";
            foreach ($param as $key => $value)
                $text .= "$key=>" . self::param_to_str($value) . ",";
            $text .= ")";        
        } else
            $text = $param;

        return $text;
    }


}