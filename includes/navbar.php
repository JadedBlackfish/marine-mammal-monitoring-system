<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">Marine Mammals</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="view_sightings.php">Sightings</a></li>
        <li class="nav-item"><a class="nav-link" href="map.php">Map</a></li>

        <?php if(isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <span class="navbar-text text-light me-3">
              Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
          </li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>