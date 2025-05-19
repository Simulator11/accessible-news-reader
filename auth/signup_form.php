<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            font-weight: 600;
            text-align: center;
        }
        .form-control {
            border-radius: 20px;
            padding-left: 40px;
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #007bff;
        }
        .btn-primary {
            border-radius: 20px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003f7f);
        }
        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header py-3">
                <h3><i class="fas fa-user-plus"></i> Signup</h3>
            </div>
            <div class="card-body">
                <form action="signup_backend.php" method="POST" onsubmit="return validateForm()">
                    
                    <!-- Username Field -->
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                    </div>
                    
                    <!-- Email Field -->
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                        <span class="input-group-text toggle-password"><i class="fas fa-eye-slash" id="togglePassword"></i></span>
                    </div>
                    
                    <!-- Signup Button -->
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Signup</button>
                </form>

                <!-- Login Link -->
                <p class="mt-3 text-center">Already have an account? <a href="login_form.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Password Toggle Script -->
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        let passwordInput = document.getElementById("password");
        let icon = this;
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
</script>

</body>
</html>
