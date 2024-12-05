<?php
include 'config.php'; // PDO 연결

$result_clubs = []; // 동아리 목록 초기화
$result_professors = []; // 교수 목록 초기화

// 동아리 삭제 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_club'])) {
    $club_id = $_POST['Club_id'];
    $sql_delete_club = "DELETE FROM CLUB WHERE Club_id = :club_id";
    $stmt_delete_club = $pdo->prepare($sql_delete_club);
    $stmt_delete_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
    $stmt_delete_club->execute();
    header("Location: index.php");
    exit;
}

// 교수 삭제 처리
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_professor'])) {
    $pf_id = $_POST['Pf_id'];
    $sql_delete_professor = "DELETE FROM PROFESSOR WHERE Pf_id = :pf_id";
    $stmt_delete_professor = $pdo->prepare($sql_delete_professor);
    $stmt_delete_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
    $stmt_delete_professor->execute();
    header("Location: index.php");
    exit;
}

try {
    // 동아리 데이터 조회 쿼리
    $sql_clubs = "SELECT Club_id, Name, Interest FROM CLUB";
    $stmt_clubs = $pdo->prepare($sql_clubs);
    $stmt_clubs->execute();
    $result_clubs = $stmt_clubs->fetchAll(PDO::FETCH_ASSOC);

    // 교수 데이터 조회 쿼리
    $sql_professors = "SELECT Pf_id, Name, Phone_number, Major FROM PROFESSOR";
    $stmt_professors = $pdo->prepare($sql_professors);
    $stmt_professors->execute();
    $result_professors = $stmt_professors->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "데이터 조회 실패: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>충북대학교 소프트웨어학부 동아리 관리 시스템</title>
    <style>
        .tables {
            display: flex;
            gap: 20px;
        }
        table {
            border-collapse: collapse;
            width: 45%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>충북대학교 소프트웨어학부 동아리 관리 시스템</h1>
    <div class="tables">
        <!-- 동아리 목록 -->
        <div>
            <h2>동아리 목록</h2>
            <table>
                <tr>
                    <th>동아리 ID</th>
                    <th>동아리 이름</th>
                    <th>관심 분야</th>
                    <th>기능</th>
                </tr>
                <?php
                if (!empty($result_clubs)) {
                    foreach ($result_clubs as $row) {
                        echo "<tr>
                            <td>{$row['Club_id']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Interest']}</td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='Club_id' value='{$row['Club_id']}'>
                                    <button type='submit' name='delete_club'>삭제</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>동아리가 없습니다.</td></tr>";
                }
                ?>
            </table>
            <a href="add_club.php">동아리 추가</a>
        </div>

        <!-- 교수 목록 -->
        <div>
            <h2>교수 목록</h2>
            <table>
                <tr>
                    <th>교수 ID</th>
                    <th>이름</th>
                    <th>전화번호</th>
                    <th>전공</th>
                    <th>기능</th>
                </tr>
                <?php
                if (!empty($result_professors)) {
                    foreach ($result_professors as $prof) {
                        echo "<tr>
                            <td>{$prof['Pf_id']}</td>
                            <td>{$prof['Name']}</td>
                            <td>{$prof['Phone_number']}</td>
                            <td>{$prof['Major']}</td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='Pf_id' value='{$prof['Pf_id']}'>
                                    <button type='submit' name='delete_professor'>삭제</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>교수가 없습니다.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
