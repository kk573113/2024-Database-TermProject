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

// 교수 정보 조회
$sql_professor = "SELECT * FROM PROFESSOR WHERE Pf_id = :pf_id";
$stmt_professor = $pdo->prepare($sql_professor);
$stmt_professor->bindParam(':pf_id', $club['Pf_id'], PDO::PARAM_INT);
$stmt_professor->execute();
$professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);

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

// 활동 정보 조회
$sql_activities = "SELECT * FROM ACTIVITY WHERE Club_id = :club_id";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

// 수정 및 추가/삭제 처리
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 동아리 정보 수정
    if (isset($_POST['update_club'])) {
        $name = $_POST['Name'];
        $club_room = $_POST['Club_room'];
        $interest = $_POST['Interest'];

        $sql_update_club = "UPDATE CLUB SET Name = :name, Club_room = :club_room, Interest = :interest WHERE Club_id = :club_id";
        $stmt_update_club = $pdo->prepare($sql_update_club);
        $stmt_update_club->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt_update_club->bindParam(':club_room', $club_room, PDO::PARAM_STR);
        $stmt_update_club->bindParam(':interest', $interest, PDO::PARAM_STR);
        $stmt_update_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_update_club->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    // 교수 정보 수정
    if (isset($_POST['update_professor'])) {
        $name = $_POST['Prof_Name'];
        $phone = $_POST['Phone_number'];
        $major = $_POST['Major'];

        $sql_update_professor = "UPDATE PROFESSOR SET Name = :name, Phone_number = :phone, Major = :major WHERE Pf_id = :pf_id";
        $stmt_update_professor = $pdo->prepare($sql_update_professor);
        $stmt_update_professor->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt_update_professor->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt_update_professor->bindParam(':major', $major, PDO::PARAM_STR);
        $stmt_update_professor->bindParam(':pf_id', $club['Pf_id'], PDO::PARAM_INT);
        $stmt_update_professor->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    // 예산 정보 추가/수정/삭제
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
  
    if (isset($_POST['update_budget'])) {
        $manager_name = $_POST['Manager_name'];
        $amount = $_POST['Amount'];
        $purpose = $_POST['Purpose'];

        $sql_update_budget = "UPDATE BUDGET SET Manager_name = :manager_name, Amount = :amount, Purpose = :purpose WHERE Club_id = :club_id";
        $stmt_update_budget = $pdo->prepare($sql_update_budget);
        $stmt_update_budget->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
        $stmt_update_budget->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt_update_budget->bindParam(':purpose', $purpose, PDO::PARAM_STR);
        $stmt_update_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_update_budget->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    if (isset($_POST['delete_budget'])) {
        $sql_delete_budget = "DELETE FROM BUDGET WHERE Budget_id = :Budget_id";
        $stmt_delete_budget = $pdo->prepare($sql_delete_budget);
        $stmt_delete_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_budget->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    // 동아리원 추가/수정/삭제
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_member'])) {
      $name = $_POST['Name'];
      $school_id = $_POST['School_id'];
      $phone_number = $_POST['Phone_number'];
  
      $sql_insert_member = "INSERT INTO MEMBER (Name, School_id, Phone_number, Club_id) 
                            VALUES (:name, :school_id, :phone_number, :club_id)";
      $stmt_insert_member = $pdo->prepare($sql_insert_member);
      $stmt_insert_member->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt_insert_member->bindParam(':school_id', $school_id, PDO::PARAM_INT);
      $stmt_insert_member->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
      $stmt_insert_member->bindParam(':club_id', $club_id, PDO::PARAM_INT);
      $stmt_insert_member->execute();
      header("Location: club_details.php?Club_id=$club_id");
      exit;
  }
  
    if (isset($_POST['update_member'])) {
        $name = $_POST['Name'];
        $phone_number = $_POST['Phone_number'];
        $school_id = $_POST['update_member'];

        $sql_update_member = "UPDATE MEMBER SET Name = :name, Phone_number = :phone_number WHERE School_id = :school_id";
        $stmt_update_member = $pdo->prepare($sql_update_member);
        $stmt_update_member->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt_update_member->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
        $stmt_update_member->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt_update_member->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    if (isset($_POST['delete_member'])) {
        $school_id = $_POST['delete_member'];

        $sql_delete_member = "DELETE FROM MEMBER WHERE School_id = :school_id";
        $stmt_delete_member = $pdo->prepare($sql_delete_member);
        $stmt_delete_member->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt_delete_member->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    // 활동 추가/수정/삭제
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
  
    if (isset($_POST['update_activity'])) {
        $act_name = $_POST['ACT_name'];
        $start_date = $_POST['Start_date'];
        $end_date = $_POST['End_date'];
        $manager_name = $_POST['Manager_name'];
        $act_id = $_POST['update_activity'];

        $sql_update_activity = "UPDATE ACTIVITY SET ACT_name = :act_name, Start_date = :start_date, End_date = :end_date, Manager_name = :manager_name WHERE ACT_id = :act_id";
        $stmt_update_activity = $pdo->prepare($sql_update_activity);
        $stmt_update_activity->bindParam(':act_name', $act_name, PDO::PARAM_STR);
        $stmt_update_activity->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $stmt_update_activity->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt_update_activity->bindParam(':manager_name', $manager_name, PDO::PARAM_STR);
        $stmt_update_activity->bindParam(':act_id', $act_id, PDO::PARAM_INT);
        $stmt_update_activity->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }

    if (isset($_POST['delete_activity'])) {
        $act_id = $_POST['delete_activity'];

        $sql_delete_activity = "DELETE FROM ACTIVITY WHERE ACT_id = :act_id";
        $stmt_delete_activity = $pdo->prepare($sql_delete_activity);
        $stmt_delete_activity->bindParam(':act_id', $act_id, PDO::PARAM_INT);
        $stmt_delete_activity->execute();
        header("Location: club_details.php?Club_id=$club_id");
        exit;
    }
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
    <form method="POST" action="">
        <table border="1">
            <tr>
                <th>동아리 ID</th>
                <td><?php echo htmlspecialchars($club['Club_id']); ?></td>
            </tr>
            <tr>
                <th>이름</th>
                <td><input type="text" name="Name" value="<?php echo htmlspecialchars($club['Name']); ?>" required></td>
            </tr>
            <tr>
                <th>동아리 방</th>
                <td><input type="text" name="Club_room" value="<?php echo htmlspecialchars($club['Club_room']); ?>"></td>
            </tr>
            <tr>
                <th>관심 분야</th>
                <td><input type="text" name="Interest" value="<?php echo htmlspecialchars($club['Interest']); ?>" required></td>
            </tr>
        </table>
        <button type="submit" name="update_club">수정</button>
    </form>

    <!-- 지도 교수 정보 -->
    <h2>지도 교수 정보</h2>
    <?php if ($professor): ?>
        <table border="1">
          <tr>
              <th>교수 ID</th>
              <td><?php echo htmlspecialchars($professor['Pf_id']); ?></td>
          </tr>
          <tr>
              <th>이름</th>
              <td><input type="text" name="Prof_Name" value="<?php echo htmlspecialchars($professor['Name']); ?>" required></td>
          </tr>
          <tr>
              <th>전공</th>
              <td><input type="text" name="Major" value="<?php echo htmlspecialchars($professor['Major']); ?>" required></td>
          </tr>
          <tr>
              <th>전화번호</th>
              <td><input type="text" name="Phone_number" value="<?php echo htmlspecialchars($professor['Phone_number']); ?>" required></td>
          </tr>
        </table>
        <button type="submit" name="update_professor">수정</button>
        </form>
    <?php else: ?>
        <p>지도 교수 정보가 없습니다.</p>
    <?php endif; ?>

    <!-- 예산 정보 -->
    <h2>예산 정보</h2>
    <form method="POST" action="">
        <table border="1">
            <tr>
                <th>예산 ID</th>
                <th>담당자</th>
                <th>예산 금액</th>
                <th>목적</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
            <?php if ($budget): ?>
                <tr>
                    <td><?php echo htmlspecialchars($budget['Budget_id']); ?></td>
                    <td><input type="text" name="Manager_name" value="<?php echo htmlspecialchars($budget['Manager_name']); ?>" required></td>
                    <td><input type="number" name="Amount" value="<?php echo htmlspecialchars($budget['Amount']); ?>" required></td>
                    <td><input type="text" name="Purpose" value="<?php echo htmlspecialchars($budget['Purpose']); ?>" required></td>
                    <td>
                        <button type="submit" name="update_budget">수정</button>
                    </td>
                    <td>
                        <button type="submit" name="delete_budget">삭제</button>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="5">예산 정보가 없습니다.</td>
                </tr>
            <?php endif; ?>
        </table>
    </form>

    <h3>예산 추가</h3>
    <form method="POST" action="">
        <label for="Manager_name">담당자 이름:</label>
        <input type="text" id="Manager_name" name="Manager_name" required><br>
        <label for="Amount">예산 금액:</label>
        <input type="number" id="Amount" name="Amount" required><br>
        <label for="Purpose">목적:</label>
        <input type="text" id="Purpose" name="Purpose" required><br>
        <button type="submit" name="add_budget">추가</button>
    </form>

    <!-- 동아리원 목록 -->
    <h2>동아리원 목록</h2>
    <form method="POST" action="">
        <table border="1">
            <tr>
                <th>학번</th>
                <th>이름</th>
                <th>전화번호</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
            <?php foreach ($members as $member): ?>
                <tr>
                    <td>
                        <input type="text" name="School_id" value="<?php echo htmlspecialchars($member['School_id']); ?>" required>
                    </td>
                    <td>
                        <input type="text" name="Name" value="<?php echo htmlspecialchars($member['Name']); ?>" required>
                    </td>
                    <td>
                        <input type="text" name="Phone_number" value="<?php echo htmlspecialchars($member['Phone_number']); ?>" required>
                    </td>
                    <td>
                        <button type="submit" name="update_member" value="<?php echo $member['School_id']; ?>">수정</button>
                    </td>
                    <td>
                        <button type="submit" name="delete_member" value="<?php echo $member['School_id']; ?>">삭제</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
    <h3>동아리원 추가</h3>
    <form method="POST" action="">
        <label for="Name">이름:</label>
        <input type="text" id="Name" name="Name" required><br>
        <label for="School_id">학번:</label>
        <input type="number" id="School_id" name="School_id" required><br>
        <label for="Phone_number">전화번호:</label>
        <input type="text" id="Phone_number" name="Phone_number" required><br>
        <button type="submit" name="add_member">추가</button>
    </form>

    <!-- 활동 정보 -->
    <h2>활동 정보</h2>
    <form method="POST" action="">
        <table border="1">
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
                    <td><input type="text" name="ACT_name" value="<?php echo htmlspecialchars($activity['ACT_name']); ?>" required></td>
                    <td><input type="date" name="Start_date" value="<?php echo htmlspecialchars($activity['Start_date']); ?>" required></td>
                    <td><input type="date" name="End_date" value="<?php echo htmlspecialchars($activity['End_date']); ?>" required></td>
                    <td><input type="text" name="Manager_name" value="<?php echo htmlspecialchars($activity['Manager_name']); ?>" required></td>
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
        <button type="submit" name="add_activity">추가</button>
    </form>
    <a href="index.php">돌아가기</a>
    <a href="delete_club.php?Club_id=<?php echo $club_id; ?>" onclick="return confirm('정말로 삭제하시겠습니까?');">삭제하기</a>
</body>
</html>
