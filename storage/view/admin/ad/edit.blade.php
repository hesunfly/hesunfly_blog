@component('admin.component.head', ['title' => '推广'])
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
                            <div class="widget-title am-fl">编辑推广</div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label for="desc" class="am-u-sm-3 am-form-label">描述 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Description</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="desc" id="desc" autofocus
                                             value="{{ $ad->desc }}"  placeholder="请输入推广描述">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="url" class="am-u-sm-3 am-form-label">链接地址 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Url</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="url" class="tpl-form-input" id="url" name="url"
                                               value="{{ $ad->url }}"  placeholder="请输入链接地址">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="slug" class="am-u-sm-3 am-form-label">排序 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">sort</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="number" class="tpl-form-input" id="sort" name="sort"
                                               value="{{ $ad->sort }}" placeholder="请输入排序数字，数字越小越靠前">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="img_path" style="margin-top: 40px;" class="am-u-sm-3 am-form-label">图片地址 <span style="color: red;">*</span>
                                        <span
                                                class="tpl-form-line-small-title">Image Path</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" style="width: 79%;display: inline-block;margin-top: 40px;" class="tpl-form-input" id="img_path" name="img_path"
                                            value="{{ $ad->image_path }}"   placeholder="可将图片由图片管理上传，复制地址粘贴即可">
                                        <img src="{{ $ad->image_path }}" alt="图片预览" id="img_path_src" style="width: 20%;height: 70px;" />
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="status" class="am-u-sm-3 am-form-label">状态 <span
                                                class="tpl-form-line-small-title">Status</span></label>
                                    <div class="am-u-sm-9">
                                        @if ($ad->status == 1)
                                            <input type="radio" name="status" id="status-yes" value="1"
                                                   class="am-radio-inline" checked> 显示

                                            <input type="radio" name="status" id="status-no" value="-1"
                                                   class="am-radio-inline"> 隐藏
                                        @else
                                            <input type="radio" name="status" id="status-yes" value="1"
                                                   class="am-radio-inline"> 显示

                                            <input type="radio" name="status" id="status-no" value="-1" checked
                                                   class="am-radio-inline"> 隐藏
                                        @endif
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
<link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/admin/css/github.min.css">
<script>
    $(function () {

        $('#img_path').bind("input propertychange", function (event) {
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

            let image_path = $('#img_path').val();
            if (image_path.length === 0) {
                layer.msg('Image Path 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let status = $("input[name='status']:checked").val();

            let id = {{ $ad->id }}
            axios.put(
                "/admin/common/adSave",
                {
                    id, desc, url, sort, image_path, status,
                }
            ).then(function (response) {
                layer.msg('修改成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        window.location = "/admin/common/adIndex";
                    }
                );
            }).catch(function (error) {
                layer.msg(error.request.responseText, {
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