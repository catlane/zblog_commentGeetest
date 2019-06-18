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

?>
	<div id="divMain">

  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
      <a href="./main.php"><span class="m-left">评论应用设置</span></a>
      <a href="./login_main.php"><span class="m-left">登陆应用设置</span></a>
      <a href="./reward.php"><span class="m-left m-now">要饭~~</span></a>
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

          .reward {
	          width: 240px;
          }

          .reward img {
	          width: 100%;
          }

          .reward .qrcode-border {
	          border-radius: 30px;
	          width: 240px;
	          height: 240px;
	          padding: 18.05px;
	          margin-top: 25px;
          }

          .reward .qrcode-tip {
	          width: 50px;
	          position: relative;
	          margin: -12px auto 0 auto !important;
	          font-size: 12px;
	          font-weight: 700;
	          background: #fff;
	          height: 15px;
	          line-height: 15px;
	          text-indent: 0em;
	          text-align: center;
          }
	      .reward .ds-payment-way label{
		      margin-right: 10px;
	      }
          .reward .ds-payment-way input{
	          vertical-align: text-top;
	          margin-right: 3px;
          }
      </style>
      <div class="topTips">如果你觉得好用，就给作者打赏吧，毕竟这款插件免费~~~一瓶奶茶钱就好</div>
	  <div class="reward">
		<h4 style="margin-left: 5px;">选择打赏方式：</h4>
		<div class="ds-payment-way" style="margin-left: 5px;">
			<label for="wechat"><input type="radio" id="wechat" class="reward-radio" value="0" name="reward-way" checked="checked">微信</label>
			<label for="qqqb"><input type="radio" id="qqqb" class="reward-radio" value="1" name="reward-way">QQ钱包</label>
			<label for="alipay"><input type="radio" id="alipay" class="reward-radio" value="2" name="reward-way">支付宝</label>
		</div>
		<div class="ds-payment-img">
			<div class="qrcode-img wechat" id="qrCode_0" style="display: block;">
				<div class="qrcode-border box-size" style="border: 9.02px solid rgb(60, 175, 54)"><img class="qrcode-img qrCode_0" id="qrCode_0" src="<?php echo $bloghost ?>zb_users/plugin/commentGeetest/resources/wechatpay.jpeg">	</div>
				<p class="qrcode-tip">打赏</p>
			</div>
			<div class="qrcode-img qqqb" id="qrCode_1" style="display: none;">
				<div class="qrcode-border box-size" style="border: 9.02px solid rgb(102, 153, 204)"><img class="qrcode-img qrCode_1" id="qrCode_1" src="<?php echo $bloghost ?>zb_users/plugin/commentGeetest/resources/qqpay.jpeg"></div>
				<p class="qrcode-tip">打赏</p>
			</div>
			<div class="qrcode-img alipay" id="qrCode_2" style="display: none;">
				<div class="qrcode-border box-size" style="border: 9.02px solid rgb(235, 95, 1)"><img class="qrcode-img qrCode_2" id="qrCode_2" src="<?php echo $bloghost ?>zb_users/plugin/commentGeetest/resources/alipay.jpeg"></div>
				<p class="qrcode-tip">打赏</p>
			</div>
		</div>
	  </div>
  </div>
</div>
	<script>
		$ ( function () {
            $ ( '.reward input[name="reward-way"]' ).click ( function () {
                var id = $ ( this ).attr ( 'id' );
                $ ( '.ds-payment-img' ).children ( 'div' ).hide ();
                $ ( '.ds-payment-img' ).children ( '.' + id ).show ();
            } )
        } )
	</script>
	<script type="text/javascript">
        AddHeaderIcon ( "<?php echo $bloghost . 'zb_users/plugin/commentGeetest/logo-blue.png';?>" );
        ActiveTopMenu ( "topmenu_SpiderStatistics" );
    </script>
	<!--    AddHeaderIcon("--><?php //echo $bloghost . 'zb_users/plugin/SpiderStatistics/logo.png';?><!--")-->

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime ();
?>