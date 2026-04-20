# FeedBook - Social Blogging Platform

A modern PHP-based blogging platform for sharing stories and connecting with readers.

## Features
- ✅ User registration and login (with bcrypt password hashing)
- ✅ Create, edit, and delete blog posts
- ✅ Categories for organizing content
- ✅ User profiles with edit capability
- ✅ Admin panel for managing users
- ✅ RESTful API endpoints
- ✅ Responsive design (works on mobile)
- ✅ CSRF protection

## Quick Setup (3 Steps)

### Step 1: Start XAMPP
Open XAMPP Control Panel and start **Apache** and **MySQL**.

### Step 2: Create the Database
Open your browser and go to:
```
http://localhost:8080/feedbook/setup.php
```
This will automatically create the database and tables.

### Step 3: Use the App
Go to:
```
http://localhost:8080/feedbook/
```
Register a new account and start blogging!

## Project Structure
```
feedbook/
├── index.php                  ← Main entry point (router)
├── setup.php                  ← Database setup script (run once)
├── .env                       ← Environment variables
│
├── backend/
│   ├── config/
│   │   ├── config.php         ← App settings (DB credentials, etc.)
│   │   └── database.php       ← Database connection class
│   ├── models/
│   │   ├── UserModel.php      ← User database queries
│   │   ├── PostModel.php      ← Post database queries
│   │   └── CategoryModel.php  ← Category database queries
│   ├── controllers/
│   │   ├── AuthController.php ← Login, register, logout logic
│   │   ├── PostController.php ← Post CRUD logic
│   │   ├── UserController.php ← User profile logic
│   │   └── CategoryController.php
│   ├── api/                   ← API endpoints (called by JavaScript)
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── posts.php
│   │   ├── create_post.php
│   │   ├── update_post.php
│   │   ├── delete_post.php
│   │   ├── me.php
│   │   ├── categories.php
│   │   ├── users.php
│   │   ├── update_profile.php
│   │   └── csrf.php
│   └── middleware/
│       ├── AuthMiddleware.php
│       └── CsrfMiddleware.php
│
├── frontend/
│   ├── pages/                 ← PHP pages (HTML + PHP)
│   │   ├── home.php
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── dashboard.php
│   │   ├── profile.php
│   │   ├── create-post.php
│   │   ├── edit-post.php
│   │   └── admin-users.php
│   ├── components/            ← Reusable page parts
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── sidebar.php
│   ├── css/
│   │   └── style.css          ← All styles
│   └── js/
│       └── app.js             ← All JavaScript
│
├── database/
│   └── schema.sql             ← Database table definitions
│
└── uploads/                   ← User uploaded files
```

## Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Architecture**: MVC (Model-View-Controller)
- **Server**: XAMPP (Apache + MySQL)

## How It Works (For Beginners)

1. **User visits** `http://localhost:8080/feedbook/`
2. **`index.php`** looks at the URL and decides which page to show
3. **Frontend pages** (in `frontend/pages/`) display the HTML
4. **JavaScript** (`app.js`) handles form submissions and sends data to the API
5. **API files** (in `backend/api/`) receive data and call the Controllers
6. **Controllers** call the Models to interact with the database
7. **Models** run SQL queries and return results
