@component('admin.component.head', ['title' => '图片列表'])
@endcomponent
<link rel="stylesheet" href="/assets/webuploader/webuploader.css">
<script src="/assets/webuploader/webuploader.js"></script>
<style>
    .images-div {
        width: 20%;
        display: inline-block;
        margin: 25px;

    }

    .images-item {
        width: 100%;
        margin-bottom: 10px;
    }

    .button-div {
        display: flex;
        justify-content: space-around;
        width: 70%;
        margin: auto;
    }
    .file-item {
        display: inline-block;
        margin: 25px;
    }

</style>
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
                            <div class="widget-title  am-cf">图片列表</div>
                        </div>
                        <div class="widget-body  am-fr">

                            <div class="am-u-sm-12 am-u-md-6 am-u-lg-6" style="width: 100%;display: -webkit-flex;  -webkit-justify-content: center;
   -webkit-align-items: center;">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                            <div id="uploader-demo">
                                                <!--用来存放item-->
                                                <div id="fileList" class="uploader-list" style="height: 270px;"></div>
                                                <div id="filePicker" style="text-align: center">选择图片</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-u-sm-12">
                                @foreach ($images as $item)
                                    <div class="images-div">
                                        <img src="{{ $item['path'] }}" class="images-item" alt="">
                                        <div class="button-div">
                                            <input type="text" style="display: block;position: fixed;top: -1000px;"
                                                   name="" id="copy_path_{{$item['id']}}" value="{{ $item['path'] }}">
                                            <button type="button" class="am-btn am-btn-secondary am-round"
                                                    style="margin-right: 30px;" onclick="copy({{$item['id']}})">
                                                复制
                                            </button>
                                            <button type="button" class="am-btn am-btn-danger am-round"
                                                    onclick="destroy({{ $item['id'] }})">删除
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="am-u-lg-12 am-cf">

                                <div class="am-cf">
                                    <ul class="am-pagination am-pagination-centered">
                                        @if ($images->currentPage() != 1)
                                            <li class=""><a href="{{ $images->previousPageUrl() }}">«</a></li>
                                        @endif
                                        <li class="am-active"><a href="javascript:;">{{ $images->currentPage() }}</a>
                                        </li>
                                        @if ($images->lastPage() != $images->currentPage())
                                            <li><a href="{{ $images->nextPageUrl() }}" style="margin-left: 5px;">»</a>
                                            </li>
                                        @endif
                                        <li> 共 {{ $images->lastPage() }} 页</li>
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
    // 初始化Web Uploader
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: '/assets/webuploader/Uploader.swf',

        // 文件接收服务端。
        server: "{{ url('/admin/images/upload') }}",

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        fileVal: 'image',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }

    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function (file) {
        var $li = $(
            '<div id="' + file.id + '" class="file-item thumbnail">' +
            '<img>' +
            '<div class="info">' + file.name + '</div>' +
            '</div>'
            ),
            $img = $li.find('img');


        // $list为容器jQuery实例
        $list = $('#fileList');
        $list.append($li);

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        let thumbnailWidth = 200;
        let thumbnailHeight = 200;
        uploader.makeThumb(file, function (error, src) {
            if (error) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr('src', src);
        }, thumbnailWidth, thumbnailHeight);
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file, percentage) {
        var $li = $('#' + file.id),
            $percent = $li.find('.progress span');

        // 避免重复创建
        if (!$percent.length) {
            $percent = $('<p class="progress"><span></span></p>')
                .appendTo($li)
                .find('span');
        }

        $percent.css('width', percentage * 100 + '%');
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on('uploadSuccess', function (file) {
        $('#' + file.id).addClass('upload-state-done');
        layer.msg('上传成功！', {time: 1000});
        window.location.reload();
    });

    // 文件上传失败，显示上传出错。
    uploader.on('uploadError', function (file) {
        var $li = $('#' + file.id),
            $error = $li.find('div.error');

        // 避免重复创建
        if (!$error.length) {
            $error = $('<div class="error"></div>').appendTo($li);
        }

        layer.msg('上传失败！', {time: 2000});
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on('uploadComplete', function (file) {
        $('#' + file.id).find('.progress').remove();
    });
</script>
<script>
    function destroy(id) {
        if (!id) {
            return false;
        }

        layer.confirm('确定删除吗？', {
            title: '⚠️',
            btn: ['删除', '取消'] //按钮
        }, function () {
            axios.delete("{{ url('/admin/images/destroy') }}" + '/' + id)
                .then(function (response) {
                    layer.msg('删除成功！', {
                            icon: 1,
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.location.reload();
                        }
                    );
                })
                .catch(function (error) {
                    layer.msg('error！', {
                            icon: 2,
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            return false;
                        }
                    );
                });
        });
    }

    function copy(id) {
        let path = $("#copy_path_" + id);
        console.log(path.select()[0].defaultValue);
        if (document.execCommand('copy', false, null)) {
            layer.msg('复制成功');
            return true;
        } // 执行浏览器复制命令

        layer.msg('复制失败');
    }
</script>
</body>
</html>
