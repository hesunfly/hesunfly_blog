@component('component.head', ['title' => $article->title])
@endcomponent
@component('component.header', ['pages' => [], 'keyword' => ''])
@endcomponent
<script src="/assets/admin/js/highlight.min.js"></script>
<link rel="stylesheet" href="/assets/admin/css/github.min.css">
<div class="container mx-auto px-5 lg:max-w-screen-sm" style="padding-bottom: 100px;margin-bottom: 27px;">
    <h1 class="mb-5 font-sans">{{ $article->title }}</h1>

    <div class="flex items-center text-sm text-light">
        <span>{{ $article->publish_at }}</span>
        <span style="margin-left: 1rem"><i class="fa fa-folder"></i> {{ $article->category->title }}</span>
        <span style="margin-left: 1rem"><i class="fa fa-eye"></i> {{ $article->view_count }}</span>
        @if ($auth)
            <span style="margin-left: 2rem">
                <a href="{{ '/admin/articles/edit/?id=' . $article->id }}" target="_blank"
                   style="text-decoration: none;">
                编辑文章
                </a>
            </span>
        @endif
    </div>


    <div class="mt-5 leading-loose flex flex-col justify-center post-body font-serif">
        {!! $article->html_content !!}
    </div>
    <div style="text-align: center;">
        @if (isDesktop())
            @if (!empty($article->qr_path))
                <div STYLE="text-align: center;display: inline-block;">
                    <div style="margin-left: auto;margin-right: auto;display: inline-block;">
                        <img src="{{ $article->qr_path }}" alt="手机扫码浏览" title="手机扫码浏览"
                             style="display: block;width: 160px;height: 160px;margin-bottom: 15px;">
                        <span style="">手机扫码查看</span>
                    </div>
                </div>
            @endif
        @endif
        <div STYLE="text-align: center;display: inline-block;">
            <div style="margin-left: auto;margin-right: auto;display: inline-block;">
                <img src="{{ config('app.reward_code') }}" alt="赞赏码" title="微信扫码赞赏"
                     style="display: block;width: 170px;height: 170px;margin-bottom: 15px;">
                <span style="">{{ config('app.reward_desc') }}</span>
            </div>
        </div>
    </div>
</div>


<script>
    //将文章的标题显示在url地址中
    $(function () {
        let current_url = window.location.href;
        let article_title = '{{ str_replace(' ', '', $article->title) }}';
        let url = current_url + '&title=' + article_title;
        window.history.pushState(null, null, url)
    });
</script>

@component('component.footer')
@endcomponent