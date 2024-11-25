<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>

<body>
    <h1>Edit User</h1>

    <!-- Ensure $data is populated -->
    <?php if (!empty($data)): ?>
        <form method="POST" action="/update">
            <input type="hidden" id="id" name="id" value="<?= $data['id'] ?>" required>
            <br><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= $data['username'] ?>" required>
            <br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?= $data['password'] ?>" required>
            <br><br>

            <button type="submit">Update</button>
        </form>
    <?php else: ?>
        <p>User not found. Please try again.</p>
    <?php endif; ?>

</body>

</html>