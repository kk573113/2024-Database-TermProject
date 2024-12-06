<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['School_id'])) {
    $school_id = $_POST['School_id'];

    // 데이터 삭제 쿼리
    $sql_delete_member = "DELETE FROM MEMBER WHERE School_id = :school_id";
    $stmt_delete_member = $pdo->prepare($sql_delete_member);
    $stmt_delete_member->bindParam(':school_id', $school_id, PDO::PARAM_INT);

    if ($stmt_delete_member->execute()) {
        echo "<script>
                alert('동아리원이 성공적으로 삭제되었습니다.');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('동아리원 삭제 중 오류가 발생했습니다.');
                window.location.href = 'index.php';
              </script>";
    }
} else {
    echo "<script>
            alert('잘못된 요청입니다.');
            window.location.href = 'index.php';
          </script>";
}
?>
