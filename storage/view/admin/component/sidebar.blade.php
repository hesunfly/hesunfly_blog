<div class="left-sidebar" style="min-height: 82%;">
    <!-- 菜单 -->
    <ul class="sidebar-nav">
        <li class="sidebar-nav-heading">文章</li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/articles/write') }}" @if ($uri == '/admin/articles/write') class="active" @endif >
                <i class="am-icon-edit sidebar-nav-link-logo"></i> 写作
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/articles') }}" @if ( $uri != '/admin/articles/write' && mb_strstr($uri, '/admin/articles')) class="active" @endif >
                <i class="am-icon-file-text-o sidebar-nav-link-logo"></i> 文章
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/categories') }}" @if (mb_strstr($uri, '/admin/categories')) class="active" @endif>
                <i class="am-icon-folder-o sidebar-nav-link-logo"></i> 分类
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/pages') }}" @if (mb_strstr($uri, '/admin/pages')) class="active" @endif>
                <i class="am-icon-file-code-o sidebar-nav-link-logo"></i> 页面
            </a>
        </li>

        <li class="sidebar-nav-heading">资源</li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/images') }}" @if (mb_strstr($uri, '/admin/images')) class="active" @endif>
                <i class="am-icon-file-image-o sidebar-nav-link-logo"></i> 图片
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/ips') }}" @if (mb_strstr($uri, '/admin/ips')) class="active" @endif>
                <i class="am-icon-list sidebar-nav-link-logo"></i> 记录
            </a>
        </li>

        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/subscribes') }}" @if (mb_strstr($uri, '/admin/subscribes')) class="active" @endif>
                <i class="am-icon-feed sidebar-nav-link-logo"></i> 订阅
            </a>
        </li>

        <li class="sidebar-nav-heading">应用</li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/settings') }}" @if (mb_strstr($uri, '/admin/settings')) class="active" @endif>
                <i class="am-icon-cog sidebar-nav-link-logo"></i> 配置
            </a>
        </li>
        <li class="sidebar-nav-link">
            <a href="{{ url('/admin/ads') }}" @if (mb_strstr($uri, '/admin/ads')) class="active" @endif>
                <i class="am-icon-bullhorn sidebar-nav-link-logo"></i> 推广
            </a>
        </li>

    </ul>
</div>