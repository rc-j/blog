<?php
session_start();
include '../include/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['Category'];
    $query = 'INSERT INTO categories(category_name, created_by) VALUES (:category_name, :created_by)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':category_name', $category);
    $stmt->bindParam(':created_by', $_SESSION['admin']);
    $stmt->execute();
    echo '<div class="alert alert-success">Category ' . $category . ' added successfully.</div>';
    exit();
}
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
include '../include/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-xl-2 p-0">
            <?php
            include '../include/adminSidebar.php';
            ?>
        </div>

        <div class="col-auto">
            <button class="btn btn-warning" data-bs-toggle="collapse" data-bs-target="#categoryInput">New Category</button>
            <div class="collapse" id="categoryInput">
                <input type="text" class="form-control" id="category">
                <div id="category-validation"></div>
                <button class="btn btn-primary d-grid col-7 mx-auto" type="submit" onclick="handleCategory(event)">Add category</button>
            </div>
            <div class="list-group mt-5">
                All categories:
                <?php
                $query = 'SELECT category_name FROM categories ORDER BY category_id DESC';
                $colors = ['dark', 'info', 'secondary', 'success', 'danger', 'primary'];
                $count = 1;
                foreach ($db->query($query) as $row) {
                ?>
                    <a class="bg-<?= $colors[$count++ % 6] ?> text-light list-group-item list-group-item-action" href="<?= '/project/dashboard/categories.php?category=' . $row['category_name']; ?>">
                        <?= $row['category_name']; ?>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

</div>

<script>
    const handleCategory = (event) => {
        event.preventDefault()
        const formData = new FormData()
        let category = document.getElementById("category").value
        if (category !== "") {
            formData.append("Category", category)
            fetch('editCategory.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.text())
                .then(msg => document.getElementById("category-validation").innerHTML = msg)
        } else {
            document.getElementById("category-validation").innerHTML = `
        <div class="alert alert-danger">Category is empty!</div>
      `
        }
    }
</script>
<?php
include '../include/footer.php';
?>