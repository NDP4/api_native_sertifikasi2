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
                      <input type="text" class="form-control" id="add-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" id="add-email" name="email" required>
                    </div>
                    <div class="form-group">
                      <label>Telp</label>
                      <input type="text" class="form-control" id="add-telp" name="telp">
                    </div>
                    <div class="form-group">
                      <label>Kota</label>
                      <input type="text" class="form-control" id="add-kota" name="kota">
                    </div>
                    <div class="form-group">
                      <label>Provinsi</label>
                      <input type="text" class="form-control" id="add-provinsi" name="provinsi">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" id="add-password" name="password" required>
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
                    <input type="hidden" id="update-id" name="id">
                    <div class="form-group">
                      <label>Nama</label>
                      <input type="text" class="form-control" id="update-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" id="update-email" name="email" required>
                    </div>
                    <div class="form-group">
                      <label>Telp</label>
                      <input type="text" class="form-control" id="update-telp" name="telp">
                    </div>
                    <div class="form-group">
                      <label>Kota</label>
                      <input type="text" class="form-control" id="update-kota" name="kota">
                    </div>
                    <div class="form-group">
                      <label>Provinsi</label>
                      <input type="text" class="form-control" id="update-provinsi" name="provinsi">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" id="update-password" name="password">
                      <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Update User Modal -->

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
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function() {
      var userTable = $('#userTable').DataTable({
        ajax: {
          url: '../api/users.php',
          dataSrc: function(json) {
            return json.data || [];
          }
        },
        columns: [{
            data: null
          },
          {
            data: 'nama'
          },
          {
            data: 'email'
          },
          {
            data: 'telp'
          },
          {
            data: 'kota'
          },
          {
            data: 'provinsi'
          },
          {
            data: null,
            render: function(data, type, row) {
              return `<a href="#" class="edit-btn text-warning" data-id="${row.id}" data-nama="${row.nama}" data-email="${row.email}" data-telp="${row.telp}" data-kota="${row.kota}" data-provinsi="${row.provinsi}"><li class="fa fa-solid fa-pen"></li><br><span>edit</span></a><hr><a href="#" style="color: #e74a3b" class="delete-link" data-id="${row.id}"><li class="fa fa-solid fa-trash"></li><br><span>Hapus</span></a>`;
            }
          }
        ],
        columnDefs: [{
          targets: 0,
          render: function(data, type, row, meta) {
            return meta.row + 1;
          },
          width: '5%'
        }]
      });

      // Add User
      $("#addUserForm").submit(function(e) {
        e.preventDefault();
        var data = {
          nama: $('#add-nama').val(),
          email: $('#add-email').val(),
          telp: $('#add-telp').val(),
          kota: $('#add-kota').val(),
          provinsi: $('#add-provinsi').val(),
          password: $('#add-password').val()
        };
        $.ajax({
          url: '../api/users.php',
          type: 'POST',
          data: JSON.stringify(data),
          contentType: 'application/json',
          success: function(res) {
            $('#addUserModal').modal('hide');
            userTable.ajax.reload();
          }
        });
      });

      // Edit User
      $('#userTable tbody').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.get('../api/users.php?id=' + id, function(res) {
          if (res.status) {
            var u = res.data;
            $('#update-id').val(u.id);
            $('#update-nama').val(u.nama);
            $('#update-email').val(u.email);
            $('#update-telp').val(u.telp);
            $('#update-kota').val(u.kota);
            $('#update-provinsi').val(u.provinsi);
            $('#update-password').val('');
            $('#updateUserModal').modal('show');
          } else {
            alert('User tidak ditemukan');
          }
        }, 'json');
      });

      // Update User
      $('#updateUserForm').submit(function(e) {
        e.preventDefault();
        var id = $('#update-id').val();
        var data = {
          nama: $('#update-nama').val(),
          email: $('#update-email').val(),
          telp: $('#update-telp').val(),
          kota: $('#update-kota').val(),
          provinsi: $('#update-provinsi').val(),
          password: $('#update-password').val()
        };
        $.ajax({
          url: '../api/users.php?id=' + id,
          type: 'PUT',
          data: JSON.stringify(data),
          contentType: 'application/json',
          success: function(res) {
            $('#updateUserModal').modal('hide');
            userTable.ajax.reload();
          }
        });
      });

      // Delete User
      $('#userTable tbody').on('click', '.delete-link', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (confirm('Yakin ingin menghapus user ini?')) {
          $.ajax({
            url: '../api/users.php?id=' + id,
            type: 'DELETE',
            success: function(res) {
              userTable.ajax.reload();
            }
          });
        }
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