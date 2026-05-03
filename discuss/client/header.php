<?php
require_once __DIR__ . '/../common/helpers.php';
$currentUser = current_user();
$flash = get_flash();
$searchValue = $_GET['search'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
  <div class="container-fluid px-3 px-lg-4 py-2 navbar-shell">
    <a class="navbar-brand d-flex align-items-center gap-2 brand-link" href="<?php echo APP_BASE; ?>/">
      <img src="<?php echo APP_BASE; ?>/public/logo.png" class="brand-logo" alt="QueryHub logo" />
      <span class="brand-name">QueryHub</span>
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#queryhubNavbar" aria-controls="queryhubNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse navbar-collapse-row" id="queryhubNavbar">
      <ul class="navbar-nav me-lg-auto mb-2 mb-lg-0 nav-pills-gap nav-inline-group">
        <li class="nav-item">
          <a class="nav-link <?php echo !isset($_GET['latest']) && !isset($_GET['ask']) && !isset($_GET['signup']) && !isset($_GET['login']) && !isset($_GET['q-id']) && !isset($_GET['c-id']) && !isset($_GET['u-id']) && !isset($_GET['search']) ? 'active' : ''; ?>" href="<?php echo APP_BASE; ?>/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isset($_GET['latest']) ? 'active' : ''; ?>" href="<?php echo APP_BASE; ?>/?latest=true">Latest Questions</a>
        </li>
        <?php if ($currentUser): ?>
          <li class="nav-item">
            <a class="nav-link <?php echo isset($_GET['ask']) ? 'active' : ''; ?>" href="<?php echo APP_BASE; ?>/?ask=true">Ask A Question</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo (isset($_GET['u-id']) && (int)$_GET['u-id'] === (int)$currentUser['user_id']) ? 'active' : ''; ?>" href="<?php echo APP_BASE; ?>/?u-id=<?php echo (int)$currentUser['user_id']; ?>">My Questions</a>
          </li>
        <?php endif; ?>
      </ul>

      <form class="d-flex me-lg-3 mb-3 mb-lg-0 search-form navbar-search" action="<?php echo APP_BASE; ?>/" method="get">
        <input class="form-control search-input" name="search" type="search" placeholder="Search questions" value="<?php echo h($searchValue); ?>">
        <button class="btn btn-outline-info ms-2 search-btn" type="submit">Search</button>
      </form>

      <div class="d-flex align-items-center gap-2 navbar-actions">
        <button type="button" class="btn theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <span id="themeToggleIcon">☀</span>
        </button>

        <?php if ($currentUser): ?>
          <span class="user-chip">Hi, <?php echo h(ucfirst($currentUser['username'] ?? 'User')); ?></span>
          <a class="btn btn-sm btn-outline-light nav-action-btn" href="<?php echo APP_BASE; ?>/server/requests.php?logout=true">Logout</a>
        <?php else: ?>
          <a class="btn btn-sm btn-outline-light nav-action-btn" href="<?php echo APP_BASE; ?>/?login=true">Login</a>
          <a class="btn btn-sm btn-primary nav-action-btn" href="<?php echo APP_BASE; ?>/?signup=true">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php if ($flash): ?>
  <div class="container mt-3">
    <div class="alert alert-<?php echo h($flash['type']); ?> shadow-sm rounded-4">
      <?php echo h($flash['message']); ?>
    </div>
  </div>
<?php endif; ?>

<script>
(function () {
  const root = document.documentElement;
  const toggle = document.getElementById('themeToggle');
  const icon = document.getElementById('themeToggleIcon');

  function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    localStorage.setItem('queryhub-theme', theme);
    if (icon) {
      icon.textContent = theme === 'dark' ? '☀' : '🌙';
    }
  }

  const storedTheme = localStorage.getItem('queryhub-theme');
  const currentTheme = storedTheme || root.getAttribute('data-theme') || 'dark';
  applyTheme(currentTheme);

  if (toggle) {
    toggle.addEventListener('click', function () {
      const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(nextTheme);
    });
  }
})();
</script>
