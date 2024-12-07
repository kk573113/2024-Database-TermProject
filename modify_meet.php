<?php
include 'config.php'; // PDO 연결

if (!isset($_GET['Meet_id'])) {
    echo "<script>
            alert('회의 ID가 제공되지 않았습니다.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$meet_id = $_GET['Meet_id'];

// 회의 정보 가져오기
$sql_meeting = "SELECT * FROM MEETING WHERE Meet_id = :meet_id";
$stmt_meeting = $pdo->prepare($sql_meeting);
$stmt_meeting->bindParam(':meet_id', $meet_id, PDO::PARAM_INT);
$stmt_meeting->execute();
$meeting = $stmt_meeting->fetch(PDO::FETCH_ASSOC);

if (!$meeting) {
    echo "<script>
            alert('해당 회의 정보를 찾을 수 없습니다.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

// 회의 삭제 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_meeting'])) {
    $sql_delete = "DELETE FROM MEETING WHERE Meet_id = :meet_id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindParam(':meet_id', $meet_id, PDO::PARAM_INT);

    if ($stmt_delete->execute()) {
        echo "<script>
                alert('회의가 성공적으로 삭제되었습니다.');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('회의 삭제 중 오류가 발생했습니다.');
              </script>";
    }
}

// 회의 수정 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_meeting'])) {
    $date = $_POST['Date'];
    $place = $_POST['Place'];
    $agenda = $_POST['Agenda'];
    $pf_id = $_POST['Pf_id'];

    $sql_update = "UPDATE MEETING SET Date = :date, Place = :place, Agenda = :agenda, Pf_id = :pf_id WHERE Meet_id = :meet_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt_update->bindParam(':place', $place, PDO::PARAM_STR);
    $stmt_update->bindParam(':agenda', $agenda, PDO::PARAM_STR);
    $stmt_update->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
    $stmt_update->bindParam(':meet_id', $meet_id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        echo "<script>
                alert('회의가 성공적으로 수정되었습니다.');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('회의 수정 중 오류가 발생했습니다.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회의 수정/삭제</title>
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
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
        }
        .section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #11264f;
            border-bottom: 2px solid #11264f;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
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
        .actions {
            display: flex;
            justify-content: flex-start; 
            gap: 10px; 
        }
        .link-button {
            text-decoration: none;
            display: inline-block;
        }
        .back-button {
            margin-left: 20px;
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            button {
                font-size: 12px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <h1>회의 수정/삭제</h1>
    <div class="back-button">
        <a href="index.php" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <div class="section">
            <h2>회의 정보</h2>
            <form method="POST" action="">
                <label for="Date">날짜:</label>
                <input type="date" id="Date" name="Date" value="<?php echo htmlspecialchars($meeting['Date']); ?>" required><br>

                <label for="Place">장소:</label>
                <input type="text" id="Place" name="Place" value="<?php echo htmlspecialchars($meeting['Place']); ?>" required><br>

                <label for="Agenda">안건:</label>
                <input type="text" id="Agenda" name="Agenda" value="<?php echo htmlspecialchars($meeting['Agenda']); ?>" required><br>

                <label for="Pf_id">교수 ID:</label>
                <input type="number" id="Pf_id" name="Pf_id" value="<?php echo htmlspecialchars($meeting['Pf_id']); ?>" required><br>

                <div class="actions">
                    <button type="submit" name="update_meeting">수정</button>
                    <button type="submit" name="delete_meeting" onclick="return confirm('정말로 삭제하시겠습니까?');">삭제</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
