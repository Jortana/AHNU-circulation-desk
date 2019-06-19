<?php
header('Content-type:text/html;charset=utf-8');
$appkey = "4144befbd9750192d324f1bdbc3a33b1";
$url = "http://apis.juhe.cn/goodbook/query";
$params = array(
      "key" => $appkey,//应用APPKEY(应用详细页查询)
      "catalog_id" => "1",//目录编号
      "pn" => "",//数据返回起始
      "rn" => "2",//数据返回条数，最大30
    //   "dtype" => "",//返回数据的格式,xml或json，默认json
);
$paramstring = http_build_query($params);
$content = juhecurl($url, $paramstring);
$result = json_decode($content, true);
if($result){
    if($result['error_code'] == '0'){
        write_data($result);
    }else{
        echo $result['error_code'].":".$result['reason'];
    }
}else{
    echo "请求失败";
}

/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
 
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

function write_data($result) {
    $dataArr = $result['data'];
    foreach ($dataArr as $key => $value) {
        $title = $dataArr['title'];
        $cata = $dataArr['catalog'];
        $img = str_replace(',', '.', $dataArr['img']);
    }
}