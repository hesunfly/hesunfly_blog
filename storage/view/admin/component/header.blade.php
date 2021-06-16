<header>
    <!-- logo -->
    <div class="am-fl tpl-header-logo">
        <a href="/admin"><img src="/assets/images/Hesunfly-Blog-Logo.png" alt="" style="height: 55px;width: auto;"></a>
    </div>
    <!-- 右侧内容 -->
    <div class="tpl-header-fluid">
        <!-- 侧边切换 -->
        <div class="am-fl tpl-header-switch-button am-icon-list">
                    <span>
                </span>
        </div>
        <!-- 搜索 -->
        <!-- 其它功能-->
        <div class="am-fr tpl-header-navbar">
            <ul>
                <li class="am-text-sm">
                    <div class="tpl-user-panel-profile-picture">
                        <img src="/assets/images/avatar.jpg" alt="" class="am-img-bdrs" >
                    </div>
                </li>
                <li class="am-text-sm">
                    <a href="/auth/user/edit" style="padding: 0 5px;">
                        <span style="color: #0C0C0C">{{ make(\Qbhy\HyperfAuth\AuthManager::class)->user()->user_name }}</span>
                    </a>
                </li>
                <!-- 退出 -->
                <li class="am-text-sm">
                    <a href="javascript:;" onclick="logout()">
                        <span class="am-icon-sign-out"></span> 退出
                    </a>
                </li>
            </ul>
        </div>
    </div>

</header>

<script>
    function logout() {
        axios.delete("/auth/logout").then(function (response) {
            layer.msg('注销成功', {
                }, function () {
                    window.location = "/auth/login";
                }
            );
        }).catch(function (error) {
            layer.msg('error！', {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                }
            );
        });
    }
</script>