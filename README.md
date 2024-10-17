# AnimeThemeWebStore

<div>
  <button onclick="document.getElementById('chinese').scrollIntoView();">中文</button>
  <button onclick="document.getElementById('english').scrollIntoView();">English</button>
  <button onclick="document.getElementById('japanese').scrollIntoView();">日本語</button>
</div>

## 中文

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

#### 数据库导入
1. 在 MySQL 中创建一个名为 `shop` 的数据库。
2. 将 `./database/shop.sql` 文件导入该数据库。
3. 修改数据库账号和密码，更新 `./php/db.php` 文件中的相关配置。

### 注意事项
此项目仅为教学用途，可能不适合直接用于商业运营。请谨慎使用！

---

## English

### Project Features
- **Easy Deployment**: Aimed at allowing users to quickly get started, primarily intended for educational purposes.
- **Recommended Environment**:  
  - **Web Server**: `Apache 2.4.39`
  - **PHP Version**: `7.3.4nts`
  - **Database**: `MySQL 5.7.26`

### Usage Instructions
#### For Windows Users
If you find manual configuration cumbersome, you can use XAMPP to quickly deploy.

#### For Linux Users
Please set up your server environment according to your needs.

#### Database Import
1. Create a database named `shop` in MySQL.
2. Import the `./database/shop.sql` file into that database.
3. Modify the database username and password in `./php/db.php` to update the relevant configurations.

### Notes
This project is intended for educational purposes only and may not be suitable for direct commercial use. Please use it with caution!

---

## 日本語

### プロジェクトの特徴
- **簡易デプロイ**：ユーザーが迅速に始められるように設計されており、主に教育目的で使用されます。
- **推奨環境**：  
  - **Webサーバー**：`Apache 2.4.39`
  - **PHPバージョン**：`7.3.4nts`
  - **データベース**：`MySQL 5.7.26`

### 使用手順
#### Windowsユーザー向け
手動設定が面倒だと感じる場合は、XAMPPを使用して迅速にデプロイできます。

#### Linuxユーザー向け
ニーズに応じてサーバー環境を設定してください。

#### データベースのインポート
1. MySQLで`shop`という名前のデータベースを作成します。
2. `./database/shop.sql`ファイルをそのデータベースにインポートします。
3. データベースのユーザー名とパスワードを`./php/db.php`で更新します。

### 注意事項
このプロジェクトは教育目的のみを対象としており、直接商業利用には適さない場合があります。慎重に使用してください！