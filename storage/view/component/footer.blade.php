</div>
<style>
    a {
        text-decoration: none;
    }
</style>
<footer style="height:100px;margin-top: -100px;">
    <div class="border-t border-lighter mt-20" style="margin-top: 0px;">
        <div class="container mx-auto px-4 lg:max-w-screen">
            <div class="text-muted py-4 text-center">
                @if (isDesktop())
                    <div style="margin-bottom: 25px;">
                        {{--@if (count($ads = \App\Services\CacheService::getAds()) > 0)
                            @foreach($ads as $item)
                            <div style="display: inline-block;">
                                <a target="_blank" style="display: inline-block;" href="{{ $item->url }}">
                                    <img src="{{ $item->img_path }}" style="width: 150px;height: 70px;" alt="" />
                                    <br>
                                    <span style="font-size: 0.7rem;margin-bottom: 0.5rem;">{{ $item->desc }}</span>
                                </a>
                            </div>
                            @endforeach
                            @endif--}}
                    </div>
                @endif

                <div style="font-size: 0.8rem;">
                    <input type="email" id="email-value" name="email" value="" placeholder="输入您的邮箱订阅我吧！"
                           style="background-color: #F5FFFA;">
                    <button type="button" id="subscribe"
                            style="margin-left:1rem;border: 1px solid #ececec;border-radius:1000px;background-color: #F5FFFA;padding: .1em .4em;">
                        订阅
                    </button>
                </div>
                <div class="py-2">
                    <span><a href="mailto:{{ config('app.email') }}"><i
                                    class="fa fa-envelope"></i></a></span>
                    <span style="display: inline-block; width: 0.5rem;"></span>
                    <a href="{{ config('app.github') }}">
                        <span><i class="fa fa-github"></i></span>
                    </a>
                </div>
                <p class="py-2" style="font-size: 0.7rem;">
                    Copyright © <a href="http://hesunfly.com" style="text-decoration: none">Hesunfly</a> |
                    <a href="http://www.beian.miit.gov.cn"
                       style="text-decoration: none">{{ config('app.icp_number') }}</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<script>
    $(function () {
        $('#subscribe').click(function () {
            let email = $('#email-value').val();

            if (email.length === 0) {
                layer.msg('请输入邮箱地址后订阅！');
                return false;
            }

            let reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则表达式

            if (!reg.test(email)) {
                layer.msg('请输入正确的邮箱地址');
            }

            axios.post(
                "/subscribes",
                {
                    'email': email,
                }
            ).then(function (response) {
                layer.msg('已向您的邮箱发送了确认邮件，请确认后订阅生效！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
            }).catch(function (error) {
                if (error.request.status === 422) {
                    let msg = JSON.parse(error.request.responseText);
                    layer.msg(msg.errors.email[0], {
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                        }
                    );
                    return false;
                }

                layer.msg('error! ', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {

                    }
                );
            });
        });
    });
</script>

</body>
</html>