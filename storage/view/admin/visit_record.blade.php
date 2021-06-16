@component('admin.component.head', ['title' => '访问记录列表'])
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
    <div class="tpl-content-wrapper">
        <div class="row-content am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                    <div class="widget am-cf">
                        <div class="widget-head am-cf">
                            <div class="widget-title  am-cf">访问记录</div>
                        </div>
                        <div class="widget-body  am-fr">
                            <div class="am-u-sm-12">
                                <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black "
                                       id="example-r">
                                    <thead>
                                    <tr>
                                        <th>ip</th>
                                        <th>地址</th>
                                        <th>uri</th>
                                        <th>时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($record as $item)
                                        <tr class="gradeX">
                                            <td>{{ $item->ip }}</td>
                                            <td>{{ $item->address ?: '未知' }}</td>
                                            <td>{{ $item->uri }}</td>
                                            <td>{{ $item->created_at->toDatetimeString() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="am-u-lg-12 am-cf">

                                <div class="am-cf">
                                    <ul class="am-pagination am-pagination-centered">
                                        @if ($record->currentPage() != 1)
                                            <li class=""><a href="{{ $record->previousPageUrl() }}">«</a></li>
                                        @endif
                                        <li class="am-active"><a href="javascript:;">{{ $record->currentPage() }}</a>
                                        </li>
                                        @if ($record->lastPage() != $record->currentPage())
                                            <li><a href="{{ $record->nextPageUrl() }}" style="margin-left: 5px;">»</a>
                                            </li>
                                        @endif
                                        <li> 共 {{ $record->lastPage() }} 页</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@component('admin.component.foot')
@endcomponent


</body>
</html>
