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
        $order_provider = trim($_POST['order_provider']);
        $order_type = trim($_POST['order_type']);

        if (!empty($order_domain)) {
            $sql .= " AND order_domain='{$order_domain}'";
        }
        if (!empty($order_provider)) {
            $sql .= " AND order_provider='{$order_provider}'";
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
    case 'add':
        $order_domain   = _post('order_domain');
        $order_type     = _post('order_type');
        $order_provider = _post('order_provider');
        $order_costs    = _post('order_costs');
        $create_time    = _post('create_time');
        $order_remark   = _post('order_remark');

        if (empty($order_domain))   exit('{"code":-1,"msg":"域名不能为空"}');
        if (empty($order_type))     exit('{"code":-1,"msg":"订单类型不能为空"}');
        if (empty($order_provider)) exit('{"code":-1,"msg":"订单服务商不能为空"}');
        if (empty($create_time))    exit('{"code":-1,"msg":"下单时间不能为空"}');
        if (empty($order_costs))    exit('{"code":-1,"msg":"订单金额不能为空"}');
        if (!$DB->insert('order', [
            'order_domain'   => $order_domain,
            'order_type'     => $order_type,
            'order_provider' => $order_provider,
            'create_time'    => $create_time,
            'order_costs'    => $order_costs,
            'order_remark'   => $order_remark,
        ])) exit('{"code":-1,"msg":"添加订单失败[' . $DB->error() . ']"}');
        exit('{"code":0,"msg":"添加订单成功！"}');
        break;
    case 'update':
        $id             = intval(_post('id'));
        $order_domain   = _post('order_domain');
        $order_type     = _post('order_type');
        $order_provider = _post('order_provider');
        $create_time    = _post('create_time');
        $order_costs    = _post('order_costs');
        $order_remark   = _post('order_remark');

        if (empty($order_domain))   exit('{"code":-1,"msg":"域名不能为空"}');
        if (empty($order_type))     exit('{"code":-1,"msg":"订单类型不能为空"}');
        if (empty($order_provider)) exit('{"code":-1,"msg":"订单服务商不能为空"}');
        if (empty($create_time))    exit('{"code":-1,"msg":"下单时间不能为空"}');
        if (empty($order_costs))    exit('{"code":-1,"msg":"订单金额不能为空"}');

        if (!$DB->update(
            'order',
            [
                'order_domain'   => $order_domain,
                'order_type'     => $order_type,
                'order_provider' => $order_provider,
                'create_time'    => $create_time,
                'order_costs'    => $order_costs,
                'order_remark'   => $order_remark,
                'update_time'    => date("Y-m-d H:i:s")
            ],
            ['order_id' => $id]
        )) exit('{"code":-1,"msg":"修改订单失败：[' . $DB->error() . ']"}');
        exit('{"code":0,"msg":"修改订单成功"}');
        break;
    case 'info':
        $id = intval(_get('id'));
        $row = $DB->find('order', '*', ['order_id' => $id]);
        if (!$row) exit('{"code":-1,"msg":"订单不存在"}');
        exit(json_encode(['code' => 0, 'data' => $row]));
        break;
    case 'delete':
        $id = intval(_post('id'));
        $row = $DB->find('order', 'order_id', ['order_id' => $id]);
        if (!$row) exit('{"code":-1,"msg":"订单不存在"}');
        if ($DB->delete('order', ['order_id' => $id])) {
            exit('{"code":0,"msg":"删除订单成功"}');
        } else exit('{"code":-1,"msg":"删除订单失败[' . $DB->error() . ']"}');
        break;
    case 'remark':
        $id = intval(_post('id'));
        $order_remark = trim(daddslashes($_POST['remark']));
        if (empty($id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('order', array('order_remark' => $order_remark, 'update_time' => date("Y-m-d H:i:s")), ['order_id' => $id])) {
            exit('{"code":-1,"msg":"修改备注失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改备注成功！"}');
        break;
    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}
