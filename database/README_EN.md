[中文](../README.md)
[日本語](README_JA.md)

# Anime Theme Web Store
A simple online store themed around anime. The deployment process is straightforward, making it suitable for users who have difficulty applying for payment interfaces.

### Project Features
- **Easy Deployment**: Designed for quick onboarding, primarily for educational purposes.
- **Recommended Environment**:  
  - **Web Server**: `Apache 2.4.39`
  - **PHP Version**: `7.3.4nts`
  - **Database**: `MySQL 5.7.26`

### Usage Instructions
#### Windows Users
If manual configuration is cumbersome, you can use the XIAO PI panel for quick deployment.

#### Linux Users
Please set up the server environment according to your needs.

### Database Import
1. Create a database named `shop` in MySQL.
2. Import the `./database/shop.sql` file into the database.
3. Modify the database username and password, and update the relevant configurations in `./php/db.php`.

### Admin Panel
The admin management interface is located in the `admin` directory, and functionality is still under improvement; stay tuned for updates!

Test Account:
- Admin Username (default): 1024
- Admin Password (default): 123456

### Merchant Panel
The merchant management interface is located in `admin/Merchants`, and functionality is still under improvement; stay tuned for updates!
Merchant management includes product management (`index.html`), order management (`manager.html`), and automated task handling (`order_time_management.html`).
Automated tasks include detecting unpaid order timeouts (30 minutes) and preventing negative sales.

Test Account:
- Merchant Account: 1001 (or 1002, 1003)
- Merchant Password: 123456

### Client Side
1. Login page (password modification is not yet available due to the need for binding an email or phone number, so this feature is under discussion) `login.html`
2. Registration page (for account registration) `register.html`
3. Product page (main page displaying all products in random order) `index.html`
4. Product recommendation page (individual product recommendations) `host.html`
5. Product detail page (includes add to cart and direct purchase features) `commodity.html`
6. Merchant detail page (shows all products from a merchant) `merchantPage.html`
7. Product search page (allows keyword search for products) `search.html`
8. Cart page (displays all added items) `cart.html`
9. Account page (shows basic user information and features) `user.html`
10. Account modification page (for updating user information) `modify_user_information.html`
11. Order management page (view all orders) `orders.html`
12. Order detail page (view details of a specific order) `order_detail.html`
13. Create order page (purchase page) `create_order.html`

Test Account:
- Client Account: 1
- Client Password: 123456

## Notes
This project is for educational purposes only and may not be suitable for direct commercial operation. Please use with caution!