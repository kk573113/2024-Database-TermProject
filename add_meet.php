<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST 요청으로부터 데이터 가져오기
    $date = $_POST['Date'];
    $place = $_POST['Place'];
    $agenda = $_POST['Agenda'];
    $pf_id = $_POST['Pf_id'];

    // 데이터베이스에 미팅 추가
    $sql = "INSERT INTO MEETING (Date, Place, Agenda, Pf_id) 
            VALUES (:date, :place, :agenda, :pf_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':place', $place, PDO::PARAM_STR);
    $stmt->bindParam(':agenda', $agenda, PDO::PARAM_STR);
    $stmt->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<p>미팅이 성공적으로 추가되었습니다!</p>";
    } else {
        echo "<p>미팅 추가에 실패했습니다.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미팅 추가</title>
</head>
<body>
    <h1>미팅 추가</h1>
    <form method="post" action="add_meet.php">
        <label for="Date">날짜:</label><br>
        <input type="date" id="Date" name="Date" required><br>

        <label for="Place">장소:</label><br>
        <input type="text" id="Place" name="Place" required><br>

        <label for="Agenda">안건:</label><br>
        <input type="text" id="Agenda" name="Agenda" required><br>

        <label for="Pf_id">교수 ID:</label><br>
        <input type="number" id="Pf_id" name="Pf_id" required><br><br>

        <button type="submit">추가</button>
    </form>
    <a href="index.php">뒤로가기</a>
</body>
</html>
