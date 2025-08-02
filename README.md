<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

# Coffee Shop REST API

A Laravel-based backend API for a Coffee Shop website that allows users to view menus, place orders, and reserve tables.

---

## Features

- **Menu browsing:** Guests can view product categories and menu items without logging in.  
- **User authentication:** JWT-based login and registration system.  
- **User roles:**  
  - **Guests:** Can browse menus but cannot place orders or reserve tables.  
  - **Members:** Must create an account and log in to place orders and reserve tables.  
  - **Admins:** Full access to manage products, categories, tables, and orders with CRUD operations.  
- **Table reservations:** Logged-in users can select and reserve tables.  
- **Order management:** Users can add items to orders, modify them, and submit orders.  
- **Authorization:** Route protection using JWT and middleware based on user roles (admin/member/guest).

---

## Getting Started

### Prerequisites

- PHP >= 12 
- Composer  
- MySQL or compatible database  
- WAMP/LAMP/XAMPP or equivalent local server  

### Installation

1. Clone the repository:

   ```bash
   git clone git@github.com:Pouya-arsalany/coffee-shop-rest-api.git
   cd coffee-shop-rest-api
Install dependencies:

bash
Copy
Edit
composer install
Copy the .env.example file to .env and configure your database and JWT settings.

Generate application key:

bash
Copy
Edit
php artisan key:generate
Run database migrations and seeders:

bash
Copy
Edit
php artisan migrate --seed
Serve the application:

bash
Copy
Edit
php artisan serve
API Documentation
You can find the API endpoints and test them using the included Postman collection:

Postman collection file: postman/coffee_shop.postman_collection.json

Import this collection into Postman to explore and test the API.

Authentication
Authentication is handled with JWT tokens.

Use /register and /login endpoints to obtain a token.

Protect routes by passing the JWT token in the Authorization header as Bearer <token>.

Contributing
This is a personal project, but feel free to open issues or submit pull requests if you find bugs or want to add improvements.

License
This project is open source and available under the MIT License.


