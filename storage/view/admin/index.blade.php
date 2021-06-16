@component('admin.component.head', ['title' => 'Hesunfly Blog'])
@endcomponent
<script src="/assets/admin/js/echarts.min.js"></script>
<body data-type="index">
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
        <div class="container-fluid am-cf">
            <div class="row">
                <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">
                    <div class="page-header-heading"><span class="am-icon-dashboard page-header-heading-icon"></span> 仪表盘
                        <small>Dashboard</small></div>
                </div>
            </div>

        </div>

        <div class="row-content am-cf">
            <div class="row  am-cf">
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
                    <div class="widget widget-primary am-cf" onclick="window.location.href='/admin/article'">
                        <div class="widget-statistic-header" style="font-size: 27px;">
                            文章数量
                        </div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">
                                {{ $article_count }}
                            </div>

                            <span class="widget-statistic-icon am-icon-file-text-o"></span>
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
                    <div class="widget widget-primary am-cf" onclick="window.location.href='admin/image'">
                        <div class="widget-statistic-header" style="font-size: 27px;">
                            图片数量
                        </div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">
                                {{ $image_count }}
                            </div>

                            <span class="widget-statistic-icon am-icon-file-image-o"></span>
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-md-6 am-u-lg-4">
                    <div class="widget widget-primary am-cf" onclick="window.location.href='/admin/common/visitRecord'">
                        <div class="widget-statistic-header" style="font-size: 27px;">
                            浏览次数
                        </div>
                        <div class="widget-statistic-body">
                            <div class="widget-statistic-value">
                                {{ $visit_count }}
                            </div>
                            <span class="widget-statistic-icon am-icon-eye"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row am-cf">
                <div class="am-u-sm-12 am-u-md-12">
                    <div class="widget am-cf">
                        <div class="widget-head am-cf">
                            <div class="widget-title am-fl">{{ $year . ' 年文章发布统计' }}</div>
                            <div class="widget-function am-fr">
                                <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                                    <div class="am-form-group tpl-table-list-select">
                                        <select data-am-selected="{btnSize: 'sm'}" id="year_select">

                                            @foreach($years as $item)
                                                <option value="{{ $item }}"
                                                        @if ($year == $item) selected @endif>{{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="widget-body-md widget-body tpl-amendment-echarts am-fr" id="tpl-echarts">

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
        $('#year_select').change(function () {
            window.location.href = '/admin?year=' + $('#year_select').val();
        });
    });

    let eCharts = echarts.init(document.getElementById('tpl-echarts'));
    option = {
        tooltip: {
            trigger: 'axis'
        },
        grid: {
            top: '3%',
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: [{
            type: 'category',
            boundaryGap: false,
            data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        }],
        yAxis: [{
            type: 'value'
        }],
        textStyle: {
            color: '#838FA1'
        },
        series: [{
            name: '文章数',
            type: 'line',
            stack: '总量',
            areaStyle: { normal: {} },
            data: JSON.parse("{{ $statistics }}"),
            itemStyle: {
                normal: {
                    color: '#1cabdb',
                    borderColor: '#1cabdb',
                    borderWidth: '2',
                    borderType: 'solid',
                    opacity: '1'
                },
                emphasis: {

                }
            }
        }]
    };

    eCharts.setOption(option);
</script>
</body>

</html>