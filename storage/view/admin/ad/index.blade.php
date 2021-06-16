@component('admin.component.head', ['title' => '推广列表'])
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
                            <div class="widget-title  am-cf">推广列表 <span style="color: #00b7ee;"> (推广仅在pc端显示) </span></div>
                        </div>
                        <div class="widget-body  am-fr">

                            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <button type="button" class="am-btn am-btn-success am-round"
                                                    onclick="location.href='/admin/common/adCreate'">
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
                                        <th>描述</th>
                                        <th>链接</th>
                                        <th>图片</th>
                                        <th>状态</th>
                                        <th>排序</th>
                                        <th>时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ads as $item)
                                        <tr class="gradeX">
                                            <td>{{ $item->desc }}</td>
                                            <td width="30"><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                                            <td>
                                                <img src="{{ $item->img_path }}" alt="" style="width: 150px;height: 70px;">
                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <button type="button" class="am-btn am-round am-btn-success is_publish">
                                                        显示
                                                    </button>
                                                @else
                                                    <button type="button" class="am-btn am-round am-btn-warning is_publish">
                                                        隐藏
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{ $item->sort }}</td>

                                            <td>{{ $item->updated_at->toDateString() }}</td>
                                            <td>
                                                <div class="tpl-table-black-operation">
                                                    <a href="{{ '/admin/common/adEdit?id=' . $item->id }}">
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
            axios.delete("/admin/common/adDelete?id=" + id)
                .then(function (response) {
                    layer.msg('删除成功！', {
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.location = '/admin/common/adIndex';
                        }
                    );
                })
                .catch(function (error) {
                    layer.msg(error.request.responseText, {
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            return false;
                        }
                    );
                });
        });
    }

    $(function () {

        $('.is_publish').attr('disabled', true);

    });
</script>
</body>
</html>
