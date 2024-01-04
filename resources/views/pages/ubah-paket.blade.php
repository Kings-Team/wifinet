@extends('layouts.app')

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('content')
    <div class="content">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">Services /</span> Ubah Paket
            </h4>
            <div class="nav-align-top">
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
                            <table id="myTable" class=" table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra))
                                            <th>Aksi</th>
                                        @endif
                                        <th>No Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Paket Lama</th>
                                        <th>Paket Baru</th>
                                        @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Tanggal Ubah</th>
                                            <th>Status Kunjungan</th>
                                        @endif
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra))
                                            <th>Pembayaran</th>
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
                                                    @can('update ubah paket')
                                                        @role('admin ' . auth()->user()->mitra->nama_mitra)
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                        data-bs-toggle="dropdown">
                                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <button data-bs-toggle="modal"
                                                                            data-bs-target="#visitpelanggan{{ $item->id }}"
                                                                            class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                            Validasi</button>
                                                                        @if ($item->status_visit == 'Tidak Perlu' || is_null($item->status_visit))
                                                                            <button data-bs-toggle="modal"
                                                                                data-bs-target="#status{{ $item->id }}"
                                                                                class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                                Status</button>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endrole
                                                    @endcan
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>{{ $item->updated_at->format('d F Y H:i:s') }}</td>
                                                    <td>
                                                        @if (is_null($item->status_visit))
                                                            <span class="badge bg-secondary">Belum Diproses</span>
                                                        @elseif ($item->status_visit === 'Perlu')
                                                            <span class="badge bg-success">{{ $item->status_visit }}</span>
                                                        @elseif ($item->status_visit === 'Tidak Perlu')
                                                            <span class="badge bg-danger">{{ $item->status_visit }}</span>
                                                        @endif
                                                    </td>
                                                    @role('admin ' . auth()->user()->mitra->nama_mitra)
                                                        <td>
                                                            @if ($item->status_proses == 'Berhasil')
                                                                <button type="button" class="btn btn-sm btn-primary">
                                                                    <span class="tf-icons bx bxs-credit-card" data-bs-toggle="modal"
                                                                        data-bs-target="#pembayaran{{ $item->id }}"></span>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-primary" disabled>
                                                                    <span class="tf-icons bx bxs-credit-card" data-bs-toggle="modal"
                                                                        data-bs-target="#pembayaran{{ $item->id }}"></span>
                                                                </button>
                                                            @endif

                                                        </td>
                                                    @endrole
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
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>
                                                        @if ($item->status_proses == 'berhasil')
                                                            <button type="button" class="btn btn-primary">
                                                                <span class="tf-icons bx bxs-credit-card" data-bs-toggle="modal"
                                                                    data-bs-target="#pembayaran{{ $item->id }}"></span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-primary" disabled>
                                                                <span class="tf-icons bx bxs-credit-card" data-bs-toggle="modal"
                                                                    data-bs-target="#pembayaran{{ $item->id }}"></span>
                                                            </button>
                                                        @endif

                                                        @if ($item->transaksi->status == 'belum lunas')
                                                            <button type="button" class="btn btn-warning" disabled
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-warning"
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endcan
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                        <div class="card-body">
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table id="myTable" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Paket Lama</th>
                                        <th>Paket Baru</th>
                                        @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Cetak Invoice</th>
                                        @endif
                                        @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                            <th>Cetak Invoice</th>
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
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->pelanggan->paket->jenis_paket }}</td>
                                                    <td>
                                                        @if ($item->transaksi->status == 'belum lunas')
                                                            <button type="button" class="btn btn-sm btn-warning" disabled
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @elseif (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->pelanggan->paket->jenis_paket }}</td>
                                                    <td>
                                                        @if ($item->transaksi->status == 'belum lunas')
                                                            <button type="button" class="btn btn-sm btn-warning" disabled
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                id="btnCetakPdf{{ $item->id }}">
                                                                <span class="tf-icons bx bxs-printer"
                                                                    data-bs-toggle="modal"></span>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endcan
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-top-messages" role="tabpanel">
                        <div class="card-body">
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table id="myTable" class="table">
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
                                            @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                                    auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>{{ $item->updated_at->format('d F Y H:i:s') }}</td>
                                                </tr>
                                            @elseif (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                                    <td>{{ $item->pelanggan->pemasangan->nama }}</td>
                                                    <td>{{ $item->paket_lama }}</td>
                                                    <td>{{ $item->paket->jenis_paket }}</td>
                                                    <td>
                                                        {{ $item->transaksi->status }}
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
    {{-- modal tambah --}}
    <div class="modal fade" id="add-ubahpaket" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Ajukan Ubah Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('ubah-paket.add') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <label for="pelanggan_id" class="form-label">Cari Pelanggan</label>
                        <div class="row">
                            <div class="mb-3">
                                <select id="pelanggan_id" class="form-select" style="width: 100%" name="pelanggan_id"
                                    required>
                                    <option selected>Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->no_pelanggan }} |
                                            {{ $item->pemasangan->nama }} |
                                            {{ $item->paket->jenis_paket }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="paket_baru_id" class="form-label">Paket Baru</label>
                            <select id="paket_baru_id" class="form-select" name="paket_baru_id" required>
                                <option selected>Pilih Paket Baru</option>
                                @foreach ($paket as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->jenis_paket }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal visit ges --}}
    @foreach ($ubahpaket as $item)
        <div class="modal fade" id="visitpelanggan{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Validasi Visit Pelanggan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('ubah-paket.visit', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="status_visit" class="form-label">Status Visit</label>
                                <select id="status_visit" class="form-select" name="status_visit" required>
                                    <option selected>Pilih Status Visit</option>
                                    <option value="Perlu">Perlu Pemasangan Perangkat</option>
                                    <option value="Tidak Perlu">Tidak Perlu Pemasangan Perangkat</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if (is_null($item->status_proses))
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            @else
                                <button type="button" class="btn btn-primary" disabled>Simpan</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- modal status proses  --}}
    @foreach ($ubahpaket as $item)
        <div class="modal fade" id="status{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Status Proses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('ubah-paket.proses', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="status_proses" class="form-label">Status Proses</label>
                                <select id="status_proses" class="form-select" name="status_proses" required>
                                    <option selected>Pilih Status Proses</option>
                                    <option value="Berhasil">Berhasil</option>
                                    <option value="Gagal">Gagal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">Keterangan</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="keterangan_proses"
                                        name="keterangan_proses" value="" placeholder="ket...." />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if (is_null($item->status_proses))
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            @else
                                <button type="submit" class="btn btn-primary" disabled>Simpan</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- modal pembayaran ges --}}
    @foreach ($ubahpaket as $item)
        <div class="modal fade" id="pembayaran{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('ubah-paket.pembayaran', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">No Pelanggan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" id="no pelanggan" name="no pelanggan"
                                            value="{{ $item->pelanggan->no_pelanggan }}" placeholder="no pelanggan"
                                            readonly />
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label" for="tgl_action">Tanggal Action</label>
                                    <div class="input-group input-group-merge">
                                        <input type="date" class="form-control" id="tgl_action" name="tgl_action"
                                            value="" placeholder="" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">Biaya</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" id="biaya" name="biaya"
                                            value="{{ $item->paket->iuran + $item->paket->instalasi }}"
                                            placeholder="biaya" readonly />
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">Diskon</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" class="form-control" id="diskon" name="diskon"
                                            value="{{ $item->transaksi->diskon }}" placeholder="diskon" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">Bayar</label>
                                    <div class="input-group input-group-merge">
                                        <input type="number" class="form-control" id="bayar" name="bayar"
                                            value="" placeholder="" />
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <label for="status" class="form-label">Status Pembayaran</label>
                                    <select id="status" class="form-select" name="status" required>
                                        <option value="lunas">Lunas</option>
                                        <option value="belum lunas">Belum Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan Diskon</label>
                                <textarea class="form-control" id="keterangan" rows="3" name="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if ($item->transaksi == 'lunas')
                                <button type="submit" class="btn btn-primary" disabled>Simpan</button>
                            @else
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (Session::has('message'))
                Swal.fire({
                    title: 'Berhasil',
                    text: '{{ Session::get('message') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
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
                    confirmButtonText: 'OK'
                });
            @endif
        });

        $(document).ready(function() {
            $('#pelanggan_id').select2({
                placeholder: 'Cari Pelanggan',
                dropdownParent: $('#add-ubahpaket')
            });

            @foreach ($ubahpaket as $item)
                $(document).on('click', '#btnCetakPdf{{ $item->id }}', function() {
                    var id = {{ $item->id }};
                    var pdfUrl = "{{ route('ubah-paket.invoice', ['id' => ':id']) }}".replace(':id', id);
                    window.open(pdfUrl, '_blank');
                });
            @endforeach
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            var targetTab = $(e.target).attr('href'); // newly activated tab
            if (targetTab === '#navs-pills-top-profile') {
                // Initialize modals or any other actions specific to this tab
                // Example: $('#myModal').modal('show');
            }
        });
    </script>
@endpush
