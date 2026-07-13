<?php
session_start();
require 'db.php';

// If the user isn't logged in, block access
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

// --- HANDLE GET REQUESTS (Listing & Searching) ---
if ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'list') {
        $search = trim($_GET['search'] ?? '');
        
        if ($search !== '') {
            // Search name or phone numbers matching the current user
            $stmt = $pdo->prepare('SELECT * FROM contacts WHERE user_id = ? AND (name LIKE ? OR phone LIKE ?)');
            $stmt->execute([$user_id, "%$search%", "%$search%"]);
        } else {
            // Fetch all contacts belonging to the current logged-in user
            $stmt = $pdo->prepare('SELECT * FROM contacts WHERE user_id = ?');
            $stmt->execute([$user_id]);
        }
        
        echo json_encode($stmt->fetchAll());
        exit;
    }
}

// --- HANDLE POST REQUESTS (Add, Edit, Delete) ---
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($name === '' || $phone === '') {
            echo json_encode(['error' => 'Name and Phone are required.']);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO contacts (user_id, name, phone, email, address, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $name, $phone, $email, $address, $notes]);
        echo json_encode(['success' => true]);
        exit;

    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if (!$id || $name === '' || $phone === '') {
            echo json_encode(['error' => 'Invalid data provided.']);
            exit;
        }

        // Securely update ensuring the contact belongs to this specific user
        $stmt = $pdo->prepare('UPDATE contacts SET name = ?, phone = ?, email = ?, address = ?, notes = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$name, $phone, $email, $address, $notes, $id, $user_id]);
        echo json_encode(['success' => true]);
        exit;

    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if (!$id) {
            echo json_encode(['error' => 'No ID specified.']);
            exit;
        }

        // Securely delete ensuring the contact belongs to this specific user
        $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $user_id]);
        echo json_encode(['success' => true]);
        exit;
    }
}

// If no valid route matched
http_response_code(400);
echo json_encode(['error' => 'Invalid action']);