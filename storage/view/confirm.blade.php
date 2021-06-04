@component('component.head', ['title' => env('APP_NAME')])
@endcomponent

@component('component.header', ['pages' => \App\Services\CacheService::getPages(), 'keyword' => ''])
@endcomponent
<style>
    .errorCssParent{
        font-size: 62.5%;
    }
    .errorCss{
        font-size: 1.8rem;
        box-sizing: border-box;
        width: 50%;
        color: #333;
        text-align: center;
        position: absolute;
        left: 25%;
    }

</style>
<div class="errorCssParent" style="text-align: center !important; margin-top: 100px;box-sizing: border-box;">
    <div class="errorCss">{{ $msg }}</div>
</div>

<footer style="position: absolute;
            bottom: 0;
            height:100px;
            margin-top: -100px;width: 100%;">
    <div class="border-t border-lighter mt-20" style="margin-top: 0px;">
        <div class="container mx-auto px-5 lg:max-w-screen">
            <div class="text-muted py-10 text-center">
                Copyright © <a href="http://hesunfly.com" style="text-decoration: none">Hesunfly</a> |
                <a href="http://www.beian.miit.gov.cn" style="text-decoration: none">陕ICP备17022875号</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
