[中文](../README.md)
[日本語](README_JA.md)

# AnimeThemeWebStore

A simple mobile online store themed around anime. This project is easy to deploy and is especially suitable for users who find it difficult to apply for payment interfaces.

## Project Features
### Project Features
- **Easy Deployment**: Designed for users to quickly get started, primarily for educational purposes.
- **Recommended Environment**:  
  - **Web Server**: `Apache 2.4.39`
  - **PHP Version**: `7.3.4nts`
  - **Database**: `MySQL 5.7.26`

## Usage Instructions
### Windows Users
If manual configuration seems cumbersome, you can use the Little Skin Panel for quick deployment.

### Linux Users
Please set up the server environment according to your needs.

### Database Import
#### Database Import
1. Create a database named `shop` in MySQL.
2. Import the `./database/shop.sql` file into that database.
3. Modify the database username and password, and update the relevant configurations in the `./php/db.php` file.

### Merchant Side
The merchant management interface is located in the `admin` directory. The features are still under development, so please look forward to future updates! The merchant management includes product management (`index.html`), order management (`manager.html`), and an automatic task processing page (`order_time_management.html`). Automatic tasks include checking for unpaid orders that have timed out (half an hour), and preventing sales from going negative.

### Client Side
1. **Login Page**: (Currently, password modification is not available. Registration requires binding an email or phone number, which is under discussion) `login.html`
2. **Registration Page**: (For account registration) `register.html`
3. **Product Page**: (The main page displaying all products in random order) `index.html`
4. **Product Recommendation Page**: (One product at a time recommendation) `host.html`
5. **Product Detail Page**: (Includes add to cart and buy now features) `commodity.html`
6. **Merchant Detail Page**: (Displays all products from the merchant) `merchantPage.html`
7. **Product Search Page**: (Allows searching for products by keywords) `search.html`
8. **Cart Page**: (Displays all products added to the cart) `cart.html`
9. **Account Page**: (Displays basic user information and features) `user.html`
10. **Account Modification Page**: (For modifying user information) `modify_user_information.html`
11. **Order Management Page**: (View all orders) `orders.html`
12. **Order Detail Page**: (View details of a specific order) `order_detail.html`
13. **Create Order Page**: (The purchasing page) `create_order.html`

## Notes
### Notes
This project is for educational purposes only and may not be suitable for direct commercial use. Please use it with caution!