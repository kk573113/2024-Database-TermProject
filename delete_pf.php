<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Pf_id'])) {
    $pf_id = $_POST['Pf_id'];

    try {
        // 동아리 테이블의 Pf_id를 NULL로 설정
        $sql_update_club = "UPDATE CLUB SET Pf_id = NULL WHERE Pf_id = :pf_id";
        $stmt_update_club = $pdo->prepare($sql_update_club);
        $stmt_update_club->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_update_club->execute();

        // 교수 삭제 쿼리
        $sql_delete_professor = "DELETE FROM PROFESSOR WHERE Pf_id = :pf_id";
        $stmt_delete_professor = $pdo->prepare($sql_delete_professor);
        $stmt_delete_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_delete_professor->execute();

        // 성공 메시지와 함께 index.php로 리디렉션
        echo "<script>
                alert('교수가 성공적으로 삭제되었습니다.');
                window.location.href = 'index.php';
              </script>";
    } catch (PDOException $e) {
        // 오류 메시지와 함께 이전 페이지로 돌아감
        echo "<script>
                alert('삭제 중 오류가 발생했습니다: " . $e->getMessage() . "' );
                window.history.back();
              </script>";
    }
} else {
    // Pf_id가 없는 경우 index.php로 리디렉션
    echo "<script>
            alert('잘못된 요청입니다. 교수를 선택하세요.');
            window.location.href = 'index.php';
          </script>";
}
?>
