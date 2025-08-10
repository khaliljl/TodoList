<?php
$host = "localhost";
$user = "root"; // default in XAMPP
$pass = "";     // default in XAMPP
$dbname = "todolist";

$conn = new mysqli($host, $user, $pass, $dbname);
if (isset($_POST['add'])) {
    $title = trim($_POST['title']);
    if ($title != "") {
        $stmt = $conn->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->bind_param("s", $title);
        $stmt->execute();
    }
    header("Location: index.php");
    exit;
}


if (isset($_GET['done'])) {
    $id = (int)$_GET['done'];
    $conn->query("UPDATE todos SET done = 1 - done WHERE id = $id");
    header("Location: index.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM todos WHERE id = $id");
    header("Location: index.php");
    exit;
}


$result = $conn->query("SELECT * FROM todos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Todo List</h1>

    <form method="POST">
        <input type="text" name="title" placeholder="New task..." required>
        <button type="submit" name="add">Add</button>
    </form>

    <ul>
    <?php while($row = $result->fetch_assoc()): ?>
        <li>
            <span class="<?php echo $row['done'] ? 'done' : ''; ?>">
                <?php echo htmlspecialchars($row['title']); ?>
            </span>
            <span>
                <a href="?done=<?php echo $row['id']; ?>" class="done-btn">
                    <?php echo $row['done'] ? "Undo" : "Done"; ?>
                </a>
                <a href="?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Delete this task?')">Delete</a>
            </span>
        </li>
    <?php endwhile; ?>
    </ul>
</div>
</body>

</html>
