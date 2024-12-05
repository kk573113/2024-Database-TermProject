<?php
include 'config.php'; // PDO 연결

// URL에서 Club_id 가져오기
$club_id = $_GET['Club_id'];

// 동아리 정보 조회 쿼리
$sql_club = "SELECT * FROM CLUB WHERE Club_id = :club_id";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_club->execute();
$club = $stmt_club->fetch(PDO::FETCH_ASSOC);

// 예산 정보 조회 쿼리
$sql_budget = "SELECT * FROM BUDGET WHERE Club_id = :club_id";
$stmt_budget = $pdo->prepare($sql_budget);
$stmt_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_budget->execute();
$budget = $stmt_budget->fetch(PDO::FETCH_ASSOC);

// 멤버 정보 조회 쿼리
$sql_members = "SELECT * FROM MEMBER WHERE Club_id = :club_id";
$stmt_members = $pdo->prepare($sql_members);
$stmt_members->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_members->execute();
$members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

// 예산 정보 추가 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_budget'])) {
    $manager_name = $_POST['Manager_name'];
    $amount = $_POST['Amount'];
    $purpose = $_POST['Purpose'];

    $sql_insert_budget = "INSERT INTO BUDGET (Club_id, Manager_name, Amount, Purpose) 
                          VALUES (:club_id, :manager_name, :amount, :purpose)";
    $stmt_insert_budget = $pdo->prepare($sql_insert_budget);
    $stmt_insert_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt_insert_budget->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
    $stmt_insert_budget->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt_insert_budget->bindParam(':purpose', $purpose, PDO::PARAM_STR);
    $stmt_insert_budget->execute();
    header("Location: club_details.php?Club_id=$club_id");
    exit;
}

// 멤버 추가 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_member'])) {
    $name = $_POST['Name'];
    $phone_number = $_POST['Phone_number'];

    $sql_insert_member = "INSERT INTO MEMBER (Name, Phone_number, Club_id) 
                          VALUES (:name, :phone_number, :club_id)";
    $stmt_insert_member = $pdo->prepare($sql_insert_member);
    $stmt_insert_member->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt_insert_member->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt_insert_member->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt_insert_member->execute();
    header("Location: club_details.php?Club_id=$club_id");
    exit;
}
// 교수 정보 조회 쿼리
$sql_professor = "SELECT * FROM PROFESSOR WHERE Pf_id = :pf_id";
$stmt_professor = $pdo->prepare($sql_professor);
$stmt_professor->bindParam(':pf_id', $club['Pf_id'], PDO::PARAM_INT);
$stmt_professor->execute();
$professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);
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
            <td><?php echo htmlspecialchars
($club['Interest']); ?></td>
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
            <tr>
                <th>교수 ID</th>
                <td><?php echo htmlspecialchars($club['Pf_id']); ?></td>
            </tr>
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

<!-- 예산 추가 폼 -->
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

<!-- 멤버 리스트 -->
<h2>멤버 리스트</h2>
<?php if ($members): ?>
<table border="1">
    <tr>
        <th>이름</th>
        <th>전화번호</th>
    </tr>
    <?php foreach ($members as $member): ?>
        <tr>
            <td><?php echo htmlspecialchars($member['Name']); ?></td>
            <td><?php echo htmlspecialchars($member['Phone_number']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>멤버가 없습니다.</p>
<?php endif; ?>

<!-- 멤버 추가 폼 -->
<h3>멤버 추가</h3>
<form method="POST" action="">
<label for="Name">이름:</label>
<input type="text" id="Name" name="Name" required><br>
<label for="Phone_number">전화번호:</label>
<input type="text" id="Phone_number" name="Phone_number" required><br>
<button type="submit" name="add_member">멤버 추가</button>
</form>

<a href="index.php">돌아가기</a>
<a href="delete_club.php?Club_id=<?php echo $club_id; ?>" onclick="return confirm('정말로 삭제하시겠습니까?');">삭제하기</a>
</body>
</html>


