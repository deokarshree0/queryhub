<?php
require_once __DIR__ . '/../common/db.php';

if (isset($_POST['signup'])) {
    $username = trim((string)($_POST['username'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $address = trim((string)($_POST['address'] ?? ''));

    if ($username === '' || $email === '' || $password === '' || $address === '') {
        set_flash('danger', 'All signup fields are required.');
        redirect_to('/?signup=true');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Please enter a valid email address.');
        redirect_to('/?signup=true');
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $exists = $check->get_result();

    if ($exists && $exists->num_rows > 0) {
        set_flash('warning', 'This email is already registered.');
        redirect_to('/?signup=true');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user = $conn->prepare("INSERT INTO users (username, email, password, address) VALUES (?, ?, ?, ?)");
    $user->bind_param("ssss", $username, $email, $hashedPassword, $address);

    if ($user->execute()) {
        ensure_session();
        $_SESSION["user"] = [
            "username" => $username,
            "email" => $email,
            "user_id" => $conn->insert_id
        ];
        set_flash('success', 'Account created successfully.');
        redirect_to('/');
    }

    set_flash('danger', 'New user not registered.');
    redirect_to('/?signup=true');
}

if (isset($_POST['login'])) {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        set_flash('danger', 'Please enter email and password.');
        redirect_to('/?login=true');
    }

    $query = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedPassword = (string)$row['password'];
        $passwordOk = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

        if ($passwordOk) {
            ensure_session();
            $_SESSION["user"] = [
                "username" => $row['username'],
                "email" => $row['email'],
                "user_id" => $row['id']
            ];
            set_flash('success', 'Logged in successfully.');
            redirect_to('/');
        }
    }

    set_flash('danger', 'Invalid email or password.');
    redirect_to('/?login=true');
}

if (isset($_GET['logout'])) {
    ensure_session();
    session_unset();
    session_destroy();
    redirect_to('/');
}

if (isset($_POST["ask"])) {
    ensure_session();
    $currentUser = current_user();

    if (!$currentUser) {
        set_flash('warning', 'Please log in to ask a question.');
        redirect_to('/?login=true');
    }

    $title = trim((string)($_POST['title'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $category_id = (int)($_POST['category'] ?? 0);
    $user_id = (int)$currentUser['user_id'];

    if ($title === '' || $description === '' || $category_id <= 0) {
        set_flash('danger', 'Please complete all fields before posting.');
        redirect_to('/?ask=true');
    }

    $question = $conn->prepare("INSERT INTO questions (title, description, category_id, user_id) VALUES (?, ?, ?, ?)");
    $question->bind_param("ssii", $title, $description, $category_id, $user_id);

    if ($question->execute()) {
        set_flash('success', 'Your question has been posted.');
        redirect_to('/');
    }

    set_flash('danger', 'Question could not be added.');
    redirect_to('/?ask=true');
}

if (isset($_POST["answer"])) {
    ensure_session();
    $currentUser = current_user();

    if (!$currentUser) {
        set_flash('warning', 'Please log in to post an answer.');
        redirect_to('/?login=true');
    }

    $answer = trim((string)($_POST['answer'] ?? ''));
    $question_id = (int)($_POST['question_id'] ?? 0);
    $user_id = (int)$currentUser['user_id'];

    if ($answer === '' || $question_id <= 0) {
        set_flash('danger', 'Answer cannot be empty.');
        redirect_to('/?q-id=' . $question_id);
    }

    $query = $conn->prepare("INSERT INTO answers (answer, question_id, user_id) VALUES (?, ?, ?)");
    $query->bind_param("sii", $answer, $question_id, $user_id);

    if ($query->execute()) {
        set_flash('success', 'Your answer has been posted.');
        redirect_to('/?q-id=' . $question_id);
    }

    set_flash('danger', 'Answer is not submitted.');
    redirect_to('/?q-id=' . $question_id);
}

if (isset($_GET["delete"])) {
    ensure_session();
    $currentUser = current_user();

    if (!$currentUser) {
        set_flash('warning', 'Please log in first.');
        redirect_to('/?login=true');
    }

    $qid = (int)$_GET["delete"];
    $ownerCheck = $conn->prepare("SELECT user_id FROM questions WHERE id = ?");
    $ownerCheck->bind_param("i", $qid);
    $ownerCheck->execute();
    $ownerResult = $ownerCheck->get_result();
    $ownerRow = $ownerResult ? $ownerResult->fetch_assoc() : null;

    if (!$ownerRow || (int)$ownerRow['user_id'] !== (int)$currentUser['user_id']) {
        set_flash('danger', 'You can delete only your own questions.');
        redirect_to('/');
    }

    $query = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $query->bind_param("i", $qid);

    if ($query->execute()) {
        set_flash('success', 'Question deleted successfully.');
        redirect_to('/');
    }

    set_flash('danger', 'Question not deleted.');
    redirect_to('/');
}
?>
