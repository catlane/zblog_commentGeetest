$ ( function () {
    init ()
} )


function init () {
    $.ajax ( {
        url: bloghost + "zb_system/cmd.php?act=verification&type=getcode&rand=" + Math.round ( Math.random () * 100 ) ,
        type: "get" ,
        dataType: "json" ,
        success: function ( data ) {
            if ( !data.position ) {
                return;
            }
            if ( !$(data.position).length ) {
                return;
            }
            $ ( data.position ).after ( '<div id="div_id_embed" style="' + data.style + '"></div>' );
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

            } )
        }
    } )
}

