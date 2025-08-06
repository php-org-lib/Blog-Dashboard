<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
include("includes/head.php");
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
$users = new Users($pdo);
$friendRequests = new FriendRequest($pdo);
$friends = $friendRequests->getFriends($_SESSION['user_id']);
$pendingRequests = $friendRequests->getPendingRequests($_SESSION['user_id']);
$availableUsers = $users->getUsersYouCanAddAsFriends($_SESSION['user_id']);
$friendCount = count($friends);
$pendingRequestCount = count($pendingRequests);
?>

<section id="section">
<section class="overflow-x mt-5 mb-5">
<div class="container-fluid py-1">
    <div class="row mt-3 mb-3">
        <div class="col-12">
            <div class="horizontal-friends-list">
                <?php if($friendCount > 0 || $pendingRequestCount > 0) { ?>
                    <?php foreach ($friends as $friend) { ?>

                        <figure>
                            <picture>
                                <img src="./assets/img/uploads/users/<?= htmlspecialchars($friend['avatar'] ?? ''); ?>" alt="<?= htmlspecialchars($friend['username'] ?? '') ?>'s avatar">
                            </picture>
                            <figcaption>
                                <a href="profile.php?id=<?= htmlspecialchars($friend['id'] ?? ''); ?>">
                                    <?= htmlspecialchars($friend['username'] ?? ''); ?>
                                </a>
                            </figcaption>
                        </figure>

                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <p>You have no friends. Add some to populate this page.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</section>
    <div class="container-fluid py-1">
       <div class="card custom-bg-dark shadow-xl">
           <div class="card-body">
               <div class="row">
                   <?php if(!empty($availableUsers)) { ?>
                       <?php foreach($availableUsers as $u) { ?>
                           <div class="col-12 col-md-2" data-bs-original-title="<?= $u['full_name']; ?>">
                               <table class="table table-hover table-striped table-borderless" style="overflow-x: hidden !important;">
                                   <tr>
                                       <td>
                                           <div class="d-flex px-2 py-1">
                                               <div>
                                                   <img src="./assets/img/uploads/users/<?= htmlspecialchars($u['avatar'] ?? ''); ?>" class="avatar avatar-sm me-3" alt="<?= htmlspecialchars($u['username'] ?? ''); ?>'s Avatar">
                                               </div>
                                               <div class="d-flex flex-column justify-content-center">
                                                   <h6 class="mb-0 text-xs gradient-text-info text-dm-sans-bold"><?= htmlspecialchars($u['full_name'] ?? ''); ?></h6>
                                                   <p class="text-xs gradient-text-info mb-0 text-dm-sans-bold"><?= htmlspecialchars($u['email'] ?? ''); ?></p>
                                               </div>
                                           </div>
                                       </td>

                                   </tr>
                               </table>
                           </div>
                       <?php } ?>
                   <?php } else { ?>
                       <p class="p-1 mt-1 gradient-text-info">There is no one else to add.</p>
                   <?php } ?>
               </div>
           </div>
       </div>
    </div>
</section>
<?php include("includes/foot.php"); ?>
