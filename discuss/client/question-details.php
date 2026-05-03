<?php
require_once __DIR__ . '/../common/db.php';
$currentUser = current_user();

$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->bind_param("i", $qid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;
?>
<div class="container page-wrap">
    <?php if (!$row): ?>
        <div class="page-card">
            <h1 class="heading">Question not found</h1>
            <p class="text-muted mb-0">This question may have been removed or the link is invalid.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="page-card">
                    <h1 class="heading mb-4">Question</h1>
                    <?php
                        $cid = (int)$row['category_id'];
                        echo "<h4 class='margin-bottom-15 question-title'>Question: " . h($row['title']) . "</h4>";
                        echo "<p class='margin-bottom-15'>" . nl2br(h($row['description'])) . "</p>";
                        include __DIR__ . '/answers.php';
                    ?>

                    <?php if ($currentUser): ?>
                        <form action="<?php echo APP_BASE; ?>/server/requests.php" method="post" class="mt-4">
                            <input type="hidden" name="question_id" value="<?php echo (int)$qid; ?>">
                            <textarea name="answer" class="form-control form-control-lg margin-bottom-15" placeholder="Write your answer..." rows="5" required></textarea>
                            <button class="btn btn-primary">Post Answer</button>
                        </form>
                    <?php else: ?>
                        <div class="empty-state mt-4">
                            <p class="mb-2">Log in to answer this question.</p>
                            <a href="<?php echo APP_BASE; ?>/?login=true" class="btn btn-primary">Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <?php
                    $categoryQuery = $conn->prepare("SELECT name FROM category WHERE id = ?");
                    $categoryQuery->bind_param("i", $cid);
                    $categoryQuery->execute();
                    $categoryResult = $categoryQuery->get_result();
                    $categoryRow = $categoryResult ? $categoryResult->fetch_assoc() : null;

                    echo "<div class='page-card mb-4'>";
                    echo "<h2 class='mb-0'>" . h(ucfirst($categoryRow['name'] ?? 'Category')) . "</h2>";
                    echo "</div>";

                    $related = $conn->prepare("SELECT * FROM questions WHERE category_id = ? AND id != ? ORDER BY id DESC");
                    $related->bind_param("ii", $cid, $qid);
                    $related->execute();
                    $relatedResult = $related->get_result();

                    echo "<div class='page-card'><h3 class='mb-3'>Related Questions</h3>";
                    if ($relatedResult && $relatedResult->num_rows > 0) {
                        while ($rel = $relatedResult->fetch_assoc()) {
                            $id = (int)$rel['id'];
                            echo "<div class='question-list'><h4 class='mb-0'><a href='?q-id=$id'>" . h($rel['title']) . "</a></h4></div>";
                        }
                    } else {
                        echo "<p class='text-muted mb-0'>No related questions yet.</p>";
                    }
                    echo "</div>";
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
