<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load ();

$action = 'root';
if ( ! $zbp->CheckRights ( $action ) ) {
    $zbp->ShowError ( 6 );
    die();
}
if ( ! $zbp->CheckPlugin ( 'commentGeetest' ) ) {
    $zbp->ShowError ( 48 );
    die();
}

$blogtitle = '评论/登陆极验证码';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

if ( $_POST && count ( $_POST ) > 0 ) {
    if ( function_exists ( 'CheckIsRefererValid' ) )
        CheckIsRefererValid ();
    foreach ( $_POST as $k => $v ) {
        $zbp->Config ( 'commentGeetest' )->$k = GetVars ( $k , 'post' );
    }
    $zbp->SaveConfig ( 'commentGeetest' );
    $zbp->SetHint ( 'good' , "保存成功" );
    Redirect ( "./main.php" );
}
?>
	<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
      <a href="./main.php"><span class="m-left m-now">评论应用设置</span></a>
      <a href="./login_main.php"><span class="m-left">登陆应用设置</span></a>
      <a href="./reward.php"><span class="m-left">要饭~~</span></a>
      <a href="https://www.lovyou.top" target="_blank"><span class="m-left">作者博客</span></a>
  </div>
  <div id="divMain2">
<!--代码-->
      <style>
          .edit-input {
	          width: 280px;
          }

          .topTips {
	          max-width: 1000px;
	          margin: 10px 0 10px 0;
	          padding-left: 20px;
	          line-height: 40px;
	          border-radius: 2px;
	          border: solid 1px #f5dab6;
	          color: #775e3d;
	          font-size: 14px;
	          background-color: #fff1df;
          }

          .topTips span {
	          color: #cb261a;
          }
      </style>
      <form action="" method="post">
	      <?php if ( function_exists ( 'CheckIsRefererValid' ) ) {
              echo '<input type="hidden" name="csrfToken" value="' . $zbp->GetCSRFToken () . '">';
          } ?>
	      <div class="topTips">如果想看数据报表，那么申请<span>Id</span>和<span>Key</span>并填写，如果不需要，则用默认的就可以！</div>
            <table border="1" class="tableFull tableBorder tableBorder-thcenter" style="max-width: 1000px">
                <thead>
                <tr>
                    <th width="200px">配置名称</th>
                    <th>配置内容</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>启用开关</td>
                    <td>
                        <input name="active" type="text" class="checkbox" style="display:none;" value="<?php echo $zbp->Config ( 'commentGeetest' )->active; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>APP ID</td>
                    <td>
                        <input name="appId" type="text" class="edit-input" value="<?php echo $zbp->Config ( 'commentGeetest' )->appId; ?>" placeholder="请填写Geetest的ID" />
                    </td>
                </tr>
                <tr>
                    <td>APP Key</td>
                    <td>
                        <input name="appKey" type="text" class="edit-input" value="<?php echo $zbp->Config ( 'commentGeetest' )->appKey; ?>" placeholder="请填写Geetest的Key" />
                    </td>
                </tr>
                <tr>
                    <td>Geetest官网</td>
                    <td>
                        <a href="http://www.geetest.com/" target="_blank">http://www.geetest.com/</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="submit" value="保存配置" style="margin: 0; font-size: 1em;" />
        </form>
      <style>
            .readme {
	            max-width: 1000px;
	            padding: 10px;
	            margin-bottom: 10px;
	            background: #f9f9f9;
            }

            .readme h3 {
	            font-size: 16px;
	            font-weight: normal;
	            color: #000;
            }

            .readme ul li {
	            margin-bottom: 5px;
	            line-height: 30px;
            }

            .readme a {
	            color: #333 !important;
	            text-decoration: underline;
            }

            .readme code {
	            display: inline-block;
	            margin: 0 5px;
	            padding: 0 8px;
	            line-height: 25px;
	            font-size: 12px;
	            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
	            color: #1a1a1a;
	            border-radius: 4px;
	            background: #eee;
            }

            .readme code.copy {
	            cursor: pointer;
            }

            .readme-item {
	            -webkit-display: flex;
	            display: flex;
	            margin-bottom: 10px;
            }

            .readme-item .name {
	            display: block;
	            width: 100px;
	            height: 24px;
	            line-height: 24px;
            }

            .readme-item .preview {
	            display: block;
	            width: 300px;
            }

            .readme-item .options {
	            display: block;
	            width: 300px;
	            height: 24px;
            }

            .readme-item .code-pre {
	            display: none;
            }

            .readme-item .copy-btn {
	            display: inline-block;
	            width: 64px;
	            height: 24px;
	            margin: 0;
	            margin-left: 10px;
	            padding: 0;
	            line-height: 24px;
	            font-size: 13px;
	            color: #fff;
	            border: none;
	            border-radius: 2px;
	            background: #3a6ea5;
	            cursor: pointer;
            }

            .readme-item .copy-btn:active,
            .readme-item .copy-btn:focus {
	            outline: 0;
            }

            .readme-item .copy-btn:active {
	            opacity: .95;
            }
        </style>
      <div class="readme">
            <h3>插件配置说明</h3>
            <ul>
                <li>- 改插件与后台默认验证码不冲突，如需启动改极验证，点击上方启动即可，不需开启后台验证码</li>
                <li>- 该插件默认会在评论框下边生成一个极验证滑动块</li>
                <li>- 本极验证采用js验证和PHP后台验证，双次验证，更加安全</li>
                <li>- 目前只支持默认，不支持浮动验证以及其他，后续会增加</li>
                <li>- 目前只支持默认大小，后续会增加验证码相关大小配置</li>
            </ul>
        </div>
  </div>
</div>
	<script type="text/javascript">
        AddHeaderIcon ( "<?php echo $bloghost . 'zb_users/plugin/commentGeetest/logo-blue.png';?>" );
        ActiveTopMenu ( "topmenu_SpiderStatistics" );
    </script>
	<!--    AddHeaderIcon("--><?php //echo $bloghost . 'zb_users/plugin/SpiderStatistics/logo.png';?><!--")-->

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime ();
?>