<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Thay đổi nếu cần
$password = "123456"; // Thay đổi nếu cần
$dbname = "bandonoithat"; // Thay đổi thành tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $sql_delete = "DELETE FROM products WHERE id = $delete_id";
    $conn->query($sql_delete);
    header("Location: ../index.php"); // Chuyển hướng về trang chính
    exit();
}

// Biến lưu thông tin sản phẩm để sửa
$product = null;

// Xử lý sửa sản phẩm
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql_edit = "SELECT * FROM products WHERE id = $edit_id";
    $result_edit = $conn->query($sql_edit);
    $product = $result_edit->fetch_assoc();
}

// Xử lý thêm hoặc sửa sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_code = $_POST['product_code'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $percentage_discount = $_POST['percentage_discount'];
    $category_id = $_POST['category_id'];

    // Xử lý tải ảnh lên
    $target_dir = "../uploads/"; // Đảm bảo đường dẫn đúng
    $image_urls = []; // Mảng để lưu các URL hình ảnh

    // Kiểm tra nếu có hình ảnh mới được tải lên
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $target_file = $target_dir . basename($_FILES["images"]["name"][$key]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra loại tệp
            if (isset($_POST["submit"])) {
                $check = getimagesize($tmp_name);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "Tệp không phải là ảnh.";
                    $uploadOk = 0;
                }
            }

            // Kiểm tra nếu tệp đã tồn tại
            if (file_exists($target_file)) {
                echo "Xin lỗi, tệp đã tồn tại.";
                $uploadOk = 0;
            }

            // Kiểm tra kích thước tệp
            if ($_FILES["images"]["size"][$key] > 500000) {
                echo "Xin lỗi, tệp của bạn quá lớn.";
                $uploadOk = 0;
            }

            // Cho phép các định dạng tệp cụ thể
            if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                echo "Xin lỗi, chỉ cho phép các tệp JPG, JPEG, PNG & GIF.";
                $uploadOk = 0;
            }

            // Tải tệp lên
            if ($uploadOk == 1) {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $image_urls[] = $target_file; // Lưu URL hình ảnh vào mảng
                }
            }
        }
    }

    // Tạo chuỗi URL hình ảnh
    $image_url_string = implode(',', $image_urls);

    // Thêm hoặc sửa sản phẩm vào cơ sở dữ liệu
    if (isset($_POST['product_id']) && $_POST['product_id']) {
        $product_id = $_POST['product_id'];
        // Cập nhật sản phẩm, bao gồm cả hình ảnh mới nếu có
        $sql = "UPDATE products SET product_code='$product_code', name='$name', description='$description', quantity=$quantity, price=$price, percentage_discount=$percentage_discount, category_id=$category_id" . 
               (!empty($image_url_string) ? ", image='$image_url_string'" : "") . 
               " WHERE id=$product_id";
    } else {
        $sql = "INSERT INTO products (product_code, name, description, quantity, price, percentage_discount, category_id, image) VALUES ('$product_code', '$name', '$description', $quantity, $price, $percentage_discount, $category_id, '$image_url_string')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Sản phẩm đã được thêm/sửa thành công!";
        header("Location: ../index.php"); // Chuyển hướng về trang chính
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$conn->close();
?>
