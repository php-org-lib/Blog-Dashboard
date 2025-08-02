<?php
global $pdo;
require("includes/config/db_config.php");
require("includes/config/functions.php");
require("classes/users.php");
include("includes/head.php");

$users = new Users($pdo);
$user_list = $users->getUsers();
?>
<section id="section">
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card custom-bg-lighter-dark shadow-primary shadow-xl border-radius-2xl">
                <div class="card-header pb-0 bg-gradient-dark text-gradient text-primary text-bold">
                    <div class="d-lg-flex">
                        <div>
                            <h5 class="mb-0 gradient-text-info text-ubuntu-condensed-bold">All Users</h5>
                            <p class="text-sm mb-0 gradient-text-white text-bold text-ubuntu-condensed-regular">
                                Website users list
                            </p>
                        </div>
                        <div class="ms-auto my-auto mt-lg-0 mt-4">

                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-responsive custom-bg-lighter-dark" id="products-list">
                            <thead class="thead-primary text-white">
                            <tr>
                                <th class="text-gradient text-primary text-bold">ID</th>
                                <th class="text-gradient text-primary text-bold">Personal</th>
                                <th class="text-gradient text-primary text-bold">Date Of Birth</th>
                                <th class="text-gradient text-primary text-bold">Role</th>
                                <th class="text-gradient text-primary text-bold">Username</th>
                                <th class="text-gradient text-primary text-bold">Joined/Updated</th>
                                <th class="text-gradient text-primary text-bold">Bio</th>
                                <th class="text-gradient text-primary text-bold">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($user_list) > 0) { ?>
                                <?php foreach($user_list as $user) {?>
                                    <tr>
                                        <td class="text-sm text-white"><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="./assets/img/uploads/users/<?= htmlspecialchars($user['avatar'] ?? ''); ?>" class="avatar avatar-sm me-3" alt="<?= htmlspecialchars($user['username'] ?? ''); ?>'s Avatar">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs gradient-text-info text-dm-sans-bold"><?= htmlspecialchars($user['full_name'] ?? ''); ?></h6>
                                                    <p class="text-xs gradient-text-info mb-0 text-dm-sans-bold"><?= htmlspecialchars($user['email'] ?? ''); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm text-white"><?= htmlspecialchars($user['dob'] ?? ''); ?></td>
                                        <td class="text-sm text-white"><?= htmlspecialchars($user['role'] ?? ''); ?></td>
                                        <td class="text-sm text-white"><?= htmlspecialchars($user['username'] ?? ''); ?></td>

                                        <td>
                                            <div class="d-block px-2 py-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span class="left text-dm-sans-bold gradient-text-primary">Created</span> &nbsp; <span class="text-center gradient-text-warning">|</span> &nbsp; <span class="right text-dm-sans-bold gradient-text-primary">Updated</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <p class="text-xs gradient-text-info mb-0 text-dm-sans-bold">
                                                                <span class="left gradient-text-warning text-dm-sans-bold"><?= date('m-d-Y', strtotime($user['date_created'])); ?></span>&nbsp;
                                                                <span class="gradient-text-primary text-center"> | </span> &nbsp;
                                                                <span class="right gradient-text-warning text-dm-sans-bold"><?= date('m-d-Y', strtotime($user['date_updated'] ?? date('m-d-Y'))); ?></span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm text-white"><?= truncate_words($user['bio'], 5); ?></td>
                                        <td class="text-sm d-inline">
                                            <a href="profile.php?id=<?= htmlspecialchars($user['id']); ?>" data-bs-toggle="tooltip" data-bs-original-title="Preview user with the id:<?= htmlspecialchars($user['id']); ?>" class="btn btn-sm circle-12-rounded bg-gradient-info">
                                                <i class="fa-solid fa-list text-white text-sm"></i>
                                            </a>
                                            <a href="update_user.php?id=<?= htmlspecialchars($user['id']); ?>" class="btn btn-sm circle-12-rounded bg-gradient-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit user with the id: <?= htmlspecialchars($user['id']); ?>">
                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                            </a>
                                            <form action="includes/process_delete_user.php" method="POST"  class="d-inline">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']); ?>">
                                                <button type="submit" class="btn btn-sm circle-12-rounded bg-gradient-danger" data-bs-toggle="tooltip" data-bs-original-title="Delete user with the id: <?= htmlspecialchars($user['id']); ?>">
                                                    <i class="fa-solid fa-trash text-white text-sm"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td>
                                        <div class="custom-bg-warning">
                                            <p class="text-ubuntu-regular text-white">No users have been added to the system yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?php include("includes/foot.php"); ?>
