<?php
include 'connections.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body class="wrapper">
    <div id="wrapper">
        <!-- Sidebar Wrapper -->
        <?php $page = 'orders';
        include 'includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
                        <h1 class="h3 mb-0 text-gray-800">Manajemen Order</h1>
                    </div>

                    <!-- table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="orderTable" class="table table-bordered table-hover text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Transaksi</th>
                                            <th>Email</th>
                                            <th>Tanggal Order</th>
                                            <th>Total Bayar</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $hasil = "SELECT * FROM tbl_order ORDER BY tgl_order DESC";
                                        $no = 1;
                                        foreach ($conn->query($hasil) as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['trans_id']; ?></td>
                                                <td><?= htmlspecialchars($row['email']); ?></td>
                                                <td><?= $row['tgl_order']; ?></td>
                                                <td><?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <?php
                                                    $status = $row['status'];
                                                    $statusText = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Shipped', 3 => 'Delivered', 4 => 'Cancelled'];
                                                    $badge = ['secondary', 'info', 'primary', 'success', 'danger'];
                                                    echo '<span class="badge badge-' . $badge[$status] . '">' . $statusText[$status] . '</span>';
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="?detail_id=<?= $row['trans_id']; ?>" class="detail-btn text-info">
                                                        <li class="fa fa-solid fa-eye"></li>
                                                        <br><span>Detail</span>
                                                    </a>
                                                    <hr>
                                                    <a href="?delete_id=<?= $row['trans_id']; ?>" style="color: #e74a3b" class="delete-link">
                                                        <li class="fa fa-solid fa-trash"></li>
                                                        <br><span>Hapus</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end table -->

                    <!-- Detail Order Modal -->
                    <div class="modal" id="modalDetailOrder" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Detail Order</h5>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Detail Order Modal -->

                    <?php
                    // Handle Delete Order
                    if (isset($_GET['delete_id'])) {
                        $id = $_GET['delete_id'];
                        $sql1 = "DELETE FROM tbl_order_detail WHERE trans_id = ?";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->bind_param("i", $id);
                        $stmt1->execute();
                        $sql2 = "DELETE FROM tbl_order WHERE trans_id = ?";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->bind_param("i", $id);
                        if ($stmt2->execute()) {
                            echo "<script>Swal.fire({title: 'Berhasil!',text: 'Order berhasil dihapus.',icon: 'success',confirmButtonText: 'OK'}).then((result) => {if (result.isConfirmed) {window.location.href = 'order.php';}});</script>";
                        } else {
                            echo "<script>Swal.fire({title: 'Gagal!',text: 'Gagal hapus order.',icon: 'error',confirmButtonText: 'OK'});</script>";
                        }
                    }

                    // Cek jika ada permintaan detail order
                    if (isset($_GET['detail_id'])) {
                        $detail_id = $_GET['detail_id'];
                        $stmt = $conn->prepare("SELECT * FROM tbl_order WHERE trans_id = ?");
                        $stmt->bind_param("i", $detail_id);
                        $stmt->execute();
                        $order = $stmt->get_result()->fetch_assoc();
                        if ($order) {
                            $stmt2 = $conn->prepare("SELECT d.*, p.nama_produk FROM tbl_order_detail d LEFT JOIN tbl_produk p ON d.kode_brg = p.kode_brg WHERE d.trans_id = ?");
                            $stmt2->bind_param("i", $detail_id);
                            $stmt2->execute();
                            $items = $stmt2->get_result();
                    ?>
                            <script>
                                $(document).ready(function() {
                                    $('#order-detail-body').html(`<?php ob_start(); ?>
                                <div class=\"row\">
                                    <div class=\"col-md-6\">
                                        <h5>Info Order</h5>
                                        <table class=\"table table-sm\">
                                            <tr><th>ID Transaksi</th><td><?= htmlspecialchars($order['trans_id']) ?></td></tr>
                                            <tr><th>Email</th><td><?= htmlspecialchars($order['email']) ?></td></tr>
                                            <tr><th>Tanggal Order</th><td><?= $order['tgl_order'] ?></td></tr>
                                            <tr><th>Subtotal</th><td>Rp <?= number_format($order['subtotal'], 0, ',', '.') ?></td></tr>
                                            <tr><th>Ongkir</th><td>Rp <?= number_format($order['ongkir'], 0, ',', '.') ?></td></tr>
                                            <tr><th>Total Bayar</th><td>Rp <?= number_format($order['total_bayar'], 0, ',', '.') ?></td></tr>
                                            <tr><th>Status</th><td><?php $statusText = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Shipped', 3 => 'Delivered', 4 => 'Cancelled'];
                                                                    $badge = ['secondary', 'info', 'primary', 'success', 'danger'];
                                                                    echo '<span class=\"badge badge-' . $badge[$order['status']] . '\">' . $statusText[$order['status']] . '</span>'; ?></td></tr>
                                            <tr><th>Metode Bayar</th><td><?= $order['metodebayar'] ?></td></tr>
                                            <tr><th>Bukti Pembayaran</th><td><?= $order['buktipembayar'] ? '<a href=\"../uploads/' . htmlspecialchars($order['buktipembayar']) . '\" target=\"_blank\">Lihat</a>' : '-' ?></td></tr>
                                        </table>
                                    </div>
                                    <div class=\"col-md-6\">
                                        <h5>Alamat Pengiriman</h5>
                                        <table class=\"table table-sm\">
                                            <tr><th>Alamat</th><td><?= htmlspecialchars($order['alamat_kirim']) ?></td></tr>
                                            <tr><th>Telp</th><td><?= htmlspecialchars($order['telp_kirim']) ?></td></tr>
                                            <tr><th>Kota</th><td><?= htmlspecialchars($order['kota']) ?></td></tr>
                                            <tr><th>Provinsi</th><td><?= htmlspecialchars($order['provinsi']) ?></td></tr>
                                            <tr><th>Kodepos</th><td><?= htmlspecialchars($order['kodepos']) ?></td></tr>
                                            <tr><th>Lama Kirim</th><td><?= htmlspecialchars($order['lamakirim']) ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <h5>Item Order</h5>
                                <table class=\"table table-bordered table-sm\">
                                    <thead>
                                        <tr><th>No</th><th>Kode Barang</th><th>Nama Produk</th><th>Qty</th><th>Harga Jual</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        $total = 0;
                                        while ($item = $items->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($item['kode_brg']) ?></td>
                                            <td><?= htmlspecialchars($item['nama_produk'] ?? '-') ?></td>
                                            <td><?= $item['qty'] ?></td>
                                            <td>Rp <?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
                                            <td>Rp <?= number_format($item['qty'] * $item['harga_jual'], 0, ',', '.') ?></td>
                                        </tr>
                                        <?php $total += $item['qty'] * $item['harga_jual'];
                                        endwhile; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><th colspan=\"5\" class=\"text-right\">Total</th><th>Rp <?= number_format($total, 0, ',', '.') ?></th></tr>
                                    </tfoot>
                                </table>
                                <?php $html = ob_get_clean();
                                echo str_replace(['\r', '\n'], '', addslashes($html)); ?>`);
                                    $('#detailOrderModal').modal('show');
                                });
                            </script>
                    <?php
                        }
                    }
                    ?>

                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End Main Content -->
        </div>
        <!-- End of Page Wrapper -->
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/app.js"></script>
    <script>
        $(document).ready(function() {
            // Delete confirmation
            $('.delete-link').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Order akan dihapus permanen.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
            $('.btn-detail').on('click', function() {
                var trans_id = $(this).data('transid');
                var email = $(this).data('email');
                $.get('/api/orders.php', {
                    email: email,
                    trans_id: trans_id
                }, function(res) {
                    // isi modal dengan data res
                    $('#modalDetailOrder .modal-body').html( /* render data */ );
                    $('#modalDetailOrder').modal('show');
                });
            });
        });
    </script>
    <style>
        .container-fluid {
            background: #f4f6fb;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            box-shadow: 0 2px 16px rgba(44, 62, 80, 0.04);
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .modal-content {
            border-radius: 16px;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }

        .btn-primary:hover {
            background: #174ea6;
            border-color: #174ea6;
        }
    </style>
</body>

</html>