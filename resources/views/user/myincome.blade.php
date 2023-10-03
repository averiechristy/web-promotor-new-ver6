<!-- myincome.blade.php -->
@extends('layouts.user.app2')

@section('content2')
<main id="main">
    <section id="myincome" class="myincome">
        <div class="container">
            
            <div class="row">
                <!-- Filter Form -->
                <div class="col-md-6 offset-md-3">
                    
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Filter Pendapatan</h5>
                            <form id="filter-form" method="GET">
                                <div class="form-group">
                                    <label for="start_date">Mulai Tanggal</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('user.myincome') }}" class="btn btn-secondary">Reset Tanggal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <!-- Pendapatan Saya -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif
                            <h5 class="card-title">Pendapatan Saya</h5>
                            <div id="pendapatan-list">
                            @if(isset($incomePoints))
<div class="total-info">
    <h5>Total Pendapatan:</h5>
    <p>Rp. {{ number_format($incomePoints->total_pendapatan, 0, ',', '.') }},-</p>
    <h5>Total Poin:</h5>
    <p>{{ $incomePoints->total_poin }} poin</p>
</div>
@else
<div class="total-info">
    <h5>Total Pendapatan bulan {{ date('F') }}:</h5>
    <p>Rp. {{ number_format($totalIncomeThisMonth, 0, ',', '.') }},-</p>
    <h5>Total Poin bulan {{ date('F') }}:</h5>
    <p>{{ $totalPointsThisMonth }} poin</p>
</div>


        
@if ($activeReward)
<div class="progress" style="height: 20px;">
    @php
    $progressWidth = ($totalPointsThisMonth >= $activeReward->poin_reward) ? '100%' : ($totalPointsThisMonth / $activeReward->poin_reward * 100) . '%';
    @endphp
    <div class="progress-bar" role="progressbar" style="width: {{ $progressWidth }};" aria-valuenow="{{ $totalPointsThisMonth }}" aria-valuemin="0" aria-valuemax="{{ $activeReward->poin_reward }}">
        <span class="progress-label"> {{ $totalPointsThisMonth }} poin</span>
    </div>
    @if ($totalPointsThisMonth >= $activeReward->poin_reward)
    @else
        <span class="poin-reward-info">{{ $activeReward->poin_reward }} poin reward</span>
    @endif
</div>

<!-- Informasi berapa lagi poin yang harus dicapai bulan ini -->
<p class="remaining-points">
    @if ($totalPointsThisMonth >= $activeReward->poin_reward)
        Selamat!, poin Anda sudah mencukupi untuk target reward bulan ini.
    @else
        Sisa {{ $activeReward->poin_reward - $totalPointsThisMonth }} poin untuk mencapai target bulan ini.
    @endif
</p>
@else
<p>Tidak ada reward yang sedang berjalan saat ini.</p>
@endif

    @endif


                                <!-- Tampilkan detail pendapatan dan poin sesuai kebutuhan -->
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

<style>

    /* Style the progress bar and labels */
.progress {
    height: 30px;
}

.progress-bar {
    font-weight: bold;
}


/* Style the remaining points info */
.remaining-points {
    text-align: center;
    margin-top: 10px;
}

    /* myincome.css */

    /* Style the main section */
    #myincome {
        padding: 50px 0;
        background-color: #f8f8f8;
    }

    /* Style the section title */
    .section-title {
        text-align: center;
        margin-bottom: 30px;
    }

    /* Style the filter card */
    .card {
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
    }

    .card-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #333;
    }

    /* Style the filter form */
    .form-group {
        margin-bottom: 15px;
    }

    /* Style the buttons in the filter form */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    /* Style the income and points section */
    #pendapatan-list {
        margin-top: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    /* Style the total income and total points */
    .total-info h5 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        font-weight: bold;
    }

    /* Style the success message */
    .alert-success {
        background-color: #28a745;
        color: #fff;
    }

    /* Style the error message */
    .alert-danger {
        background-color: #dc3545;
        color: #fff;
    }

    /* Add additional styling as needed */
</style>