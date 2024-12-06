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
</head>
<body>
    <h1>교수 상세 정보</h1>

    <!-- 교수 정보 -->
    <h2>교수 정보</h2>
    <form method="POST" action="">
        <table border="1">
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
        <button type="submit" name="edit_professor">수정하기</button>
    </form>
    <a href="index.php">돌아가기</a>
</body>
</html>
