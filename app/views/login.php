<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
            color: #333;
        }

        .login-container {
            background: #ffffff;
            padding: 3rem;
            border-radius: 12px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.05);
        }

        .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            margin-bottom: 1.5rem;
            height: 80px;
            width: auto;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #000;
        }

        .subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #000;
        }

        input {
            width: 100%;
            padding: 0.85rem;
            background: #fff;
            border: 1.5px solid #eaeaea;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 35px;
            background: none;
            border: none;
            cursor: pointer;
            color: #555;
            font-size: 1rem;
        }

        .login-button {
            width: 100%;
            padding: 0.9rem;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }

        .login-button:hover {
            background-color: #222;
            transform: translateY(-1px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .message {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .message.error {
            background: #fff0f0;
            color: #e00;
            border: 1px solid #fdd;
        }

        .message.success {
            background: #f0fff0;
            color: #0a0;
            border: 1px solid #dfd;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand">
            <img src="/public/img/logo.png" alt="Logo" class="brand-logo">
            <h1>Welcome Back</h1>
            <p class="subtitle">Please log in to your account</p>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="message <?= strpos($_SESSION['message'], 'error') !== false ? 'error' : 'success' ?>">
                <?= $_SESSION['message'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <?= csrf(); ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="username"
                    placeholder="Enter your username"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
            </div>

            <button type="submit" class="login-button">Sign In</button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = '👁️';
            }
        }
    </script>
</body>
</html>