<?php
require_once __DIR__ . '/../common/db.php';
$query = "SELECT id, name FROM category ORDER BY name ASC";
$result = $conn->query($query);
?>
<select class="form-select form-select-lg" name="category" id="category" required>
    <option value="">Select a category</option>
    <?php if ($result): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?php echo (int)$row['id']; ?>"><?php echo h(ucfirst($row['name'])); ?></option>
        <?php endwhile; ?>
    <?php endif; ?>
</select>
