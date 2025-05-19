<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM news WHERE id = $id";
    $result = $conn->query($sql);
    $news = $result->fetch_assoc();
} else {
    echo "News not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $news['title']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="container my-5">
    <h1><?php echo $news['title']; ?></h1>
    <p><strong>Published:</strong> <?php echo $news['published_at']; ?></p>
    <img src="<?php echo $news['image']; ?>" class="img-fluid" alt="News Image">
    <p><?php echo $news['content']; ?></p>
    <a href="homepage.php" class="btn btn-primary">Back to News</a>
  </div>
</body>
</html>
