# Discover Saudi Arabia 🇸🇦

An interactive Arabic-language cultural website introducing Saudi Arabia's regions, historical landmarks, and important places. Built as a course project using PHP, MySQL, HTML, CSS, and JavaScript.

---

## Pages

### Public
- **Home** — Introduction to Saudi Arabia with region preview cards and dark mode
- **Gallery** — Browse and filter all regions retrieved from the database
- **Details** — Full information, landmarks, activities, and image gallery for each region

### Admin Panel
- **Login** — Secure authentication with session management
- **Dashboard** — View, edit, and delete all content with confirmation dialogs
- **Add** — Add new regions with image upload
- **Update** — Edit existing region information

---

## Technologies

| Technology | Usage |
|---|---|
| PHP | Server-side logic, CRUD operations, session management |
| MySQL | Database for regions and admin credentials |
| HTML/CSS | Page structure and styling |
| JavaScript | Dark mode toggle, gallery filtering, delete confirmation |

---

## Setup

1. Clone the repository

2. Place the project folder inside `htdocs` (XAMPP) or `www` (WAMP)

3. Import the database
   - Open phpMyAdmin
   - Create a database named `saudi_web`
   - Import `saudi_web.sql`

4. Configure the database connection
   - Create a `config.php` file in the root folder:
   ```php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'saudi_web');

   $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   $conn->set_charset("utf8mb4");
   ?>
   ```

5. Visit `http://localhost/discover-saudi-arabia/`

---

## Admin Credentials

| Field | Value |
|---|---|
| Username | admin |
| Password | password |

---

## Project Structure

```
discover-saudi-arabia/
├── index.php          # Home page
├── gallery.php        # Regions gallery
├── details.php        # Region details
├── config.php         # Database config (not included)
├── saudi_web.sql      # Database schema and sample data
├── css/
│   ├── style.css      # Public pages styles
│   └── admin.css      # Admin panel styles
├── admin/
│   ├── login.php      # Admin login
│   ├── dashboard.php  # Admin dashboard
│   ├── add.php        # Add content
│   ├── update.php     # Update content
│   ├── logout.php     # Logout
│   └── auth_check.php # Session guard
└── uploads/           # Uploaded images (not included)
```
