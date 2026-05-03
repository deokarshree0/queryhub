<div class="answers-block">
    <h5 class="mb-3">Answers</h5>
    <?php
    $stmt = $conn->prepare("SELECT * FROM answers WHERE question_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $qid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $answer = $row['answer'];
            echo "<div class='answer-wrapper'>" . nl2br(h($answer)) . "</div>";
        }
    } else {
        echo "<div class='empty-state'><p class='mb-0'>No answers yet. Be the first to help.</p></div>";
    }
    ?>
</div>
