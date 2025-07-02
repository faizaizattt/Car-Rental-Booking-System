<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'] ?? '';
$role = $_SESSION['role'] ?? 'customer';

$feedback_message = $feedback_error = '';
$feedbackList = [];

// Handle feedback form submission (for customers only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $role === 'customer') {
    $feedback_text = trim($_POST['feedback']);
    $rating = intval($_POST['rating']);

    if (!empty($feedback_text) && $rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, feedback_text, rating) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $feedback_text, $rating]);
        $feedback_message = "Thank you for your feedback!";
    } else {
        $feedback_error = "Please enter your feedback and select a rating.";
    }
}

// Fetch feedback list (admin only)
if ($role === 'admin') {
    $sql = "SELECT f.feedback_text, f.created_at, f.rating, u.name 
            FROM feedback f
            JOIN users u ON f.user_id = u.id
            ORDER BY f.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $feedbackList = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star-rating {
            font-size: 3rem;
            direction: rtl;
            display: flex;
            justify-content: center;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4"><?= $role === 'admin' ? 'Customer Feedback Overview' : 'We Value Your Feedback' ?></h2>

    <?php if ($role === 'customer'): ?>
        <?php if ($feedback_message) echo "<div class='alert alert-success'>$feedback_message</div>"; ?>
        <?php if ($feedback_error) echo "<div class='alert alert-danger'>$feedback_error</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Rating:</label>
                <div class="star-rating">
                    <input type="radio" id="5-stars" name="rating" value="5"><label for="5-stars">&#9733;</label>
                    <input type="radio" id="4-stars" name="rating" value="4"><label for="4-stars">&#9733;</label>
                    <input type="radio" id="3-stars" name="rating" value="3"><label for="3-stars">&#9733;</label>
                    <input type="radio" id="2-stars" name="rating" value="2"><label for="2-stars">&#9733;</label>
                    <input type="radio" id="1-star"  name="rating" value="1"><label for="1-star">&#9733;</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="feedback" class="form-label">Your Feedback</label>
                <textarea name="feedback" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
            </div>


            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>
    <?php else: ?>
        <?php if (count($feedbackList) > 0): ?>
            <style>
                .star-cell-admin {
                    color: gold;
                    font-size: 1.5rem;
                    letter-spacing: 2px;
                }
            </style>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Customer Name</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbackList as $feedback): ?>
                        <tr>
                            <td><?= htmlspecialchars($feedback['name']) ?></td>
                            <td class="star-cell-admin">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?= $i <= $feedback['rating'] ? "&#9733;" : "&#9734;" ?>
                                <?php endfor; ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($feedback['feedback_text'])) ?></td>
                            <td><?= $feedback['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No feedback has been submitted yet.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
