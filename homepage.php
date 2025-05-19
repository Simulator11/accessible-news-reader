<?php
// Database Connection
$servername = "localhost";
$username = "root";   
$password = "";  
$dbname = "news_db";  

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all news articles
$sql = "SELECT * FROM news ORDER BY published_at DESC";
$result = $conn->query($sql);

// Fetch single news if ID is present
$news = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $news_sql = "SELECT * FROM news WHERE id = $id";
    $news_result = $conn->query($news_sql);
    $news = $news_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accessible News Feed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --dark-bg: #121212;
      --dark-card: #1e1e1e;
      --dark-text: #e0e0e0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-color);
      color: var(--dark-color);
      transition: all 0.3s ease;
    }

    /* Dark Mode Styles */
    body.dark-mode {
      background-color: var(--dark-bg);
      color: var(--dark-text);
    }

    .dark-mode .navbar {
      background-color: var(--dark-card) !important;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .dark-mode .card {
      background-color: var(--dark-card);
      color: var(--dark-text);
      border: 1px solid #333;
    }

    .dark-mode .hero-banner {
      background: linear-gradient(135deg, #1a1a2e, #16213e) !important;
    }

    /* Navbar */
    .navbar {
      background-color: var(--primary-color);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 15px 0;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: 0.5px;
    }

    .nav-link {
      font-weight: 500;
      padding: 8px 15px;
      border-radius: 6px;
      transition: all 0.3s;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    /* Hero Section */
    .hero-banner {
      background: linear-gradient(135deg, #4361ee, #3f37c9);
      color: white;
      padding: 80px 20px;
      text-align: center;
      margin-bottom: 40px;
    }

    .hero-banner h1 {
      font-size: 2.8rem;
      font-weight: 700;
      margin-bottom: 20px;
      animation: fadeInDown 1s;
    }

    .hero-banner p {
      font-size: 1.2rem;
      opacity: 0.9;
      animation: fadeInUp 1s;
    }

    /* News Cards */
    .news-card {
      border: none;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      height: 100%;
    }

    .dark-mode .news-card {
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .news-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .news-card .card-img-top {
      height: 200px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .news-card:hover .card-img-top {
      transform: scale(1.05);
    }

    .card-body {
      padding: 20px;
    }

    .card-title {
      font-weight: 600;
      margin-bottom: 15px;
      min-height: 60px;
    }

    .card-text {
      color: #666;
      margin-bottom: 20px;
    }

    .dark-mode .card-text {
      color: #aaa;
    }

    .btn-read-more {
      background-color: var(--primary-color);
      border: none;
      padding: 8px 20px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-read-more:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
    }

    /* Article Page */
    .article-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 0 15px;
    }

    .article-image {
      width: 100%;
      max-height: 500px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .article-content {
      line-height: 1.8;
      font-size: 1.1rem;
    }

    .back-button {
      margin-top: 30px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .hero-banner h1 {
        font-size: 2rem;
      }
      
      .hero-banner p {
        font-size: 1rem;
      }
      
      .news-card .card-img-top {
        height: 150px;
      }
      
      .article-container {
        margin: 20px auto;
      }
    }
  </style>
</head>
<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark-mode' : ''; ?>">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-newspaper me-2"></i>NewsHub
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="homepage.php">
              <i class="fas fa-home me-1"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="fas fa-user me-1"></i> Profile
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="fas fa-cog me-1"></i> Settings
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
          </li>
          <li class="nav-item ms-2">
            <button id="darkModeToggle" class="btn btn-outline-light">
              <i class="fas fa-moon"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <?php if ($news) { ?>
    <!-- Single News Article -->
    <div class="article-container animate__animated animate__fadeIn">
      <h1 class="mb-4"><?php echo htmlspecialchars($news['title']); ?></h1>
      <?php if (!empty($news['image'])): ?>
        <img src="uploads/<?php echo basename($news['image']); ?>" class="article-image" alt="<?php echo htmlspecialchars($news['title']); ?>">
      <?php endif; ?>
      <div class="article-content">
        <?php echo nl2br(htmlspecialchars($news['content'])); ?>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-5">
        <span class="text-muted">
          <i class="fas fa-calendar me-1"></i> <?php echo date('F j, Y', strtotime($news['published_at'])); ?>
        </span>
        <a href="homepage.php" class="btn btn-primary back-button">
          <i class="fas fa-arrow-left me-1"></i> Back to News
        </a>
      </div>
    </div>
  <?php } else { ?>
    <!-- Hero Section -->
    <div class="hero-banner animate__animated animate__fadeIn">
      <div class="container">
        <h1>Welcome to NewsHub</h1>
        <p>Your premier destination for accessible and inclusive news coverage</p>
      </div>
    </div>

    <!-- News Feed Section -->
    <div class="container my-5 animate__animated animate__fadeInUp">
      <h2 class="mb-4 text-center">Latest News Updates</h2>
      <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="col-lg-4 col-md-6">
            <div class="news-card card h-100">
              <?php if (!empty($row['image'])): ?>
                <img src="uploads/<?php echo basename($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
              <?php else: ?>
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                  <i class="fas fa-newspaper fa-4x text-white"></i>
                </div>
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo substr(htmlspecialchars($row['content']), 0, 150); ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i> <?php echo date('M j, Y', strtotime($row['published_at'])); ?>
                  </small>
                  <a href="readmore.php?id=<?php echo $row['id']; ?>" class="btn btn-read-more">
                    Read More <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Dark Mode Toggle
    document.addEventListener("DOMContentLoaded", function() {
      const darkModeToggle = document.getElementById("darkModeToggle");
      const body = document.body;

      // Check for saved preference
      if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
      }

      // Toggle dark mode
      darkModeToggle.addEventListener("click", function() {
        body.classList.toggle("dark-mode");
        const isDarkMode = body.classList.contains("dark-mode");
        localStorage.setItem("darkMode", isDarkMode ? "enabled" : "disabled");
        document.cookie = "dark_mode=" + isDarkMode + "; path=/";
        
        if (isDarkMode) {
          darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
          darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
      });
    });
  </script>
</body>
</html>