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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        h1 {
            background-color: #11264f;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #11264f;
            border-bottom: 2px solid #11264f;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #11264f;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        button:hover {
            background-color: #0d1a37;
        }
        .link-button {
            text-decoration: none;
            display: inline-block;
        }
        .back-button {
          margin-left: 20px;
          margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>교수 추가</h1>
    <!-- 뒤로가기 버튼 -->
    <div class="back-button">
        <a href="index.php" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <form method="post" action="add_pf.php">
            <label for="Pf_id">교수 ID:</label>
            <input type="number" id="Pf_id" name="Pf_id" required>

            <label for="Name">이름:</label>
            <input type="text" id="Name" name="Name" required>

            <label for="Phone_number">전화번호:</label>
            <input type="text" id="Phone_number" name="Phone_number" required>

            <label for="Major">전공:</label>
            <input type="text" id="Major" name="Major" required>

            <button type="submit">추가</button>
        </form>
    </div>
</body>
</html>
