<?php
define('IN_ADMIN', true);
include("../includes/common.php");
if ($admin_islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
// $act=isset($_GET['act'])?daddslashes($_GET['act']):null;
$act = isset($_REQUEST['act']) ? daddslashes($_REQUEST['act']) : 'list';
if (!checkRefererHost()) exit('{"code":403}');

@header('Content-Type: application/json; charset=UTF-8');

switch ($act) {
    case 'list':
        $sql = " 1=1";
        $order_server_id = isset($_POST['order_server_id']) && !empty($_POST['order_server_id']) ? trim(daddslashes($_POST['order_server_id'])) : null;
        $order_provider = isset($_POST['order_provider']) && !empty($_POST['order_provider']) ? trim(daddslashes($_POST['order_provider'])) : null;
        $order_type = isset($_POST['order_type']) && !empty($_POST['order_type']) ? trim(daddslashes($_POST['order_type'])) : null;

        if (!empty($order_server_id)) {
            $sql .= " AND `order_server_id`='{$order_server_id}'";
        }
        if (!empty($order_provider)) {
            $sql .= " AND `order_provider`='{$order_provider}'";
        }
        if (!empty($order_type)) {
            $sql .= " AND `order_type`='{$order_type}'";
        }
        $offset = intval($_POST['offset']);
        $limit = intval($_POST['limit']);
        $total = $DB->count('order_server', $sql);
        $list = $DB->findAll('order_server', '*', $sql, 'create_time desc', "$offset,$limit");

        exit(json_encode(['total' => $total, 'rows' => $list]));
        break;
    case 'remark':
        $order_id = intval($_POST['id']);
        $order_remark = trim(daddslashes($_POST['remark']));
        if (empty($order_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('order_server', array('order_remark' => $order_remark, 'update_time' => date("Y-m-d H:i:s")), ['order_id' => $order_id])) {
            exit('{"code":-1,"msg":"修改备注失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改备注成功！"}');
        break;
    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}
