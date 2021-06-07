@component('admin.component.head', ['title' => '文章列表'])
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
                            <div class="widget-title  am-cf">文章列表</div>
                        </div>
                        <div class="widget-body  am-fr">

                            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <button type="button" class="am-btn am-btn-success am-round"
                                                    onclick="location.href='/admin/articles/write'">
                                                <span class="am-icon-plus"></span> 新增
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                                <div class="am-form-group tpl-table-list-select">
                                    <select data-am-selected="{btnSize: 'sm'}" id="category_select">
                                        <option value=" " @if ($category_id === ' ') selected @endif>所有类别</option>
                                        @foreach($categories as $item)

                                            <option value=" "
                                                    @if ($category_id === $item->id) selected @endif>{{ $item->category_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="am-u-sm-12 am-u-md-12 am-u-lg-3">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                    <input type="text" class="am-form-field " id="keyword" value="{{ $keyword }}">
                                    <span class="am-input-group-btn">
            <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search"
                    type="button" id="search"></button>
          </span>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black "
                                       id="example-r">
                                    <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>分类</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($articles as $item)
                                        <tr class="gradeX">
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category->category_title }}</td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span style="color: green;font-weight: bold;">已发布</span>
                                                @else
                                                    <span style="color: #8c8c8c;font-weight: bold;">未发布</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <div class="tpl-table-black-operation">
                                                    <a href="{{ '/articles/' . '/' . $item->slug}}" target="_blank"
                                                       style="border: 1px solid orange;color: orange;">
                                                        <i class="am-icon-eye"></i> 查看
                                                    </a>
                                                    <a href="{{ '/admin/articles/edit') . '/' . $item->id}}">
                                                        <i class="am-icon-pencil"></i> 编辑
                                                    </a>
                                                    <a href="javascript:;" onclick="destroy('{{ $item->id }}')"
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
                            <div class="am-u-lg-12 am-cf">

                                <div class="am-cf">
                                    <ul class="am-pagination am-pagination-centered">
                                        @if ($articles->currentPage() != 1)
                                            <li class=""><a href="{{ $articles->previousPageUrl() }}">«</a></li>
                                        @endif
                                        <li class="am-active"><a href="javascript:;">{{ $articles->currentPage() }}</a>
                                        </li>
                                        @if ($articles->lastPage() != $articles->currentPage())
                                            <li><a href="{{ $articles->nextPageUrl() }}" style="margin-left: 5px;">»</a>
                                            </li>
                                        @endif
                                        <li> 共 {{ $articles->lastPage() }} 页</li>
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

<script>
    $(function () {
        $('#search').click(function () {
            search();
        });

        $('#category_select').change(function () {
            search();
        });
    });

    function search() {
        let category = $('.am-selected-status').text();
        let keyword = $('#keyword').val();
        let url = "{{ 'admin/articles/search' }}" + '/' + category + '/' + keyword;
        window.location.href = url;
    }

    function destroy(id) {
        if (!id) {
            return false;
        }

        layer.confirm('确定删除吗？', {
            title: '⚠️',
            btn: ['删除', '取消'] //按钮
        }, function () {
            axios.delete("{{ '/admin/articles/destroy' }}" + '/' + id)
                .then(function (response) {
                    layer.msg('删除成功！', {
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.location = "{{ '/admin/articles/' }}";
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
