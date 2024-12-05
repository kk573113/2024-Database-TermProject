<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST 요청으로부터 데이터 가져오기
    $club_id = $_POST['Club_id'];
    $name = $_POST['Name'];
    $club_room = $_POST['Club_room'];
    $interest = $_POST['Interest'];
    $pf_id = $_POST['Pf_id'];

    // 데이터베이스에 동아리 추가
    $sql = "INSERT INTO CLUB (Club_id, Name, Club_room, Interest, Pf_id) 
            VALUES (:club_id, :name, :club_room, :interest, :pf_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':club_room', $club_room, PDO::PARAM_STR);
    $stmt->bindParam(':interest', $interest, PDO::PARAM_STR);
    $stmt->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<p>동아리가 성공적으로 추가되었습니다!</p>";
    } else {
        echo "<p>동아리 추가에 실패했습니다.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 추가</title>
</head>
<body>
    <h1>동아리 추가</h1>
    <form method="post" action="add_club.php">
        <label for="Club_id">동아리 ID:</label><br>
        <input type="number" id="Club_id" name="Club_id" required><br>

        <label for="Name">동아리 이름:</label><br>
        <input type="text" id="Name" name="Name" required><br>

        <label for="Club_room">동아리 방:</label><br>
        <input type="text" id="Club_room" name="Club_room"><br>

        <label for="Interest">관심 분야:</label><br>
        <input type="text" id="Interest" name="Interest" required><br>

        <label for="Pf_id">교수 ID:</label><br>
        <input type="number" id="Pf_id" name="Pf_id" required><br><br>

        <button type="submit">추가</button>
    </form>
    <a href="index.php">돌아가기</a>
</body>
</html>
