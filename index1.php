<!DOCTYPE html>
<html>
<head>
    <title>Wisata Mekarsari</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {background-color: #f5f5f5;}

        .form-container {
            margin-top: 20px;
            padding: 10 px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container label {
            display: inline-block;
            width: 100px;
            margin-bottom: 10px;
        }

        .form-container input[type="text"] {
            width: 300px;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <table id="destinationTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Wisata</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div class="form-container">
        <h2>Tambah Informasi Wisata</h2>
        <div>
            <label for="id">Id Wisata: </label>
            <input type="text" id="id">
        </div>
        <div>
            <label for="nama">Nama Wisata: </label>
            <input type="text" id="nama">
        </div>
        <div>
            <label for="deskripsi">Deskripsi Wisata: </label>
            <input type="text" id="deskripsi">
        </div>
        <button id="tambahdata">Tambah Data</button>
    </div>

    <script>
        $(document).ready(function() {
            function getData() {
                $.ajax({
                    url: 'http://localhost/tubes/koneksi.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var tableBody = $('#destinationTable tbody');
                        tableBody.empty();
                        for (var i = 0; i < data.length; i++) {
                            var wisata = data[i];
                            var row = "<tr>";
                            row += "<td>" + wisata.id + "</td>";
                            row += "<td>" + wisata.nama + "</td>";
                            row += "<td>" + wisata.deskripsi + "</td>";
                            row += "<td><button class='updateBtn' data-id='" + wisata.id + "'>Update</button> <button class='deleteBtn' data-id='" + wisata.id + "'>Delete</button></td>";
                            row += "</tr>";
                            tableBody.append(row);
                        }
                        setUpdateButtonHandlers();
                        setDeleteButtonHandlers();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            getData();

            function addData() {
                var id = $('#id').val();
                var nama = $('#nama').val();
                var deskripsi = $('#deskripsi').val();

                var newData = {
                    id: id,
                    nama: nama,
                    deskripsi: deskripsi
                };

                $.ajax({
                    url: 'http://localhost/tubes/koneksi.php',
                    type: 'POST',
                    data: newData,
                    dataType: 'json',
                    success: function(response) {
                        getData();

                        $('#id').val('');
                        $('#nama').val('');
                        $('#deskripsi').val('');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $('#tambahdata').click(addData);

            function setUpdateButtonHandlers() {
                $('.updateBtn').click(function() {
                    var id = $(this).data('id');
                    var row = $(this).closest('tr');
                    var nama = row.find('td:nth-child(2)').text();
                    var deskripsi = row.find('td:nth-child(3)').text();

                    var updateForm = "<td><input type='text' class='updateNama' name='nama' value='" + nama + "'></td>";
                    updateForm += "<td><input type='text' class='updateDeskripsi' name='deskripsi' value='" + deskripsi + "'></td>";
                    updateForm += "<td><button class='saveBtn' data-id='" + id + "'>Save</button></td>";

                    row.html(updateForm);
                    setSaveButtonHandlers();
                });
            }

            function setDeleteButtonHandlers() {
                $('.deleteBtn').click(function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'http://localhost/tubes/koneksi.php?id=' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            getData();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            }

            function setSaveButtonHandlers() {
                $('.saveBtn').click(function() {
                    var id = $(this).data('id');
                    var row = $(this).closest('tr');
                    var nama = row.find('.updateNama').val();
                    var deskripsi = row.find('.updateDeskripsi').val();

                    var updateData = {
                        id: id,
                        nama: nama,
                        deskripsi: deskripsi
                    };

                    $.ajax({
                        url: 'http://localhost/tubes/koneksi.php?id=' + id,
                        type: 'PUT',
                        data: updateData,
                        dataType: 'json',
                        success: function(response) {
                            getData();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
