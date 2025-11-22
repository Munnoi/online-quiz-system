<?php
session_start();
include("../config/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include("../includes/header.php");

// Fetch all users
$query = mysqli_query($conn, "
    SELECT * FROM users
    ORDER BY user_id DESC
");
?>

<div class="page-content">

    <h2 class="text-center mb-4 admin-title">Manage Users</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger text-center mb-3">
            User deleted successfully!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['role_updated'])): ?>
        <div class="alert alert-info text-center mb-3">
            User role updated!
        </div>
    <?php endif; ?>

    <div class="admin-table-wrapper">

        <table class="table-dark-aesthetic">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $i = 1;
                while ($user = mysqli_fetch_assoc($query)): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>

                    <td><?php echo $user['name']; ?></td>

                    <td><?php echo $user['email']; ?></td>

                    <td class="<?php echo ($user['role'] == 'admin') ? 'text-danger fw-bold' : 'text-success fw-bold'; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </td>

                    <td><?php echo $user['created_at']; ?></td>

                    <td>

                        <a href="update_role.php?user_id=<?php echo $user['user_id']; ?>" 
                           class="btn btn-sm btn-warning admin-btn mb-1">
                            Change Role
                        </a>

                        <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>" 
                           class="btn btn-sm btn-danger admin-btn mb-1"
                           onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete
                        </a>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
