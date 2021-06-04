@component('admin.component.head', ['title' => '登录'])
@endcomponent

<body data-type="login">
<script src="/assets/admin/js/theme.js"></script>
<div class="am-g tpl-g">
    <!-- 风格切换 -->
    @component('admin.component.skin')
    @endcomponent
    <div class="tpl-login">
        <div class="tpl-login-content">
            <div class="tpl-login-logo">
            </div>
            <form class="am-form tpl-form-line-form">
                <div class="am-form-group">
                    <input type="text" class="tpl-form-input" id="name" name="name" placeholder="请输入账号">
                </div>
                <div class="am-form-group">
                    <input type="password" class="tpl-form-input" id="password" name="password"
                           placeholder="请输入密码">
                </div>
                {{--<div class="am-form-group tpl-login-remember-me">
                    <input id="remember-me" type="checkbox">
                    <label for="remember-me">
                        记住密码
                    </label>
                </div>--}}
                <div class="am-form-group">
                    <button type="button" id="submit"
                            class="am-btn am-btn-primary  am-btn-block tpl-btn-bg-color-success  tpl-login-btn">提交
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@component('admin.component.foot')
@endcomponent
<script src="/assets/base64-min.js"></script>
<script>
    $(function () {
        $('#submit').click(function () {
            let name = $('#name').val();
            let password = $('#password').val();
            if (name.length === 0) {
                layer.msg('账号 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            if (password.length < 6) {
                layer.msg('密码 格式错误！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            password = encryptObj.base64encode(password);

            axios.post(
                "{{ url('/admin/login') }}",
                {
                    'name': name,
                    'password': password,
                }
            ).then(function (response) {
                layer.msg('登录成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        window.location = "{{ url('/admin/') }}";
                    }
                );
            }).catch(function (error) {
                layer.msg('error！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
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