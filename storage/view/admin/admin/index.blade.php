<div class="layuimini-container layuimini-page-anim">
    <div class="layuimini-main">

        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-normal layui-btn-sm" id="btn-add"> 添加 </button>
            </div>
        </script>

        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
            <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="auth">授权</a>
            <a class="layui-btn layui-btn-normal layui-btn-xs data-count-edit" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
        </script>

    </div>
</div>

<script>
    layui.use(['form', 'table','form','element','ajax'], function () {
        var $ = layui.jquery,
            table = layui.table,
            ajax = layui.ajax,
            form = layui.form;

        table.render({
            elem: '#currentTableId',
            url: '/admin/admin/list',
            toolbar: '#toolbarDemo',
            id : 'adminReload',
            defaultToolbar: ['filter', 'exports', 'print'],
            cols: [[
                {type: "checkbox"},
                {field: 'id', title: 'ID'},
                {field: 'name',  title: '昵称'},
                {
                    field: 'status',
                    title: '登录',
                    templet: function (d) {
                        var state = "";
                        if (d.status == "1") {
                            state = "<input type='checkbox' value='" + d.id + "' id='status' lay-filter='stat' checked='checked' name='status'  lay-skin='switch' lay-text='允许|禁止' >";
                        }
                        else {
                            state = "<input type='checkbox' value='" + d.id + "' id='status' lay-filter='stat'  name='status'  lay-skin='switch' lay-text='允许|禁止' >";

                        }

                        return state;
                    }
                },
                {title: '操作', toolbar: '#currentTableBar', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });

        //重载
        var active = {
            reload: function () {
                table.reload('adminReload');
            }
        };

        form.on('switch(stat)', function (data) {
            var contexts;
            var sta;
            var x = data.elem.checked;
            if (x==true) {
                contexts = "打开";
                sta=1;
            } else {
                contexts = "关闭";
                sta=0;
            }
            var obj =new Object();
            obj.id=data.value;
            obj.status=sta;
            ajax.add('/admin/admin/login',obj);
            active.reload();
            return false;
        });



        var a_href = '/admin/admin/page';
        var a_title = "{{$admin_menu_url['/admin/admin/page']}}";

        //添加
        $("#btn-add").click(function () {
            var new_href = a_href+'/0';
            $(this).attr('layuimini-content-href',new_href);
            $(this).attr('data-title',a_title);
        });

        //编辑和删除
        table.on('tool(currentTableFilter)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                var new_href = a_href+'/'+ data.id;
                $(this).attr('layuimini-content-href',new_href);
                $(this).attr('data-title',a_title);
            } else if(obj.event==='auth'){
                var new_href = '/admin/admin/auth'+'/'+ data.id;
                $(this).attr('layuimini-content-href',new_href);
                $(this).attr('data-title',"{{$admin_menu_url['/admin/admin/auth']}}");

            }else if (obj.event === 'delete') {
                var obj=new Object();
                obj.id=data.id;
                var status = ajax.add('/admin/admin/del',obj);
                if(status){
                    window.parent.location.reload();
                }
            }
        });

    });
</script>
