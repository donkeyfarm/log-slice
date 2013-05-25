//0.函数名、变量名、返回值不可更改
function agent($line)
{
    //1. 函数体前面部分填写所有需要在统计中使用的变量（不管是否能够匹配出变量值）
    $res['_OriginalLogLine'] = $line;
    $line = str_replace("\\x","%",$line); //以\x开头的需要换乘%才能解析出汉字
    $line = urldecode($line);  //将line中的汉字解码出来   
    $res['_UrlPre'] = null;
    $res['_UrlFields'] = array();

    $res['_ClientIp'] = null;
    $res['_ClientIpInLong'] = null;
    $res['_AccessTimeHour'] = null;
    $res['_AccessTimeMinute'] = null;
    $res['_AccessTimeSecond'] = null;
    $res['_TimeZone'] = null;
    $res['_Method'] = null;
    $res['_Url'] = null;
    $res['_HttpVersion'] = null;
    $res['_StatusCode'] = null;
    $res['_Referer'] = null;
    $res['_Cookie'] = null;
    $res['_BaiduId'] = null;
    $res['_UserAgent'] = null;

    //2.书写匹配规则，并将匹配出来的结果赋给变量
    if(preg_match("/(\d+\.\d+\.\d+\.\d+) .*?\[.*?:(\d+):(\d+):(\d+) (.*?)\] \"(.*?) (.*?) .TTP\/(.*?)\" (.*?) .*?\"(.*?)\"(.*?) \"(.*)\"$/", $line, $out)){
        $res['_ClientIp'] = $out[1];
        $res['_ClientIpInLong'] = ip2long($out[1]);
        $res['_AccessTimeHour'] = $out[2];
        $res['_AccessTimeMinute'] = $out[3];
        $res['_AccessTimeSecond'] = $out[4];
        $res['_TimeZone'] = $out[5];
        $res['_Method'] = $out[6];
        $res['_Url'] = $out[7];
        $res['_HttpVersion'] = $out[8];
        $res['_StatusCode'] = $out[9];
        $res['_Referer'] = $out[10];
        $res['_Cookie'] = $out[11];
        $res['_UserAgent'] = $out[12];

        $tmp = parse_url("http://www.someurl.com".$res['_Url']);
        $res['_UrlPre'] = $tmp['path'];
        parse_str($tmp['query'],$tmp);
        $res['_UrlFields'] = $tmp;

        if(preg_match("/BAIDUID=(.*?)[: ]/",$res['_Cookie'],$out)){
            $res['_BaiduId'] = $out[1];
        }

    }

    return $res;
}
