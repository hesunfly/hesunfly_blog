@component('admin.component.head', ['title' => '应用配置'])
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
                            <div class="widget-title am-fl">应用配置 <span style="color: #00b7ee;"> (图片类的配置可以在图片管理中上传，然后复制地址填写到对应的录入元素中即可) </span></div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form" id="setting_form">
                                @foreach($config as $item)
                                <div class="am-form-group">
                                    <label for="{{ $item['name'] }}" class="am-u-sm-3 am-form-label">{{ $item['title'] }} <small><span
                                                    style="color: red;">*</span></small>
                                        <span
                                                class="tpl-form-line-small-title">{{ $item['en_title'] }}</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="{{ $item['name'] }}"
                                               name="{{ $item['name'] }}" autofocus placeholder=""
                                               value="{{ $item['value'] }}">
                                    </div>
                                </div>
                                @endforeach

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

            let form_data = $('#setting_form').serializeArray();

            let data = {};
            $.each(form_data, function() {
                data[this.name] = this.value;
            });

            axios.put(
                "/admin/common/configSave",
                data
            ).then(function (response) {
                layer.msg('保存成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
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