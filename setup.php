<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/backend/config/config.php';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');

$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$db_name = DB_NAME;

echo "<html><head><title>FeedBook Setup</title><style>body{font-family:'Segoe UI',sans-serif;max-width:600px;margin:40px auto;padding:20px;background:#f8f9fa}h2{color:#1a1a2e}.ok{color:#10b981}.err{color:#ef4444}a{color:#6366f1;font-weight:600}</style></head><body>";
echo "<h2>📖 FeedBook — Database Setup</h2>";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='ok'>✓ Database connection ready ($db_name)</p>";

    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    $conn->exec("DROP TABLE IF EXISTS comments, posts, categories, users");
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");

    $conn->exec("CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','editor','user') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p class='ok'>✓ Users table</p>";

    $conn->exec("CREATE TABLE categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p class='ok'>✓ Categories table</p>";

    $conn->exec("CREATE TABLE posts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        category_id INT,
        title VARCHAR(255) NOT NULL,
        content LONGTEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
        INDEX idx_user (user_id),
        INDEX idx_category (category_id),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p class='ok'>✓ Posts table</p>";

    $conn->exec("CREATE TABLE comments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        post_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "<p class='ok'>✓ Comments table</p>";

    $conn->exec("INSERT INTO categories (name) VALUES ('Technology'),('Travel'),('Food'),('Lifestyle'),('Business'),('Health & Wellness'),('Finance'),('Education'),('Productivity'),('Science'),('Programming'),('Startups'),('Design'),('Sports'),('Entertainment'),('Books'),('Personal Development')");
    echo "<p class='ok'>✓ Categories seeded</p>";

    $pw = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->exec("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@feedbook.com', '$pw', 'admin')");
    echo "<p class='ok'>✓ Admin user created</p>";

    echo "<hr><h3 class='ok'>✓ Setup Complete!</h3>";
    echo "<p><b>Admin:</b> admin@feedbook.com / admin123</p>";
    echo "<p><a href='" . $basePath . "/'>→ Open FeedBook</a></p>";
} catch (PDOException $e) {
    echo "<p class='err'>Error: " . $e->getMessage() . "</p>";
}
echo "</body></html>";
?>
