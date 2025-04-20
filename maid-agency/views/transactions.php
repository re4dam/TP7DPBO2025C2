<?php
// Helper functions for status badges
function getStatusBadge($status)
{
    switch ($status) {
        case 'completed':
            return 'success';
        case 'confirmed':
            return 'primary';
        case 'pending':
            return 'warning';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}


// Get search term if exists
$searchTerm = $_GET['search'] ?? '';

// Check if we're in edit mode
$editMode = false;
$editTransactionData = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editMode = true;
    $editTransactionData = $transaction->getTransactionById($_GET['edit']);
    if (!$editTransactionData) {
        echo "<p>Transaction not found.</p>";
        $editMode = false;
    }
}

// Get all maids and users for dropdown selections
$allMaids = $maid->getAllMaids();
$allUsers = $user->getAllUsers();
?>

<h3>Transactions List</h3>

<!-- Search Form -->
<div class="search-container mb-4">
    <form method="get" action="">
        <div class="input-group">
            <input type="hidden" name="page" value="transactions">
            <input type="text"
                class="form-control"
                name="search"
                placeholder="Search by maid, customer or job type..."
                value="<?= htmlspecialchars($searchTerm) ?>">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($searchTerm)): ?>
                <a href="?page=transactions" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (!empty($searchTerm)): ?>
    <div class="alert alert-info mb-3">
        Showing results for: <strong><?= htmlspecialchars($searchTerm) ?></strong>
    </div>
<?php endif; ?>

<table border="1" class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Maid</th>
            <th>Customer</th>
            <th>Job Type</th>
            <th>Description</th>
            <th>Location</th>
            <th>Date/Time</th>
            <th>Status</th>
            <th>Amount (RP)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Get transactions (either all or filtered)
        $transactions = !empty($searchTerm)
            ? $transaction->searchTransactions($searchTerm)
            : $transaction->getAllTransactions();

        if (count($transactions) > 0):
            foreach ($transactions as $tr):
        ?>
                <tr>
                    <td><?= htmlspecialchars($tr['id']) ?></td>
                    <td><?= htmlspecialchars($tr['maid_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($tr['user_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($tr['job_type']) ?></td>
                    <td><?= htmlspecialchars($tr['description']) ?></td>
                    <td><?= htmlspecialchars($tr['address_of_job']) ?></td>
                    <td><?= date('d M Y', strtotime($tr['date'])) ?></td>
                    <td>
                        <span class="badge bg-<?= getStatusBadge($tr['status']) ?>">
                            <?= ucfirst($tr['status']) ?>
                        </span>
                    </td>
                    <td class="text-end">Rp. <?= number_format($tr['total_cost'], 2) ?></td>
                    <td>
                        <a href="?page=transactions&edit=<?= $tr['id'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">Edit</a>

                        <form method="post" action="" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                            <input type="hidden" name="action" value="delete_transaction">
                            <input type="hidden" name="id" value="<?= $tr['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="11" class="text-center py-4">
                    <div class="alert alert-warning mb-0">
                        No transactions found <?= !empty($searchTerm) ? 'matching your search' : '' ?>.
                    </div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Add/Edit Transaction Form -->
<div class="form-container mb-4">
    <h4><?php echo $editMode ? 'Edit Transaction' : 'Add New Transaction'; ?></h4>
    <form method="post" action="">
        <input type="hidden" name="action" value="<?php echo $editMode ? 'update_transaction' : 'add_transaction'; ?>">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $editTransactionData['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="id_maid">Maid:</label>
            <select name="id_maid" required>
                <option value="">Select Maid</option>
                <?php foreach ($allMaids as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $editMode && $editTransactionData['id_maid'] == $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_user">Customer:</label>
            <select name="id_user" required>
                <option value="">Select Customer</option>
                <?php foreach ($allUsers as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $editMode && $editTransactionData['id_user'] == $u['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="job_type">Job Type:</label>
            <input type="text" name="job_type" value="<?= $editMode ? htmlspecialchars($editTransactionData['job_type']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <input type="text" name="description" value="<?= $editMode ? htmlspecialchars($editTransactionData['description']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Location/Address:</label>
            <input type="text" name="address" value="<?= $editMode ? htmlspecialchars($editTransactionData['address_of_job']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" name="date"
                value="<?= $editMode ? date('Y-m-d', strtotime($editTransactionData['date'])) : '' ?>"
                required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="pending" <?= $editMode && $editTransactionData['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="confirmed" <?= $editMode && $editTransactionData['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="completed" <?= $editMode && $editTransactionData['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $editMode && $editTransactionData['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="form-group">
            <label for="total_cost">Amount (RP):</label>
            <input type="number" step="0.01" name="total_cost" value="<?= $editMode ? $editTransactionData['total_cost'] : '' ?>" required>
        </div>

        <button type="submit"><?php echo $editMode ? 'Update Transaction' : 'Add Transaction'; ?></button>
        <?php if ($editMode): ?>
            <a href="?page=transactions">Cancel</a>
        <?php endif; ?>
    </form>
</div>
