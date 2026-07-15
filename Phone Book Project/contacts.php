<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
$userId = $_SESSION['user_id'];
$action = $_REQUEST['action'] ?? '';

switch ($action) {

    case 'list':
        $search = trim($_GET['search'] ?? '');
        $type = $_GET['type'] ?? 'personal'; // 'personal' or 'emergency'

        if ($type === 'emergency') {
            // 1. EMERGENCY MODE - Now reads safely from the renamed table
            if ($search !== '') {
                $stmt = $pdo->prepare("SELECT id, name, number AS phone, email, NULL AS address, description AS notes, 'emergency' AS contact_type 
                                       FROM emergency_db.emergency_contacts
                                       WHERE name LIKE ? OR number LIKE ? 
                                       ORDER BY name ASC");
                $like = "%$search%";
                $stmt->execute([$like, $like]);
            } else {
                $stmt = $pdo->prepare("SELECT id, name, number AS phone, email, NULL AS address, description AS notes, 'emergency' AS contact_type 
                                       FROM emergency_db.emergency_contacts
                                       ORDER BY name ASC");
                $stmt->execute();
            }
        } else {
            // 2. PERSONAL MODE - Standard user isolation
            if ($search !== '') {
                $stmt = $pdo->prepare("SELECT id, name, phone, email, address, notes, 'personal' AS contact_type 
                                       FROM contacts 
                                       WHERE user_id = ? AND (name LIKE ? OR phone LIKE ?) 
                                       ORDER BY name ASC");
                $like = "%$search%";
                $stmt->execute([$userId, $like, $like]);
            } else {
                $stmt = $pdo->prepare("SELECT id, name, phone, email, address, notes, 'personal' AS contact_type 
                                       FROM contacts 
                                       WHERE user_id = ? 
                                       ORDER BY name ASC");
                $stmt->execute([$userId]);
            }
        }
        echo json_encode($stmt->fetchAll());
        break;

    case 'add':
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        if ($name === '' || $phone === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Name and phone are required.']);
            break;
        }
        $stmt = $pdo->prepare('INSERT INTO contacts (user_id, name, phone, email, address, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $name, $phone, $_POST['email'] ?? '', $_POST['address'] ?? '', $_POST['notes'] ?? '']);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'edit':
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        if ($id === 0 || $name === '' || $phone === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Name and phone are required.']);
            break;
        }
        // ownership check: only edit rows belonging to this user
        $stmt = $pdo->prepare('UPDATE contacts SET name=?, phone=?, email=?, address=?, notes=? WHERE id=? AND user_id=?');
        $stmt->execute([$name, $phone, $_POST['email'] ?? '', $_POST['address'] ?? '', $_POST['notes'] ?? '', $id, $userId]);
        echo json_encode(['ok' => true]);
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM contacts WHERE id=? AND user_id=?');
        $stmt->execute([$id, $userId]);
        echo json_encode(['ok' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}
