@component('admin.component.head', ['title' => '分类编辑'])
@endcomponent

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
                            <div class="widget-title am-fl">编辑分类</div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label for="category_title" class="am-u-sm-3 am-form-label">分类名称 <small><span
                                                    style="color: red;">*</span></small>
                                        <span
                                                class="tpl-form-line-small-title">Category Title</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="category_title"
                                               name="category_title" autofocus placeholder="请输入分类名称"
                                               value="{{ $category_title }}">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="button" class="am-btn am-btn-primary tpl-btn-bg-color-success "
                                                id="submit">提交
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

<script>

    $(function () {
        $('#submit').click(function () {
            let category_title = $('#category_title').val();
            if (category_title.length === 0) {
                layer.msg('Category Title 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            axios.put(
                "{{ url('/admin/categories/save/') . '/' . $id }}",
                {
                    'title': category_title
                }
            ).then(function (response) {
                layer.msg('编辑成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        window.location = "{{ url('/admin/categories') }}";
                    }
                );
            }).catch(function (error) {
                layer.msg('error！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        return false;
                    }
                );
                if (error.request.status === 422) {
                    let msg = JSON.parse(error.request.responseText);
                    let errors = msg.errors;
                    let length = errors.length;
                    // if (errors.email[0]);
                }
            });
        });
    });

</script>

</body>

</html>