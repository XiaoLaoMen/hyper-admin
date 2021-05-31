<div class="layuimini-container layuimini-page-anim">

    <div class="layuimini-main">

        <form class="layui-form" lay-filter="example">

            <div class="layui-form-item">
                <label class="layui-form-label">上级</label>
                <div class="layui-input-block">
                    <select name="pid">
                        <option value="0">顶级</option>
                        @foreach($list as $v)
                        <option value="{{$v->id}}" @if($info->id ?? '0' ==$v->id) selected="selected" @endif>{{$v->name}}</option>
                            @foreach($v->child as $vv)
                                <option value="{{$vv->id}}" @if($info->id ?? '0' ==$vv->id) selected="selected" @endif>----{{$vv->name}}</option>
                                @foreach($vv->child as $vvv)
                                    <option value="{{$vvv->id}}" @if($info->id ?? '0' ==$vvv->id) selected="selected" @endif>--------{{$vvv->name}}</option>
                                @endforeach
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">权限名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" autocomplete="off" placeholder="请输入权限名称" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">菜单url</label>
                <div class="layui-input-block">
                    <input type="text" name="url" autocomplete="off" placeholder="请输入菜单url" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">图标</label>
                <div class="layui-input-block">
                    <input type="text" name="icon" autocomplete="off" placeholder="请输入图标" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" autocomplete="off" placeholder="请输入数字" class="layui-input" value="{{$info->sort ?? '50'}}">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" @if(isset($info->status) && $info->status=='1') checked="checked" @endif name="status" lay-skin="switch" lay-filter="switchTest" lay-text="显示|禁止">

                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">白名单</label>
                <div class="layui-input-block">
                    <input type="checkbox" @if(isset($info->is_default) && $info->is_default=='1') checked="checked" @endif name="is_default" lay-skin="switch" lay-filter="switchTest" lay-text="是|否">

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
            var url='/admin/menu/handler';
            var data = data.field;
            var status = ajax.add(url,data);
            if(status){
                history.go(-1);
                // window.parent.location.reload();
            }
           return false;
        });

        //表单初始赋值
        form.val('example', {
            "pid": "{{$info->pid ?? '0'}}",
            "name": "{{$info->name ?? ''}}",
            "url": "{{$info->url ?? ''}}",
            "icon": "{{$info->icon ?? ''}}",
        })
    });
</script>
