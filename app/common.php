<?php
// 应用公共文件
/**
 * 返回json数据方法
 *
 * @Author 孙双洋 
 * @DateTime 2021-02-24
 * @param array $array
 * @return void
 */
function return_json($array = [])
{
    if (count($array)<1) 
    {
        exit('returnJson方法未接收正确数据');
    }
    //code代表含义
    /**
     * 1001成功
     * 2001缺少参数
     * 3001数据库无匹配数据
     * 3002数据库已有改数据
     * 4001数据库操作失败
     * 5001规则类失败
     */
    exit(json_encode($array));
}


/**
 * 创建token方法
 *
 * @Author 孙双洋 
 * @DateTime 2021-02-24
 * @return void
 */
function make_token()
{
    return md5(uniqid(md5(microtime(true)),true));
}


/**
 * 检查是否缺少参数
 *
 * @Author 孙双洋 
 * @DateTime 2021-02-24
 * @param [type] $request
 * @param [type] $array
 * @return void
 */
function check_must_param($request,$array)
{
    if ($request->param()) {
        foreach ($array as  $val) {
            if (!$request->has($val)) {
                return_json(array('code'=>'2001','msg'=>'缺少必要参数'.$val));
            }
        }
    } else {
        return_json(array('code'=>'2001','msg'=>'无参数'));
    }
    
}