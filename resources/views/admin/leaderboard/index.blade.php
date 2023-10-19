@extends('layouts.admin.app')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Leaderboard</h1>
    <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
    @csrf 
    <div class="form-group">
        <label for="role">Kode Role</label>
        <select name="role_id" id="role" class="form-control {{$errors->has('role_id') ? 'is-invalid' : ''}}"
            style="border-color: #01004C;" oninvalid="this.setCustomValidity('Pilih salah satu kode role')"
            oninput="setCustomValidity('')">
            <option value="" disabled selected>Pilih Kode Role</option>
            <!-- Hidden option -->
            @php
            $selectedRoleIds = []; // Array untuk menyimpan role_id yang telah ditambahkan
            @endphp

            @foreach ($produk as $item)
            @if (!in_array($item->role_id, $selectedRoleIds))
            @php
            $selectedRoleIds[] = $item->role_id;
            @endphp

            <option value="{{ $item->role_id }}" {{ old('role_id') == $item->role_id ? 'selected' : '' }}>
                {{ $item->Role->kode_role }} - {{ $item->Role->jenis_role }}
            </option>
            
            @endif
            @endforeach

        </select>
    </div>

    <!-- Pesan "Pilih salah satu kode role" -->
    <div id="selectRoleMessage" class="alert alert-warning mt-3">
    Silakan memilih peran terlebih dahulu untuk melihat data.
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
    @include('components.alert')


    <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card-leaderboard" data-aos-delay="100">
                <i class="bx bx-calculator"></i>
                <h4>Download Template</h4>
                <a href="{{ route('export.excel') }}" class="btn btn-warning btn-sm" id="downloadTemplateBtn" style="display: none;">Download Template</a>
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="card-leaderboard" data-aos-delay="200">
             
              
                <h4>Import Data</h4>
                <div class="mb-3 mt-3">

<input class="form" id="formFileSm" type="file" name="file" style="display: none;" required accept=".xls, .xlsx">

</div>

<button type="submit" class="btn btn-primary btn-sm" style="display: none;">Import Data</button>              </a>
            </div>
          </div>
        </div>
    
<!-- Tombol Download Template -->
</form>




    </div>

    <div class="card-body">
        

  

<div class="dataTables_length mb-3" id="myDataTable_length">
<label for="entries"> Show
<select id="entries" name="myDataTable_length" aria-controls="myDataTable"  onchange="changeEntries()" class>
<option value="10">10</option>
<option value="25">25</option>
<option value="50">50</option>
<option value="100">100</option>
</select>
entries
</label>
</div>


<div id="myDataTable_filter" class="dataTables_filter">
<label>
Search
<input id="search"  placeholder>
</label>
</div>



    <!-- Tabel -->
    <div class="table-responsive" id="tableContainer" style="display: none;">
        <table id="myTable" class="table table-bordered" width="100%" cellspacing="0" style="border-radius: 10px;">
      
      
        <thead>
     
        <div style="display: flex; align-items: center;">
  
    <div class="form-group" style="margin-right: 10px;">
        <label for="month">Filter Bulan</label>
        <input type="month" id="month" name="month" class="form-control">
    </div>

    <button type="button" class="btn btn-success btn-sm mt-3" onclick="filterDataByMonth()">Terapkan</button>
</div>


            <tr>
                <th>Role</th>
                <th id="sortNama">Nama</th>
                <th>Kode Sales</th>
                <th>Pencapaian Penjualan Produk</th>
                <th>Total Poin</th>
                <th>Tanggal</th>
                <th>Action</th>
                <!-- Kolom lainnya -->
            </tr>
        </thead>

      <!-- Di dalam file Blade Anda -->
    <tbody id="tableBody">
    @php
    $roleCounter = []; // Array untuk menyimpan nomor urut berdasarkan peran
    @endphp
    @foreach ($leaderboardData as $item)
    @php
    $role_id = $item->role_id;

    // Inisialisasi nomor urut untuk peran jika belum ada
    if (!isset($roleCounter[$role_id])) {
        $roleCounter[$role_id] = 1;
    }

    // Ambil nomor urut untuk peran dan kemudian tambahkan satu
    $roleNumber = $roleCounter[$role_id]++;
    @endphp
    <tr data-row-id="{{ $item->id }}" data-role="{{ $item->role_id }}">        <!-- <td>{{ $roleNumber  }}</td> Nomor urutan -->
        <td>{{ $item->Role->jenis_role }}</td> <!-- Kolom Role -->
        <td>{{ $item->nama }}</td> <!-- Kolom Nama -->
        <td>{{ $item->User->username }}</td> <!-- Kolom Role -->
        <td><ul>
    @foreach ($item->pencapaian as $key => $value)
        <li>{{ $key }} : {{ $value }}</li>
    @endforeach
</ul>
</td>      

        <td>{{ $item->total }} poin</td>
    
        <td>{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
        <td>
            <form method="POST" action="{{ route('deleteleaderboard', $item->id) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="btn show_confirm" data-toggle="tooltip" title='Hapus'><i class="fas fa-fw fa-trash" style="color:red" ></i></button>
                        </form> </td>
        <!-- Kolom lainnya -->

    </tr>
    @endforeach
</tbody>

        </table>

        

        <div class="dataTables_info" id="dataTableInfo" role="status" aria-live="polite">
    Showing <span id="showingStart">1</span> to <span id="showingEnd">10</span> of <span id="totalEntries">0</span> entries
</div>

        
        <div class ="dataTables_paginate paging_simple_numbers" id="myDataTable_paginate">

    <a href="#" class="paginate_button" id="prevButton"  onclick="previousPage()"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    
    <span>
    <a  id="pageNumbers" aria-controls="myDataTable" role="link" aria-current="page" data-dt-idx="0" tabindex="0"></a>
</span>


<a href="#"  class="paginate_button" id="nextButton"  onclick="nextPage()"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
</div>

</div>
    </div>

    


</div>

<style>

.dataTables_paginate{float:right;text-align:right;padding-top:.25em}
.paginate_button{box-sizing:border-box;display:inline-block;min-width:1.5em;padding:.5em 1em;margin-left:2px;text-align:center;text-decoration:none !important;cursor:pointer;color:inherit !important;border:1px solid transparent;border-radius:2px;background:transparent}
.dataTables_length{float:left}.dataTables_wrapper .dataTables_length select{border:1px solid #aaa;border-radius:3px;padding:5px;background-color:transparent;color:inherit;padding:4px}
.dataTables_info{clear:both;float:left;padding-top:.755em}    
.dataTables_filter{float:right;text-align:right}
.dataTables_filter input{border:1px solid #aaa;border-radius:3px;padding:5px;background-color:transparent;color:inherit;margin-left:3px}

</style>
<!-- Modal Import -->
<!-- Modal Import -->
<script>
    // Fungsi ini akan dipanggil saat pilihan peran berubah

  
    var itemsPerPage = 10; // Change this value to set the number of items per page
var currentPage = 1;
var filteredData = [];

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
    }
}

function nextPage() {
    var totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
    }
}

function updatePagination() {
    // Calculate the start and end indexes for the current page
    var startIndex = (currentPage - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;

    // Hide all rows
    filteredData.forEach(function (row) {
        row.style.display = 'none';
    });

    // Display the rows for the current page
    for (var i = startIndex; i < endIndex && i < filteredData.length; i++) {
        filteredData[i].style.display = 'table-row';
    }

    // Update page numbers
    var totalPages = Math.ceil(filteredData.length / itemsPerPage);
    var pageNumbers = document.getElementById('pageNumbers');
    pageNumbers.innerHTML = '';

    var startIndex = (currentPage - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;
    var totalEntries = filteredData.length;

    document.getElementById('showingStart').textContent = startIndex + 1;
    document.getElementById('showingEnd').textContent = Math.min(endIndex, totalEntries);
    document.getElementById('totalEntries').textContent = totalEntries;

    for (var i = 1; i <= totalPages; i++) {
        var pageButton = document.createElement('button');
        pageButton.className = 'btn btn-primary btn-sm mr-2';
        pageButton.textContent = i;
        pageButton.onclick = function () {
            currentPage = parseInt(this.textContent);
            updatePagination();
        };
        pageNumbers.appendChild(pageButton);
    }

    // Update pagination controls (optional)
    // You can add next and previous buttons to navigate through pages.
    // Example:
    // var prevButton = document.getElementById('prevButton');
    // var nextButton = document.getElementById('nextButton');
    // prevButton.disabled = currentPage === 1;
    // nextButton.disabled = endIndex >= filteredData.length;
}

    function onRoleChange() {
    var roleSelect = document.getElementById('role');
    var selectedRole = roleSelect.options[roleSelect.selectedIndex].value;
    var downloadLink = "{{ route('export.excel') }}?role_id=" + selectedRole;


    
    // Sembunyikan semua baris data dalam tbody
    var allRows = document.querySelectorAll('#tableBody tr');
    allRows.forEach(function (row) {
        row.style.display = 'none';
    });

    

    if (selectedRole !== "") {
        // Sembunyikan pesan "Pilih Kode Role"
        document.getElementById('selectRoleMessage').style.display = 'none';

        // Tampilkan tabel
        document.getElementById('tableContainer').style.display = 'block';

        // Tampilkan baris data sesuai dengan peran yang dipilih
        var selectedRoleRows = document.querySelectorAll('#tableBody tr[data-role="' + selectedRole + '"]');
        selectedRoleRows.forEach(function (row) {
            row.style.display = 'table-row';
        });

    
        // Menampilkan tombol "Import Data"
        var importDataBtn = document.querySelector('.btn-primary.btn-sm');
        importDataBtn.style.display = 'inline-block';

        var downloadTemplateBtn = document.querySelector('.btn-warning.btn-sm');
        downloadTemplateBtn.style.display = 'inline-block';
        downloadTemplateBtn.href = downloadLink;

        var importFile = document.querySelector('.form');
        importFile.style.display = 'inline-block';
    } else {
        // Tampilkan pesan "Pilih Kode Role" jika tidak ada peran yang dipilih
        document.getElementById('selectRoleMessage').style.display = 'block';

        // Sembunyikan tabel
        document.getElementById('tableContainer').style.display = 'none';

        // Sembunyikan tombol "Import Data"
        var importDataBtn = document.querySelector('.btn-primary.btn-sm');
        importDataBtn.style.display = 'none';

        var downloadTemplateBtn = document.querySelector('.btn-warning.btn-sm');
        downloadTemplateBtn.style.display = 'none';

        var importFile = document.querySelector('.form');
        importFile.style.display = 'none';
    }

    currentPage = 1;

// Populate filteredData with data for the selected role
filteredData = [];
var selectedRoleRows = document.querySelectorAll('#tableBody tr[data-role="' + selectedRole + '"]');
selectedRoleRows.forEach(function (row) {
    filteredData.push(row);
});

// Update pagination
updatePagination();
search();
filterDataByMonth();
}


function filterDataByMonth() {
    var selectedMonth = document.getElementById('month').value;
   console.log(selectedMonth);
    var selectedRole = document.getElementById('role').value;
    var allRows = document.querySelectorAll('#tableBody tr');

    // Lakukan iterasi pada setiap baris dan periksa apakah bulan cocok
    allRows.forEach(function (row) {
        var roleValue = row.getAttribute('data-role');
        var tanggalColumn = row.querySelector('td:nth-child(6)').textContent;
        
        var rowMonth = tanggalColumn.split('-')[2] + '-' + tanggalColumn.split('-')[1]; // Format 'mm-yyyy'
        console.log(rowMonth);
       

        if (
            (roleValue === selectedRole || selectedRole === "") &&
            (selectedMonth === "" || rowMonth === selectedMonth)
        ) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });

}



function changeEntries() {
        var entriesSelect = document.getElementById('entries');
        var selectedEntries = parseInt(entriesSelect.value);

        // Update the 'itemsPerPage' variable with the selected number of entries
        itemsPerPage = selectedEntries;

        // Reset the current page to 1 when changing the number of entries
        currentPage = 1;

        // Update pagination based on the new number of entries
        updatePagination();
    }

    function search() {
        var keyword = document.getElementById('search').value.toLowerCase();
        var selectedRole = document.getElementById('role').value;

        // Semua baris data dalam tabel
        var allRows = document.querySelectorAll('#tableBody tr');

        // Lakukan iterasi pada setiap baris dan periksa apakah kata kunci cocok
        allRows.forEach(function (row) {
            var roleColumn = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            var namaColumn = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            var kodeSalesColumn = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            var jumlahcolumn = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            var totalpoincolumn = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            var tanggalcolumn = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

            var roleValue = row.getAttribute('data-role');

            if (
                (roleValue === selectedRole || selectedRole === "") &&
                (roleColumn.includes(keyword) || namaColumn.includes(keyword) || kodeSalesColumn.includes(keyword) || jumlahcolumn.includes(keyword) || totalpoincolumn.includes(keyword) || tanggalcolumn.includes(keyword))
            ) {
                row.style.display = 'table-row';
                
            } else {
                row.style.display = 'none';
            }
  
        });

        
    }

    // Panggil fungsi search saat input pencarian berubah
    document.getElementById('search').addEventListener('input', search);

    // Panggil onRoleChange saat halaman dimuat ulang
    window.onload = onRoleChange;
    
    // Panggil onRoleChange saat elemen select dengan id 'role' berubah
    document.getElementById('role').addEventListener('change', onRoleChange);
</script>



@endsection