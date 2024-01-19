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
        $order_domain = trim($_POST['domain']);
        $order_from = trim($_POST['order_from']);
        $order_type = trim($_POST['order_type']);

        if (!empty($order_domain)) {
            $sql .= " AND order_domain='{$order_domain}'";
        }
        if (!empty($order_from)) {
            $sql .= " AND order_from='{$order_from}'";
        }
        if (!empty($order_type)) {
            $sql .= " AND `order_type`='{$order_type}'";
        }
        $offset = intval($_POST['offset']);
        $limit = intval($_POST['limit']);
        $total = $DB->count('order', $sql);
        $list = $DB->findAll('order', '*', $sql, 'create_time desc', "$offset,$limit");

        exit(json_encode(['total' => $total, 'rows' => $list]));
        break;
        // $sql = " A.status=0";
        // $domain = trim($_POST['domain']);
        // $did = intval($_POST['did']);
        // $appid = trim($_POST['appid']);

        // // TODO 域名删除后就找不到了，此处不能用关联存储，直接存储到表
        // if (!empty($domain)) {
        //     $sql .= " AND B.domain='{$domain}'";
        // } elseif (!empty($did)) {
        //     $sql .= " AND A.did='{$did}'";
        // }
        // if (!empty($appid)) {
        //     $sql .= " AND `appid`='{$appid}'";
        // }
        // $offset = intval($_POST['offset']);
        // $limit = intval($_POST['limit']);
        // $total = $DB->getColumn("SELECT count(A.id) FROM pre_order A JOIN pre_domain B ON A.did=B.id WHERE{$sql}");
        // $list = $DB->getAll("SELECT A.*,B.domain FROM pre_order A JOIN pre_domain B ON A.did=B.id WHERE{$sql} order by A.id desc limit $offset,$limit");

        // exit(json_encode(['total' => $total, 'rows' => $list]));
        // break;
    case 'remark':
        $order_id = intval($_POST['id']);
        $order_remark = trim(daddslashes($_POST['remark']));
        if (empty($order_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('order', array('order_remark' => $order_remark, 'update_time' => date("Y-m-d H:i:s")), ['order_id' => $order_id])) {
            exit('{"code":-1,"msg":"修改备注失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改备注成功！"}');
        break;
    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}
