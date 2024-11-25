<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        form {
            display: inline-block;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
        }
    </style>
</head>

<body>
    <h1>CRUD Operations</h1>

    <h2>Create User</h2>
    <form method="POST" action="/create">
        <input type="text" name="username" placeholder="Enter username" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Create</button>
    </form>

    <h2>Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td>
                            <!-- Fixed Edit Form -->
                            <form method="GET" action="/edit">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit">Edit</button>
                            </form>

                            <!-- Fixed Delete Form -->
                            <form method="POST" action="/delete">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>
