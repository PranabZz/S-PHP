<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 16px;
        }

        .login-container {
            background: #f9f9f9;
            padding: 2rem;
            border-radius: 10px;
            max-width: 360px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #111;
        }

        .subtitle {
            text-align: center;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            color: #333;
        }

        input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
            font-size: 1rem;
        }

        input:focus {
            border-color: #000;
            outline: none;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            background: none;
            border: none;
            font-size: 0.9rem;
            cursor: pointer;
            color: #000;
        }

        .form-group {
            position: relative;
        }

        .login-button {
            width: 100%;
            padding: 0.9rem;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background-color: #333;
        }

        .message {
            text-align: center;
            padding: 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .message.error {
            background: #fdd;
            color: #a00;
            border: 1px solid #faa;
        }

        .message.success {
            background: #dfd;
            color: #080;
            border: 1px solid #afa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>

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
                >
            </div>

            <div class="form-group">
                <label for="username">Email</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autocomplete="username"
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
                >
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
            </div>

            <button type="submit" class="login-button">Log In</button>
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
