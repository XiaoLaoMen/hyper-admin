<style>
    .layui-btn:not(.layui-btn-lg ):not(.layui-btn-sm):not(.layui-btn-xs) {height:34px;line-height:34px;padding:0 8px;}
</style>
<body>
<div class="layuimini-container layuimini-page-anim">
    <div class="layuimini-main">
        <div>
            <div class="layui-btn-group">
                <button class="layui-btn layui-btn-primary" id="btn-expand">全部展开</button>
                <button class="layui-btn" id="btn-fold">全部折叠</button>
                <button class="layui-btn layui-btn-normal" id="btn-add">添加</button>
            </div>
            <table id="munu-table" class="layui-table" lay-filter="munu-table"></table>
        </div>
    </div>
</div>
<!-- 操作列 -->
<script type="text/html" id="auth-state">
<a class="layui-btn layui-btn-normal layui-btn-xs btn-edit" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs btn-del" lay-event="del">删除</a>
</script>
<script>
    layui.use(['table', 'treetable','ajax'], function () {
        var $ = layui.jquery;
        var table = layui.table;
        var ajax = layui.ajax;
        var treetable = layui.treetable;

        // 渲染表格
        layer.load();
        treetable.render({
            treeColIndex: 1,
            treeSpid: 0,
            treeIdName: 'name',
            treePidName: 'pid',
            elem: '#munu-table',
            url: '/admin/menu/list',
            page: false,
            event:true,
            cols: [[
                {type: 'numbers'},
                {field: 'name', title: '权限名称'},
                {field: 'url', title: '菜单url'},
                {field: 'icon', title: '图标'},
                {templet: '#auth-state', align: 'center', title: '操作'}
            ]],
            done: function () {
                layer.closeAll('loading');
            }
        });

        //展开收起
        $('#btn-expand').click(function () {
            treetable.expandAll('#munu-table');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#munu-table');
        });

        //添加,编辑和删除
        var a_href = '/admin/menu/page';
        var a_title = "{{$admin_menu_url['/admin/menu/page']}}";
        $("#btn-add").click(function () {
            var new_href = a_href+'/0';
            $(this).attr('layuimini-content-href',new_href);
            $(this).attr('data-title',a_title);
            // $(this).triggerHandler("click");
        });

        table.on('tool(munu-table)', function (obj) {
            var data = obj.data;
            var layEvent = obj.event;
            if (layEvent === 'del') {
                var obj=new Object();
                obj.id=data.id;
                var status = ajax.add('/admin/menu/del',obj);
                if(status){
                    window.parent.location.reload();
                }
            } else if (layEvent === 'edit') {
                var new_href = a_href+'/'+ data.id;
                $(this).attr('layuimini-content-href',new_href);
                $(this).attr('data-title',a_title);
                // $(this).triggerHandler("click");
            }
        });
    });
</script>
</body>
</html>

