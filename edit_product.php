<?php 
include 'connection.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM products WHERE id='$id'");
    $row = $result->fetch_assoc();


}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3 class="mb-4">Edit Product</h3>

    <form id="editProductForm">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= $row['description'] ?></textarea>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $row['price'] ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="admin.php" class="btn btn-secondary">Cancel</a>
    </form>
    <audio id="clickSound" src="click.mp3" preload="auto"></audio>

    <script>

        const form = document.getElementById("editProductForm");
        const sound = document.getElementById("clickSound");

        form.addEventListener("submit", function(e) {
            e.preventDefault();

            sound.currentTime = 0;
            sound.play().then(() => {
                setTimeout(() => {
                    const formData = new FormData(form);

                    fetch("update_product.php", {
                        method: "POST",
                        body: formData
                    }).then(() => {
                        window.location.href = "admin.php";
                    });
                }, 1000);
            }).catch(() => {
                form.submit(); // fallback
            });
        });

        document.querySelector("a.btn-secondary").addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            sound.currentTime = 0;
            sound.play().then(() => {
                setTimeout(() => {
                    window.location.href = url;
                }, 1000);
            }).catch(() => {
                window.location.href = url;
            });
        });
    </script>
</body>
</html>

    </script>