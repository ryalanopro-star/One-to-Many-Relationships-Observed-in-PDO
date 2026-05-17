<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'restaurant_system');
define('DB_USER', 'root');       
define('DB_PASS', '');           
define('DB_CHARSET', 'utf8mb4');


function getPDO(): PDO
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        
        PDO::ATTR_EMULATE_PREPARES   => false,                   
    ];

    try {
        // Create and return the PDO connection
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // If connection fails, stop and show a clean error message
        die('<div style="font-family:system-ui;padding:2rem;color:#c00;">
                <strong>Database Connection Failed:</strong><br>' . $e->getMessage() . '
             </div>');
    }
}
