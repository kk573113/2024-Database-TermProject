<?php
include 'config.php'; // PDO 연결

// URL에서 Club_id 가져오기
$club_id = $_GET['Club_id'];

// 동아리 목록 조회 (현재 동아리는 제외)
$sql_all_clubs = "SELECT * FROM CLUB WHERE Club_id != :club_id";
$stmt_all_clubs = $pdo->prepare($sql_all_clubs);
$stmt_all_clubs->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_all_clubs->execute();
$all_clubs = $stmt_all_clubs->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 합류 동아리 처리
    if (isset($_POST['merge_club_id'])) {
        $merge_club_id = $_POST['merge_club_id'];

        // 멤버 테이블 업데이트: 동아리 1의 인원을 동아리 2로 이동
        $sql_merge_members = "UPDATE MEMBER SET Club_id = :merge_club_id WHERE Club_id = :club_id";
        $stmt_merge_members = $pdo->prepare($sql_merge_members);
        $stmt_merge_members->bindParam(':merge_club_id', $merge_club_id, PDO::PARAM_INT);
        $stmt_merge_members->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_merge_members->execute();

        // 동아리 1 삭제
        $sql_delete_club = "DELETE FROM CLUB WHERE Club_id = :club_id";
        $stmt_delete_club = $pdo->prepare($sql_delete_club);
        $stmt_delete_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_club->execute();

        header("Location: club_details.php?Club_id=$merge_club_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 합류</title>
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
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #11264f;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0d1a37;
        }
        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>동아리 합류</h1>
    <div class="container">
        <div class="section">
            <h2>합류할 동아리 선택</h2>
            <form method="POST" action="">
                <label for="merge_club_id">합류할 동아리를 선택하세요:</label>
                <select name="merge_club_id" id="merge_club_id" required>
                    <option value="">동아리를 선택하세요</option>
                    <?php foreach ($all_clubs as $other_club): ?>
                        <option value="<?php echo $other_club['Club_id']; ?>">
                            <?php echo htmlspecialchars($other_club['Name']); ?> (ID: <?php echo $other_club['Club_id']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">합류하기</button>
            </form>
            <div class="back-button">
                <a href="club_details.php?Club_id=<?php echo $club_id; ?>">
                    <button type="button">뒤로가기</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
