<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$kode = $_GET['kode'] ?? '';
if (!$kode) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tbl_product WHERE kode = ?");
$stmt->execute([$kode]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "../../uploads/products/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $foto = $product['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        if ($foto && file_exists($uploadDir . $foto)) {
            unlink($uploadDir . $foto);
        }
        $tempName = $_FILES['foto']['tmp_name'];
        $fileName = uniqid() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($tempName, $uploadDir . $fileName);
        $foto = $fileName;
    }

    $stmt = $conn->prepare("UPDATE tbl_product SET 
        merk = ?, kategori = ?, satuan = ?, hargabeli = ?, 
        diskonbeli = ?, hargapokok = ?, hargajual = ?, 
        diskonjual = ?, stok = ?, foto = ?, deskripsi = ?
        WHERE kode = ?");

    $stmt->execute([
        $_POST['merk'],
        $_POST['kategori'],
        $_POST['satuan'],
        $_POST['hargabeli'],
        $_POST['diskonbeli'],
        $_POST['hargapokok'],
        $_POST['hargajual'],
        $_POST['diskonjual'],
        $_POST['stok'],
        $foto,
        $_POST['deskripsi'],
        $kode
    ]);

    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold">Admin Dashboard</h1>
                        </div>
                        <div class="hidden md:ml-6 md:flex md:space-x-8">
                            <a href="../dashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="index.php" class="border-b-2 border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                                Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Code</label>
                                <input type="text" value="<?php echo htmlspecialchars($product['kode']); ?>" disabled
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Product Name</label>
                                <input type="text" name="merk" required value="<?php echo htmlspecialchars($product['merk']); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" name="kategori" required value="<?php echo htmlspecialchars($product['kategori']); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Unit</label>
                                <input type="text" name="satuan" required value="<?php echo htmlspecialchars($product['satuan']); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purchase Price</label>
                                <input type="number" name="hargabeli" required value="<?php echo $product['hargabeli']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purchase Discount</label>
                                <input type="number" name="diskonbeli" value="<?php echo $product['diskonbeli']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Base Price</label>
                                <input type="number" name="hargapokok" required value="<?php echo $product['hargapokok']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selling Price</label>
                                <input type="number" name="hargajual" required value="<?php echo $product['hargajual']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selling Discount</label>
                                <input type="number" name="diskonjual" value="<?php echo $product['diskonjual']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stock</label>
                                <input type="number" name="stok" required value="<?php echo $product['stok']; ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Photo</label>
                            <?php if ($product['foto']): ?>
                                <div class="mt-2">
                                    <img src="../../uploads/products/<?php echo htmlspecialchars($product['foto']); ?>"
                                        alt="Current product photo" class="h-32 w-32 object-cover">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="foto" accept="image/*"
                                class="mt-1 block w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="deskripsi" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo htmlspecialchars($product['deskripsi']); ?></textarea>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="index.php"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>