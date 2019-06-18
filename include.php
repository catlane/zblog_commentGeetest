<?php
//打开session,如果打开了，可以删除

if ( session_status () == 1 ) {
    session_start ();
}


#注册插件
RegisterPlugin ( "commentGeetest" , "ActivePlugin_commentGeetest" );

$GLOBALS[ 'actions' ][ 'verification' ] = 6;

function ActivePlugin_commentGeetest () {
    global $zbp;

    //验证
    Add_Filter_Plugin ( "Filter_Plugin_Cmd_Begin" , "commentGeetestCmdBegin" );
    /**
     * 开关检查，开启了，才会用到
     */
    if ( $zbp->Config ( 'commentGeetest' )->active ) {

        Add_Filter_Plugin ( 'Filter_Plugin_Zbp_Load' , 'commentGeetest_Load' );
    }

    /**
     * 开关检查，开启了，才会用到
     */
    if ( $zbp->Config ( 'commentGeetest' )->loginActive ) {
        //JS插入
        Add_Filter_Plugin ( "Filter_Plugin_Login_Header" , "commentGeetest_Set_Header_js" );
    }

}
//Zbp类的加载接口
function commentGeetest_Load () {
	global $zbp;
    if ($zbp->user->Level != 1) {
        Add_Filter_Plugin ( 'Filter_Plugin_ViewPost_Template' , 'commentGeetestJsAdd' );
    }
}


function InstallPlugin_commentGeetest () {

    global $zbp;
    //看看有没有
    if ( ! $zbp->Config ( 'commentGeetest' )->appId ) {
        $zbp->Config ( 'commentGeetest' )->appId = '3386e03c620a4067f18fa92c370f1594';
        $zbp->Config ( 'commentGeetest' )->appKey = '5fe89444b54d3a3b8e49594c42a770cf';
        $zbp->Config ( 'commentGeetest' )->active = 0;


        $zbp->Config ( 'commentGeetest' )->loginActive = 0;
        $zbp->Config ( 'commentGeetest' )->loginStyle = 'float:left;';
        $zbp->Config ( 'commentGeetest' )->loginPosition = '.password';
        $zbp->SaveConfig ( 'commentGeetest' );
    }
}

function UninstallPlugin_commentGeetest () {
	global $zbp;
    $zbp->DelConfig('commentGeetest');
}

/**
 * 路由开始
 */
function commentGeetestCmdBegin () {
	global $zbp;
    $action = GetVars ( 'act' , 'GET' );
    switch ( $action ) {
        case 'verify':
            if ( $zbp->Config ( 'commentGeetest' )->loginActive ) {
                commentGeetest_Login_Validate ($_POST);//登陆验证
            }
            break;
        case "verification":
            $param = GetVars ( 'type' , 'GET' );
            switch ( $param ) {
                case 'getcode':
                    commentGeetestGetCode ();//获取验证码
                    break;
                case 'validate_captcha':
                    commentGeetestGetCodeValidateCaptcha ( $_POST );//极验证二次验证
                    break;
            }
		    break;
	    default:
            return true;
    }


}

/**
 *  获取验证码
 */
function commentGeetestGetCode () {
	global $zbp;
    require_once $zbp->path . 'zb_users/plugin/commentGeetest/sdk/gt-php-sdk/lib/class.geetestlib.php';
    $GtSdk = new \GeetestLib();
    $return = $GtSdk->register ();
    if ( $return ) {
        $_SESSION[ 'gtserver' ] = 1;
        $result = array (
            'success' => 1 ,
            'gt' => CAPTCHA_ID ,
            'challenge' => $GtSdk->challenge,
        );
    } else {
        $_SESSION[ 'gtserver' ] = 0;
        $rnd1 = md5 ( rand ( 0 , 100 ) );
        $rnd2 = md5 ( rand ( 0 , 100 ) );
        $challenge = $rnd1 . substr ( $rnd2 , 0 , 2 );
        $result = array (
            'success' => 0 ,
            'gt' => CAPTCHA_ID ,
            'challenge' => $challenge
        );
        $_SESSION[ 'challenge' ] = $result[ 'challenge' ];

    }
    if ($zbp->Config ( 'commentGeetest' )->loginActive) {
        $result[ 'style' ] = $zbp->Config ( 'commentGeetest' )->loginStyle;
        $result[ 'position' ] = $zbp->Config ( 'commentGeetest' )->loginPosition;
    }
    echo json_encode ( $result );
    die;
}

/**
 * 这里二次验证极验证是否正确
 */
function commentGeetestGetCodeValidateCaptcha ( $postData ) {
    global $zbp;
    require_once $zbp->path . 'zb_users/plugin/commentGeetest/sdk/gt-php-sdk/lib/class.geetestlib.php';

    $result = false;
    $GtSdk = new \GeetestLib();
    if ( $_SESSION[ 'gtserver' ] == 1 ) {
        $result = $GtSdk->validate ( $postData[ 'geetest_challenge' ] , $postData[ 'geetest_validate' ] , $postData[ 'geetest_seccode' ] );
        if ( $result == TRUE ) {
            $result = true;
        } else if ( $result == FALSE ) {
            $result = false;
        } else {
            $result = false;
        }
    } else {
        if ( $GtSdk->get_answer ( $postData[ 'geetest_validate' ] ) ) {
            $result = true;
        } else {
            $result = false;
        }
    }
    if ( ! $result ) {
        echo json_encode ( [
            'code' => 0 ,
            'msg' => '错误'
        ] );
    } else {
        echo json_encode ( [
            'code' => 200 ,
            'msg' => '正确'
        ] );
    }
    die;
}


/**
 * 添加js
 */
function commentGeetestJsAdd ( &$template ) {
    global $zbp;

    $src = '<script src="' . $zbp->host . 'zb_users/plugin/commentGeetest/resources/commentGeetest.js' . '" type="text/javascript"></script>';
    $src .= '<script src="' . $zbp->host . 'zb_users/plugin/commentGeetest/resources/gt.js' . '" type="text/javascript"></script>';

    $src .= <<<eof
eof;

    $content = $template->GetTags ( 'article' )->Content;
    $template->GetTags ( 'article' )->Content = $content . $src;
}


/**
 * 登陆设置
 */

function commentGeetest_Set_Header_js () {
    global $zbp;
    ?>
	<script type='text/javascript' src="<?php echo $zbp->host; ?>zb_users/plugin/commentGeetest/resources/gt.js"></script>
	<script type='text/javascript' src="<?php echo $zbp->host; ?>zb_users/plugin/commentGeetest/resources/LoginGeetest.js"></script>
    <?php
}


/**
 * 登陆验证
 * @param $postData
 */
function commentGeetest_Login_Validate ( $postData ) {

    global $zbp;
    require_once $zbp->path . 'zb_users/plugin/commentGeetest/sdk/gt-php-sdk/lib/class.geetestlib.php';



    $result = false;
    $GtSdk = new \GeetestLib();
    if ( $_SESSION[ 'gtserver' ] == 1 ) {
        $result = $GtSdk->validate ( $postData[ 'geetest_challenge' ] , $postData[ 'geetest_validate' ] , $postData[ 'geetest_seccode' ] );
        if ( $result == TRUE ) {
            $result = true;
        } else if ( $result == FALSE ) {
            $result = false;
        } else {
            $result = false;
        }
    } else {
        if ( $GtSdk->get_answer ( $postData[ 'geetest_validate' ] ) ) {
            $result = true;
        } else {
            $result = false;
        }
    }
    if ( ! $result ) {
        $zbp->ShowError('验证失败:拖动滑块将悬浮图像正确拼合');
        die();
    }
}