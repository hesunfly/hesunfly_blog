@component('component.head', ['title' => config('app.blog_name')])
@endcomponent

@component('component.header', ['pages' => [], 'keyword' => ''])
@endcomponent
<div style="text-align: center !important; margin-top: 100px;box-sizing: border-box;">
    <div style="font-weight: bold;font-size: 177px;box-sizing: border-box;">404</div>
    <div style="font-size: 50px;box-sizing: border-box;">Page Not Found</div>
</div>

<footer style="position: absolute;
            bottom: 0;
            height:100px;
            margin-top: -100px;width: 100%;">
    <div class="border-t border-lighter mt-20" style="margin-top: 0px;">
        <div class="container mx-auto px-5 lg:max-w-screen">
            <div class="text-muted py-10 text-center">
                Copyright © <a href="{{ config('app.url') }}" style="text-decoration: none">Hesunfly</a> |
                <a href="http://www.beian.miit.gov.cn" style="text-decoration: none">{{ config('app.icp_number') }}</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
