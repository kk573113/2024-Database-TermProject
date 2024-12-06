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

// 교수 정보 조회 (Pf_id가 NULL이 아닐 경우에만 조회)
$professor = null;
if ($club['Pf_id']) {
    $sql_professor = "SELECT * FROM PROFESSOR WHERE Pf_id = :pf_id";
    $stmt_professor = $pdo->prepare($sql_professor);
    $stmt_professor->bindParam(':pf_id', $club['Pf_id'], PDO::PARAM_INT);
    $stmt_professor->execute();
    $professor = $stmt_professor->fetch(PDO::FETCH_ASSOC);
}

// 멤버 정보 조회
$sql_members = "SELECT * FROM MEMBER WHERE Club_id = :club_id";
$stmt_members = $pdo->prepare($sql_members);
$stmt_members->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_members->execute();
$members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 동아리 정보 수정
    if (isset($_POST['update_club'])) {
        $name = $_POST['Name'];
        $club_room = $_POST['Club_room'];
        $interest = $_POST['Interest'];
        $pf_id = $_POST['Pf_id']; // 지도교수 ID 추가

        // 입력된 Pf_id가 PROFESSOR 테이블에 존재하는지 확인
        $sql_check_professor = "SELECT COUNT(*) FROM PROFESSOR WHERE Pf_id = :pf_id";
        $stmt_check_professor = $pdo->prepare($sql_check_professor);
        $stmt_check_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_check_professor->execute();
        $professor_exists = $stmt_check_professor->fetchColumn();

        // 입력된 Pf_id가 다른 동아리에 이미 사용되고 있는지 확인
        $sql_check_club = "SELECT COUNT(*) FROM CLUB WHERE Pf_id = :pf_id AND Club_id != :club_id";
        $stmt_check_club = $pdo->prepare($sql_check_club);
        $stmt_check_club->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_check_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_check_club->execute();
        $pf_id_in_use = $stmt_check_club->fetchColumn();

        if ($professor_exists > 0 && $pf_id_in_use == 0) {
            // Pf_id가 유효하고 다른 동아리에 사용되지 않았을 경우 업데이트
            $sql_update_club = "UPDATE CLUB SET Name = :name, Club_room = :club_room, Interest = :interest, Pf_id = :pf_id WHERE Club_id = :club_id";
            $stmt_update_club = $pdo->prepare($sql_update_club);
            $stmt_update_club->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt_update_club->bindParam(':club_room', $club_room, PDO::PARAM_STR);
            $stmt_update_club->bindParam(':interest', $interest, PDO::PARAM_STR);
            $stmt_update_club->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
            $stmt_update_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
            $stmt_update_club->execute();
            header("Location: club_details.php?Club_id=$club_id");
            exit;
        } elseif ($pf_id_in_use > 0) {
            // Pf_id가 이미 다른 동아리에 사용 중일 경우 오류 메시지
            echo "<script>alert('해당 교수는 이미 다른 동아리를 지도하고 있습니다.');</script>";
        } else {
            echo "<script>alert('존재하지 않는 교수 ID입니다.');</script>";
        }
    }

    // 지도 교수 정보 수정 및 반영
    if (isset($_POST['update_professor'])) {
        $name = $_POST['Prof_Name'];
        $phone = $_POST['Phone_number'];
        $major = $_POST['Major'];

        if ($professor) {
            // 교수 정보 테이블 업데이트
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
    }
  // 동아리원 추가/수정/삭제
  if (isset($_POST['add_member'])) {
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
                <th>동아리 이름</th>
                <td><input type="text" name="Name" value="<?php echo htmlspecialchars($club['Name']); ?>" required></td>
            </tr>
            <tr>
                <th>관심 분야</th>
                <td><input type="text" name="Interest" value="<?php echo htmlspecialchars($club['Interest']); ?>" required></td>
            </tr>
            <tr>
                <th>동아리 방</th>
                <td><input type="text" name="Club_room" value="<?php echo htmlspecialchars($club['Club_room']); ?>"></td>
            </tr>
            <tr>
                <th>지도 교수 ID</th>
                <td><input type="number" name="Pf_id" value="<?php echo htmlspecialchars($club['Pf_id']); ?>" required></td>
            </tr>
        </table>
        <button type="submit" name="update_club">수정</button>
        <!-- 예산 및 활동 정보 링크 추가 -->
        <h2>추가 정보</h2>
        <ul>
            <li><a href="budget_details.php?Club_id=<?php echo $club_id; ?>">동아리 예산 상세 정보 보기</a></li>
            <li><a href="activity_details.php?Club_id=<?php echo $club_id; ?>">동아리 활동 상세 정보 보기</a></li>
        </ul>
    </form>

    <!-- 지도 교수 정보 -->
    <h2>지도 교수 정보</h2>
    <?php if ($professor): ?>
        <!-- 지도 교수 정보를 감싸는 form 태그 추가 -->
        <form method="POST" action="">
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
            <!-- form 태그 안에 submit 버튼 포함 -->
            <button type="submit" name="update_professor">수정</button>
        </form>
    <?php else: ?>
        <p>지도 교수 정보가 없습니다.</p>
    <?php endif; ?>

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
    <a href="index.php">뒤로가기</a>
    <a href="delete_club.php?Club_id=<?php echo $club_id; ?>" onclick="return confirm('정말로 삭제하시겠습니까?');">삭제하기</a>
</body>
</html>
