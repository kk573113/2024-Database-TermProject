<?php
include 'config.php'; // PDO 연결

$result = []; // 초기화
try {
    // 동아리 데이터 조회 쿼리
    $sql = "SELECT Club_id, Name, Interest FROM CLUB";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "데이터 조회 실패: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>충북대학교 소프트웨어학부 동아리 관리 시스템</title>
</head>
<body>
    <h1>충북대학교 소프트웨어학부 동아리 관리 시스템</h1>
    <h2>동아리 목록</h2>
    <table border="1">
        <tr>
            <th>동아리 ID</th>
            <th>동아리 이름</th>
            <th>관심 분야</th>
            <th>자세히 보기</th>
        </tr>
        <?php
        if (!empty($result)) {
            foreach ($result as $row) {
                echo "<tr>
                    <td>{$row['Club_id']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['Interest']}</td>
                    <td><a href='club_details.php?Club_id={$row['Club_id']}'>자세히 보기</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>동아리가 없습니다.</td></tr>";
        }
        ?>
    </table>
    <a href="add_club.php">동아리 추가</a> <!-- 동아리 추가 링크 -->
</body>
</html>
