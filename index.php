<?php
include 'config.php'; // PDO 연결

$result_clubs = []; // 동아리 목록 초기화
$result_professors = []; // 교수 목록 초기화

try {
    // 동아리 데이터 조회 쿼리
    $sql_clubs = "SELECT Club_id, Name, Interest, Pf_id FROM CLUB";
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
            gap: 40px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
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
                    <th>교수 ID</th>
                    <th>자세히 보기</th>
                </tr>
                <?php
                if (!empty($result_clubs)) {
                    foreach ($result_clubs as $row) {
                        echo "<tr>
                            <td>{$row['Club_id']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Interest']}</td>
                            <td>{$row['Pf_id']}</td>
                            <td><a href='club_details.php?Club_id={$row['Club_id']}'>자세히 보기</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>동아리가 없습니다.</td></tr>";
                }
                ?>
            </table>
            <a href="add_club.php">추가하기</a>
            <a href="delete_club.php">삭제하기</a>
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
                </tr>
                <?php
                if (!empty($result_professors)) {
                    foreach ($result_professors as $prof) {
                        echo "<tr>
                            <td>{$prof['Pf_id']}</td>
                            <td>{$prof['Name']}</td>
                            <td>{$prof['Phone_number']}</td>
                            <td>{$prof['Major']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>교수가 없습니다.</td></tr>";
                }
                ?>
            </table>
            <a href="add_pf.php">추가하기</a>
            <a href="delete_pf.php">삭제하기</a>
        </div>
    </div>
</body>
</html>
