<?php
include 'config.php'; // PDO 연결

if (isset($_GET['Club_id'])) {
    $club_id = $_GET['Club_id'];

    try {
        // 동아리 삭제 쿼리
        $sql = "DELETE FROM CLUB WHERE Club_id = :club_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->execute();

        // 성공 메시지와 함께 index.php로 리디렉션
        echo "<script>
                alert('동아리가 성공적으로 삭제되었습니다.');
                window.location.href = 'index.php';
              </script>";
    } catch (PDOException $e) {
        // 오류 메시지와 함께 이전 페이지로 돌아감
        echo "<script>
                alert('삭제 중 오류가 발생했습니다: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    // Club_id가 없는 경우 index.php로 리디렉션
    echo "<script>
            alert('잘못된 요청입니다.');
            window.location.href = 'index.php';
          </script>";
}
