@component('component.head', ['title' => env('APP_NAME')])
@endcomponent

@component('component.header', ['keyword' => ''])
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


@component('component.footer')
@endcomponent
