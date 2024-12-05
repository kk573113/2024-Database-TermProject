<?php
$host = '192.168.56.101'; // 데이터베이스 호스트
$dbname = 'SWClubManagement'; // 데이터베이스 이름
$username = 'root'; // MySQL 사용자명
$password = '1234'; // MySQL 비밀번호

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
