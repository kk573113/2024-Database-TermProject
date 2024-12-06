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
    <title>동아리 예산 상세 정보</title>
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
        .back-button {
            margin-left: 20px; 
            margin-top: 10px;
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
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .add-form {
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            th, td {
                font-size: 14px;
            }
            button {
                font-size: 12px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <h1>예산 상세 정보</h1>
    <div class="back-button">
        <a href="club_details.php?Club_id=<?php echo $club_id; ?>" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <!-- 예산 목록 -->
        <div class="section">
            <h2>예산 내역</h2>
            <form method="POST" action="">
                <table>
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
                            <td>
                                <input type="text" name="Manager_name_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Manager_name']); ?>" required>
                            </td>
                            <td>
                                <input type="number" name="Amount_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Amount']); ?>" required>
                            </td>
                            <td>
                                <input type="text" name="Purpose_<?php echo $item['Budget_id']; ?>" value="<?php echo htmlspecialchars($item['Purpose']); ?>" required>
                            </td>
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
        </div>

        <!-- 예산 추가 -->
        <div class="section add-form">
            <h2>예산 내역 추가</h2>
            <form method="POST" action="">
                <label for="Manager_name">담당자 이름:</label>
                <input type="text" id="Manager_name" name="Manager_name" required><br>
                <label for="Amount">금액:</label>
                <input type="number" id="Amount" name="Amount" required><br>
                <label for="Purpose">목적:</label>
                <input type="text" id="Purpose" name="Purpose" required><br>
                <button type="submit" name="add_budget">추가</button>
            </form>
        </div>
    </div>
</body>
</html>
