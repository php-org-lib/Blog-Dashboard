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
                    <p>You have no friends. Add some to populate this page.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</section>
</section>
<?php include("includes/foot.php"); ?>
