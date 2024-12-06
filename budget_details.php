<?php
include 'config.php'; // PDO 연결

// URL에서 Club_id 가져오기
$club_id = $_GET['Club_id'];

// 예산 정보 조회
$sql_budget = "SELECT * FROM BUDGET WHERE Club_id = :club_id";
$stmt_budget = $pdo->prepare($sql_budget);
$stmt_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_budget->execute();
$budget = $stmt_budget->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // 예산 추가 처리
  if (isset($_POST['add_budget'])) {
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
      header("Location: budget_details.php?Club_id=$club_id");
      exit;
  }

  // 예산 수정 처리
  if (isset($_POST['update_budget'])) {
      $budget_id = $_POST['update_budget'];
      $manager_name = $_POST["Manager_name_$budget_id"];
      $amount = $_POST["Amount_$budget_id"];
      $purpose = $_POST["Purpose_$budget_id"];

      $sql_update_budget = "UPDATE BUDGET 
                            SET Manager_name = :manager_name, Amount = :amount, Purpose = :purpose 
                            WHERE Budget_id = :budget_id";
      $stmt_update_budget = $pdo->prepare($sql_update_budget);
      $stmt_update_budget->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
      $stmt_update_budget->bindParam(':amount', $amount, PDO::PARAM_INT);
      $stmt_update_budget->bindParam(':purpose', $purpose, PDO::PARAM_STR);
      $stmt_update_budget->bindParam(':budget_id', $budget_id, PDO::PARAM_INT);
      $stmt_update_budget->execute();
      header("Location: budget_details.php?Club_id=$club_id");
      exit;
  }

  // 예산 삭제 처리
  if (isset($_POST['delete_budget'])) {
      $budget_id = $_POST['delete_budget'];

      $sql_delete_budget = "DELETE FROM BUDGET WHERE Budget_id = :budget_id";
      $stmt_delete_budget = $pdo->prepare($sql_delete_budget);
      $stmt_delete_budget->bindParam(':budget_id', $budget_id, PDO::PARAM_INT);
      $stmt_delete_budget->execute();
      header("Location: budget_details.php?Club_id=$club_id");
      exit;
  }
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 예산 상세 정보 보기</title>
</head>
<body>
    <h1>예산 내역</h1>

    <!-- 예산 수정 및 삭제 -->
    <form method="POST" action="">
        <table border="1">
            <tr>
                <th>예산 ID</th>
                <th>담당자</th>
                <th>금액</th>
                <th>목적</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
            <?php foreach ($budget as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['Budget_id']); ?></td>
                    <td><input type="text" name="Manager_name_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Manager_name']); ?>" required></td>
                    <td><input type="number" name="Amount_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Amount']); ?>" required></td>
                    <td><input type="text" name="Purpose_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Purpose']); ?>" required></td>
                    <td>
                        <button type="submit" name="update_budget" value="<?php echo $item['Budget_id']; ?>">수정</button>
                    </td>
                    <td>
                        <button type="submit" name="delete_budget" value="<?php echo $item['Budget_id']; ?>">삭제</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>

    <!-- 예산 추가 -->
    <h3>예산 내역 추가</h3>
    <form method="POST" action="">
        <label for="Manager_name">담당자 이름:</label>
        <input type="text" id="Manager_name" name="Manager_name" required><br>
        <label for="Amount">금액:</label>
        <input type="number" id="Amount" name="Amount" required><br>
        <label for="Purpose">목적:</label>
        <input type="text" id="Purpose" name="Purpose" required><br>
        <button type="submit" name="add_budget">추가</button>
    </form>

    <a href="club_details.php?Club_id=<?php echo $club_id; ?>">뒤로가기</a>
</body>
</html>

