<?php
define('IN_ADMIN', true);
include("../includes/common.php");
if ($admin_islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
// $act=isset($_GET['act'])?daddslashes($_GET['act']):null;
$act = isset($_REQUEST['act']) ? daddslashes($_REQUEST['act']) : null;
if (!checkRefererHost()) exit('{"code":403}');

@header('Content-Type: application/json; charset=UTF-8');

switch ($act) {
    case 'info':
        $id = intval($_GET['id']);
        $row = $DB->find('token', '*', ['id' => $id]);
        if (!$row) exit('{"code":-1,"msg":"记录不存在"}');
        exit(json_encode(['code' => 0, 'data' => $row]));
        break;
    case 'list':
        $sql = " 1=1";
        if (isset($_POST['kw']) && !empty($_POST['kw'])) {
            $kw = trim(daddslashes($_POST['kw']));
            $sql .= " AND `appid`='{$kw}' OR `name` like '%{$kw}%'";
        }
        $offset = intval($_POST['offset']);
        $limit = intval($_POST['limit']);
        $total = $DB->getColumn("SELECT count(*) from pre_token WHERE{$sql}");
        $list = $DB->getAll("SELECT * FROM pre_token WHERE{$sql} order by id desc limit $offset,$limit");

        exit(json_encode(['total' => $total, 'rows' => $list]));
        break;
    case 'checkapi':
        $id = intval($_POST['id']);
        $row = $DB->find('token', 'id', ['id' => $id]);
        if (!$row) exit('{"code":-1,"msg":"记录不存在"}');
        try {
            refresh_wx_access_token($row['id'], true);
            exit(json_encode(['code' => 0, 'msg' => '接口连接测试成功！']));
        } catch (\Exception $e) {
            exit(json_encode(['code' => -1, 'msg' => $e->getMessage()]));
        }
        break;
    case 'save':
        $name = trim(daddslashes($_POST['name']));
        $type = intval($_POST['type']);
        $id = intval(trim($_POST['id']));
        $appid = trim(daddslashes($_POST['appid']));
        $appsecret = trim(daddslashes($_POST['appsecret']));
        $action = trim(daddslashes($_POST['action']));
        if (!$name || !$appid || !$appsecret) exit('{"code":-1,"msg":"必填项不能为空"}');
        if ($_POST['action'] == 'add') {
            if ($DB->find('token', 'name', ['name' => $name])) exit('{"code":-1,"msg":"名称重复，请勿重复添加"}');
            if ($DB->find('token', 'name', ['appid' => $appid])) exit('{"code":-1,"msg":"AppID重复，请勿重复添加"}');

            if (!$DB->insert('token', [
                'type' => $type,
                'name' => $name,
                'status' => 1,
                'appid' => $appid,
                'appsecret' => $appsecret,
                'create_time' => date("Y-m-d H:i:s")
            ])) exit('{"code":-1,"msg":"添加失败：<br />[' . $DB->error() . ']"}');
            exit('{"code":0,"msg":"添加成功！"}');
        } else {
            if (!$id) exit('{"code":-1,"msg":"ID不能为空"}');
            if ($DB->find('token', 'name', "name='{$name}' AND id<>'$id' ", NULL, 1)) exit('{"code":-1,"msg":"名称重复，请勿重复添加"}');
            if ($DB->find('token', 'name', "appid='{$appid}' AND id<>'$id' ", NULL, 1)) exit('{"code":-1,"msg":"AppID重复，请勿重复添加"}');

            if (!$DB->update(
                'token',
                [
                    'type' => $type,
                    'name' => $name,
                    'appid' => $appid,
                    'appsecret' => $appsecret,
                ],
                ['id' => $id]
            )) exit('{"code":-1,"msg":"修改失败：<br />[' . $DB->error() . ']"}');
            exit('{"code":0,"msg":"修改成功！"}');
        }
        break;
    case 'del':
        $id = intval($_POST['id']);
        if (!$DB->find('token', 'name', ['id' => $id])) exit('{"code":-1,"msg":"不存在！"}');
        if ($DB->delete('token', ['id' => $id])) {
            exit('{"code":0,"msg":"删除成功！"}');
        }
        exit('{"code":-1,"msg":"删除失败：<br />[' . $DB->error() . ']"}');
        break;
    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}
