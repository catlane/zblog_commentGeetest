<?php 
/**
*  Geetest配置文件
* @author Tanxu
*/
//define("CAPTCHA_ID", "3386e03c620a4067f18fa92c370f1594");
//define("PRIVATE_KEY", "5fe89444b54d3a3b8e49594c42a770cf");

define("CAPTCHA_ID", $zbp->config ( 'commentGeetest' )->appId ? $zbp->config ( 'commentGeetest' )->appId : "3386e03c620a4067f18fa92c370f1594");
define("PRIVATE_KEY", $zbp->config ( 'commentGeetest' )->appKey ? $zbp->config ( 'commentGeetest' )->appKey : "5fe89444b54d3a3b8e49594c42a770cf");

 ?>