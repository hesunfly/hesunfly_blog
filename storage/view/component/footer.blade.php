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
                        @if (count($ads = make(\App\Service\CacheService::class)->getAds()) > 0)
                            @foreach($ads as $item)
                                <div style="display: inline-block;">
                                    <a target="_blank" style="display: inline-block;" href="{{ $item->url }}">
                                        <img src="{{ $item->image_path }}" style="width: 150px;height: 70px;" alt=""/>
                                        <br>
                                        <span style="font-size: 0.7rem;margin-bottom: 0.5rem;">{{ $item->desc }}</span>
                                    </a>
                                </div>
                            @endforeach
                        @endif
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
                    <span><a href="mailto:{{ make(\App\Service\CacheService::class)->getConfig('email') }}"><i
                                    class="fa fa-envelope"></i></a></span>
                    <span style="display: inline-block; width: 0.5rem;"></span>
                    <a href="{{ make(\App\Service\CacheService::class)->getConfig('github') }}">
                        <span><i class="fa fa-github"></i></span>
                    </a>
                </div>
                <p class="py-2" style="font-size: 0.7rem;">
                    Copyright © <a href="{{ env('APP_URL') }}" style="text-decoration: none">Hesunfly</a> |
                    <a href="http://www.beian.miit.gov.cn"
                       style="text-decoration: none">{{ make(\App\Service\CacheService::class)->getConfig('icp_record') }}</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="/js/openUrlNewTab.js"></script>

<script>
    $(function () {
        $('#subscribe').click(function () {
            let email = $('#email-value').val();

            if (email.length === 0) {
                layer.msg('请输入邮箱地址后订阅！');
                return false;
            }

            axios.post(
                "/subscribe",
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
                layer.msg(error.request.responseText, {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return false;
            });
        });
    });
</script>

</body>
</html>