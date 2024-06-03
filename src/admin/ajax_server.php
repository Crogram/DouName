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
    case 'list':
        $sql = " 1=1";
        $server_kw = isset($_POST['kw']) && !empty($_POST['kw']) ? trim(daddslashes($_POST['kw'])) : null;
        $server_name = isset($_POST['server_name']) && !empty($_POST['server_name']) ? trim(daddslashes($_POST['server_name'])) : null;
        $server_status = isset($_POST['server_status']) ? intval($_POST['server_status']) : '';
        $server_id = isset($_POST['server_id']) && !empty($_POST['server_id']) ? trim(daddslashes($_POST['server_id'])) : null;
        $server_ip = isset($_POST['server_ip']) && !empty($_POST['server_ip']) ? trim(daddslashes($_POST['server_ip'])) : null;
        $server_provider = isset($_POST['server_provider']) && !empty($_POST['server_provider']) ? trim(daddslashes($_POST['server_provider'])) : null;
        if (!empty($server_kw)) {
            $sql .= " AND `server_name` LIKE '%{$server_kw}%'";
        } else if (!empty($server_name)) {
            $sql .= " AND `server_name`='{$server_name}'";
        }
        if (!empty($server_id)) {
            $sql .= " AND `server_id`='{$server_id}'";
        }
        if ($server_status != '') {
            $sql .= " AND `server_status`={$server_status}";
        }
        if (!empty($server_provider)) {
            $sql .= " AND `server_provider`='{$server_provider}'";
        }
        if (!empty($server_ip)) {
            $sql .= " AND `server_ip`='{$server_ip}'";
        }
        $offset = intval($_POST['offset']);
        $limit = intval($_POST['limit']);
        $total = $DB->count('server', "{$sql}");
        $list = $DB->findAll('server', '*', $sql, 'server_id desc', "$offset,$limit");

        exit(json_encode(array('code' => 0, 'total' => $total, 'rows' => $list)));
        break;
    case 'set':
        $server_id = intval($_POST['id']);
        $server_status = intval($_POST['status']);
        if (empty($server_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('server', array('server_status' => $server_status, 'update_time' => date("Y-m-d H:i:s")), ['server_id' => $server_id])) {
            exit('{"code":-1,"msg":"修改域名失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改域名成功！"}');
        break;
    case 'remark':
        $server_id = intval($_POST['id']);
        $server_remark = trim(daddslashes($_POST['remark']));
        if (empty($server_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('server', array('server_remark' => $server_remark, 'update_time' => date("Y-m-d H:i:s")), ['server_id' => $server_id])) {
            exit('{"code":-1,"msg":"修改备注失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改备注成功！"}');
        break;
    case 'add':
        $server_name = trim(daddslashes($_POST['server']));
        if (empty($server_name)) exit('{"code":-1,"msg":"域名不能为空"}');
        if (!checkDomain($server_name)) exit('{"code":-1,"msg":"域名格式不正确"}');

        $row = $DB->find('server', 'server_id', ['server_name' => $server_name]);
        if ($row) exit('{"code":-1,"msg":"该域名已存在，请勿重复添加"}');

        if (!$DB->insert('server', array(
            'server_name' => $server_name,
            'server_status' => 1,
            'update_time' => date("Y-m-d H:i:s")
        ))) exit('{"code":-1,"msg":"添加域名失败[' . $DB->error() . ']"}');
        exit('{"code":0,"msg":"添加域名成功！"}');
        break;
    case 'del':
        $server_id = intval($_POST['id']);
        $row = $DB->getRow("select * from pre_server where server_id='$server_id' limit 1");
        if (!$row) exit('{"code":-1,"msg":"当前域名不存在！"}');
        $sql = "DELETE FROM pre_server WHERE server_id='$server_id'";
        if ($DB->exec($sql)) exit('{"code":0,"msg":"删除域名成功！"}');
        else exit('{"code":-1,"msg":"删除域名失败[' . $DB->error() . ']"}');
        break;
    case 'whois':
        if (!isset($_POST['server'])) {
            exit('{"code":-1,"msg":"请输入查询域名！"}');
        }
        $server = strip_tags($_POST['server']);

        include_once(SYSTEM_ROOT . '/phpwhois/whois.main.php');
        include_once(SYSTEM_ROOT . '/phpwhois/whois.utils.php');

        $whois = new Whois();
        $allowproxy = false;
        $whois->deep_whois = false;
        $whois->non_icann = true;
        $result = $whois->Lookup($server);
        $winfo = '';

        if (!empty($result['rawdata'])) {
            $winfo .= implode($result['rawdata'], "\n");
        } else {
            $winfo = implode($whois->Query['errstr'], "\n<br></br>");
        }
        exit(json_encode(array('code' => 0, 'data' => $winfo)));
        break;
    default:
        exit('{"code":-4,"msg":"未定义操作"}');
        break;
}
