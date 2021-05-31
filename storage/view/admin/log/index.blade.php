<div class="layuimini-container layuimini-page-anim">
    <div class="layuimini-main">

        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">

            </div>
        </script>

        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
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
            url: '/admin/log/list',
            toolbar: '#toolbarDemo',
            id : 'adminReload',
            defaultToolbar: ['filter', 'exports', 'print'],
            cols: [[
                {type: "checkbox"},
                {field: 'id', title: 'ID'},
                {field: 'ip',  title: 'ip'},
                {field: 'addr',  title: '地址'},
                {field: 'created_at',  title: '时间'},
                {title: '操作', toolbar: '#currentTableBar', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 15,
            page: true,
            skin: 'line'
        });

        //编辑和删除
        table.on('tool(currentTableFilter)', function (obj) {
            var data = obj.data;
            if (obj.event === 'delete') {
                var obj=new Object();
                obj.id=data.id;
                var status = ajax.add('/admin/log/del',obj);
                if(status){
                    window.parent.location.reload();
                }
            }
        });

    });
</script>
