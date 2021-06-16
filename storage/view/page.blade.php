@component('component.head', ['title' => $page->title])
@endcomponent
@component('component.header', ['keyword' => ''])
@endcomponent
<script src="/assets/admin/js/highlight.min.js"></script>
<link rel="stylesheet" href="/assets/admin/css/github.min.css">
<div class="container mx-auto px-5 lg:max-w-screen-sm" style="padding-bottom: 100px;margin-bottom: 27px;">
    <h1 class="mb-5 font-sans">{{ $page->title }}</h1>

    <div class="flex items-center text-sm text-light">
        <span>最后编辑于 {{ $page->created_at->toDateString() }} </span>
    </div>

    <div class="mt-5 leading-loose flex flex-col justify-center items-center post-body font-serif">
        {!! $page->html_content !!}
    </div>
</div>

@component('component.footer')
@endcomponent