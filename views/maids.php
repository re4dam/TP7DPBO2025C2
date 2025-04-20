<?php
// Get and sanitize search input
$search = isset($_GET['search']) ? trim(htmlspecialchars($_GET['search'])) : '';

// Get maids data based on search
$maids = ($search !== '')
    ? $maid->searchMaids($search)
    : $maid->getAllMaids();
?>

<h3>Maid List</h3>

<!-- Simple Search Form -->
<form method="get" action="">
    <input type="hidden" name="page" value="maids">
    <input type="text"
        name="search"
        placeholder="Search by name or specialization..."
        value="<?= $search ?>">
    <button type="submit">Search</button>
    <?php if (!empty($search)): ?>
        <a href="?page=maids">Reset</a>
    <?php endif; ?>
</form>

<!-- Search Feedback -->
<?php if (!empty($search)): ?>
    <p>Found <?= count($maids) ?> maid(s) matching "<?= $search ?>"</p>
<?php endif; ?>

<!-- Maids Table -->
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Salary (RM)</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($maids)): ?>
            <?php foreach ($maids as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['name']) ?></td>
                    <td><?= htmlspecialchars($m['specialization']) ?></td>
                    <td><?= number_format($m['salary'], 2) ?></td>
                    <td><?= ucfirst($m['availability_status']) ?></td>
                    <td>
                        <a href="?page=maids&edit=<?= $m['id'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">Edit</a>

                        <form method="post" action="" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this maid?')">
                            <input type="hidden" name="action" value="delete_maid">
                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">
                    No maids found <?= !empty($search) ? 'matching your search' : 'in the system' ?>.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
// Check if we're in edit mode
$editMode = false;
$editMaidData = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editMode = true;
    $editMaidData = $maid->getMaidById($_GET['edit']);
    if (!$editMaidData) {
        echo "<p>Maid not found.</p>";
        $editMode = false;
    }
}

?>

<!-- Add/Edit Maid Form -->
<div class="form-container mb-4">
    <h4><?php echo $editMode ? 'Edit Maid' : 'Add New Maid'; ?></h4>
    <form method="post" action="">
        <input type="hidden" name="action" value="<?php echo $editMode ? 'update_maid' : 'add_maid'; ?>">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $editMaidData['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= $editMode ? htmlspecialchars($editMaidData['name']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="specialization">Specialization:</label>
            <input type="text" name="specialization" value="<?= $editMode ? htmlspecialchars($editMaidData['specialization']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="salary">Salary:</label>
            <input type="number" name="salary" value="<?= $editMode ? $editMaidData['salary'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="availability">Availability:</label>
            <select name="availability">
                <option value="Available" <?= $editMode && $editMaidData['availability_status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Unavailable" <?= $editMode && $editMaidData['availability_status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                <option value="On Leave" <?= $editMode && $editMaidData['availability_status'] == 'On Leave' ? 'selected' : '' ?>>On Leave</option>
            </select>
        </div>
        <button type="submit"><?php echo $editMode ? 'Update Maid' : 'Add Maid'; ?></button>
        <?php if ($editMode): ?>
            <a href="?page=maids">Cancel</a>
        <?php endif; ?>
    </form>
</div>
