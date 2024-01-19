<?php
define('IN_ADMIN', true);
include("../includes/common.php");
if ($admin_islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$act = isset($_REQUEST['act']) ? daddslashes($_REQUEST['act']) : null;
if (!checkRefererHost()) exit('{"code":403}');

@header('Content-Type: application/json; charset=UTF-8');

switch ($act) {
    case 'check':
        if (!isset($_POST['domain'])) {
            exit('{"code":-1,"msg":"请输入查询域名！"}');
        }
        $domain = strip_tags($_POST['domain']);

        include_once(SYSTEM_ROOT . '/phpwhois/whois.main.php');
        include_once(SYSTEM_ROOT . '/phpwhois/whois.utils.php');

        $whois = new Whois();

        // Set to true if you want to allow proxy requests
        $allowproxy = false;

        // get faster but less acurate results
        $whois->deep_whois = false;

        // To use special whois servers (see README)
        //$whois->UseServer('uk','whois.nic.uk:1043?{hname} {ip} {query}');
        //$whois->UseServer('au','whois-check.ausregistry.net.au');

        // Comment the following line to disable support for non ICANN tld's
        $whois->non_icann = true;

        $result = $whois->Lookup($domain);
        $winfo = '';

        if (!empty($result['rawdata'])) {
            // $winfo .= '<pre>' . implode($result['rawdata'], "\n") . '</pre>';
            $winfo .= implode($result['rawdata'], "\n");
        } else {
            $winfo = implode($whois->Query['errstr'], "\n<br></br>");
        }
        exit(json_encode(array('code' => 0, 'data' => $winfo)));
        break;
    default:
        exit('{"code":-4,"msg":"No Act"}');
        break;
}
