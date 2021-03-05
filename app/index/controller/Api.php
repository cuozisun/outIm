<?php
namespace app\index\controller;

use think\Request;
use app\BaseController;
use GatewayClient\Gateway;
use app\common\logic\UserLogic;
use app\common\logic\CompanyLogic;



class Api extends BaseController
{
    public function __construct()
    {
        Gateway::$registerAddress = '127.0.0.1:1238';
    }

    public function index()
    {
        echo 'api';
    }

    public function sendToAll(Request $request)
    {
        $check_param = array('uid','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToAll($params['data']);
    }

    public function sendToClient(Request $request)
    {
        $check_param = array('uid','client_id','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToClient($params['client_id'], $params['data']);
    }

    public function closeClient(Request $request)
    {
        $check_param = array('uid','client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::closeClient($params['client_id']);
    }


    public function isOnline(Request $request)
    {
        $check_param = array('uid','client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::isOnline($params['client_id']);
    }



    public function bindUid(Request $request)
    {
        $check_param = array('uid','client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::bindUid($params['client_id'], $params['uid']);        
    }
    
    public function isUidOnline(Request $request)
    {
        $check_param = array('uid');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::isUidOnline($params['uid']);
    }

    public function getClientIdByUid(Request $request)
    {
        $check_param = array('uid','token');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getClientIdByUid($params['token']);
    }

    public function unbindUid(Request $request)
    {
        $check_param = array('uid','client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::unbindUid($params['client_id'], $params['uid']);        
    }

    public function sendToUid(Request $request)
    {
        $check_param = array('uid','data','otheruid');
        check_must_param($request, $check_param);
        $params = $request->param();
        $data = [];

        $data['from_id'] = $params['uid'];
        $data['data'] = $params['data'];
        $data = json_encode(array('code'=>6006,'msg'=>'接收消息',$data));
        Gateway::sendToUid($params['otheruid'], $data);
    }

    public function joinGroup(Request $request)
    {
        $check_param = array('uid','client_id','group');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::joinGroup($params['client_id'], $params['group']);
    }

    public function sendToGroup(Request $request)
    {
        $check_param = array('uid','group','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToGroup($params['group'], $params['data']);
    }

    public function leaveGroup(Request $request)
    {
        $check_param = array('uid','client_id','group');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::leaveGroup($params['client_id'], $params['group']);
    }

    public function getClientCountByGroup(Request $request)
    {
        $check_param = array('uid','group','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getClientCountByGroup($params['group']);
    }


    public function getClientSessionsByGroup(Request $request)
    {
        $check_param = array('uid','group','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getClientSessionsByGroup($params['group']);
    }

    public function getAllClientCount()
    {
        Gateway::getAllClientCount();
    }

    public function getAllClientSessions()
    {
        Gateway::getAllClientSessions();
    }


    public function setSession(Request $request)
    {
        $check_param = array('uid','client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::setSession($params['client_id'], $params['session']);
    }


    public function updateSession(Request $request)
    {
        $check_param = array('uid','client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::updateSession($params['client_id'], $params['session']);
    }


    public function getSession(Request $request)
    {
        $check_param = array('uid','client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getSession($params['client_id']);
    }

    /**
     * 请求添加好友,未读保留3天
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-02
     * @param Request $request
     * uid:自己
     * otheruid:对方id
     * @return void
     */
    public function makeFriend(Request $request)
    {
        //实时发送,如果未读离线则3天内存储到mysql,超过三天自动销毁
        $check_param = array('uid','otheruid');
        check_must_param($request, $check_param);
        //附带本人信息
        $uinfo = [];
        $uinfo['remarks'] = $request->param('remarks','');
        $data = json_encode(array('code'=>'6001','data'=>$uinfo,'msg'=>'添加好友请求'));
        $sendMsg['client_id'] = $check_param['otheruid'];
        $sendMsg['data'] = $data;
        Gateway::sendToUid($sendMsg['client_id'], $sendMsg['data']);
    }


    /**
     * 通过账号或者手机号搜索好友
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-02
     * @param Request $request
     * searchinfo:搜索文本
     * @return void
     */
    public function searchAccidOrTel(Request $request)
    {
        $check_param = array('uid','searchinfo');
        check_must_param($request, $check_param);
        //查询账号或手机号对应的人
        $User = new UserLogic();
        $uinfo = $User->getUserInfo($check_param['searchinfo']);
        $data = json_encode(array('code'=>'6004','data'=>$uinfo,'msg'=>'查找成功'));

        Gateway::sendToUid($check_param['uid'], $data);
    }


    /**
     * 处理交友请求
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-03
     * @param Request $request
     * @return void
     */
    public function delMakeFriend(Request $request)
    {
        $check_param = array('uid','otheruid','del_type');
        check_must_param($request, $check_param);
        $params = $request->param();
        //处理还有请求
        $User = new UserLogic();
        switch ($request['del_type']) {
            case '1':
                #   同意
                $uinfo = $User->agreeRequest($params);
                Gateway::sendToUid($params['uid'], json_encode(array('code'=>'6002','msg'=>'同意添加好友成功','data'=>$uinfo['otherinfo'])));
                Gateway::sendToUid($params['otheruid'], json_encode(array('code'=>'6003','msg'=>'请求添加好友成功','data'=>$uinfo['uinfo'])));
                break;
            default:
                # 拒绝
                $User->refuseRequest($params);
                Gateway::sendToUid($params['uid'], json_encode(array('code'=>'1001','msg'=>'拒绝成功')));
                break;
        }
    }


    /**
     * 纯粹im软件使用登录功能
     *
     * @Author 孙双洋 
     * @DateTime 2021-02-26
     * @return void
     * 
     * 必须参数
     * accid
     * password
     * appid
     * secret
     */
    public function userLogin(Request $request)
    {
        $check_param = array('accid', 'appid', 'password');
        check_must_param($request, $check_param);
        $params = $request->param();

        //匹配与appid相对的公司
        $CompanyLogic = new CompanyLogic();
        $companyInfo = $CompanyLogic->getCompanyInfo($params);

        //匹配账号
        $UserLogic = new UserLogic();
        $result = $UserLogic->userLogin($params,$companyInfo);
        
        if (!$result) {
            return_json(array('code'=>'3001','登录失败'));
        } 
        
        return_json(array('code'=>'1001','登录成功','data'=>$result));
    }


    /**
     * 获取需要到客户端缓存的内容,当用户登录后且本地无缓存时触发
     *
     * @Author 孙双洋 
     * @DateTime 2021-03-03
     * @param Request $request
     * @return void
     */
    public function getCacheInfo(Request $request)
    {
        $check_param = array('uid');
        check_must_param($request, $check_param);
        //查询存储信息,好友关系,聊天记录,群记录,消息请求存储到redis队列中分开发送
    }



    
}
