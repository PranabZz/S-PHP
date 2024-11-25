<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <style>
        :root {
            --primary-color: #4a90e2;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --background-color: #f5f6fa;
            --card-background: #ffffff;
            --text-color: #2c3e50;
            --border-radius: 8px;
            --box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: var(--text-color);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            font-weight: 600;
        }

        h2 {
            color: var(--text-color);
            margin: 2rem 0 1rem;
            font-size: 1.5rem;
            font-weight: 500;
        }

        .card {
            background: var(--card-background);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .create-form {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        input[type="text"],
        input[type="password"] {
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            flex: 1;
            min-width: 200px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        button {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-create {
            background-color: var(--success-color);
            color: white;
        }

        .btn-edit {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-background);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        th {
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-top: 1px solid #eee;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .actions form {
            margin: 0;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .create-form {
                flex-direction: column;
            }

            .actions {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Management System</h1>
        <form method="POST" action="/logout" class="create-form">
            <button type="submit" class="btn-create">Logout User</button>
        </form>
        <br>
        <div class="card">
            <h2>Add New User</h2>
            <form method="POST" action="/create" class="create-form">
                <input type="text" name="username" placeholder="Enter username" required>
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit" class="btn-create">Create User</button>
            </form>
        </div>

        <div class="card">
            <h2>User List</h2>
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
                                <td class="actions">
                                    <button type="button" class="btn-edit">
                                        <a href="/edit/<?= $user['id'] ?>">Edit</a>
                                    </button>
                                    <form method="POST" action="/delete">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty-state">No users found. Create a new user to get started.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>