<?php
global $pdo;
require_once __DIR__ . '/autoload.php';
if(!isset($_SESSION['user_id'])) {
    redirect('login.php');
}
include("includes/head.php");
$request = new FriendRequest($pdo);
$requests = $request->getPendingRequests($_SESSION['user_id']);
?>
<section id="section">
<div class="container-fluid my-5 py-1">
    <h2 class="mb-4 text-dm-sans-bold gradient-text-primary">Friend Requests</h2>

    <?php if (empty($requests)): ?>
        <div class="alert alert-info">You have no new friend requests.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($requests as $req): ?>
                <div class="col-12">
                    <div class="card notification-card p-3 d-flex flex-row align-items-center justify-content-between custom-bg-grey">
                        <div class="d-flex align-items-center gap-3">
                            <img src="./assets/img/uploads/users/<?= htmlspecialchars($req['sender_avatar'] ?? 'default.png') ?>" alt="avatar" class="notification-avatar">
                            <div>
                                <p class="text-white text-ubuntu-regular"><strong class="gradient-text-primary text-ubuntu-bold"><?= htmlspecialchars($req['sender_name']) ?></strong> sent you a friend request.</p>
                            </div>
                        </div>
                        <div>
                            <button class="btn custom-bg-success btn-sm me-2 respond-btn"
                                    data-id="<?= $req['id'] ?>" data-status="accepted">
                                <span class="text-white text-ubuntu-bold">Accept</span>
                            </button>
                            <button class="btn custom-bg-danger btn-sm respond-btn"
                                    data-id="<?= $req['id'] ?>" data-status="declined">
                                <span class="text-white text-ubuntu-bold">Decline</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</section>
<script>
    document.querySelectorAll('.respond-btn').forEach(button => {
        button.addEventListener('click', function () {
            const requestId = this.dataset.id;
            const status = this.dataset.status;
            const card = this.closest('.notification-card');

            fetch('includes/process_friend_request.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'respond',
                    request_id: requestId,
                    status: status
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.innerHTML = `<div class="text-success">Request ${status}.</div>`;
                    } else {
                        card.innerHTML = `<div class="text-danger">An error occurred.</div>`;
                    }
                });
        });
    });
</script>
<?php include("includes/foot.php"); ?>
