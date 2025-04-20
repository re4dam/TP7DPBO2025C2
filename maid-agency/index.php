<?php
require_once 'class/Maid.php';
require_once 'class/User.php';
require_once 'class/Transaction.php';

$maid = new Maid();
$user = new User();
$transaction = new Transaction();

// Handle form submissions for CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new maid
    if (isset($_POST['action']) && $_POST['action'] === 'add_maid') {
        $name = $_POST['name'] ?? '';
        $specialization = $_POST['specialization'] ?? '';
        $salary = $_POST['salary'] ?? 0;
        $availability = $_POST['availability'] ?? 'Available';

        $result = $maid->addMaid($name, $specialization, $salary, $availability, 0);
        if ($result) {
            header('Location: ?page=maids&message=Maid added successfully');
            // exit;
        }
    }

    // Update maid
    if (isset($_POST['action']) && $_POST['action'] === 'update_maid') {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $specialization = $_POST['specialization'] ?? '';
        $salary = $_POST['salary'] ?? 0;
        $availability = $_POST['availability'] ?? 'Available';

        $result = $maid->updateMaid($id, $name, $specialization, $salary, $availability, 0);
        if ($result) {
            header('Location: ?page=maids&message=Maid updated successfully');
            // exit;
        }
    }

    // Delete maid
    if (isset($_POST['action']) && $_POST['action'] === 'delete_maid') {
        $id = $_POST['id'] ?? 0;

        $result = $maid->deleteMaid($id);
        if ($result) {
            header('Location: ?page=maids&message=Maid deleted successfully');
            // exit;
        }
    }

    // Add new user
    if (isset($_POST['action']) && $_POST['action'] === 'add_user') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        $result = $user->addUser($name, $email, $phone);
        if ($result) {
            header('Location: ?page=users&message=User added successfully');
        }
    }

    // Update user
    if (isset($_POST['action']) && $_POST['action'] === 'update_user') {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        $result = $user->updateUser($id, $name, $email, $phone);
        if ($result) {
            header('Location: ?page=users&message=User added successfully');
        }
    }

    // Delete user
    if (isset($_POST['action']) && $_POST['action'] === 'delete_user') {
        $id = $_POST['id'] ?? 0;

        $result = $user->deleteUser($id);
        if ($result) {
            header('Location: ?page=users&message=User deleted successfully');
            // exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Maid Service Agency</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'views/page/header.php'; ?>
    <main>
        <h2>Welcome to Test</h2>
        <nav>
            <a href="?page=maids">Maids</a> |
            <a href="?page=users">Users</a> |
            <a href="?page=transactions">Orders</a>
        </nav>

        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == 'maids') include 'views/maids.php';
            elseif ($page == 'users') include 'views/users.php';
            elseif ($page == 'transactions') include 'views/transactions.php';
        }
        ?>
    </main>
    <?php include 'views/page/footer.php'; ?>
</body>

</html>
