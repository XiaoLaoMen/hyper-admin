<div class="layuimini-container layuimini-page-anim">

    <div class="layuimini-main">

        <form class="layui-form" lay-filter="example">

            <div class="layui-form-item">
                <label class="layui-form-label">角色名称</label>
                <div class="layui-input-block">
                    <input type="text" name="role_name" autocomplete="off" placeholder="请输入角色名称" class="layui-input">
                </div>
            </div>

            <input type="hidden" name="id" value="{{$info->id ?? '0'}}">
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="demo">提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    layui.use(['form', 'layedit', 'ajax'], function () {
        var form = layui.form,
            ajax = layui.ajax;

        /**
         * 初始化表单，要加上，不然刷新部分组件可能会不加载
         */
        form.render();

        //监听提交
        form.on('submit(demo)', function (data) {
            var url='/admin/role/handler';
            var data = data.field;
            var status = ajax.add(url,data);
            if(status){
                // window.parent.location.reload();
                history.go(-1);
            }
            return false;
        });

        //表单初始赋值
        form.val('example', {
            "role_name": "{{$info->role_name ?? ''}}",
        })
    });
</script>
