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
        }
    }

    // Delete maid
    if (isset($_POST['action']) && $_POST['action'] === 'delete_maid') {
        $id = $_POST['id'] ?? 0;

        $result = $maid->deleteMaid($id);
        if ($result) {
            header('Location: ?page=maids&message=Maid deleted successfully');
            exit;
        } else {
            header('Location: ?page=maids&error=Cannot delete maid: This maid has associated transactions');
            exit;
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
        }
    }

    // Add new transaction
    if (isset($_POST['action']) && $_POST['action'] === 'add_transaction') {
        $id_maid = $_POST['id_maid'] ?? 0;
        $id_user = $_POST['id_user'] ?? 0;
        $job_type = $_POST['job_type'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $date = $_POST['date'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $total_cost = $_POST['total_cost'] ?? 0;

        $result = $transaction->addTransaction(
            $id_maid,
            $id_user,
            $job_type,
            $description,
            $address,
            $date,
            $status,
            $total_cost
        );

        if ($result) {
            header('Location: ?page=transactions&message=Transaction added successfully');
        }
    }

    // Update transaction
    if (isset($_POST['action']) && $_POST['action'] === 'update_transaction') {
        $id = $_POST['id'] ?? 0;
        $id_maid = $_POST['id_maid'] ?? 0;
        $id_user = $_POST['id_user'] ?? 0;
        $job_type = $_POST['job_type'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $date = $_POST['date'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $total_cost = $_POST['total_cost'] ?? 0;

        $result = $transaction->updateTransaction(
            $id,
            $id_maid,
            $id_user,
            $job_type,
            $description,
            $address,
            $date,
            $status,
            $total_cost
        );

        if ($result) {
            header('Location: ?page=transactions&message=Transaction updated successfully');
        }
    }

    // Delete transaction
    if (isset($_POST['action']) && $_POST['action'] === 'delete_transaction') {
        $id = $_POST['id'] ?? 0;

        $result = $transaction->deleteTransaction($id);
        if ($result) {
            header('Location: ?page=transactions&message=Transaction deleted successfully');
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
        <h2>Premium Maid Services At Your Fingertips</h2>
        <nav>
            <a href="/">Home</a> |
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
