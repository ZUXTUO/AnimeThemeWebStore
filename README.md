[English](database/README_EN.md)
[日本語](database/README_JA.md)

# AnimeThemeWebStore
动漫主题风格的简易手机线上商城，本项目的部署过程简单，相对来讲比较适合那些难以申请支付接口的用户。

### 项目特点
- **简易部署**：旨在让用户快速上手，主要还是在教育方面使用。
- **推荐环境**：  
  - **Web 服务器**：`Apache 2.4.39`
  - **PHP 版本**：`7.3.4nts`
  - **数据库**：`MySQL 5.7.26`

### 使用说明
#### Windows 用户
如果觉得手动配置麻烦，可以使用小皮面板来快速部署。

#### Linux 用户
请根据自己的需求自行设置服务器环境。

### 数据库导入
1. 在 MySQL 中创建一个名为 `shop` 的数据库。
2. 将 `./database/shop.sql` 文件导入该数据库。
3. 修改数据库账号和密码，更新 `./php/db.php` 文件中的相关配置。

### 商户端
商户管理界面位于 `admin`里，功能尚在完善中，敬请期待后续更新！
商户管理分为商品管理`index.html`和订单管理`manager.html`，以及用于自动处理任务的`order_time_management.html`。
自动处理的任务包括未支付订单超时检测（半小时），以及防止销量出现负数等。
### 客户端
1. 登录页面（暂时还不可以修改密码，为没有注册需要绑定的邮箱或手机号等，所以这块功能有待商议）`login.html`
2. 注册页面（用于注册账号）`register.html`
3. 商品页（也就是主页面，随机顺序展示所有商品）`index.html`
4. 商品推荐页（一个一个商品推荐）`host.html`
5. 商品详情页（包含添加购物车和直接购买功能）`commodity.html`
6. 商户详情页（展示商户的所有商品）`merchantPage.html`
7. 商品搜索页（可以通过关键词搜索商品）`search.html`
8. 购物车页面（所有添加到购物车的商品）`cart.html`
9. 账号页面（展示基本用户信息和功能）`user.html`
10. 账号修改页面（修改用户信息）`modify_user_information.html`
11. 订单管理页面（查看所有订单）`orders.html`
12. 订单详情页面（查看某笔订单内容）`order_detail.html`
13. 创建订单页面（也就是购买页面）`create_order.html`
## 注意事项
此项目仅为教学用途，可能不适合直接用于商业运营。请谨慎使用！