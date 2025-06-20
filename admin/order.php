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
                                                    <a href="#" class="detail-btn text-info" data-id="<?= $row['trans_id']; ?>">
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
                    <div class="modal fade" id="detailOrderModal" tabindex="-1" role="dialog" aria-labelledby="detailOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-white" id="detailOrderModalLabel">Detail Order</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="order-detail-body">
                                    <!-- detail order akan di-load via ajax -->
                                </div>
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
            // Detail Order
            $('.detail-btn').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'order_detail.php',
                    type: 'GET',
                    data: {
                        trans_id: id
                    },
                    success: function(res) {
                        $('#order-detail-body').html(res);
                        $('#detailOrderModal').modal('show');
                    }
                });
            });
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