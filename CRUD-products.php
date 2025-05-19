                                                                                                                                                                                                                   <?php
$conn = new mysqli("localhost", "root", "", "r64_exam");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ADD PRODUCT
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $conn->query("INSERT INTO products (name, price, quantity) VALUES ('$name', '$price', '$quantity')");
}

// DELETE PRODUCT
if (isset($_GET['delete'])) {
   $id= $_GET['delete'];
    $conn->query("DELETE FROM products WHERE name='$id'");
}

// FETCH SINGLE PRODUCT FOR EDITING
$edit_product = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $edit_product = $result->fetch_assoc();
}

// UPDATE PRODUCT
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $conn->query("UPDATE products SET name='$name', price='$price', quantity='$quantity' WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Manager</title>
    <style>
       
           
           form input{
            margin: 2px;
            padding: 2px;
          
           }
    </style>
</head>
<body>
    <h2><?php echo $edit_product ? 'Edit' : 'Add'; ?> Product</h2>
    <form method="POST">
       <?php if ($edit_product): ?>
            <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
        <?php endif; ?>
        Name: <input type="text" name="name" value="<?php echo $edit_product['name'] ?? ''; ?>" required><br>
        Price: <input type="text" name="price" value="<?php echo $edit_product['price'] ?? ''; ?>" required><br>
        Quantity: <input type="text" name="quantity" value="<?php echo $edit_product['quantity'] ?? ''; ?>" required><br>
        <button type="submit" name="<?php echo $edit_product ? 'update' : 'add'; ?>">
            <?php echo $edit_product ? 'Update' : 'Add'; ?>
        </button>
        <!-- clear button -->
        <button type="button" onclick="window.location.href = 'CRUD-products.php'">Clear</button>
    </form>

    <h2>Product List</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Actions</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
