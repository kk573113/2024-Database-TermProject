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

// 모든 동아리 목록 조회 (현재 동아리는 제외)
$sql_all_clubs = "SELECT * FROM CLUB WHERE Club_id != :club_id";
$stmt_all_clubs = $pdo->prepare($sql_all_clubs);
$stmt_all_clubs->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt_all_clubs->execute();
$all_clubs = $stmt_all_clubs->fetchAll(PDO::FETCH_ASSOC);

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
        .split-container {
            display: flex;
            gap: 20px;
        }
        .split-container > .section {
            flex: 1;
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
        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
            }
            th, td {
                font-size: 14px;
            }
            button {
                font-size: 12px;
                padding: 8px 16px;
            }
        }
            .additional-info {
                margin-top: 20px;
                padding: 10px;
                background-color: #f2f2f2;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .additional-info h3 {
                color: #11264f;
                margin-bottom: 10px;
            }
            .additional-info ul {
                list-style-type: none;
                padding: 0;
            }
            .additional-info ul li {
                margin-bottom: 10px;
            }
            .additional-info ul li a {
                color: #11264f;
                text-decoration: none;
                font-weight: bold;
            }
            .additional-info ul li a:hover {
                text-decoration: underline;
            }

    </style>
</head>
<body>
    <h1>동아리 상세 정보</h1>
    <div class="back-button">
        <a href="index.php" class="link-button">
            <button type="button">뒤로가기</button>
        </a>
    </div>
    <div class="container">
        <!-- Split Container for 동아리 정보 and 지도 교수 정보 -->
        <div class="split-container">
            <!-- 동아리 정보 -->
            <div class="section">
                <h2>동아리 정보</h2>
                <form method="POST" action="">
                    <table>
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
                    <div class="actions">
                        <button type="submit" name="update_club">수정</button>
                        <a href="delete_club.php?Club_id=<?php echo $club_id; ?>" class="link-button" onclick="return confirm('동아리의 모든 정보가 삭제됩니다. 삭제하시겠습니까?');">
                            <button type="button">삭제</button>
                        </a>
                        <a href="merge_club.php?Club_id=<?php echo $club_id; ?>" class="link-button">
                            <button type="button">합류하기</button>
                        </a>
                    </div>

                </form>
            <!-- 추가 정보 링크 -->
            <h3>추가 정보</h3>
            <div class="additional-info">
                <ul>
                    <li><a href="budget_details.php?Club_id=<?php echo $club_id; ?>">예산 내역</a></li>
                    <li><a href="activity_details.php?Club_id=<?php echo $club_id; ?>">활동 목록</a></li>
                </ul>
            </div>
        </div>

            <!-- 지도 교수 정보 -->
            <div class="section">
                <h2>지도 교수 정보</h2>
                <?php if ($professor): ?>
                    <form method="POST" action="">
                        <table>
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
            </div>
        </div>

        <!-- 동아리원 목록 -->
        <div class="section">
            <h2>동아리원 목록</h2>
            <form method="POST" action="">
                <table>
                    <tr>
                        <th>학번</th>
                        <th>이름</th>
                        <th>전화번호</th>
                        <th>수정</th>
                        <th>삭제</th>
                    </tr>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><input type="text" name="School_id" value="<?php echo htmlspecialchars($member['School_id']); ?>" readonly></td>
                            <td><input type="text" name="Name" value="<?php echo htmlspecialchars($member['Name']); ?>" required></td>
                            <td><input type="text" name="Phone_number" value="<?php echo htmlspecialchars($member['Phone_number']); ?>" required></td>
                            <td><button type="submit" name="update_member" value="<?php echo $member['School_id']; ?>">수정</button></td>
                            <td><button type="submit" name="delete_member" value="<?php echo $member['School_id']; ?>">삭제</button></td>
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
        </div>
    </div>
</body>
</html>
