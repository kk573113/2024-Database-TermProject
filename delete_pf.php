<?php
include 'config.php'; // PDO 연결

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Pf_id'])) {
    $pf_id = $_POST['Pf_id'];

    try {
        // 해당 교수가 지도하고 있는 동아리 확인
        $sql_check_club = "SELECT * FROM CLUB WHERE Pf_id = :pf_id";
        $stmt_check_club = $pdo->prepare($sql_check_club);
        $stmt_check_club->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_check_club->execute();
        $clubs = $stmt_check_club->fetchAll(PDO::FETCH_ASSOC);

        // 지도하고 있는 동아리가 있는 경우 삭제 금지
        if (!empty($clubs)) {
            echo "<script>
                    alert('해당 교수는 동아리를 지도하고 있습니다. 먼저 동아리 정보를 수정하세요.');
                    window.history.back();
                  </script>";
            exit;
        }

        // 지도 동아리가 없는 경우 삭제 진행
        $sql_delete_professor = "DELETE FROM PROFESSOR WHERE Pf_id = :pf_id";
        $stmt_delete_professor = $pdo->prepare($sql_delete_professor);
        $stmt_delete_professor->bindParam(':pf_id', $pf_id, PDO::PARAM_INT);
        $stmt_delete_professor->execute();

        // 성공 메시지와 함께 index.php로 리디렉션
        echo "<script>
                alert('성공적으로 삭제되었습니다.');
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
            alert('잘못된 요청입니다. 삭제할 항목을 선택하세요.');
            window.location.href = 'index.php';
          </script>";
}
?>
