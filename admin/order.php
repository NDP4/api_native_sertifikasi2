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
                                        $res = $conn->query($hasil);
                                        while ($row = $res->fetch_assoc()) :
                                            // Ambil detail order sekalian
                                            $detail_items = array();
                                            $sql2 = "SELECT d.*, p.nama_produk FROM tbl_order_detail d LEFT JOIN tbl_produk p ON d.kode_brg = p.kode_brg WHERE d.trans_id = " . $row['trans_id'];
                                            $res2 = $conn->query($sql2);
                                            while ($item = $res2->fetch_assoc()) {
                                                $detail_items[] = $item;
                                            }
                                        ?>
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
                                                    <button type="button" class="btn btn-info btn-detail"
                                                        data-order='<?= json_encode($row) ?>'
                                                        data-items='<?= json_encode($detail_items) ?>'>
                                                        <li class="fa fa-solid fa-eye"></li>
                                                        <br><span>Detail</span>
                                                    </button>
                                                    <hr>
                                                    <a href="?delete_id=<?= $row['trans_id']; ?>" style="color: #e74a3b" class="delete-link">
                                                        <li class="fa fa-solid fa-trash"></li>
                                                        <br><span>Hapus</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end table -->

                    <!-- Detail Order Modal -->
                    <div class="modal fade" id="modalDetailOrder" tabindex="-1" role="dialog" aria-labelledby="modalDetailOrderLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalDetailOrderLabel">Detail Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="order-detail-body"></div>
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
            // Detail order modal
            $('.btn-detail').on('click', function() {
                var order = $(this).data('order');
                var items = $(this).data('items');
                var statusText = ['Pending', 'Confirmed', 'Shipped', 'Delivered', 'Cancelled'];
                var badge = ['secondary', 'info', 'primary', 'success', 'danger'];
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-6">';
                html += '<h5>Info Order</h5>';
                html += '<table class="table table-sm">';
                html += '<tr><th>ID Transaksi</th><td>' + order.trans_id + '</td></tr>';
                html += '<tr><th>Email</th><td>' + order.email + '</td></tr>';
                html += '<tr><th>Tanggal Order</th><td>' + order.tgl_order + '</td></tr>';
                html += '<tr><th>Subtotal</th><td>Rp ' + Number(order.subtotal).toLocaleString('id-ID') + '</td></tr>';
                html += '<tr><th>Ongkir</th><td>Rp ' + Number(order.ongkir).toLocaleString('id-ID') + '</td></tr>';
                html += '<tr><th>Total Bayar</th><td>Rp ' + Number(order.total_bayar).toLocaleString('id-ID') + '</td></tr>';
                html += '<tr><th>Status</th><td><span class="badge badge-' + badge[order.status] + '">' + statusText[order.status] + '</span></td></tr>';
                html += '<tr><th>Metode Bayar</th><td>' + (order.metodebayar ?? '-') + '</td></tr>';
                html += '<tr><th>Bukti Pembayaran</th><td>' + (order.buktipembayar ? '<a href="../uploads/' + order.buktipembayar + '" target="_blank">Lihat</a>' : '-') + '</td></tr>';
                html += '</table>';
                html += '</div>';
                html += '<div class="col-md-6">';
                html += '<h5>Alamat Pengiriman</h5>';
                html += '<table class="table table-sm">';
                html += '<tr><th>Alamat</th><td>' + (order.alamat_kirim ?? '-') + '</td></tr>';
                html += '<tr><th>Telp</th><td>' + (order.telp_kirim ?? '-') + '</td></tr>';
                html += '<tr><th>Kota</th><td>' + (order.kota ?? '-') + '</td></tr>';
                html += '<tr><th>Provinsi</th><td>' + (order.provinsi ?? '-') + '</td></tr>';
                html += '<tr><th>Kodepos</th><td>' + (order.kodepos ?? '-') + '</td></tr>';
                html += '<tr><th>Lama Kirim</th><td>' + (order.lamakirim ?? '-') + '</td></tr>';
                html += '</table>';
                html += '</div>';
                html += '</div>';
                html += '<hr>';
                html += '<h5>Item Order</h5>';
                html += '<table class="table table-bordered table-sm">';
                html += '<thead><tr><th>No</th><th>Kode Barang</th><th>Nama Produk</th><th>Qty</th><th>Harga Jual</th><th>Subtotal</th></tr></thead>';
                html += '<tbody>';
                var total = 0;
                for (var i = 0; i < items.length; i++) {
                    var item = items[i];
                    var subtotal = item.qty * item.harga_jual;
                    total += subtotal;
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + item.kode_brg + '</td>';
                    html += '<td>' + (item.nama_produk ?? '-') + '</td>';
                    html += '<td>' + item.qty + '</td>';
                    html += '<td>Rp ' + Number(item.harga_jual).toLocaleString('id-ID') + '</td>';
                    html += '<td>Rp ' + Number(subtotal).toLocaleString('id-ID') + '</td>';
                    html += '</tr>';
                }
                html += '</tbody>';
                html += '<tfoot><tr><th colspan="5" class="text-right">Total</th><th>Rp ' + Number(total).toLocaleString('id-ID') + '</th></tr></tfoot>';
                html += '</table>';
                $('#order-detail-body').html(html);
                $('#modalDetailOrder').modal('show');
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