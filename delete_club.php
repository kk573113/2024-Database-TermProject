<?php
include 'config.php'; // PDO 연결

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Club_id'])) {
    $club_id = $_GET['Club_id'];

    try {
        // 트랜잭션 시작
        $pdo->beginTransaction();

        // BUDGET 테이블의 Club_id 관련 데이터 삭제
        $sql_delete_budget = "DELETE FROM BUDGET WHERE Club_id = :club_id";
        $stmt_delete_budget = $pdo->prepare($sql_delete_budget);
        $stmt_delete_budget->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_budget->execute();

        // MEMBER 테이블의 Club_id 관련 데이터 삭제
        $sql_delete_members = "DELETE FROM MEMBER WHERE Club_id = :club_id";
        $stmt_delete_members = $pdo->prepare($sql_delete_members);
        $stmt_delete_members->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_members->execute();

        // ACTIVITY 테이블의 Club_id 관련 데이터 삭제
        $sql_delete_activity = "DELETE FROM ACTIVITY WHERE Club_id = :club_id";
        $stmt_delete_activity = $pdo->prepare($sql_delete_activity);
        $stmt_delete_activity->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_activity->execute();

        // CLUB 테이블에서 데이터 삭제
        $sql_delete_club = "DELETE FROM CLUB WHERE Club_id = :club_id";
        $stmt_delete_club = $pdo->prepare($sql_delete_club);
        $stmt_delete_club->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt_delete_club->execute();

        // 트랜잭션 커밋
        $pdo->commit();

        // 삭제 완료 후 리다이렉트
        header("Location: index.php?message=Club deleted successfully");
        exit;
    } catch (PDOException $e) {
        // 오류 발생 시 트랜잭션 롤백
        $pdo->rollBack();
        echo "삭제 중 오류가 발생했습니다: " . $e->getMessage();
    }
} else {
    echo "유효하지 않은 요청입니다.";
}
