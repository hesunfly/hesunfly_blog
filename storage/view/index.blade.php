@component('component.head', ['title' => env('APP_NAME')])
@endcomponent

@component('component.header', ['pages' => [], 'keyword' => $keyword ?? ''])
@endcomponent

<div class="container mx-auto px-5 lg:max-w-screen-sm" style="padding-bottom: 100px;">
    @if (count($articles) !== 0)
    @foreach( $articles as $item)
        <a class="no-underline transition block border border-lighter w-full mb-4 p-3 rounded post-card"
           href="{{ 'article?slug=' . $item->slug }}" style="background-color: #F5FFFA;">
            <div class="flex flex-col justify-between flex-1">
                <div>
                    <h2 class="font-sans block leading-normal mb-1" style="font-size: 1.25rem;">
                        {{ $item->title }}
                    </h2>

                    <p class="mb-1 leading-normal font-serif leading-loose" style="font-size: 1rem;">
                        {{ $item->description }}
                    </p>
                </div>

                <div class="flex items-center text-sm text-light">
                    <span style="margin-left: 5px;"> <i class="fa fa-folder"></i> {{ $item->category->title }}</span>
                    &nbsp;&nbsp;
                    <span class="ml-2"> <i class="fa fa-eye"></i> {{ $item->view_count }} </span>
                    <span class="ml-auto">{{ $item->publish_at}}</span>
                </div>
            </div>
        </a>
    @endforeach
    <div class="uppercase flex items-center justify-center flex-1 font-sans" style="padding-bottom: 1rem;">
        @if ($articles->currentPage() != 1)
            <a href="{{ $articles->previousPageUrl() }}" rel="next"
               class="block no-underline text-light hover:text-black px-5">上一页</a>
            <a href="{{ $articles->previousPageUrl() }}" rel="next"
               class="block no-underline text-light hover:text-black px-5">{{ $articles->currentPage() - 1 }}</a>
        @else
            <a href="javascript:;" rel="next"
               class="block no-underline text-light hover:text-black px-5"></a>
        @endif
            <span class="px-5">{{ $articles->currentPage() }}</span>
        @if ($articles->lastPage() != $articles->currentPage())
                <a href="{{ $articles->nextPageUrl() }}" rel="next"
                   class="block no-underline text-light hover:text-black px-5">{{ $articles->currentPage() + 1 }}</a>
            <a href="{{ $articles->nextPageUrl() }}" rel="next"
               class="block no-underline text-light hover:text-black px-5">下一页</a>
        @else
            <a href="javascript:;" rel="next"
               class="block no-underline text-light hover:text-black px-5"></a>
        @endif
            <span class="px-5">共 {{ $articles->total() }} 篇文章</span>
    </div>
    @else
        <div style="font-size:2.5rem;width: 50%;text-align:center;position: absolute;top: 50%;margin-top: -50px;height: 100px;">
            抱歉，暂时没有相关文章！
        </div>
    @endif

</div>

@component('component.footer')
@endcomponent



