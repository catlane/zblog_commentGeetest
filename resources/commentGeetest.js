
$ ( document ).on ( 'pjax:complete' , function () {

    init ()
} )
$ ( function () {
    init ()
} )



function init () {
    //每次初始化之前，先看看是不是文章页面
    if ( $ ( "#txaArticle" ).length == 0 ) {
        return
    }


    var submitBtn = $ ( "#inpId" ).parent ( "form" ).find ( "input[type='submit']" );
    var defaultVal = submitBtn.val ();

    //在看看是不是已经有了，有的话，就滞空了
    if ( $ ( "#div_id_embed" ).length != 0 ) {
        $ ( "#div_id_embed" ).html ( '加载验证码中...' );
    } else {
        $ ( "#txaArticle" ).after ( '<div id="div_id_embed" style="padding: 10px 0;">加载验证码中...</div>' );
    }
    $.ajax ( {
        url: bloghost + "zb_system/cmd.php?act=verification&type=getcode&rand=" + Math.round ( Math.random () * 100 ) ,
        type: "get" ,
        dataType: "json" ,
        success: function ( data ) {
            $ ( "#div_id_embed" ).html ( '' );
            //请检测data的数据结构， 保证data.gt, data.challenge, data.success有值
            initGeetest ( {
                // 以下配置参数来自服务端 SDK
                gt: data.gt ,
                challenge: data.challenge ,
                offline: !data.success ,
                new_captcha: true ,
                width: '100px' ,
                product: "bind" ,
            } , function ( captchaObj ) {

                // 这里可以调用验证实例 captchaObj 的实例方法

                //验证码追加到元素里面
                captchaObj.appendTo ( '#div_id_embed' );

                //然后修改样式
                captchaObj.onReady ( function () {
                    $ ( '#div_id_embed .gt_slider' ).css ( {
                        marginTop: "10px" ,
                        background: "#ede4dc" ,
                        borderRadius: "20px" ,
                        width: "257px" ,
                        border: "1px solid #ccc" ,
                        left: 0
                    } )
                } );


                //验证码成功之后，二次验证
                captchaObj.onSuccess ( function () {
                    var result = captchaObj.getValidate ();

                    // validate ( submitBtn,defaultVal,result );
                } );
                //提交数据的时

                commint ( submitBtn , defaultVal , captchaObj );
            } )
        }
    } )
}


/**
 * 提交数据
 */
function commint ( submitBtn , defaultVal , captchaObj ) {
    // captchaObj.reset ();
    zbp.plugin.unbind ( "comment.verifydata" , "commentGeetest" );
    // //绑定评论回复事件，点击回复按钮时移动评论框

    zbp.plugin.on ( "comment.verifydata" , "commentGeetest" , function ( error , formData ) {
        var geetest_validate = $ ( "#inpId" ).parent ( "form" ).find ( 'input[name="geetest_validate"]' ).val ();
        var geetest_challenge = $ ( "#inpId" ).parent ( "form" ).find ( 'input[name="geetest_challenge"]' ).val ();
        var geetest_seccode = $ ( "#inpId" ).parent ( "form" ).find ( 'input[name="geetest_seccode"]' ).val ();
      


        if ( !geetest_validate ) {
           
            submitBtn.attr ( { value: defaultVal , disabled: false } ).removeClass ( 'loading' );
            alert ( '验证失败:拖动滑块将悬浮图像正确拼合' )
            $ ( "#inpId" ).parent ( "form" ).submit ( function () {
                return false;
            } )
            throw "验证失败:拖动滑块将悬浮图像正确拼合";
        }
        result = {
            geetest_challenge: geetest_challenge ,
            geetest_validate: geetest_validate ,
            geetest_seccode: geetest_seccode
        };
        validate ( submitBtn , defaultVal , result , function ( captchaObj ) {
            // console.log ( captchaObj );
            init ();
            // captchaObj.reset ();
        } )

    } );
}

function validate ( submitBtn , defaultVal , result , success = null ) {
    $.ajax ( {
        url: bloghost + "zb_system/cmd.php?act=verification&type=validate_captcha&rand=" + Math.round ( Math.random () * 100 ) ,
        type: "post" ,
        data: result ,
        dataType: "json" ,
        beforeSend: function () {
            submitBtn.val ( "Waiting..." ).attr ( "disabled" , "disabled" ).addClass ( "loading" )
        } ,
        success: function ( result ) {
            submitBtn.attr ( { value: defaultVal , disabled: false } ).removeClass ( 'loading' );
            if ( success ) {
                success ();
            }
        } ,
        error: function ( err ) {
            submitBtn.attr ( { value: defaultVal , disabled: false } ).removeClass ( 'loading' );
            alert ( '验证失败:拖动滑块将悬浮图像正确拼合' )
            $ ( "#inpId" ).parent ( "form" ).submit ( function () {
                return false;
            } )
            throw "验证失败:拖动滑块将悬浮图像正确拼合";
        }
    } )
}

