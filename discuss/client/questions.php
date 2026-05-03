<?php
require_once __DIR__ . '/../common/db.php';
$currentUser = current_user();

$pageTitle = 'Questions';
$result = null;

if (isset($_GET['c-id'])) {
    $pageTitle = 'Questions by Category';
    $stmt = $conn->prepare('SELECT * FROM questions WHERE category_id = ? ORDER BY id DESC');
    $stmt->bind_param('i', $cid);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_GET['u-id'])) {
    $pageTitle = 'My Questions';
    $stmt = $conn->prepare('SELECT * FROM questions WHERE user_id = ? ORDER BY id DESC');
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (isset($_GET['search'])) {
    $pageTitle = 'Search Results';
    $searchTerm = trim((string)$search);
    $stmt = $conn->prepare('SELECT * FROM questions WHERE title LIKE ? ORDER BY id DESC');
    $like = '%' . $searchTerm . '%';
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare('SELECT * FROM questions ORDER BY id DESC');
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<div class="container page-wrap">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="page-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h1 class="heading mb-1"><?php echo h($pageTitle); ?></h1>
                        <p class="text-muted mb-0">A clean space to ask, answer, and discover useful discussions.</p>
                    </div>
                </div>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $title = $row['title'];
                            $id = (int)$row['id'];
                            $isOwner = $currentUser && (int)$currentUser['user_id'] === (int)$row['user_id'];
                        ?>
                        <div class="question-list">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <h4 class="my-question mb-0">
                                    <a href="?q-id=<?php echo $id; ?>"><?php echo h($title); ?></a>
                                </h4>
                                <?php if ($isOwner): ?>
                                    <a class="btn btn-sm btn-outline-danger delete-link" href="<?php echo APP_BASE; ?>/server/requests.php?delete=<?php echo $id; ?>" onclick="return confirm('Delete this question?')">Delete</a>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted mt-2 mb-0">Click to view answers and join the discussion.</p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <h4>No questions found</h4>
                        <p class="mb-0">Try another search, browse categories, or ask the first question.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <?php include __DIR__ . '/categorylist.php'; ?>
        </div>
    </div>
</div>
