<?php

function not_null_numeric($value)
{
    if (is_numeric($value) && $value >= 1)
        return $value;
}

function not_empty_string($value)
{
    if (!empty($value) && is_string($value) && mb_strlen(trim($value)) > 0)
        return $value;
}

function upper($string)
{
    return preg_replace('/^(\S)(.*)$/eu', "mb_strtoupper('\\1', 'UTF-8').mb_strtoupper('\\2', 'UTF-8')", $string);
}

function brief($text,$count){
    $isShort=substr_count($text,"\n") >= $count;
    if($isShort){
        preg_match('/(.*?[\n]){1,'.$count.'}/', $text, $arr );
        $text=$arr[0];
    }
    return $text;
}


function is_bot($agent)
{
    if (strstr($agent, 'Yandex')
        || strstr($agent, 'Googlebot')
        || strstr($agent, 'Yahoo')
        || strstr($agent, 'Aport')
        || strstr($agent, 'StackRambler')
        || strstr($agent, 'Mail.Ru')
        || strstr($agent, 'msnbot')
        || strstr($agent, 'DotBot')
        || strstr($agent, 'bingbot') 
        || strstr($agent, 'ia_archiver')
        || strstr($agent, 'MJ12bot')
        || strstr($agent, 'goo.ne.jp')
        || strstr($agent, 'Purebot')
        || strstr($agent, 'Gigabot')
        || strstr($agent, 'Huaweisymantecspider')
       )
        return true;
    else
        return false;
}