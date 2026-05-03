<?php
$currentUser = current_user();
if (!$currentUser) {
    set_flash('warning', 'Please log in to ask a question.');
}
?>
<div class="container page-wrap">
  <div class="page-card">
    <h1 class="heading mb-4">Ask A Question</h1>

    <form action="<?php echo APP_BASE; ?>/server/requests.php" method="post" class="form-grid">
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" class="form-control form-control-lg" id="title" placeholder="Enter your question title" required maxlength="180">
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control" id="description" placeholder="Provide details for better answers" rows="6" required></textarea>
      </div>

      <div class="mb-4">
        <label for="category" class="form-label">Category</label>
        <?php include __DIR__ . '/category.php'; ?>
      </div>

      <button type="submit" name="ask" class="btn btn-primary btn-lg px-4">Post Question</button>
    </form>
  </div>
</div>
