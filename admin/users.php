<?php
// users.php - Manajemen User (CRUD)
include_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen User</title>
  <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap4.min.css">
</head>

<body>
  <div class="container mt-4">
    <h2>Manajemen User</h2>
    <button class="btn btn-primary mb-3" id="btnAddUser">Tambah User</button>
    <table class="table table-bordered" id="userTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Telp</th>
          <th>Kota</th>
          <th>Provinsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- Modal User -->
  <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="userForm">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="userId" name="id">
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
              <label>Telp</label>
              <input type="text" class="form-control" id="telp" name="telp">
            </div>
            <div class="form-group">
              <label>Kota</label>
              <input type="text" class="form-control" id="kota" name="kota">
            </div>
            <div class="form-group">
              <label>Provinsi</label>
              <input type="text" class="form-control" id="provinsi" name="provinsi">
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" class="form-control" id="password" name="password">
              <small id="passwordHelp" class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.
              </small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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
            data: 'id'
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
              return `<button class="btn btn-sm btn-info btnEdit" data-id="${row.id}">Edit</button> ` +
                `<button class="btn btn-sm btn-danger btnDelete" data-id="${row.id}">Hapus</button>`;
            }
          }
        ]
      });

      $('#btnAddUser').click(function() {
        $('#userForm')[0].reset();
        $('#userId').val('');
        $('#userModalLabel').text('Tambah User');
        $('#password').attr('required', true);
        $('#userModal').modal('show');
      });

      $('#userTable tbody').on('click', '.btnEdit', function() {
        var id = $(this).data('id');
        $.get('../api/users.php?id=' + id, function(res) {
          if (res.status) {
            var u = res.data;
            $('#userId').val(u.id);
            $('#nama').val(u.nama);
            $('#email').val(u.email);
            $('#telp').val(u.telp);
            $('#kota').val(u.kota);
            $('#provinsi').val(u.provinsi);
            $('#password').val('');
            $('#password').attr('required', false);
            $('#userModalLabel').text('Edit User');
            $('#userModal').modal('show');
          } else {
            alert('User tidak ditemukan');
          }
        }, 'json');
      });

      $('#userTable tbody').on('click', '.btnDelete', function() {
        if (confirm('Yakin ingin menghapus user ini?')) {
          var id = $(this).data('id');
          $.ajax({
            url: '../api/users.php?id=' + id,
            type: 'DELETE',
            success: function(res) {
              userTable.ajax.reload();
            }
          });
        }
      });

      $('#userForm').submit(function(e) {
        e.preventDefault();
        var id = $('#userId').val();
        var data = {
          nama: $('#nama').val(),
          email: $('#email').val(),
          telp: $('#telp').val(),
          kota: $('#kota').val(),
          provinsi: $('#provinsi').val(),
          password: $('#password').val()
        };
        var method = id ? 'PUT' : 'POST';
        var url = '../api/users.php' + (id ? ('?id=' + id) : '');
        $.ajax({
          url: url,
          type: method,
          data: JSON.stringify(data),
          contentType: 'application/json',
          success: function(res) {
            $('#userModal').modal('hide');
            userTable.ajax.reload();
          }
        });
      });
    });
  </script>
</body>

</html>