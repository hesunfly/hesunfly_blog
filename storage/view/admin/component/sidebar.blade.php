<div class="left-sidebar" style="min-height: 82%;">
    <!-- 菜单 -->
    <ul class="sidebar-nav">
        <li class="sidebar-nav-heading">文章</li>
        @php
        $uri = make(\Hyperf\HttpServer\Contract\RequestInterface::class)->getRequestUri();
        @endphp
        <li class="sidebar-nav-link">
            <a href="/admin/article/create" @if ($uri == '/admin/article/create') class="active" @endif >
                <i class="am-icon-edit sidebar-nav-link-logo"></i> 写作
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/admin/article" @if ( $uri != '/admin/article/create' && mb_strstr($uri, '/admin/article')) class="active" @endif >
                <i class="am-icon-file-text-o sidebar-nav-link-logo"></i> 文章
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/admin/category" @if (mb_strstr($uri, '/admin/category')) class="active" @endif>
                <i class="am-icon-folder-o sidebar-nav-link-logo"></i> 分类
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/admin/page" @if (mb_strstr($uri, '/admin/page')) class="active" @endif>
                <i class="am-icon-file-code-o sidebar-nav-link-logo"></i> 页面
            </a>
        </li>

        <li class="sidebar-nav-heading">资源</li>
        <li class="sidebar-nav-link">
            <a href="/admin/images" @if (mb_strstr($uri, '/admin/images')) class="active" @endif>
                <i class="am-icon-file-image-o sidebar-nav-link-logo"></i> 图片
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/admin/common/visitRecord" @if (mb_strstr($uri, '/admin/common/visitRecord')) class="active" @endif>
                <i class="am-icon-list sidebar-nav-link-logo"></i> 记录
            </a>
        </li>

        <li class="sidebar-nav-link">
            <a href="/admin/subscribes'" @if (mb_strstr($uri, '/admin/subscribes')) class="active" @endif>
                <i class="am-icon-feed sidebar-nav-link-logo"></i> 订阅
            </a>
        </li>

        <li class="sidebar-nav-heading">应用</li>
        <li class="sidebar-nav-link">
            <a href="/admin/settings" @if (mb_strstr($uri, '/admin/settings')) class="active" @endif>
                <i class="am-icon-cog sidebar-nav-link-logo"></i> 配置
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="/admin/ads" @if (mb_strstr($uri, '/admin/ads')) class="active" @endif>
                <i class="am-icon-bullhorn sidebar-nav-link-logo"></i> 推广
            </a>
        </li>

    </ul>
</div>