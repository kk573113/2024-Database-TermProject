<?php
include 'config.php'; // PDO 연결

// URL에서 Club_id 가져오기
$club_id = $_GET['Club_id'];

// 활동 정보 조회
$sql_activities = "SELECT * FROM ACTIVITY WHERE Club_id = :club_id";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // 활동 추가 처리
  if (isset($_POST['add_activity'])) {
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
      header("Location: activity_details.php?Club_id=$club_id");
      exit;
  }

  // 활동 수정 처리
  if (isset($_POST['update_activity'])) {
      $act_id = $_POST['update_activity'];
      $act_name = $_POST["ACT_name_$act_id"];
      $start_date = $_POST["Start_date_$act_id"];
      $end_date = $_POST["End_date_$act_id"];
      $manager_name = $_POST["Manager_name_$act_id"];

      $sql_update_activity = "UPDATE ACTIVITY 
                              SET ACT_name = :act_name, Start_date = :start_date, End_date = :end_date, Manager_name = :manager_name 
                              WHERE ACT_id = :act_id";
      $stmt_update_activity = $pdo->prepare($sql_update_activity);
      $stmt_update_activity->bindParam(':act_name', $act_name, PDO::PARAM_STR);
      $stmt_update_activity->bindParam(':start_date', $start_date, PDO::PARAM_STR);
      $stmt_update_activity->bindParam(':end_date', $end_date, PDO::PARAM_STR);
      $stmt_update_activity->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
      $stmt_update_activity->bindParam(':act_id', $act_id, PDO::PARAM_INT);
      $stmt_update_activity->execute();
      header("Location: activity_details.php?Club_id=$club_id");
      exit;
  }

  // 활동 삭제 처리
  if (isset($_POST['delete_activity'])) {
      $act_id = $_POST['delete_activity'];

      $sql_delete_activity = "DELETE FROM ACTIVITY WHERE ACT_id = :act_id";
      $stmt_delete_activity = $pdo->prepare($sql_delete_activity);
      $stmt_delete_activity->bindParam(':act_id', $act_id, PDO::PARAM_INT);
      $stmt_delete_activity->execute();
      header("Location: activity_details.php?Club_id=$club_id");
      exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>동아리 활동 상세 정보</title>
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
        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .add-form, .activity-table {
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
    <h1>동아리 활동 상세 정보</h1>
    <div class="back-button">
        <a href="club_details.php?Club_id=<?php echo $club_id; ?>" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <!-- 활동 목록 -->
        <div class="section">
            <h2>활동 목록</h2>
            <form method="POST" action="">
                <table class="activity-table">
                    <tr>
                        <th>활동 ID</th>
                        <th>활동 이름</th>
                        <th>시작 날짜</th>
                        <th>종료 날짜</th>
                        <th>담당자</th>
                        <th>수정</th>
                        <th>삭제</th>
                    </tr>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['ACT_id']); ?></td>
                            <td>
                                <input type="text" name="ACT_name_<?php echo $activity['ACT_id']; ?>" value="<?php echo htmlspecialchars($activity['ACT_name']); ?>" required>
                            </td>
                            <td>
                                <input type="date" name="Start_date_<?php echo $activity['ACT_id']; ?>" value="<?php echo htmlspecialchars($activity['Start_date']); ?>" required>
                            </td>
                            <td>
                                <input type="date" name="End_date_<?php echo $activity['ACT_id']; ?>" value="<?php echo htmlspecialchars($activity['End_date']); ?>" required>
                            </td>
                            <td>
                                <input type="text" name="Manager_name_<?php echo $activity['ACT_id']; ?>" value="<?php echo htmlspecialchars($activity['Manager_name']); ?>" required>
                            </td>
                            <td>
                                <button type="submit" name="update_activity" value="<?php echo $activity['ACT_id']; ?>">수정</button>
                            </td>
                            <td>
                                <button type="submit" name="delete_activity" value="<?php echo $activity['ACT_id']; ?>">삭제</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>

        <!-- 활동 추가 -->
        <div class="section add-form">
            <h2>활동 추가</h2>
            <form method="POST" action="">
                <label for="ACT_name">활동 이름:</label>
                <input type="text" id="ACT_name" name="ACT_name" required><br>
                <label for="Start_date">시작 날짜:</label>
                <input type="date" id="Start_date" name="Start_date" required><br>
                <label for="End_date">종료 날짜:</label>
                <input type="date" id="End_date" name="End_date" required><br>
                <label for="Manager_name">담당자 이름:</label>
                <input type="text" id="Manager_name" name="Manager_name" required><br>
                <button type="submit" name="add_activity">추가</button>
            </form>
        </div>
    </div>
</body>
</html>
