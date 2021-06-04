@component('admin.component.head', ['title' => '页面编辑'])
@endcomponent
<link rel="stylesheet" href="/assets/admin/css/simplemde.min.css">
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
    <!-- 内容区域 -->
    <div class="tpl-content-wrapper">
        <div class="row-content am-cf">
            <div class="row">

                <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                    <div class="widget am-cf">
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">编辑页面</div>
                        </div>
                        <div class="widget-body am-fr">

                            <form class="am-form tpl-form-line-form">
                                <div class="am-form-group">
                                    <label for="title" class="am-u-sm-3 am-form-label">页面标题 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Title</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="title" id="title" value="{{ $page->title }}" autofocus
                                               placeholder="请输入页面标题">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="slug" class="am-u-sm-3 am-form-label">页面路由 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">Slug</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" id="slug" name="slug"
                                               placeholder="请输入页面路由" value="{{ $page->slug }}">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="slug" class="am-u-sm-3 am-form-label">排序 <span
                                                style="color: red;">*</span> <span
                                                class="tpl-form-line-small-title">sort</span></label>
                                    <div class="am-u-sm-9">
                                        <input type="number" class="tpl-form-input" id="sort" name="sort"
                                               placeholder="请输入排序数字，数字越小越靠前" value="{{ $page->sort }}">
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="content" class="am-u-sm-3 am-form-label">页面内容 <span style="color: red;">*</span>
                                        <span
                                                class="tpl-form-line-small-title">Content</span></label>
                                    <div class="am-u-sm-9">
                                            <textarea class="" id="content" name="content" data-save-id="{{ $page->slug }}"
                                            >{{ $page->content }}</textarea>
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <label for="status" class="am-u-sm-3 am-form-label">发布 <span
                                                class="tpl-form-line-small-title">Status</span></label>
                                    <div class="am-u-sm-9">
                                        @if ($page->status == 1)
                                            <input type="radio" name="status" id="status-yes" value="1"
                                                   class="am-radio-inline" checked> 发布

                                            <input type="radio" name="status" id="status-no" value="-1"
                                                   class="am-radio-inline"> 草稿
                                        @else
                                            <input type="radio" name="status" id="status-yes" value="1"
                                                   class="am-radio-inline"> 发布

                                            <input type="radio" name="status" id="status-no" value="-1" checked
                                                   class="am-radio-inline"> 草稿
                                        @endif
                                    </div>
                                </div>


                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="button"
                                                class="am-btn am-btn-primary tpl-btn-bg-color-success " id="submit">
                                            提交
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@component('admin.component.foot')
@endcomponent
<script src="/assets/admin/js/simplemde.min.js"></script>
<script src="/assets/admin/js/codemirror-4.inline-attachment.js"></script>
<script src="/assets/admin/js/highlight.min.js"></script>
<link rel="stylesheet" href="/assets/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/admin/css/github.min.css">
<script>
    $(function () {
        let target = $('#content');

        let simplemde = new SimpleMDE({
            autoDownloadFontAwesome: undefined,
            autosave: {
                enabled: true,
                uniqueId: target.data('save-id'),
                delay: 1000,
            },
            element: document.getElementById('content'),
            insertTexts: {
                image: ["![](", ")"],
                link: ["[", "]()"],
            },
            renderingConfig: {
                codeSyntaxHighlighting: true,
            },
            spellChecker: true,
            toolbar: ["bold", "italic", "strikethrough", "heading", "|", "quote", 'code', 'ordered-list',
                'unordered-list', 'horizontal-rule', 'link',
                'image',
                'table',
                '|', 'preview', 'side-by-side', 'fullscreen'],
        });
        inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
            uploadUrl: "{{ url('/admin/images/upload') }}",
            uploadFieldName: 'image',
            extraParams: {},
        });

        $('.editor-toolbar').click(function () {
            if (simplemde.isFullscreenActive()) {
                $('.left-sidebar').hide();
                $('header').hide();

            } else {
                $('.left-sidebar').show();
                $('header').show();
            }
        });

        $('#submit').click(function () {
            let title = $('#title').val();
            if (title.length === 0) {
                layer.msg('Title 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let slug = $('#slug').val();
            if (slug.length === 0) {
                layer.msg('Slug 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }
            let sort = $('#sort').val();
            if (sort.length === 0) {
                layer.msg('Sort 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let content = simplemde.value();
            if (content.length === 0) {
                layer.msg('Content 为必填项！', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                    }
                );
                return;
            }

            let html_content = simplemde.markdown(content);

            let status = $("input[name='status']:checked").val();

            axios.put(
                "{{ url('/admin/pages/save') . '/' . $id }}",
                {
                    'title': title,
                    'slug': slug,
                    'sort': sort,
                    'content': content,
                    'html_content': html_content,
                    'status': status
                }
            ).then(function (response) {
                layer.msg('修改成功！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        window.location = "{{ url('/admin/pages') }}";
                    }
                );
            }).catch(function (error) {
                layer.msg('error！', {
                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                        return false;
                    }
                );
            });
        });
    });

</script>

</body>

</html>