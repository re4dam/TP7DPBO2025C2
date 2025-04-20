<?php
// Get and sanitize search input
$search = isset($_GET['search']) ? trim(htmlspecialchars($_GET['search'])) : '';

// Get users data based on search
$users = ($search !== '')
    ? $user->searchUsers($search)
    : $user->getAllUsers();

// Check for error message in session or query parameters
$errorMsg = $_GET['error'] ?? '';
$successMsg = $_GET['message'] ?? '';
?>

<h3>Users List</h3>

<!-- Display error/success messages -->
<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger mb-3">
        <?= htmlspecialchars($errorMsg) ?>
    </div>
<?php endif; ?>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success mb-3">
        <?= htmlspecialchars($successMsg) ?>
    </div>
<?php endif; ?>

<!-- Search Form -->
<form method="get" action="">
    <input type="hidden" name="page" value="users">
    <input type="text"
        name="search"
        placeholder="Search by name, email or phone..."
        value="<?= $search ?>">
    <button type="submit">Search</button>
    <?php if (!empty($search)): ?>
        <a href="?page=users">Reset</a>
    <?php endif; ?>
</form>

<!-- Search Feedback -->
<?php if (!empty($search)): ?>
    <p>Found <?= count($users) ?> user(s) matching "<?= $search ?>"</p>
<?php endif; ?>

<!-- Users Table -->
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['phone']) ?></td>
                    <td>
                        <a href="?page=users&edit=<?= $u['id'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">Edit</a>

                        <?php if (!$user->hasUserTransactions($u['id'])): ?>
                            <form method="post" action="" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="disabled-btn" title="Cannot delete: This user has associated transactions" disabled>Delete</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">
                    No users found <?= !empty($search) ? 'matching your search' : 'in the system' ?>.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Check if we're in edit mode
$editMode = false;
$editUserData = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editMode = true;
    $editUserData = $user->getUserById($_GET['edit']);
    if (!$editUserData) {
        echo "<p>User not found.</p>";
        $editMode = false;
    }
}
?>

<!-- Add/Edit User Form -->
<div class="form-container mb-4">
    <h4><?php echo $editMode ? 'Edit User' : 'Add New User'; ?></h4>
    <form method="post" action="">
        <input type="hidden" name="action" value="<?php echo $editMode ? 'update_user' : 'add_user'; ?>">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $editUserData['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= $editMode ? htmlspecialchars($editUserData['name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= $editMode ? htmlspecialchars($editUserData['email']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" name="phone" value="<?= $editMode ? htmlspecialchars($editUserData['phone']) : '' ?>" required>
        </div>

        <button type="submit"><?php echo $editMode ? 'Update User' : 'Add User'; ?></button>
        <?php if ($editMode): ?>
            <a href="?page=users">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<!-- Add styles for disabled button -->
<style>
    .disabled-btn {
        background-color: #ccc;
        color: #666;
        cursor: not-allowed;
    }
</style>
