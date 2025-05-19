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

// Fetch single news article
$news = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $news_sql = "SELECT * FROM news WHERE id = $id";
    $news_result = $conn->query($news_sql);
    $news = $news_result->fetch_assoc();
}

// Set default language (English)
$current_language = isset($_GET['lang']) && $_GET['lang'] === 'sw' ? 'sw' : 'en';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($news['title']); ?> | NewsHub</title>
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

    /* TTS Controls */
    .tts-controls {
      display: flex;
      gap: 10px;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .tts-btn {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .tts-speed-control {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .language-switcher {
      margin-left: auto;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .article-container {
        margin: 20px auto;
      }

      .tts-controls {
        flex-direction: column;
      }
      
      .language-switcher {
        margin-left: 0;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark-mode' : ''; ?>">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="homepage.php">
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
          <li class="nav-item ms-2">
            <button id="darkModeToggle" class="btn btn-outline-light">
              <i class="fas fa-moon"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Single News Article -->
  <div class="article-container animate__animated animate__fadeIn">
    <h1 class="mb-4"><?php echo htmlspecialchars($news['title']); ?></h1>
    
    <?php if (!empty($news['image'])): ?>
      <img src="uploads/<?php echo basename($news['image']); ?>" class="article-image" alt="<?php echo htmlspecialchars($news['title']); ?>">
    <?php endif; ?>
    
    <!-- Language Switcher and TTS Controls -->
    <div class="tts-controls">
      <button id="readAloudBtn" class="btn btn-primary tts-btn">
        <i class="fas fa-volume-up"></i> <?php echo $current_language === 'en' ? 'Read Aloud' : 'Soma Kwa Sauti'; ?>
      </button>
      <button id="pauseResumeBtn" class="btn btn-warning tts-btn" disabled>
        <i class="fas fa-pause"></i> <?php echo $current_language === 'en' ? 'Pause' : 'Simamisha'; ?>
      </button>
      <button id="stopReadingBtn" class="btn btn-danger tts-btn" disabled>
        <i class="fas fa-stop"></i> <?php echo $current_language === 'en' ? 'Stop' : 'Acha'; ?>
      </button>
      
      <div class="tts-speed-control">
        <label for="ttsSpeed"><?php echo $current_language === 'en' ? 'Speed:' : 'Mwenendo:'; ?></label>
        <select id="ttsSpeed" class="form-select">
          <option value="0.5">0.5x</option>
          <option value="0.75">0.75x</option>
          <option value="1" selected>1.0x</option>
          <option value="1.25">1.25x</option>
          <option value="1.5">1.5x</option>
          <option value="2">2.0x</option>
        </select>
      </div>
      
      <div class="language-switcher">
        <div class="btn-group" role="group">
          <a href="readmore.php?id=<?php echo $news['id']; ?>&lang=en" class="btn btn-outline-primary <?php echo $current_language === 'en' ? 'active' : ''; ?>">
            <i class="fas fa-language"></i> English
          </a>
          <a href="readmore.php?id=<?php echo $news['id']; ?>&lang=sw" class="btn btn-outline-primary <?php echo $current_language === 'sw' ? 'active' : ''; ?>">
            <i class="fas fa-language"></i> Kiswahili
          </a>
        </div>
      </div>
    </div>
    
    <div class="article-content" id="articleContent">
      <?php 
      if ($current_language === 'sw' && !empty($news['content_sw'])) {
          echo nl2br(htmlspecialchars($news['content_sw']));
      } else {
          echo nl2br(htmlspecialchars($news['content']));
      }
      ?>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-5">
      <span class="text-muted">
        <i class="fas fa-calendar me-1"></i> <?php echo date('F j, Y', strtotime($news['published_at'])); ?>
      </span>
      <a href="homepage.php" class="btn btn-primary back-button">
        <i class="fas fa-arrow-left me-1"></i> <?php echo $current_language === 'en' ? 'Back to News' : 'Rudi kwa Habari'; ?>
      </a>
    </div>
  </div>

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

      // Text-to-Speech Functionality
      const readAloudBtn = document.getElementById('readAloudBtn');
      const pauseResumeBtn = document.getElementById('pauseResumeBtn');
      const stopReadingBtn = document.getElementById('stopReadingBtn');
      const ttsSpeed = document.getElementById('ttsSpeed');
      const articleContent = document.getElementById('articleContent');
      const currentLanguage = '<?php echo $current_language; ?>';

      let speechSynthesis = window.speechSynthesis;
      let utterance = null;
      let isPaused = false;

      // Load available voices
      let voices = [];
      
      function loadVoices() {
        voices = speechSynthesis.getVoices();
      }
      
      // Wait for voices to be loaded
      speechSynthesis.onvoiceschanged = loadVoices;
      loadVoices();

      readAloudBtn.addEventListener('click', () => {
        if (speechSynthesis.speaking && !isPaused) {
          speechSynthesis.pause();
          readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Resume' : 'Endelea'}`;
          pauseResumeBtn.disabled = true;
          isPaused = true;
          return;
        }

        if (isPaused) {
          speechSynthesis.resume();
          readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Reading...' : 'Inasoma...'}`;
          pauseResumeBtn.innerHTML = `<i class="fas fa-pause"></i> ${currentLanguage === 'en' ? 'Pause' : 'Simamisha'}`;
          pauseResumeBtn.disabled = false;
          isPaused = false;
          return;
        }

        const text = articleContent.textContent;
        utterance = new SpeechSynthesisUtterance(text);
        
        // Configure voice based on language
        if (currentLanguage === 'sw') {
          // Try to find a Swahili voice (if available)
          utterance.voice = voices.find(voice => voice.lang.includes('sw') || voice.lang.includes('en')) || voices[0];
          utterance.lang = 'sw-KE'; // Swahili (Kenya)
        } else {
          // Default to English
          utterance.voice = voices.find(voice => voice.lang.includes('en')) || voices[0];
          utterance.lang = 'en-US';
        }
        
        utterance.rate = parseFloat(ttsSpeed.value);
        utterance.pitch = 1.0;
        utterance.volume = 1.0;
        
        // Enable controls
        readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Reading...' : 'Inasoma...'}`;
        pauseResumeBtn.disabled = false;
        stopReadingBtn.disabled = false;
        
        utterance.onend = () => {
          readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Read Aloud' : 'Soma Kwa Sauti'}`;
          pauseResumeBtn.disabled = true;
          stopReadingBtn.disabled = true;
          isPaused = false;
        };
        
        utterance.onpause = () => {
          readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Resume' : 'Endelea'}`;
          pauseResumeBtn.innerHTML = `<i class="fas fa-play"></i> ${currentLanguage === 'en' ? 'Resume' : 'Endelea'}`;
          isPaused = true;
        };
        
        utterance.onresume = () => {
          readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Reading...' : 'Inasoma...'}`;
          pauseResumeBtn.innerHTML = `<i class="fas fa-pause"></i> ${currentLanguage === 'en' ? 'Pause' : 'Simamisha'}`;
          isPaused = false;
        };
        
        speechSynthesis.speak(utterance);
      });

      pauseResumeBtn.addEventListener('click', () => {
        if (speechSynthesis.paused) {
          speechSynthesis.resume();
          pauseResumeBtn.innerHTML = `<i class="fas fa-pause"></i> ${currentLanguage === 'en' ? 'Pause' : 'Simamisha'}`;
        } else {
          speechSynthesis.pause();
          pauseResumeBtn.innerHTML = `<i class="fas fa-play"></i> ${currentLanguage === 'en' ? 'Resume' : 'Endelea'}`;
        }
      });

      stopReadingBtn.addEventListener('click', () => {
        speechSynthesis.cancel();
        readAloudBtn.innerHTML = `<i class="fas fa-volume-up"></i> ${currentLanguage === 'en' ? 'Read Aloud' : 'Soma Kwa Sauti'}`;
        pauseResumeBtn.disabled = true;
        stopReadingBtn.disabled = true;
        isPaused = false;
      });

      ttsSpeed.addEventListener('change', () => {
        if (speechSynthesis.speaking && utterance) {
          speechSynthesis.cancel();
          utterance.rate = parseFloat(ttsSpeed.value);
          speechSynthesis.speak(utterance);
        }
      });
    });
  </script>
</body>
</html>