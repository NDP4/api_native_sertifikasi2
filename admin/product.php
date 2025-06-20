<?php
include 'connections.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>

<body class="wrapper">
  <div id="wrapper">
    <!-- Sidebar Wrapper -->
    <?php $page = 'product';
    include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-4">
            <h1 class="h3 mb-0 text-white">Products</h1>
          </div>

          <!-- table -->
          <div class="card shadow">
            <div class="card-body">
              <div class="text-right py-2">
                <!-- <a href="#" class="add-btn"> -->
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Tambah Baru</button>
                <!-- </a> -->
              </div>
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>Merk</th>
                      <th>Kategori</th>
                      <th>Satuan</th>
                      <th>Harga Beli</th>
                      <th>Diskon Beli</th>
                      <th>Harga Pokok</th>
                      <th>Harga Jual</th>
                      <th>Diskon Jual</th>
                      <th>Stok</th>
                      <th>Foto</th>
                      <th>Deskripsi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $hasil = "select * from tbl_product";
                    $no = 1;
                    foreach ($conn->query($hasil) as $row) : ?>
                      <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['kode']; ?></td>
                        <td><?php echo $row['merk']; ?></td>
                        <td><?php echo $row['kategori']; ?></td>
                        <td><?php echo $row['satuan']; ?></td>
                        <td><?php echo $row['hargabeli']; ?></td>
                        <td><?php echo $row['diskonbeli']; ?></td>
                        <td><?php echo $row['hargapokok']; ?></td>
                        <td><?php echo $row['hargajual']; ?></td>
                        <td><?php echo $row['diskonjual']; ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td>
                          <img src="../uploads/products/<?php echo $row['foto']; ?>" style="width: 60px;">
                        </td>
                        <td><?php
                            $text = $row['deskripsi'];
                            if (strlen($text) > 60) {
                              echo substr($text, 0, 60) . "...";
                            } else {
                              echo $text;
                            }
                            ?> </td>
                        <td>
                          <a href="#" class="edit-btn" data-id="<?php echo $row['kode']; ?>"
                            data-merk="<?php echo $row['merk']; ?>" data-kategori="<?php echo $row['kategori']; ?>"
                            data-satuan="<?php echo $row['satuan']; ?>" data-hargabeli="<?php echo $row['hargabeli']; ?>"
                            data-diskonbeli="<?php echo $row['diskonbeli']; ?>"
                            data-hargapokok="<?php echo $row['hargapokok']; ?>"
                            data-hargajual="<?php echo $row['hargajual']; ?>"
                            data-diskonjual="<?php echo $row['diskonjual']; ?>" data-stok="<?php echo $row['stok']; ?>"
                            data-foto="<?php echo $row['foto']; ?>" data-deskripsi="<?php echo $row['deskripsi']; ?>">
                            <li class="fa fa-solid fa-pen"></li>
                            <br>
                            <span>edit</span>
                          </a>
                          <hr>
                          <a href="#" style="color: #e74a3b" class="delete-link" data-id="<?php echo $row['kode']; ?>">
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
          <!-- End of Main Content -->

          <!-- Add Modal -->
          <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white" id="addModalLabel">Tambah Data</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="addForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="kode">Kode</label>
                      <input type="text" class="form-control" id="kode" name="kode" required>
                    </div>
                    <div class="form-group">
                      <label for="merk">Merek</label>
                      <input type="text" class="form-control" id="merk" name="merk" required>
                    </div>
                    <div class="form-group">
                      <label for="kategori">Kategori</label>
                      <input type="text" class="form-control" id="kategori" name="kategori" required>
                    </div>
                    <div class="form-group">
                      <label for="satuan">Satuan</label>
                      <input type="text" class="form-control" id="satuan" name="satuan" required>
                    </div>
                    <div class="form-group">
                      <label for="hargabeli">Harga Beli</label>
                      <input type="number" class="form-control" id="hargabeli" name="hargabeli" required>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="toggleDiskonBeli">
                        <label class="form-check-label" for="toggleDiskonBeli">Ada Diskon Beli?</label>
                      </div>
                    </div>
                    <div class="form-group" id="formDiskonBeli" style="display:none;">
                      <label for="diskonbeli">Diskon Beli (%)</label>
                      <input type="number" class="form-control" id="diskonbeli" name="diskonbeli">
                    </div>
                    <div class="form-group">
                      <label for="hargapokok">Harga Pokok</label>
                      <input type="number" class="form-control" id="hargapokok" name="hargapokok">
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="toggleDiskonJual">
                        <label class="form-check-label" for="toggleDiskonJual">Ada Diskon Jual?</label>
                      </div>
                    </div>
                    <div class="form-group" id="formDiskonJual" style="display:none;">
                      <label for="diskonjual">Diskon Jual (%)</label>
                      <input type="number" class="form-control" id="diskonjual" name="diskonjual">
                    </div>
                    <div class="form-group" id="formHargaJual">
                      <label for="hargajual">Harga Jual</label>
                      <input type="number" class="form-control" id="hargajual" name="hargajual">
                    </div>
                    <div class="form-group">
                      <label for="stok">Stok</label>
                      <input type="number" class="form-control" id="stok" name="stok" required>
                    </div>
                    <div class="form-group">
                      <label for="foto">Foto</label>
                      <input type="file" class="form-control" id="foto" name="foto" required>
                      <img id="preview-foto-upload" src="../uploads/products/default.png" width="80"
                        class="mt-2 mb-3"><br>
                    </div>
                    <div class="form-group">
                      <label for="deskripsi">Deskripsi</label>
                      <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add">Tambah</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Add Modal -->

          <!-- Update Modal -->
          <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white" id="updateModalLabel">Update Data</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kode" id="update-id">
                    <div class="form-group">
                      <label for="update-merk">Merek</label>
                      <input type="text" class="form-control" id="update-merk" name="merk" required>
                    </div>
                    <div class="form-group">
                      <label for="update-kategori">Kategori</label>
                      <input type="text" class="form-control" id="update-kategori" name="kategori" required>
                    </div>
                    <div class="form-group">
                      <label for="update-satuan">Satuan</label>
                      <input type="text" class="form-control" id="update-satuan" name="satuan" required>
                    </div>
                    <div class="form-group">
                      <label for="update-hargabeli">Harga Beli</label>
                      <input type="number" class="form-control" id="update-hargabeli" name="hargabeli" required>
                    </div>
                    <div class="form-group">
                      <label for="update-diskonbeli">Diskon Beli</label>
                      <input type="number" class="form-control" id="update-diskonbeli" name="diskonbeli" required>
                    </div>
                    <div class="form-group">
                      <label for="update-hargapokok">Harga Pokok</label>
                      <input type="number" class="form-control" id="update-hargapokok" name="hargapokok" required>
                    </div>
                    <div class="form-group">
                      <label for="update-hargajual">Harga Jual</label>
                      <input type="number" class="form-control" id="update-hargajual" name="hargajual" required>
                    </div>
                    <div class="form-group">
                      <label for="update-diskonjual">Diskon Jual</label>
                      <input type="number" class="form-control" id="update-diskonjual" name="diskonjual" required>
                    </div>
                    <div class="form-group">
                      <label for="update-stok">Stok</label>
                      <input type="number" class="form-control" id="update-stok" name="stok" required>
                    </div>
                    <img id="preview-foto" width="80" class="mt-2 mb-3"><br>
                    <div class="form-group">
                      <input type="file" class="form-control" id="update-foto" name="foto" accept="image/*">
                      <!-- <input type="file" class="form-control" id="update-foto" name="foto"> -->
                    </div>
                    <div class="form-group">
                      <label for="update-deskripsi">Deskripsi</label>
                      <textarea class="form-control" id="update-deskripsi" name="deskripsi" rows="3"
                        required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Update Modal -->

          <?php
          // handle to add
          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
            $kode = $_POST['kode'];
            $merk = $_POST['merk'];
            $kategori = $_POST['kategori'];
            $satuan = $_POST['satuan'];
            $hargabeli = $_POST['hargabeli'];
            $diskonbeli = $_POST['diskonbeli'];
            $hargapokok = $_POST['hargapokok'];
            $hargajual = $_POST['hargajual'];
            $diskonjual = $_POST['diskonjual'];
            $stok = $_POST['stok'];
            $deskripsi = $_POST['deskripsi'];

            // Upload foto
            $foto = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $path = "../uploads/products/" . $foto;

            // Cek apakah file diupload
            if (!empty($foto)) {
              move_uploaded_file($tmp, $path);
              $sql = "INSERT INTO tbl_product (kode, merk, kategori, satuan, hargabeli, diskonbeli, hargapokok, hargajual, diskonjual, stok, foto, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("ssssdddddiss", $kode, $merk, $kategori, $satuan, $hargabeli, $diskonbeli, $hargapokok, $hargajual, $diskonjual, $stok, $foto, $deskripsi);
            }
            if ($stmt->execute()) {
              echo "<script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Data berhasil ditambahkan.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'product.php';
                                        }
                                    });
                                </script>";
            } else {
              echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal menambahkan data.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
            }
          }

          if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
            // Ambil data dari form
            $kode = $_POST['kode'] ?? '';
            $merk = $_POST['merk'] ?? '';
            $kategori = $_POST['kategori'] ?? '';
            $satuan = $_POST['satuan'] ?? '';
            $hargabeli = $_POST['hargabeli'] ?? 0;
            $diskonbeli = $_POST['diskonbeli'] ?? 0;
            $hargapokok = $_POST['hargapokok'] ?? 0;
            $hargajual = $_POST['hargajual'] ?? 0;
            $diskonjual = $_POST['diskonjual'] ?? 0;
            $stok = $_POST['stok'] ?? 0;
            $deskripsi = $_POST['deskripsi'] ?? '';

            // Inisialisasi variabel foto
            $newFilename = null;

            // Tangani upload foto jika ada
            if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
              $foto = $_FILES['foto'];
              $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);

              // Generate nama unik untuk menghindari konflik
              $newFilename = uniqid('prod_', true) . '.' . $ext;
              $path = "../uploads/products/" . $newFilename;

              if (!move_uploaded_file($foto['tmp_name'], $path)) {
                echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Data gagal diupload.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                exit;
              }
            }

            try {
              // Siapkan query berdasarkan ada/tidaknya foto baru
              if ($newFilename) {
                $sql = "UPDATE tbl_product SET 
                    merk=?, kategori=?, satuan=?, 
                    hargabeli=?, diskonbeli=?, hargapokok=?, 
                    hargajual=?, diskonjual=?, stok=?, 
                    foto=?, deskripsi=? 
                    WHERE kode=?";

                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                  throw new Exception("Prepare failed: " . $conn->error);
                }

                // Perbaikan binding parameter
                $stmt->bind_param(
                  "sssdddddisss",
                  $merk,
                  $kategori,
                  $satuan,
                  $hargabeli,
                  $diskonbeli,
                  $hargapokok,
                  $hargajual,
                  $diskonjual,
                  $stok, // integer
                  $newFilename,
                  $deskripsi,
                  $kode
                );
              } else {
                $sql = "UPDATE tbl_product SET 
                    merk=?, kategori=?, satuan=?, 
                    hargabeli=?, diskonbeli=?, hargapokok=?, 
                    hargajual=?, diskonjual=?, stok=?, 
                    deskripsi=? 
                    WHERE kode=?";

                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                  throw new Exception("Prepare failed: " . $conn->error);
                }

                // Perbaikan binding parameter
                $stmt->bind_param(
                  "sssdddddiss",
                  $merk,
                  $kategori,
                  $satuan,
                  $hargabeli,
                  $diskonbeli,
                  $hargapokok,
                  $hargajual,
                  $diskonjual,
                  $stok, // integer
                  $deskripsi,
                  $kode
                );
              }

              // Eksekusi query
              if ($stmt->execute()) {
                echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data produk berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'product.php';
                    }
                });
            </script>";
              } else {
                throw new Exception("Gagal update data: " . $stmt->error);
              }
            } catch (Exception $e) {
              // Hapus file yang sudah diupload jika ada error
              if ($newFilename && file_exists($path)) {
                unlink($path);
              }

              echo "<script>
            Swal.fire({
                title: 'Error!',
                text: '" . addslashes($e->getMessage()) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
            }
          }

          // handle to delete
          if (isset($_GET['delete_id'])) {
            $id = $_GET['delete_id'];

            $sql = "DELETE FROM tbl_product WHERE kode = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
              $stmt->bind_param("s", $id);

              if ($stmt->execute()) {
                echo "<script>
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Data berhasil dihapus.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'product.php';
                                        }
                                    });
                                </script>";
              } else {
                echo "<script>
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Data gagal dihapus.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
              }
            } else {
              echo "<script>
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Gagal mempersiapkan statement.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
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

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/app.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- script modal -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggleDiskonBeli = document.getElementById('toggleDiskonBeli');
      const formDiskonBeli = document.getElementById('formDiskonBeli');
      const inputDiskonBeli = document.getElementById('diskonbeli');

      const toggleDiskonJual = document.getElementById('toggleDiskonJual');
      const formDiskonJual = document.getElementById('formDiskonJual');
      const formHargaJual = document.getElementById('formHargaJual');
      const inputDiskonJual = document.getElementById('diskonjual');
      const inputHargaPokok = document.getElementById('hargapokok');
      const inputHargaJual = document.getElementById('hargajual');

      // Diskon Beli toggle
      toggleDiskonBeli.addEventListener('change', function() {
        if (this.checked) {
          formDiskonBeli.style.display = 'block';
          inputDiskonBeli.required = true;
        } else {
          formDiskonBeli.style.display = 'none';
          inputDiskonBeli.value = 0;
          inputDiskonBeli.required = false;
        }
      });

      // Diskon Jual toggle
      toggleDiskonJual.addEventListener('change', function() {
        if (this.checked) {
          formDiskonJual.style.display = 'block';
          inputDiskonJual.required = true;
          formHargaJual.style.display = 'block';
          inputHargaJual.required = true;
        } else {
          formDiskonJual.style.display = 'none';
          inputDiskonJual.value = 0;
          inputDiskonJual.required = false;
          inputHargaJual.value = inputHargaPokok.value;
          inputHargaJual.required = false;
        }
      });

      // Hitung Harga Jual otomatis berdasarkan diskon
      inputDiskonJual.addEventListener('input', function() {
        const hargaPokok = parseFloat(inputHargaPokok.value);
        const diskon = parseFloat(inputDiskonJual.value);
        if (!isNaN(hargaPokok) && !isNaN(diskon)) {
          const hargaJual = hargaPokok - (hargaPokok * (diskon / 100));
          inputHargaJual.value = Math.round(hargaJual);
        }
      });

      // Jika harga pokok berubah, update juga harga jual
      inputHargaPokok.addEventListener('input', function() {
        if (toggleDiskonJual.checked) {
          const diskon = parseFloat(inputDiskonJual.value);
          const hargaPokok = parseFloat(inputHargaPokok.value);
          if (!isNaN(hargaPokok) && !isNaN(diskon)) {
            const hargaJual = hargaPokok - (hargaPokok * (diskon / 100));
            inputHargaJual.value = Math.round(hargaJual);
          }
        } else {
          inputHargaJual.value = inputHargaPokok.value;
        }
      });
    });

    // DataTable
    $(document).ready(function() {
      if ($.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable().destroy();
      }
      var table = $('#dataTable').DataTable({
        columnDefs: [{
            width: '5%',
            targets: 0
          },
          {
            width: '8%',
            targets: 1
          },
          {
            width: '10%',
            targets: 2
          },
          {
            width: '10%',
            targets: 3
          },
          {
            width: '15%',
            targets: 4
          },
          {
            width: '10%',
            targets: 5
          },
          {
            width: '10%',
            targets: 6
          },
          {
            width: '10%',
            targets: 7
          },
        ]
      });

      function bindEditButtons() {
        $(".edit-btn").on("click", function() {
          var id = $(this).data("id");
          var merk = $(this).data("merk");
          var kategori = $(this).data("kategori");
          var satuan = $(this).data("satuan");
          var hargabeli = $(this).data("hargabeli");
          var diskonbeli = $(this).data("diskonbeli");
          var hargapokok = $(this).data("hargapokok");
          var hargajual = $(this).data("hargajual");
          var diskonjual = $(this).data("diskonjual");
          var stok = $(this).data("stok");
          var foto = $(this).data("foto");
          var deskripsi = $(this).data("deskripsi");

          $("#preview-foto").attr("src", "../uploads/products/" + foto);
          $("#update-id").val(id);
          $("#update-merk").val(merk);
          $("#update-kategori").val(kategori);
          $("#update-satuan").val(satuan);
          $("#update-hargabeli").val(hargabeli);
          $("#update-diskonbeli").val(diskonbeli);
          $("#update-hargapokok").val(hargapokok);
          $("#update-hargajual").val(hargajual);
          $("#update-diskonjual").val(diskonjual);
          $("#update-stok").val(stok);
          $("#update-deskripsi").val(deskripsi);
          $("#update-foto").on("change", function() {
            var reader = new FileReader();
            reader.onload = function(e) {
              $("#preview-foto").attr("src", e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
          });

          $("#updateModal").modal("show");
        });
      }

      function bindAddButtons() {
        $(".add-btn").on("click", function() {
          try {
            $("#addModal").modal("show");
            console.log("Modal 'Tambah Data' berhasil dimunculkan.");
          } catch (error) {
            console.error("Gagal menampilkan modal:", error);
          }
        });

      }

      function bindDelButtons() {
        $(".delete-link").on("click", function(event) {
          event.preventDefault();
          var id = $(this).data("id");

          Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data akan dihapus permanen.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = "?delete_id=" + id;
            }
          });
        });
      }

      function previewImage(input, previewElement) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $(previewElement).attr("src", e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }
      $("#foto").on("change", function() {
        previewImage(this, "#preview-foto-upload");
      });
      $("#update-foto").on("change", function() {
        previewImage(this, "#preview-foto");
      });

      bindAddButtons();
      bindEditButtons();
      bindDelButtons();
      table.on('draw', function() {
        bindEditButtons();
        bindDelButtons();
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