<style>
    #demo1,#demo11,#demo111{
        max-width: 92px;
    }
    .layui-form-mid {
        float: none;
    }
</style>
<div class="layuimini-container layuimini-page-anim">

    <div class="layuimini-main">

        <form class="layui-form" lay-filter="example">
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($v->key=='logo'): ?>
                    <div class="layui-form-item">
                        <label class="layui-form-label">logo</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="test1">上传图片</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" id="demo1" src="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                                <div class="layui-form-mid layui-word-aux">建议高度为50px</div>
                                <input type="hidden" name="logo" value="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                                <p id="demoText"></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if($v->key=='icon'): ?>
                    <div class="layui-form-item">
                        <label class="layui-form-label">网站icon</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="test11">上传Icon</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" id="demo11" src="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                                <div class="layui-form-mid layui-word-aux">标题栏旁边图标，建议大小32*32</div>
                                <input type="hidden" name="icon" value="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                                <p id="demoText1"></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                    <?php if($v->key=='upload_ext'): ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上传后缀</label>
                            <div class="layui-input-block">
                                <input type="text" name="upload_ext" autocomplete="off" placeholder="上传后缀" class="layui-input" value="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($v->key=='upload_mime'): ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上传类型</label>
                            <div class="layui-input-block">
                                <input type="text" name="upload_mime" autocomplete="off" placeholder="上传类型" class="layui-input" value="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($v->key=='upload_image_size'): ?>
                        <div class="layui-form-item">
                            <label class="layui-form-label">上传图片大小</label>
                            <div class="layui-input-block">
                                <input type="text" name="upload_image_size" autocomplete="off" placeholder="上传图片大小" class="layui-input" value="<?php echo \Hyperf\ViewEngine\T::e($v->val ?? ''); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
    layui.use(['form', 'layedit', 'ajax','notice','upload'], function () {
        var form = layui.form,
            $ = layui.jquery,
            notice = layui.notice,
            upload = layui.upload,
            ajax = layui.ajax;
        //logo,icon,缩略图
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/admin/upload/layui' //改成您自己的上传接口
            ,done: function(res){
                //如果上传失败
                if(res.success != '1'){
                    return layer.msg('上传失败');
                }
                $('#demo1').attr('src', res.url);
                $("input[name='logo']").attr('value', res.url);
                //上传成功
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });

        var uploadInst1 = upload.render({
            elem: '#test11'
            ,url: '/admin/upload/layui' //改成您自己的上传接口
            ,accept: 'file'
            ,done: function(res){
                //如果上传失败
                if(res.success != '1'){
                    return layer.msg('上传失败');
                }
                $('#demo11').attr('src', res.url);
                $("input[name='icon']").attr('value', res.url);
                //上传成功
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText1');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst1.upload();
                });
            }
        });



        /**
         * 初始化表单，要加上，不然刷新部分组件可能会不加载
         */
        form.render();

        //监听提交
        form.on('submit(demo)', function (data) {
            var url='/admin/set/handler';
            var data = data.field;
            var status = ajax.add(url,data);
            if(status){
                window.parent.location.reload();
            }
            return false;
        });

    });
</script>
<?php /**PATH /home/nicaine/Desktop/wwwroot/www.b.com/storage/view/admin/set/index.blade.php ENDPATH**/ ?>