@component('admin.component.head', ['title' => '推广添加'])
@endcomponent
<link rel="stylesheet" href="/assets/admin/css/simplemde.min.css">
<body data-type="widgets">
<script src="/assets/admin/js/theme.js"></script>
<div class="am-g tpl-g">
    <!-- 头部 -->
@component('admin.component.header')
@endcomponent
<!-- 风格切换 -->
@component('admin.component.skin')
@endcomponent
<!-- 侧边导航栏 -->
@component('admin.component.sidebar')
@endcomponent

<!-- 内容区域 -->
    <!-- 内容区域 -->
    <div class="tpl-content-wrapper">
        <div class="row-content am-cf">
            <div class="row">

                <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                    <div class="widget am-cf">
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">创建推广</div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label for="desc" class="am-u-sm-3 am-form-label">描述 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Description</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="desc" id="desc" autofocus
                                               placeholder="请输入推广描述">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="url" class="am-u-sm-3 am-form-label">链接地址 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Url</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="url" name="url"
                                               placeholder="请输入链接地址">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="slug" class="am-u-sm-3 am-form-label">排序 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">sort</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="number" class="tpl-form-input" id="sort" name="sort"
                                               placeholder="请输入排序数字，数字越小越靠前">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="img_path" style="margin-top: 40px;" class="am-u-sm-3 am-form-label">图片地址 <span style="color: red;">*</span>
                                        <span
                                                class="tpl-form-line-small-title">Image Path</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" style="width: 79%;display: inline-block;margin-top: 40px;" class="tpl-form-input" id="img_path" name="img_path"
                                               placeholder="可将图片由图片管理上传，复制地址粘贴即可">
                                            <img src="" alt="" id="img_path_src" style="width: 20%;height: 70px;" />
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="status" class="am-u-sm-3 am-form-label">状态 <span
                                                class="tpl-form-line-small-title">Status</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="radio" name="status" id="status-yes" value="1"
                                               class="am-radio-inline"> 显示
                                        <input type="radio" name="status" id="status-no" value="-1" checked
                                               class="am-radio-inline"> 隐藏
                                    </div>
                                </div>


                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="button"
                                                class="am-btn am-btn-primary tpl-btn-bg-color-success " id="submit">
                                            提交
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@component('admin.component.foot')
@endcomponent
<script src="/assets/admin/js/simplemde.min.js"></script>
<script src="/assets/admin/js/codemirror-4.inline-attachment.js"></script>
<script src="/assets/admin/js/highlight.min.js"></script>
<link rel="stylesheet" href="/assets/admin/css/github.min.css">
<link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css">
<script>
    $(function () {
        $('#img_path').bind("input propertychange",function(event){
            let src = $(this).val();
            let src_el = $('#img_path_src');
            if (src.length > 0) {
                src_el.attr('src', src);
                src_el.attr('alt', '图片预览');
            } else {
                src_el.attr('src', '');
                src_el.attr('alt', '');
            }
        });

        $('#submit').click(function () {
            let desc = $('#desc').val();
            if (desc.length === 0) {
                layer.msg('Description 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let url = $('#url').val();
            if (url.length === 0) {
                layer.msg('Url 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }
            let sort = $('#sort').val();
            if (sort.length === 0) {
                layer.msg('Sort 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let img_path = $('#img_path').val();
            if (img_path.length === 0) {
                layer.msg('Image Path 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let status = $("input[name='status']:checked").val();

            axios.post(
                "{{ url('/admin/ads/store') }}",
                {
                    desc,url,sort,img_path,status,
                }
            ).then(function (response) {
                layer.msg('创建成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        window.location = "{{ url('/admin/ads') }}";
                    }
                );
            }).catch(function (error) {
                layer.msg('error！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        return false;
                    }
                );
            });
        });
    });

</script>

</body>

</html>