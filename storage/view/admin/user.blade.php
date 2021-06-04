@component('admin.component.head', ['title' => '用户编辑'])
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
                            <div class="widget-title am-fl">编辑信息</div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label for="name" class="am-u-sm-3 am-form-label">用户名 <small><span
                                                    style="color: red;">*</span></small>
                                        <span
                                                class="tpl-form-line-small-title">Name</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="name"
                                               name="name" required  placeholder="请输入用户名"
                                               value="{{ $user->name }}">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="email" class="am-u-sm-3 am-form-label">邮箱 <small><span
                                                    style="color: red;">*</span></small>
                                        <span
                                                class="tpl-form-line-small-title">Email</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="email" class="tpl-form-input" id="email"
                                               name="email" required  placeholder="请输入邮箱"
                                               value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="password" class="am-u-sm-3 am-form-label">密码  <span
                                                class="tpl-form-line-small-title">Password</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="password" class="tpl-form-input" id="password"
                                               name="password"  placeholder="请输入密码"
                                               value="">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="re_password" class="am-u-sm-3 am-form-label">再次输入密码 <span
                                                class="tpl-form-line-small-title">Password</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="password" class="tpl-form-input" id="re_password"
                                               name="re_password"   placeholder="请输入密码"
                                               value="">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="avatar" class="am-u-sm-3 am-form-label">头像 <span
                                                class="tpl-form-line-small-title">Avatar</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="avatar"
                                               name="avatar"  placeholder="将图片上传至图片管理，复制图片地址到这里即可"
                                               value="{{ $user->avatar }}">
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
            let name = $('#name').val();
            if (name.length === 0) {
                layer.msg('Name 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let email = $('#email').val();
            if (email.length === 0) {
                layer.msg('Email 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let password = $('#password').val();
            let pass_len = password.length;
            if (pass_len !== 0 && pass_len < 6) {
                layer.msg('Password 格式错误！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let re_password = $('#re_password').val();
            if (pass_len !== 0 && re_password !== password) {
                layer.msg('两次输入的密码不一致！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let avatar = $('#avatar').val();

            axios.put(
                "{{ url('/admin/users')}}",
                {
                    'name': name,
                    'email': email,
                    'password': password,
                    'avatar': avatar,
                }
            ).then(function (response) {
                layer.msg('修改成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        if (+response.status === 201) {
                            axios.delete("{{ url('/admin/logout') }}").then(function (response) {
                                layer.msg('请重新登录！', {
                                    }, function () {
                                        window.location = "{{ url('/admin/login') }}";
                                    }
                                );
                            });
                        }
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