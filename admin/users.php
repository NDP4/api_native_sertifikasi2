<?php
include 'connections.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body class="wrapper">
  <div id="wrapper">
    <!-- Sidebar Wrapper -->
    <?php $page = 'users';
    include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
            <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
          </div>

          <!-- table -->
          <div class="card shadow">
            <div class="card-body">
              <div class="text-right py-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Tambah User</button>
              </div>
              <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Telp</th>
                      <th>Kota</th>
                      <th>Provinsi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $hasil = "SELECT * FROM tbl_pelanggan";
                    $no = 1;
                    foreach ($conn->query($hasil) as $row) : ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['telp']); ?></td>
                        <td><?= htmlspecialchars($row['kota']); ?></td>
                        <td><?= htmlspecialchars($row['provinsi']); ?></td>
                        <td>
                          <a href="#" class="edit-btn text-warning" data-id="<?= $row['id']; ?>" data-nama="<?= htmlspecialchars($row['nama']); ?>" data-email="<?= htmlspecialchars($row['email']); ?>" data-telp="<?= htmlspecialchars($row['telp']); ?>" data-kota="<?= htmlspecialchars($row['kota']); ?>" data-provinsi="<?= htmlspecialchars($row['provinsi']); ?>">
                            <li class="fa fa-solid fa-pen"></li>
                            <br>
                            <span>edit</span>
                          </a>
                          <hr>
                          <a href="?delete_id=<?= $row['id']; ?>" style="color: #e74a3b" class="delete-link">
                            <li class="fa fa-solid fa-trash"></li>
                            <br>
                            <span>Hapus</span>
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

          <!-- Add User Modal -->
          <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white" id="addUserModalLabel">Tambah User</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="addUserForm" action="" method="POST">
                    <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                      <label>Telp</label>
                      <input type="text" class="form-control" name="telp">
                    </div>
                    <div class="form-group">
                      <label>Kota</label>
                      <input type="text" class="form-control" name="kota">
                    </div>
                    <div class="form-group">
                      <label>Provinsi</label>
                      <input type="text" class="form-control" name="provinsi">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add">Tambah</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Add User Modal -->

          <!-- Update User Modal -->
          <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white" id="updateUserModalLabel">Edit User</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="updateUserForm" action="" method="POST">
                    <input type="hidden" name="id" id="update-id">
                    <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" name="nama" id="update-nama" required>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" id="update-email" required>
                    </div>
                    <div class="form-group">
                      <label>Telp</label>
                      <input type="text" class="form-control" name="telp" id="update-telp">
                    </div>
                    <div class="form-group">
                      <label>Kota</label>
                      <input type="text" class="form-control" name="kota" id="update-kota">
                    </div>
                    <div class="form-group">
                      <label>Provinsi</label>
                      <input type="text" class="form-control" name="provinsi" id="update-provinsi">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" name="password" id="update-password">
                      <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Update User Modal -->

          <?php
          // Handle Add User
          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $telp = $_POST['telp'];
            $kota = $_POST['kota'];
            $provinsi = $_POST['provinsi'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO tbl_pelanggan (nama, email, telp, kota, provinsi, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nama, $email, $telp, $kota, $provinsi, $password);
            if ($stmt->execute()) {
              echo "<script>Swal.fire({title: 'Berhasil!',text: 'User berhasil ditambahkan.',icon: 'success',confirmButtonText: 'OK'}).then((result) => {if (result.isConfirmed) {window.location.href = 'users.php';}});</script>";
            } else {
              echo "<script>Swal.fire({title: 'Gagal!',text: 'Gagal menambahkan user.',icon: 'error',confirmButtonText: 'OK'});</script>";
            }
          }

          // Handle Update User
          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $telp = $_POST['telp'];
            $kota = $_POST['kota'];
            $provinsi = $_POST['provinsi'];
            $password = $_POST['password'];
            if (!empty($password)) {
              $password = password_hash($password, PASSWORD_DEFAULT);
              $sql = "UPDATE tbl_pelanggan SET nama=?, email=?, telp=?, kota=?, provinsi=?, password=? WHERE id=?";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("ssssssi", $nama, $email, $telp, $kota, $provinsi, $password, $id);
            } else {
              $sql = "UPDATE tbl_pelanggan SET nama=?, email=?, telp=?, kota=?, provinsi=? WHERE id=?";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("sssssi", $nama, $email, $telp, $kota, $provinsi, $id);
            }
            if ($stmt->execute()) {
              echo "<script>Swal.fire({title: 'Berhasil!',text: 'User berhasil diupdate.',icon: 'success',confirmButtonText: 'OK'}).then((result) => {if (result.isConfirmed) {window.location.href = 'users.php';}});</script>";
            } else {
              echo "<script>Swal.fire({title: 'Gagal!',text: 'Gagal update user.',icon: 'error',confirmButtonText: 'OK'});</script>";
            }
          }

          // Handle Delete User
          if (isset($_GET['delete_id'])) {
            $id = $_GET['delete_id'];
            $sql = "DELETE FROM tbl_pelanggan WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
              echo "<script>Swal.fire({title: 'Berhasil!',text: 'User berhasil dihapus.',icon: 'success',confirmButtonText: 'OK'}).then((result) => {if (result.isConfirmed) {window.location.href = 'users.php';}});</script>";
            } else {
              echo "<script>Swal.fire({title: 'Gagal!',text: 'Gagal hapus user.',icon: 'error',confirmButtonText: 'OK'});</script>";
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
      // Edit User
      $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var email = $(this).data('email');
        var telp = $(this).data('telp');
        var kota = $(this).data('kota');
        var provinsi = $(this).data('provinsi');
        $('#update-id').val(id);
        $('#update-nama').val(nama);
        $('#update-email').val(email);
        $('#update-telp').val(telp);
        $('#update-kota').val(kota);
        $('#update-provinsi').val(provinsi);
        $('#update-password').val('');
        $('#updateUserModal').modal('show');
      });
      // Delete confirmation
      $('.delete-link').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
          title: "Apakah Anda yakin?",
          text: "User akan dihapus permanen.",
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