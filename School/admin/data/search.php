<?php
function search_users($search_term) {
    global $conn;
    $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = '%' . $search_term . '%';
    $stmt->bind_param('ss', $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
}