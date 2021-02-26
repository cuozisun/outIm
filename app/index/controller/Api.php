<?php
namespace app\index\controller;

use think\Request;
use app\BaseController;
use GatewayClient\Gateway;



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
        $check_param = array('data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToAll($params['data']);
    }

    public function sendToClient(Request $request)
    {
        $check_param = array('client_id','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToClient($params['client_id'], $params['data']);
    }

    public function closeClient(Request $request)
    {
        $check_param = array('client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::closeClient($params['client_id']);
    }


    public function isOnline(Request $request)
    {
        $check_param = array('client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::isOnline($params['client_id']);
    }



    public function bindUid(Request $request)
    {
        $check_param = array('client_id','token');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::bindUid($params['client_id'], $params['token']);        
    }
    
    public function isUidOnline(Request $request)
    {
        $check_param = array('client_id');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::isUidOnline($params['client_id']);
    }

    public function getClientIdByUid(Request $request)
    {
        $check_param = array('token');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getClientIdByUid($params['token']);
    }

    public function unbindUid(Request $request)
    {
        $check_param = array('client_id','token');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::unbindUid($params['client_id'], $params['token']);        
    }

    public function sendToUid(Request $request)
    {
        $check_param = array('token','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToUid($params['token'], $params['data']);
    }

    public function joinGroup(Request $request)
    {
        $check_param = array('client_id','group');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::joinGroup($params['client_id'], $params['group']);
    }

    public function sendToGroup(Request $request)
    {
        $check_param = array('group','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::sendToGroup($params['group'], $params['data']);
    }

    public function leaveGroup(Request $request)
    {
        $check_param = array('client_id','group');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::leaveGroup($params['client_id'], $params['group']);
    }

    public function getClientCountByGroup(Request $request)
    {
        $check_param = array('group','data');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getClientCountByGroup($params['group']);
    }


    public function getClientSessionsByGroup(Request $request)
    {
        $check_param = array('group','data');
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
        $check_param = array('client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::setSession($params['client_id'], $params['session']);
    }


    public function updateSession(Request $request)
    {
        $check_param = array('client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::updateSession($params['client_id'], $params['session']);
    }


    public function getSession(Request $request)
    {
        $check_param = array('client_id','session');
        check_must_param($request, $check_param);
        $params = $request->param();

        Gateway::getSession($params['client_id']);
    }





    
}
