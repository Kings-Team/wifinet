@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">Services /</span> Ubah Paket
            </h4>
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="true"
                            style="color: white">Proses
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile"
                            aria-selected="false" style="color: white">
                            Berhasil
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-top-messages" aria-controls="navs-pills-top-messages"
                            aria-selected="false" style="color: white">
                            Gagal
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
                        <h5 class="card-header mb-5">
                            @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                    auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                <button class="btn btn-outline-primary float-end" data-bs-toggle="modal"
                                    data-bs-target="#add-ubahpaket">Tambah</button>
                            @endif
                        </h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table mb-3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>No Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Paket Lama</th>
                                        <th>Paket Baru</th>
                                        @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Tanggal Ubah</th>
                                            <th>Status Kunjungan</th>
                                        @endif
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @can('read ubah paket')
                                        @foreach ($ubahpaket as $item)
                                            @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                    auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @can('update ubah paket')
                                                                    @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra))
                                                                        <button data-bs-toggle="modal"
                                                                            data-bs-target="#visitpelanggan{{ $item->id }}"
                                                                            class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                            Validasi</button>
                                                                    @endif
                                                                    @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra))
                                                                        <button data-bs-toggle="modal"
                                                                            data-bs-target="#status{{ $item->id }}"
                                                                            class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                            Status</button>
                                                                        <button data-bs-toggle="modal"
                                                                            data-bs-target="#pembayaran{{ $item->id }}"
                                                                            class="dropdown-item"><i class="bx bx-card me-1"></i>
                                                                            Pembayaran</button>
                                                                        {{-- <button data-bs-toggle="modal"
                                                                            data-bs-target="#ubahpaket{{ $item->id }}"
                                                                            class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                            Cetak Nota</button> --}}
                                                                    @endif
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>{{ $item->updated_at->format('d F Y H:i:s') }}</td>
                                                    <td>
                                                        @if (is_null($item->status_visit))
                                                            <span class="badge bg-secondary">Belum Diproses</span>
                                                        @elseif ($item->status_visit === 'Perlu')
                                                            <span class="badge bg-secondary">{{ $item->status_visit }}</span>
                                                        @elseif ($item->status_visit === 'Tidak Perlu')
                                                            <span class="badge bg-secondary">{{ $item->status_visit }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @elseif (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                @can('update ubah paket')
                                                                    <button data-bs-toggle="modal"
                                                                        data-bs-target="#status{{ $item->id }}"
                                                                        class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                        Status</button>
                                                                    <button data-bs-toggle="modal"
                                                                        data-bs-target="#pembayaran{{ $item->id }}"
                                                                        class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                        Pembayaran</button>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>
                                                        {{ $item->lunas }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endcan
                                </tbody>
                            </table>
                            {{-- <div class="col-lg-12 ">{{ $kolektors->links('pagination::bootstrap-5') }}</div> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                        <div class="card-body mb-4">
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table mb-4">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Paket Lama</th>
                                        <th>Paket Baru</th>
                                        @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Tanggal Ubah</th>
                                        @endif
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @can('read ubah paket')
                                        @foreach ($berhasil as $item)
                                            @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                    auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->paket }}</td>
                                                    <td>{{ $item->updated_at->format('d F Y H:i:s') }}</td>
                                                </tr>
                                            @elseif (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->paket }}</td>
                                                    <td>
                                                        {{ $item->lunas }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endcan
                                </tbody>
                            </table>
                            {{-- <div class="col-lg-12 ">{{ $kolektors->links('pagination::bootstrap-5') }}</div> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-top-messages" role="tabpanel">
                        <div class="card-body mb-4">
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table mb-4">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Paket Lama</th>
                                        <th>Paket Baru</th>
                                        @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Tanggal Ubah</th>
                                        @endif
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                            <th>Status</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @can('read ubah paket')
                                        @foreach ($gagal as $item)
                                            @if (auth()->user()->hasRole('admin') ||
                                                    auth()->user()->hasRole('sales'))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->paket }}</td>
                                                    <td>{{ $item->updated_at->format('d F Y H:i:s') }}</td>
                                                </tr>
                                            @elseif (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->paket }}</td>
                                                    <td>
                                                        {{ $item->lunas }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endcan
                                </tbody>
                            </table>
                            {{-- <div class="col-lg-12 ">{{ $kolektors->links('pagination::bootstrap-5') }}</div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (Session::has('message'))
                Swal.fire({
                    title: 'Berhasil',
                    text: '{{ Session::get('message') }}',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            @endif
            @if ($errors->any())
                var errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '{{ $error }}\n';
                @endforeach

                Swal.fire({
                    title: 'Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            @endif
        });
    </script>
@endpush
