<?php
include '../config.php';  
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Initialize variables
$title = $content = $category = $author = '';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    $author = trim($_POST['author']);
    
    $uploadDir = "../uploads/";
    $imagePath = null;

    // Validate inputs
    if (empty($title) || empty($content) || empty($category) || empty($author)) {
        $error = "All fields except image are required!";
    } else {
        // Process image upload if present
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedFormats)) {
                $error = "Error: Unsupported file format! Only JPG, PNG, GIF, and WEBP are allowed.";
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB limit
                $error = "Error: File size exceeds 5MB limit!";
            } else {
                // Generate unique filename and move file
                $newFileName = uniqid("news_") . "." . $fileExt;
                $imagePath = $uploadDir . $newFileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    $error = "Error: Failed to upload image.";
                }
            }
        }

        // Insert into database if no errors
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO news (title, content, category, author, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $content, $category, $author, $imagePath);
            
            if ($stmt->execute()) {
                $success = "News posted successfully!";
                // Reset form fields
                $title = $content = $category = $author = '';
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post News | Admin Panel</title>

    <!-- Google Font, Bootstrap & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
            transition: all 0.3s ease;
        }
        
        /* Dark Mode Styles */
        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }
        
        .dark-mode .navbar, 
        .dark-mode .container, 
        .dark-mode .card {
            background-color: var(--dark-card);
            color: var(--dark-text);
        }
        
        .dark-mode .form-control, 
        .dark-mode .form-select {
            background-color: #2d2d2d;
            color: var(--dark-text);
            border-color: #444;
        }
        
        .dark-mode .form-control:focus, 
        .dark-mode .form-select:focus {
            background-color: #333;
            color: var(--dark-text);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .dark-mode .input-group-text {
            background-color: #333;
            color: var(--dark-text);
            border-color: #444;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Card Styling */
        .news-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .dark-mode .news-card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Form Styling */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        /* File Upload Styling */
        .file-upload {
            position: relative;
            overflow: hidden;
        }
        
        .file-upload-input {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: block;
            padding: 12px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload-label:hover {
            border-color: var(--accent-color);
            background-color: rgba(72, 149, 239, 0.05);
        }
        
        .dark-mode .file-upload-label {
            border-color: #444;
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .dark-mode .file-upload-label:hover {
            border-color: var(--accent-color);
            background-color: rgba(72, 149, 239, 0.1);
        }
        
        /* Alert Styling */
        .alert {
            border-radius: 8px;
        }
        
        /* Content Textarea */
        #content {
            min-height: 300px;
        }
    </style>
</head>
<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark-mode' : ''; ?>">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: var(--primary-color);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-newspaper me-2"></i>News Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-plus me-1"></i> Post News</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <button id="darkModeToggle" class="btn btn-outline-light me-2">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="logout.php" class="btn btn-light">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-container mt-4">
        <div class="news-card p-4 mb-4">
            <h2 class="mb-4"><i class="fas fa-plus-circle me-2"></i> Create New Post</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger animate__animated animate__shakeX">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="newsForm">
                <div class="mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?php echo htmlspecialchars($title); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="12" required><?php echo htmlspecialchars($content); ?></textarea>
                    <small class="text-muted">Plain text only - no formatting will be saved</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="Technology" <?php echo ($category === 'Technology') ? 'selected' : ''; ?>>Technology</option>
                            <option value="Business" <?php echo ($category === 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Health" <?php echo ($category === 'Health') ? 'selected' : ''; ?>>Health</option>
                            <option value="Sports" <?php echo ($category === 'Sports') ? 'selected' : ''; ?>>Sports</option>
                            <option value="Entertainment" <?php echo ($category === 'Entertainment') ? 'selected' : ''; ?>>Entertainment</option>
                            <option value="Politics" <?php echo ($category === 'Politics') ? 'selected' : ''; ?>>Politics</option>
                            <option value="Science" <?php echo ($category === 'Science') ? 'selected' : ''; ?>>Science</option>
                            <option value="Education" <?php echo ($category === 'Education') ? 'selected' : ''; ?>>Education</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo htmlspecialchars($author); ?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Featured Image</label>
                    <div class="file-upload">
                        <label for="image" class="file-upload-label">
                            <div id="file-upload-text">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p class="mb-1">Click to upload or drag and drop</p>
                                <p class="small text-muted">JPG, PNG, GIF, or WEBP (Max. 5MB)</p>
                            </div>
                            <input type="file" class="file-upload-input" id="image" name="image" accept="image/*">
                        </label>
                    </div>
                    <div id="image-preview" class="mt-3 text-center"></div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Publish News
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;
        
        // Check for saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
        
        // Toggle dark mode
        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDarkMode = body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
            document.cookie = "dark_mode=" + isDarkMode + "; path=/";
            
            if (isDarkMode) {
                darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });
        
        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const fileUploadText = document.getElementById('file-upload-text');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                        <div class="border p-2 rounded" style="max-width: 300px;">
                            <img src="${e.target.result}" class="img-fluid rounded" alt="Preview">
                            <div class="mt-2">${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>
                        </div>
                    `;
                    fileUploadText.innerHTML = `
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="mb-1">Image selected</p>
                        <p class="small text-muted">Click to change</p>
                    `;
                }
                
                reader.readAsDataURL(file);
            }
        });
        
        // Form submission loading state
        document.getElementById('newsForm').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Publishing...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>