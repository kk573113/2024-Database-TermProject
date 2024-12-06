<?php
include 'config.php'; // PDO 연결

$result_clubs = []; // 동아리 목록 초기화
$result_professors = []; // 교수 목록 초기화
$result_members = []; // 동아리원 목록 초기화
$result_meetings = []; // 회의 목록 초기화

try {
    // 동아리 데이터 조회 쿼리
    $sql_clubs = "SELECT Club_id, Name, Interest, Club_room, Pf_id FROM CLUB";
    $stmt_clubs = $pdo->prepare($sql_clubs);
    $stmt_clubs->execute();
    $result_clubs = $stmt_clubs->fetchAll(PDO::FETCH_ASSOC);

    // 교수 데이터 조회 쿼리
    $sql_professors = "SELECT Pf_id, Name, Phone_number, Major FROM PROFESSOR";
    $stmt_professors = $pdo->prepare($sql_professors);
    $stmt_professors->execute();
    $result_professors = $stmt_professors->fetchAll(PDO::FETCH_ASSOC);

    // 동아리원 데이터 조회 쿼리
    $sql_members = "SELECT Name, School_id, Phone_number, Club_id FROM MEMBER";
    $stmt_members = $pdo->prepare($sql_members);
    $stmt_members->execute();
    $result_members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

    // 회의 데이터 조회 쿼리
    $sql_meetings = "SELECT Meet_id, Date, Place, Agenda, Pf_id FROM MEETING";
    $stmt_meetings = $pdo->prepare($sql_meetings);
    $stmt_meetings->execute();
    $result_meetings = $stmt_meetings->fetchAll(PDO::FETCH_ASSOC);
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
        .tables {
            display: flex;
            flex-direction: column;
            gap: 40px;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        tr:hover {
            background-color: #ddd;
        }
        a {
            color: #11264f;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
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
            background-color: #1A2530;
        }
        .add-button {
            text-align: right;
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
                    <th>동아리 방</th>
                    <th>지도 교수 ID</th>
                    <th>상세 정보</th>
                </tr>
                <?php if (!empty($result_clubs)) {
                    foreach ($result_clubs as $row) {
                        echo "<tr>
                            <td>{$row['Club_id']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['Interest']}</td>
                            <td>{$row['Club_room']}</td>
                            <td>{$row['Pf_id']}</td>
                            <td><a href='club_details.php?Club_id={$row['Club_id']}'>상세 정보</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>동아리가 없습니다.</td></tr>";
                } ?>
            </table>
            <div class="add-button"><a href="add_club.php"><button>추가하기</button></a></div>
        </div>

        <!-- 교수 목록 -->
        <div>
            <h2>교수 목록</h2>
            <form method="POST" action="delete_pf.php">
                <table>
                    <tr>
                        <th>선택</th>
                        <th>교수 ID</th>
                        <th>이름</th>
                        <th>전화번호</th>
                        <th>전공</th>
                        <th>상세 정보</th>
                    </tr>
                    <?php if (!empty($result_professors)) {
                        foreach ($result_professors as $prof) {
                            echo "<tr>
                                <td><input type='radio' name='Pf_id' value='{$prof['Pf_id']}' required></td>
                                <td>{$prof['Pf_id']}</td>
                                <td>{$prof['Name']}</td>
                                <td>{$prof['Phone_number']}</td>
                                <td>{$prof['Major']}</td>
                                <td><a href='prof_details.php?Pf_id={$prof['Pf_id']}'>상세 정보</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>교수가 없습니다.</td></tr>";
                    } ?>
                </table>
                <button type="submit">삭제하기</button>
            </form>
            <div class="add-button"><a href="add_pf.php"><button>추가하기</button></a></div>
        </div>

        <!-- 동아리원 목록 -->
        <div>
            <h2>전체 동아리원 목록</h2>
            <form method="POST" action="delete_member.php">
                <table>
                    <tr>
                        <th>선택</th>
                        <th>이름</th>
                        <th>학번</th>
                        <th>전화번호</th>
                        <th>소속 동아리 ID</th>
                    </tr>
                    <?php if (!empty($result_members)) {
                        foreach ($result_members as $member) {
                            echo "<tr>
                                <td><input type='radio' name='School_id' value='{$member['School_id']}' required></td>
                                <td>{$member['Name']}</td>
                                <td>{$member['School_id']}</td>
                                <td>{$member['Phone_number']}</td>
                                <td>{$member['Club_id']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>동아리원이 없습니다.</td></tr>";
                    } ?>
                </table>
                <button type="submit">삭제하기</button>
            </form>
        </div>

        <!-- 회의 목록 -->
        <div>
            <h2>회의 목록</h2>
            <table>
                <tr>
                    <th>회의 ID</th>
                    <th>교수 ID</th>
                    <th>날짜</th>
                    <th>장소</th>
                    <th>안건</th>
                    <th>수정</th>
                </tr>
                <?php if (!empty($result_meetings)) {
                    foreach ($result_meetings as $meeting) {
                        echo "<tr>
                            <td>{$meeting['Meet_id']}</td>
                            <td>{$meeting['Pf_id']}</td>
                            <td>{$meeting['Date']}</td>
                            <td>{$meeting['Place']}</td>
                            <td>{$meeting['Agenda']}</td>
                            <td><a href='modify_meet.php?Meet_id={$meeting['Meet_id']}'>수정하기</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>회의 정보가 없습니다.</td></tr>";
                } ?>
            </table>
            <div class="add-button"><a href="add_meet.php"><button>추가하기</button></a></div>
        </div>
    </div>
</body>
</html>
