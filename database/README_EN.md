[中文](../README.md)
[日本語](README_JA.md)

# AnimeThemeWebStore
A simple online store themed around anime, this project is easy to deploy, making it suitable for users who find it difficult to apply for payment interfaces.

### Project Features
- **Easy Deployment**: Designed for quick setup, primarily for educational purposes.
- **Recommended Environment**:  
  - **Web Server**: `Apache 2.4.39`
  - **PHP Version**: `7.3.4nts`
  - **Database**: `MySQL 5.7.26`

### Usage Instructions
#### For Windows Users
If manual configuration seems troublesome, you can use the Xiaopi panel for quick deployment.

#### For Linux Users
Please set up your server environment according to your needs.

### Database Import
1. Create a database named `shop` in MySQL.
2. Import the `./database/shop.sql` file into that database.
3. Modify the database username and password, updating the relevant configuration in `./php/db.php`.

### Admin Interface
The admin management interface is located in the `admin` directory. Features are still being improved, so stay tuned for updates!

### Merchant Interface
The merchant management interface is found in `admin/Merchants`. Features are also under development. The management is divided into product management (`index.html`), order management (`manager.html`), and automated task handling (`order_time_management.html`). Automated tasks include timeout detection for unpaid orders (half an hour) and preventing negative sales.

### Client Interface
1. Login Page (currently does not allow password changes; email or phone binding for unregistered users is under discussion) `login.html`
2. Registration Page (for account creation) `register.html`
3. Product Page (the main page displaying all products in random order) `index.html`
4. Product Recommendation Page (one product at a time) `host.html`
5. Product Detail Page (includes add to cart and direct purchase functions) `commodity.html`
6. Merchant Detail Page (displays all products from the merchant) `merchantPage.html`
7. Product Search Page (search for products using keywords) `search.html`
8. Cart Page (all products added to the cart) `cart.html`
9. Account Page (displays basic user information and functions) `user.html`
10. Account Modification Page (modify user information) `modify_user_information.html`
11. Order Management Page (view all orders) `orders.html`
12. Order Detail Page (view contents of a specific order) `order_detail.html`
13. Create Order Page (purchase page) `create_order.html`

## Notes
This project is for educational purposes only and may not be suitable for direct commercial use. Please use it with caution!