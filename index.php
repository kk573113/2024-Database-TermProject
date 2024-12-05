<?php
include 'config.php'; // PDO 연결

// 동아리 데이터 조회 쿼리
$sql = "SELECT Club_id, Name, Interest FROM CLUB";
$stmt = $pdo->prepare($sql); // PDO Statement 준비
$stmt->execute(); // 쿼리 실행
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 결과 가져오기
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 리스트</title>
</head>
<body>
    <h1>동아리 리스트</h1>
    <table border="1">
        <tr>
            <th>동아리 ID</th>
            <th>동아리 이름</th>
            <th>관심 분야</th>
            <th>자세히 보기</th>
        </tr>
        <?php
        if ($result) {
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
</body>
</html>
