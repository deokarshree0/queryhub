<div class="page-card">
    <h2 class="heading mb-4">Categories</h2>
    <?php
    require_once __DIR__ . '/../common/db.php';

    $query = "SELECT id, name FROM category ORDER BY name ASC";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = ucfirst($row['name']);
            $id = (int)$row['id'];
            echo "<div class='question-list category-item'>
                    <h4 class='mb-0'><a href='?c-id=$id'>" . h($name) . "</a></h4>
                  </div>";
        }
    } else {
        echo "<p class='text-muted mb-0'>No categories available.</p>";
    }
    ?>
</div>
