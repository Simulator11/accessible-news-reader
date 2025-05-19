<?php
include '../config.php';  
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all news with pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM news";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);

// Fetch paginated news
$sql = "SELECT * FROM news ORDER BY published_at DESC LIMIT $offset, $per_page";
$result = $conn->query($sql);

// Get image directory path
$uploadDir = '../uploads/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Dashboard | Admin Panel</title>

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
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
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
        
        .dark-mode .navbar {
            background-color: var(--dark-card) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .dark-mode .card, 
        .dark-mode .table {
            background-color: var(--dark-card);
            color: var(--dark-text);
        }
        
        .dark-mode .table th,
        .dark-mode .table td {
            border-color: #444 !important;
        }
        
        .dark-mode .form-control, 
        .dark-mode .form-select {
            background-color: #2d2d2d;
            color: var(--dark-text);
            border-color: #444;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
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
        
        .nav-item.active .nav-link {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Card Styling */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .dark-mode .dashboard-card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Table Styling */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            border: none;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(72, 149, 239, 0.1);
        }
        
        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: var(--dark-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        
        /* Image Thumbnail */
        .news-thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            transition: transform 0.3s;
        }
        
        .news-thumbnail:hover {
            transform: scale(1.5);
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .thumbnail-container {
            position: relative;
            display: inline-block;
        }
        
        /* Pagination */
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color);
        }
        
        .dark-mode .pagination .page-link {
            background-color: var(--dark-card);
            border-color: #444;
        }
        
        /* Stats Cards */
        .stats-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stats-card .count {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .stats-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            
            .table-responsive {
                border-radius: 8px;
            }
            
            .table thead {
                display: none;
            }
            
            .table tr {
                display: block;
                margin-bottom: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .dark-mode .table tr {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            }
            
            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
            }
            
            .dark-mode .table td {
                border-bottom: 1px solid #444;
            }
            
            .table td::before {
                content: attr(data-label);
                font-weight: 500;
                margin-right: 20px;
            }
            
            .table td:last-child {
                border-bottom: none;
            }
            
            .news-thumbnail {
                width: 100%;
                height: auto;
                max-height: 200px;
            }
        }
    </style>
</head>
<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark-mode' : ''; ?>">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-newspaper me-2"></i>News Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item active" id="dashboardLink">
                        <a class="nav-link" href="./admin_dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item" id="newsLink">
                        <a class="nav-link" href="./admin_dashboard.php">
                            <i class="fas fa-newspaper me-1"></i> All News
                        </a>
                    </li>
                    <li class="nav-item" id="addNewsLink">
                        <a class="nav-link" href="./admin_post_news.php">
                            <i class="fas fa-plus me-1"></i> Add News
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['admin_username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                    <button id="darkModeToggle" class="btn btn-outline-light">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-container mt-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #4361ee, #3f37c9);">
                    <i class="fas fa-newspaper"></i>
                    <div class="count"><?php echo $total_rows; ?></div>
                    <div class="label">Total News</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #4cc9f0, #4895ef);">
                    <i class="fas fa-eye"></i>
                    <div class="count">1.2K</div>
                    <div class="label">Today's Views</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #f72585, #b5179e);">
                    <i class="fas fa-users"></i>
                    <div class="count">356</div>
                    <div class="label">Active Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #38b000, #2d6a4f);">
                    <i class="fas fa-comments"></i>
                    <div class="count">42</div>
                    <div class="label">New Comments</div>
                </div>
            </div>
        </div>

        <!-- News Table -->
        <div class="dashboard-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0"><i class="fas fa-newspaper me-2"></i> Latest News</h4>
                    <a href="./admin_post_news.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Preview</th>
                                <th>Image</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { 
                                $imagePath = !empty($row['image']) ? $uploadDir . basename($row['image']) : '';
                                ?>
                                <tr>
                                    <td data-label="Title"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td data-label="Category">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($row['category']); ?></span>
                                    </td>
                                    <td data-label="Author"><?php echo htmlspecialchars($row['author']); ?></td>
                                    <td data-label="Preview">
                                        <?php echo substr(strip_tags(htmlspecialchars($row['content'])), 0, 50); ?>...
                                    </td>
                                    <td data-label="Image">
                                        <?php if (!empty($row['image']) && file_exists($imagePath)): ?>
                                            <div class="thumbnail-container">
                                                <img src="<?php echo $imagePath; ?>" class="news-thumbnail" alt="News Thumbnail">
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Date"><?php echo date('M j, Y', strtotime($row['published_at'])); ?></td>
                                    <td data-label="Actions">
                                        <div class="d-flex gap-2">
                                            <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_news.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this news item?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
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
        
        // Set active nav item
        $(document).ready(function() {
            const currentPage = window.location.pathname.split('/').pop();
            $('.nav-item').removeClass('active');
            
            if (currentPage === 'admin_dashboard.php') {
                $('#dashboardLink').addClass('active');
            } else if (currentPage === 'admin_post_news.php') {
                $('#addNewsLink').addClass('active');
            }
            
            // Confirm before deleting
            $('.btn-danger').click(function() {
                return confirm('Are you sure you want to delete this item?');
            });
        });
    </script>
</body>
</html>