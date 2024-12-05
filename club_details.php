<?php
include 'config.php'; // PDO 연결

// URL에서 Club_id 가져오기
$club_id = $_GET['Club_id'];

// 동아리 정보 조회
$sql_club = "SELECT * FROM CLUB WHERE Club_id = :club_id";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_club->execute();
$club = $stmt_club->fetch(PDO::FETCH_ASSOC);

// 예산 정보 조회
$sql_budget = "SELECT * FROM BUDGET WHERE Club_id = :club_id";
$stmt_budget = $pdo->prepare($sql_budget);
$stmt_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_budget->execute();
$budget = $stmt_budget->fetch(PDO::FETCH_ASSOC);

// 멤버 정보 조회
$sql_members = "SELECT * FROM MEMBER WHERE Club_id = :club_id";
$stmt_members = $pdo->prepare($sql_members);
$stmt_members->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_members->execute();
$members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

// 교수 정보 조회
$sql_professor = "SELECT * FROM PROFESSOR WHERE Pf_id = :pf_id";
$stmt_professor = $pdo->prepare($sql_professor);
$stmt_professor->bindParam(':pf_id', $club['Pf_id'], PDO::PARAM_INT);
$stmt_professor->execute();
$professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);

// 활동 정보 조회
$sql_activities = "SELECT * FROM ACTIVITY WHERE Club_id = :club_id";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

// 활동 추가 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_activity'])) {
    $act_name = $_POST['ACT_name'];
    $start_date = $_POST['Start_date'];
    $end_date = $_POST['End_date'];
    $manager_name = $_POST['Manager_name'];

    $sql_insert_activity = "INSERT INTO ACTIVITY (ACT_name, Start_date, End_date, Club_id, Manager_name) 
                            VALUES (:act_name, :start_date, :end_date, :club_id, :manager_name)";
    $stmt_insert_activity = $pdo->prepare($sql_insert_activity);
    $stmt_insert_activity->bindParam(':act_name', $act_name, PDO::PARAM_STR);
    $stmt_insert_activity->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $stmt_insert_activity->bindParam(':end_date', $end_date, PDO::PARAM_STR);
    $stmt_insert_activity->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt_insert_activity->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
    $stmt_insert_activity->execute();
    header("Location: club_details.php?Club_id=$club_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 상세 정보</title>
</head>
<body>
    <h1>동아리 상세 정보</h1>

    <!-- 동아리 정보 -->
    <h2>동아리 정보</h2>
    <table border="1">
        <tr>
            <th>동아리 ID</th>
            <td><?php echo htmlspecialchars($club['Club_id']); ?></td>
        </tr>
        <tr>
            <th>동아리 이름</th>
            <td><?php echo htmlspecialchars($club['Name']); ?></td>
        </tr>
        <tr>
            <th>동아리 방</th>
            <td><?php echo htmlspecialchars($club['Club_room']); ?></td>
        </tr>
        <tr>
            <th>관심 분야</th>
            <td><?php echo htmlspecialchars($club['Interest']); ?></td>
        </tr>
        <tr>
            <th>교수 ID</th>
            <td><?php echo htmlspecialchars($club['Pf_id']); ?></td>
        </tr>
    </table>

    <!-- 교수 정보 -->
    <h2>지도 교수 정보</h2>
    <?php if ($professor): ?>
        <table border="1">
            <tr>
                <th>교수 이름</th>
                <td><?php echo htmlspecialchars($professor['Name']); ?></td>
            </tr>
            <tr>
                <th>전공</th>
                <td><?php echo htmlspecialchars($professor['Major']); ?></td>
            </tr>
            <tr>
                <th>전화번호</th>
                <td><?php echo htmlspecialchars($professor['Phone_number']); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>교수 정보가 없습니다.</p>
    <?php endif; ?>

    <!-- 예산 정보 -->
    <h2>예산 정보</h2>
    <?php if ($budget): ?>
        <table border="1">
            <tr>
                <th>매니저 이름</th>
                <td><?php echo htmlspecialchars($budget['Manager_name']); ?></td>
            </tr>
            <tr>
                <th>예산 금액</th>
                <td><?php echo htmlspecialchars($budget['Amount']); ?></td>
            </tr>
            <tr>
                <th>목적</th>
                <td><?php echo htmlspecialchars($budget['Purpose']); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>예산 정보가 없습니다.</p>
    <?php endif; ?>
    <h3>예산 추가</h3>
      <form method="POST" action="">
        <label for="Manager_name">매니저 이름:</label>
        <input type="text" id="Manager_name" name="Manager_name" required><br>
        <label for="Amount">예산 금액:</label>
        <input type="number" id="Amount" name="Amount" required><br>
        <label for="Purpose">목적:</label>
        <input type="text" id="Purpose" name="Purpose" required><br>
        <button type="submit" name="add_budget">예산 추가</button>
      </form>

    <h2>동아리원 목록</h2>
    <?php if ($members): ?>
        <table border="1">
            <tr>
                <th>이름</th>
                <th>학번</th>
                <th>전화번호</th>
                <th>소속 동아리 ID</th>
            </tr>
            <?php foreach ($members as $member): ?>
                <tr>
                    <td><?php echo htmlspecialchars($member['Name']); ?></td>
                    <td><?php echo htmlspecialchars($member['School_id']); ?></td>
                    <td><?php echo htmlspecialchars($member['Phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($member['Club_id']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>멤버가 없습니다.</p>
    <?php endif; ?>
    <!-- 멤버 추가 -->
    <h3>멤버 추가</h3>
      <form method="POST" action="">
      <label for="Name">이름:</label>
      <input type="text" id="Name" name="Name" required><br>
      <label for="School_id">학번:</label>
      <input type="number" id="School_id" name="School_id" required><br>
      <label for="Phone_number">전화번호:</label>
      <input type="text" id="Phone_number" name="Phone_number" required><br>
      <label for="Club_id">소속 동아리 ID:</label>
      <input type="number" id="Club_id" name="Club_id" required><br>
      <button type="submit" name="add_member">멤버 추가</button>
    </form>

    <!-- 활동 정보 -->
    <h2>활동 정보</h2>
    <?php if ($activities): ?>
        <table border="1">
            <tr>
                <th>활동 ID</th>
                <th>활동 이름</th>
                <th>시작 날짜</th>
                <th>종료 날짜</th>
                <th>담당자</th>
            </tr>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?php echo htmlspecialchars($activity['ACT_id']); ?></td>
                    <td><?php echo htmlspecialchars($activity['ACT_name']); ?></td>
                    <td><?php echo htmlspecialchars($activity['Start_date']); ?></td>
                    <td><?php echo htmlspecialchars($activity['End_date']); ?></td>
                    <td><?php echo htmlspecialchars($activity['Manager_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>활동 정보가 없습니다.</p>
    <?php endif; ?>

    <!-- 활동 추가 -->
    <h3>활동 추가</h3>
    <form method="POST" action="">
        <label for="ACT_name">활동 이름:</label>
        <input type="text" id="ACT_name" name="ACT_name" required><br>
        <label for="Start_date">시작 날짜:</label>
        <input type="date" id="Start_date" name="Start_date" required><br>
        <label for="End_date">종료 날짜:</label>
        <input type="date" id="End_date" name="End_date" required><br>
        <label for="Manager_name">담당자 이름:</label>
        <input type="text" id="Manager_name" name="Manager_name" required><br>
        <button type="submit" name="add_activity">활동 추가</button>
    </form>

    <a href="index.php">돌아가기</a>
    <a href="delete_club.php?Club_id=<?php echo $club_id; ?>" onclick="return confirm('정말로 삭제하시겠습니까?');">삭제하기</a>
</body>
</html>
