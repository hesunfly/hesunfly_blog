@component('admin.component.head', ['title' => '页面列表'])
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
                            <div class="widget-title  am-cf">页面列表</div>
                        </div>
                        <div class="widget-body  am-fr">

                            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <button type="button" class="am-btn am-btn-success am-round"
                                                    onclick="location.href='{{ url('/admin/pages/create') }}'">
                                                <span class="am-icon-plus"></span> 新增
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black "
                                       id="example-r">
                                    <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>排序</th>
                                        <th>路由</th>
                                        <th>状态</th>
                                        <th>时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($pages as $item)
                                        <tr class="gradeX">
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->sort }}</td>
                                            <td>{{ $item->slug }}</td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span style="color: green;font-weight: bold;">显示</span>
                                                @else
                                                    <span style="color: #8c8c8c;font-weight: bold;">隐藏</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->toDateString() }}</td>
                                            <td>
                                                <div class="tpl-table-black-operation">
                                                    <a href="{{ url('/admin/pages/edit') . '/' . $item->id }}">
                                                        <i class="am-icon-pencil"></i> 编辑
                                                    </a>
                                                    <a href="javascript:;" onclick="destroy({{ $item->id }})"
                                                       class="tpl-table-black-operation-del">
                                                        <i class="am-icon-trash"></i> 删除
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

<script>
    function destroy(id) {
        if (!id) {
            return false;
        }

        layer.confirm('确定删除吗？', {
            title: '⚠️',
            btn: ['删除', '取消'] //按钮
        }, function () {
            axios.delete("{{ url('/admin/pages/destroy') }}" + '/' + id)
                .then(function (response) {
                    layer.msg('删除成功！', {
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.location = "{{ url('/admin/pages/') }}";
                        }
                    );
                })
                .catch(function (error) {
                    layer.msg('error！', {
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            return false;
                        }
                    );
                });
        });
    }
</script>
</body>
</html>
