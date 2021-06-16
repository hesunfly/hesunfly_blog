
## 项目概述
hesunfly-blog 是一个简洁的博客应用，使用 hyperf 框架编写而成，具有博客的基本功能, 包括文章发布，独立页面发布，邮件订阅，广告推广等功能。

## 运行环境要求

- PHP(需要的扩展参考 hyperf 的文档即可) [hyperf 文档传送门](https://hyperf.wiki/2.1/#/zh-cn/quick-start/install)
- Mysql(5.7+)
- Redis

## 开发环境部署/安装

本项目代码使用 PHP 框架 [Hyperf](https://hyperf.wiki/2.1/#/) 开发，基本的运行环境可参考 Hyperf 文档。

### 基础安装

#### 1. 克隆源代码

克隆本项目源代码到本地：
```shell
git clone https://gitee.com/hesunfly/hyperf_blog.git
```

#### 3. 安装扩展包依赖
```shell
composer install
```

#### 4. 生成配置文件
```
cp .env.example .env
```

你可以根据情况修改 `.env` 文件里的内容，如数据库连接、缓存、邮件设置等：

```
APP_URL=http://localhost  (务必正确配置)
```

#### 5. 生成数据表及生成测试数据
```shell
php bin/hyperf.php migrate
```

#### 6. 用户和配置初始化
进入 `/init` 路由进行项目初始化

### 链接入口

* 管理后台：`/admin`

至此, 安装完成 ^_^。

登录后台后，可以在配置栏填写适用您的信息。

