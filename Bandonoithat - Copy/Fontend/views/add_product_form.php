<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm</title>
    <style>
        /* CSS giữ nguyên như bạn đã viết */
    </style>
</head>
<body>
    <h3><?php echo $product ? 'Sửa Sản Phẩm' : 'Thêm Sản Phẩm'; ?></h3>
    <form action="../modules/add_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product ? $product['id'] : ''; ?>">
        
        <label for="product_code">Mã sản phẩm:</label>
        <input type="text" id="product_code" name="product_code" value="<?php echo $product ? $product['product_code'] : ''; ?>" required>

        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" value="<?php echo $product ? $product['name'] : ''; ?>" required>

        <label for="images">Ảnh sản phẩm:</label>
        <input type="file" id="images" name="images[]" accept="image/*" multiple required>

        <label for="description">Mô tả:</label>
        <textarea id="description" name="description"><?php echo $product ? $product['description'] : ''; ?></textarea>

        <label for="quantity">Số lượng:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $product ? $product['quantity'] : '0'; ?>" required>

        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo $product ? $product['price'] : ''; ?>" required>

        <label for="percentage_discount">Giảm giá (%):</label>
        <input type="number" id="percentage_discount" name="percentage_discount" step="0.01" value="<?php echo $product ? $product['percentage_discount'] : '0'; ?>">

        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" required>
            <option value="">Chọn danh mục</option>
            <?php
            // Lấy danh sách danh mục từ cơ sở dữ liệu nếu có
            $sql_categories = "SELECT * FROM product_catalog";
            $result_categories = $conn->query($sql_categories);
            while ($category = $result_categories->fetch_assoc()) {
                $selected = $product && $product['category_id'] == $category['id'] ? 'selected' : '';
                echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
            }
            ?>
        </select>

        <input type="submit" value="<?php echo $product ? 'Cập Nhật' : 'Thêm'; ?>">
    </form>

    <a href="../index.php">Trở về danh sách sản phẩm</a>
</body>
</html>
