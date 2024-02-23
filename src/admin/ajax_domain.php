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
        // $type_arr = array('aliyun' => 'aliyun', 'juming' => '聚名网', 'tencent' => '腾讯', 'xinnet' => '新网');
        if (isset($_POST['domain_status']) && $_POST['domain_status'] != '') {
            $domain_status = $_POST['domain_status'];
            $sql .= " AND `domain_status`={$domain_status}";
        }
        if (isset($_POST['kw']) && !empty($_POST['kw'])) {
            $kw = trim(daddslashes($_POST['kw']));
            $sql .= " AND `domain_name` LIKE '%{$kw}%'";
        } else if (isset($_POST['domain_name']) && !empty($_POST['domain_name'])) {
            $domain_name = trim(daddslashes($_POST['domain_name']));
            $sql .= " AND `domain_name`='{$domain_name}'";
        }
        $offset = intval($_POST['offset']);
        $limit = intval($_POST['limit']);
        $total = $DB->count('domain', "{$sql}");
        $list = $DB->findAll('domain', '*', $sql, 'domain_id desc', "$offset,$limit");

        exit(json_encode(array('code' => 0, 'total' => $total, 'rows' => $list)));
        break;
    case 'set':
        $domain_id = intval($_POST['id']);
        $domain_status = intval($_POST['status']);
        if (empty($domain_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('domain', array('domain_status' => $domain_status, 'update_time' => date("Y-m-d H:i:s")), ['domain_id' => $domain_id])) {
            exit('{"code":-1,"msg":"修改域名失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改域名成功！"}');
        break;
    case 'remark':
        $domain_id = intval($_POST['id']);
        $domain_remark = trim(daddslashes($_POST['remark']));
        if (empty($domain_id)) exit('{"code":-1,"msg":"id不能为空"}');
        if (!$DB->update('domain', array('domain_remark' => $domain_remark, 'update_time' => date("Y-m-d H:i:s")), ['domain_id' => $domain_id])) {
            exit('{"code":-1,"msg":"修改备注失败[' . $DB->error() . ']"}');
        }
        exit('{"code":0,"msg":"修改备注成功！"}');
        break;
    case 'add':
        $domain_name = trim(daddslashes($_POST['domain']));
        if (empty($domain_name)) exit('{"code":-1,"msg":"域名不能为空"}');
        if (!checkDomain($domain_name)) exit('{"code":-1,"msg":"域名格式不正确"}');

        $row = $DB->find('domain', 'domain_id', ['domain_name' => $domain_name]);
        if ($row) exit('{"code":-1,"msg":"该域名已存在，请勿重复添加"}');

        if (!$DB->insert('domain', array(
            'domain_name' => $domain_name,
            'domain_status' => 1,
            'update_time' => date("Y-m-d H:i:s")
        ))) exit('{"code":-1,"msg":"添加域名失败[' . $DB->error() . ']"}');
        exit('{"code":0,"msg":"添加域名成功！"}');
        break;
    case 'del':
        $domain_id = intval($_POST['id']);
        $row = $DB->getRow("select * from pre_domain where domain_id='$domain_id' limit 1");
        if (!$row) exit('{"code":-1,"msg":"当前域名不存在！"}');
        $sql = "DELETE FROM pre_domain WHERE domain_id='$domain_id'";
        if ($DB->exec($sql)) exit('{"code":0,"msg":"删除域名成功！"}');
        else exit('{"code":-1,"msg":"删除域名失败[' . $DB->error() . ']"}');
        break;
    case 'whois':
        if (!isset($_POST['domain'])) {
            exit('{"code":-1,"msg":"请输入查询域名！"}');
        }
        $domain = strip_tags($_POST['domain']);

        include_once(SYSTEM_ROOT . '/phpwhois/whois.main.php');
        include_once(SYSTEM_ROOT . '/phpwhois/whois.utils.php');

        $whois = new Whois();
        $allowproxy = false;
        $whois->deep_whois = false;
        $whois->non_icann = true;
        $result = $whois->Lookup($domain);
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
