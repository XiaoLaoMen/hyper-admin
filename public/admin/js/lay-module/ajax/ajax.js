/**
 * date:2020/02/27
 * ajax
 */
layui.define(["jquery", "notice","layer"], function (exports) {
    var $ = layui.$,
        notice = layui.notice,
        layer = layui.layer;
    var status;
    var ajax = {

        add: function(url,data){
            var index = layer.load();
            $.ajax({
                url : url,
                type : "POST",
                data : data,
                dataType : "json",
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success : function(result) {
                    if(result.code==0){
                        notice.success(result.message);
                        layer.close(index);
                        status=true;
                    }else{
                        layer.close(index);
                        notice.error(result.message);
                        status= false;
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrow ){
                    layer.close(index);
                    notice.error(XMLHttpRequest['responseText']);
                    status= false;
                }
            });
            return status;
        },

    };


    exports("ajax", ajax);
});
