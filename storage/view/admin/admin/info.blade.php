
<div class="layuimini-container layuimini-page-anim">

    <div class="layuimini-main">

        <form class="layui-form" lay-filter="example">

            <div class="layui-form-item">
                <label class="layui-form-label">昵称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-block">
                    <input type="text" name="email" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-block">
                    <input type="text" name="mobile" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" class="layui-input" name="passwd" placeholder="请输入密码" maxlength="20">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" class="layui-input" name="passwd_confirmation" placeholder="请在输入一次密码" maxlength="20">
                </div>
            </div>

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
    layui.use(['form', 'layedit', 'ajax','tableSelect'], function () {
        var form = layui.form,
            $ = layui.jquery,
            tableSelect = layui.tableSelect,
            ajax = layui.ajax;

        tableSelect.render({
            elem: '#demo',
            searchKey: 'role_name',
            checkedKey: 'id',
            searchPlaceholder: '角色名称',
            table: {
                url: '/admin/role/list',
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', title: 'ID'},
                    {field: 'role_name',  title: '角色名称'},
                ]]
            },
            done: function (elem, data) {
                var NEWJSON = [];
                var ids=[];
                layui.each(data.data, function (index, item) {
                    NEWJSON.push(item.role_name);
                    ids.push(item.id);
                });
                elem.val(NEWJSON.join(","));
                $("input[name='roles']").val(ids.join(","));
            }
        });
        $('.bind-password').on('click', function () {
            if ($(this).hasClass('icon-5')) {
                $(this).removeClass('icon-5');
                $("input[name='password']").attr('type', 'password');
            } else {
                $(this).addClass('icon-5');
                $("input[name='password']").attr('type', 'text');
            }
        });
        /**
         * 初始化表单，要加上，不然刷新部分组件可能会不加载
         */
        form.render();

        //监听提交
        form.on('submit(demo)', function (data) {
            var url='/admin/admin/info';
            var data = data.field;
            var status =  ajax.add(url,data);
            if(status){
                window.parent.location.reload();
            }
            return false;
        });

        //表单初始赋值
        form.val('example', {
            "name": "{{$info->name ?? ''}}",
            "email": "{{$info->email ?? ''}}",
            "mobile": "{{$info->mobile ?? ''}}",
        })
    });
</script>
