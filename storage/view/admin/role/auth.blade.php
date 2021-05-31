<link rel="stylesheet" href="/admin/css/eleTree/eleTree.css">

<div class="layuimini-container layuimini-page-anim">
    <div class="layuimini-main welcome">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-row layui-col-space15">
                    <div class="layui-card">
                        <div class="layui-card-header">角色授权</div>
                        <div class="layui-card-body">
                            <div class="content1">
                            <div class="eleTree" id="ele1"></div>
                            </div>
                            <div class="layuimini-main">
                            <div class="layui-form-item">
                                <input type="hidden" name="id" value="{{$id}}">
                                <div class="layui-input-block">
                                    <button class="layui-btn" id="add-btn">提交</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use(['jquery','table','eleTree','code','ajax'], function () {
        var $ = layui.jquery,
            eleTree = layui.eleTree,
            ajax = layui.ajax;

        var el = eleTree.render({
            elem: '#ele1',
            showCheckbox: true,
            method: "get",
            url: "/admin/role/getauth/{{$id}}",
            defaultExpandAll:true,
            indent:20
        });

        $("#add-btn").click(function () {
            var els = el.getChecked(false,true);
            var ids=new Array();
            $.each(els,function (i,v) {
                ids.push(v.id);
            });
            var obj=new Object();
            obj.ids = ids;
            obj.id=$("input[name='id']").val();
            var status = ajax.add('/admin/auth/role',obj);
            if(status){
                window.parent.location.reload();
            }
        });
    });
</script>
