<?php
include 'config.php'; // PDO 연결

// URL에서 Pf_id 가져오기
$pf_id = $_GET['Pf_id'];

// 교수 정보 조회
$sql_professor = "SELECT * FROM PROFESSOR WHERE Pf_id = :pf_id";
$stmt_professor = $pdo->prepare($sql_professor);
$stmt_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
$stmt_professor->execute();
$professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);

// 미팅 정보 조회
$sql_meetings = "SELECT * FROM MEETING WHERE Pf_id = :pf_id";
$stmt_meetings = $pdo->prepare($sql_meetings);
$stmt_meetings->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
$stmt_meetings->execute();
$meetings = $stmt_meetings->fetchAll(PDO::FETCH_ASSOC);

// 교수 정보 수정 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_professor'])) {
    $name = $_POST['Name'];
    $phone_number = $_POST['Phone_number'];
    $major = $_POST['Major'];

    $sql_update_professor = "UPDATE PROFESSOR SET Name = :name, Phone_number = :phone_number, Major = :major WHERE Pf_id = :pf_id";
    $stmt_update_professor = $pdo->prepare($sql_update_professor);
    $stmt_update_professor->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt_update_professor->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt_update_professor->bindParam(':major', $major, PDO::PARAM_STR);
    $stmt_update_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
    $stmt_update_professor->execute();
    header("Location: prof_details.php?Pf_id=$pf_id");
    exit;
}

// 미팅 추가 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_meeting'])) {
    $date = $_POST['Date'];
    $place = $_POST['Place'];
    $agenda = $_POST['Agenda'];

    $sql_insert_meeting = "INSERT INTO MEETING (Date, Place, Agenda, Pf_id) VALUES (:date, :place, :agenda, :pf_id)";
    $stmt_insert_meeting = $pdo->prepare($sql_insert_meeting);
    $stmt_insert_meeting->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt_insert_meeting->bindParam(':place', $place, PDO::PARAM_STR);
    $stmt_insert_meeting->bindParam(':agenda', $agenda, PDO::PARAM_STR);
    $stmt_insert_meeting->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
    $stmt_insert_meeting->execute();
    header("Location: prof_details.php?Pf_id=$pf_id");
    exit;
}

// 미팅 삭제 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_meeting'])) {
    $meet_id = $_POST['Meet_id'];

    $sql_delete_meeting = "DELETE FROM MEETING WHERE Meet_id = :meet_id";
    $stmt_delete_meeting = $pdo->prepare($sql_delete_meeting);
    $stmt_delete_meeting->bindParam(':meet_id', $meet_id, PDO::PARAM_INT);
    $stmt_delete_meeting->execute();
    header("Location: prof_details.php?Pf_id=$pf_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>교수 상세 정보</title>
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
            max-width: 800px;
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #11264f;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        button {
            background-color: #11264f;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
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
    <h1>교수 상세 정보</h1>
    <div class="back-button">
        <a href="index.php" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <?php if ($professor): ?>
            <h2>교수 정보</h2>
            <form method="POST" action="">
                <table>
                    <tr>
                        <th>교수 ID</th>
                        <td><?php echo htmlspecialchars($professor['Pf_id']); ?></td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td><input type="text" name="Name" value="<?php echo htmlspecialchars($professor['Name']); ?>" required></td>
                    </tr>
                    <tr>
                        <th>전화번호</th>
                        <td><input type="text" name="Phone_number" value="<?php echo htmlspecialchars($professor['Phone_number']); ?>" required></td>
                    </tr>
                    <tr>
                        <th>전공</th>
                        <td><input type="text" name="Major" value="<?php echo htmlspecialchars($professor['Major']); ?>" required></td>
                    </tr>
                </table>
                <button type="submit" name="edit_professor">수정</button>
            </form>
        <?php else: ?>
            <p>해당 교수 정보를 찾을 수 없습니다.</p>
        <?php endif; ?>
    </div>
</body>
</html>
