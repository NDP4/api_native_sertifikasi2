<?php
include 'connections.php';
if (!isset($_GET['trans_id'])) {
    echo '<div class="alert alert-danger">ID Transaksi tidak ditemukan.</div>';
    exit;
}
$trans_id = $_GET['trans_id'];
// Ambil data order utama
$stmt = $conn->prepare("SELECT * FROM tbl_order WHERE trans_id = ?");
$stmt->bind_param("i", $trans_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) {
    echo '<div class="alert alert-danger">Order tidak ditemukan.</div>';
    exit;
}
// Ambil detail item order
$stmt2 = $conn->prepare("SELECT d.*, p.nama_produk FROM tbl_order_detail d JOIN tbl_produk p ON d.produk_id = p.id WHERE d.trans_id = ?");
$stmt2->bind_param("i", $trans_id);
$stmt2->execute();
$items = $stmt2->get_result();
?>
<div class="row">
    <div class="col-md-6">
        <h5>Info Order</h5>
        <table class="table table-sm">
            <tr>
                <th>ID Transaksi</th>
                <td><?= htmlspecialchars($order['trans_id']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($order['email']) ?></td>
            </tr>
            <tr>
                <th>Tanggal Order</th>
                <td><?= $order['tgl_order'] ?></td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <td>Rp <?= number_format($order['total_bayar'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php
                    $statusText = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Shipped', 3 => 'Delivered', 4 => 'Cancelled'];
                    $badge = ['secondary', 'info', 'primary', 'success', 'danger'];
                    echo '<span class="badge badge-' . $badge[$order['status']] . '">' . $statusText[$order['status']] . '</span>';
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h5>Alamat Pengiriman</h5>
        <div><?= nl2br(htmlspecialchars($order['alamat_pengiriman'])) ?></div>
    </div>
</div>
<hr>
<h5>Item Order</h5>
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        $total = 0;
        while ($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                <td><?= $item['qty'] ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($item['qty'] * $item['harga'], 0, ',', '.') ?></td>
            </tr>
        <?php $total += $item['qty'] * $item['harga'];
        endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total</th>
            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>