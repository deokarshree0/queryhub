<div class="container page-wrap">
  <div class="page-card auth-card">
    <h1 class="heading mb-4">Signup</h1>

    <form method="post" action="<?php echo APP_BASE; ?>/server/requests.php">
      <div class="mb-3">
        <label for="username" class="form-label">User Name</label>
        <input type="text" name="username" class="form-control form-control-lg" id="username" placeholder="Enter your name" required maxlength="80">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">User Email</label>
        <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">User Password</label>
        <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Create a strong password" required minlength="6">
      </div>

      <div class="mb-4">
        <label for="address" class="form-label">User Address</label>
        <input type="text" name="address" class="form-control form-control-lg" id="address" placeholder="Enter your address" required maxlength="255">
      </div>

      <button type="submit" name="signup" class="btn btn-primary btn-lg w-100">Create Account</button>
    </form>
  </div>
</div>
