<div class="container page-wrap">
  <div class="page-card auth-card">
    <h1 class="heading mb-4">Login</h1>

    <form action="<?php echo APP_BASE; ?>/server/requests.php" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">User Email</label>
        <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">User Password</label>
        <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Enter your password" required>
      </div>

      <button type="submit" name="login" class="btn btn-primary btn-lg w-100">Login</button>
    </form>
  </div>
</div>
