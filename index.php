<?php

include 'config.php';

try {
    $stmt = $pdo->query("SELECT * FROM PROFESSOR");
    $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Professor List</h1>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr>
            <th>Professor ID</th>
            <th>Name</th>
            <th>Phone_Number</th>
            <th>Major</th>
          </tr>";

    foreach ($professors as $professor) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($professor['Pf_id']) . "</td>";
        echo "<td>" . htmlspecialchars($professor['Name']) . "</td>";
        echo "<td>" . htmlspecialchars($professor['Phone_number']) . "</td>";
        echo "<td>" . htmlspecialchars($professor['Major']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

