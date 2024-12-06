<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POST 요청으로부터 데이터 가져오기
    $pf_id = $_POST['Pf_id'];
    $name = $_POST['Name'];
    $phone_number = $_POST['Phone_number'];
    $major = $_POST['Major'];

    // 데이터베이스에 교수 추가
    $sql = "INSERT INTO PROFESSOR (Pf_id, Name, Phone_number, Major) 
            VALUES (:pf_id, :name, :phone_number, :major)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt->bindParam(':major', $major, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<p>교수가 성공적으로 추가되었습니다!</p>";
    } else {
        echo "<p>교수 추가에 실패했습니다.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>교수 추가</title>
</head>
<body>
    <h1>교수 추가</h1>
    <form method="post" action="add_pf.php">
        <label for="Pf_id">교수 ID:</label><br>
        <input type="number" id="Pf_id" name="Pf_id" required><br>

        <label for="Name">이름:</label><br>
        <input type="text" id="Name" name="Name" required><br>

        <label for="Phone_number">전화번호:</label><br>
        <input type="text" id="Phone_number" name="Phone_number" required><br>

        <label for="Major">전공:</label><br>
        <input type="text" id="Major" name="Major" required><br><br>

        <button type="submit">추가</button>
    </form>
    <a href="index.php">돌아가기</a>
</body>
</html>
