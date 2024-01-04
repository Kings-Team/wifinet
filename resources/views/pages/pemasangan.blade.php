@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">
                    Service /</span> Pemasangan</h4>
            <div class="card">
                <h5 class="card-header">
                    @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                            auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                        <button class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add-pemasangan">Tambah</button>
                    @endif
                </h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="myTable" class=" table mb-3">
                            <thead>
                                @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                        auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>No. Pendaftaran</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Paket</th>
                                        <th>Nama Sales</th>
                                        @if (auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                            <th>Nama Teknisi</th>
                                        @endif
                                        <th>Status</th>
                                    </tr>
                                @endif
                                @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>No. Pelanggan</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($pemasangan as $item)
                                    @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra) ||
                                            auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @can('update pemasangan')
                                                            @if (auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra))
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#update{{ $item->id }}"
                                                                    class="dropdown-item"><i class="bx bx-edit-alt me-1"></i>
                                                                    Edit</button>
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#assignment{{ $item->id }}"
                                                                    class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                    Assignment</button>
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#delete{{ $item->id }}"
                                                                    class="dropdown-item"><i class="bx bx-trash me-1"></i>
                                                                    Hapus</button>
                                                            @endif
                                                            @if (auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#update-survey{{ $item->id }}"
                                                                    class="dropdown-item"><i class="bx bx-edit-alt me-1"></i>
                                                                    Status Survey</button>
                                                                <button data-bs-toggle="modal"
                                                                    data-bs-target="#assignment-teknisi{{ $item->id }}"
                                                                    class="dropdown-item"><i class="bx bx-share me-1"></i>
                                                                    Assignment</button>
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->no_pendaftaran }}
                                            </td>
                                            <td>
                                                {{ $item->nik }}
                                            </td>
                                            <td>
                                                {{ $item->nama }}
                                            </td>
                                            <td>
                                                {{ $item->alamat }}
                                            </td>
                                            <td>
                                                {{ $item->telepon }}
                                            </td>
                                            <td>
                                                {{ optional($item->paket)->jenis_paket }}
                                            </td>
                                            <td>{{ $item->user_survey }}</td>
                                            @if (auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra))
                                                <td>{{ optional($item->transaksi)->user_action }}</td>
                                            @endif
                                            <td>
                                                @if ($item->status_survey === 'belum survey')
                                                    <span class="badge bg-secondary">{{ $item->status_survey }}</span>
                                                @elseif ($item->status_survey === 'gagal survey')
                                                    <span class="badge bg-danger">{{ $item->status_survey }}</span>
                                                @elseif ($item->status_survey === 'berhasil survey')
                                                    <span class="badge bg-success">{{ $item->status_survey }}</span>
                                                @else
                                                    <span class="badge bg-dark">{{ $item->status_survey }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if (auth()->user()->hasRole('teknisi ' . auth()->user()->mitra->nama_mitra))
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @can('update pemasangan')
                                                        <button data-bs-toggle="modal"
                                                            data-bs-target="#show{{ $item->id }}" class="dropdown-item"><i
                                                                class="bx bx-id-card me-1"></i>
                                                            Show</button>
                                                        <button data-bs-toggle="modal"
                                                            data-bs-target="#update-instalasi{{ $item->id }}"
                                                            class="dropdown-item"><i class="bx bx-slider-alt me-1"></i>
                                                            Instalasi</button>
                                                        <button data-bs-toggle="modal"
                                                            data-bs-target="#update-aktivasi{{ $item->id }}"
                                                            class="dropdown-item"><i class="bx bx-slider-alt me-1"></i>
                                                            Aktivasi</button>
                                                        <button class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#delete"><i class="bx bx-trash me-1"></i>
                                                            Delete</button>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->telepon }}</td>
                                        <td>
                                            @if ($item->status_aktivasi == 'berhasil aktivasi' && $item->transaksi->status == 'belum lunas')
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
                                            @if ($item->transaksi->status == 'belum lunas')
                                                <button type="button" class="btn btn-sm btn-warning" disabled
                                                    id="btnCetakPdf{{ $item->id }}">
                                                    <span class="tf-icons bx bxs-printer" data-bs-toggle="modal"></span>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-warning"
                                                    id="btnCetakPdf{{ $item->id }}">
                                                    <span class="tf-icons bx bxs-printer" data-bs-toggle="modal"></span>
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add pemasangan --}}
    <div class="modal fade" id="add-pemasangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambahkan Data Pemasangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambah" method="POST" action="{{ route('pemasangan.add') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nik" class="form-label">Nomer Induk Kependudukan</label>
                            <input class="form-control" type="text" id="nik" name="nik" required />
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input class="form-control" type="text" id="nama" name="nama" required />
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input class="form-control" type="text" id="alamat" name="alamat" required />
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input class="form-control" type="text" id="telepon" name="telepon" required />
                        </div>

                        <div class="mb-3">
                            <label for="paket_id" class="form-label">Pilih Paket</label>
                            <select id="paket_id" class="form-select" name="paket_id" required>
                                <option value="" selected>Pilih Paket</option>
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- update data pemasangan --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="update{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Data Pemasangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.update', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="Masukan Nama" value="{{ $value->nama }}" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Nomer Induk
                                        Kependudukan</label>
                                    <input type="text" id="nik" name="nik" class="form-control"
                                        placeholder="Masukan NIK" value="{{ $value->nik }}" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Alamat</label>
                                    <input type="text" id="alamat" name="alamat" class="form-control"
                                        placeholder="Masukan alamat" value="{{ $value->alamat }}" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Telepon</label>
                                    <input type="text" id="telepon" name="telepon" class="form-control"
                                        placeholder="Masukan telepon" value="{{ $value->telepon }}" required />
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="paket_id" class="form-label">Pilih Paket</label>
                                <select id="paket_id" class="form-select" name="paket_id" required>
                                    @foreach ($paket as $item)
                                        <option value="{{ $item->id }}" {{ $item->jenis_paket ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- assignment sales --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="assignment{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Assignment Sales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.assignment.sales', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="user_survey" class="form-label">Pilih Sales</label>
                                <select id="user_survey" class="form-select" name="user_survey" required>
                                    <option value="" selected>Pilih Sales</option>
                                    @foreach ($sales as $item)
                                        <option value="{{ $item->name }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- assignment teknisi --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="assignment-teknisi{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Assignment Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.assignment.teknisi', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="user_action" class="form-label">Pilih Teknisi</label>
                                <select id="user_action" class="form-select" name="user_action" required>
                                    <option value="" selected>Pilih Teknisi</option>
                                    @foreach ($teknisi as $item)
                                        <option value="{{ $item->name }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if (is_null($value->transaksi->user_action))
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

    {{-- update survey --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="update-survey{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Survey</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.survey', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="status_survey">Status Survey</label>
                                <select class="form-select" id="status_survey" name="status_survey">
                                    <option value="belum survey"
                                        {{ $value->status_survey === 'belum survey' ? 'selected' : '' }}>Belum
                                        Survey
                                    </option>
                                    <option value="berhasil survey"
                                        {{ $value->status_survey === 'berhasil survey' ? 'selected' : '' }}>
                                        Berhasil Survey
                                    </option>
                                    <option value="gagal survey"
                                        {{ $value->status_survey === 'gagal survey' ? 'selected' : '' }}>Gagal
                                        Survey
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tgl_action" class="form-label">Tanggal Survey</label>
                                <input class="form-control" type="date" name="tgl_action" id="tgl_action"
                                    value="{{ $value->tgl_action }}" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">Keterangan Hasil
                                    Survey</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" id="keterangan_survey"
                                        name="keterangan_survey" value="{{ $value->keterangan_survey }}"
                                        placeholder="Keterangan" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if ($value->status_survey == 'belum survey')
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

    {{-- modal delete --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="delete{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="formHapus" method="POST" action="{{ route('user.delete', $value->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Apakah Anda Yakin Ingin Menghapus data?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- show --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="show{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Detail Pelanggan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">No Pelanggan</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($value->pelanggan)->no_pelanggan }}" readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Nama</label>
                                    <input type="text" value="{{ $value->nama }}" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" value="{{ $value->alamat }}" readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Telepon</label>
                                    <input type="text" value="{{ $value->telepon }}" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Username Pppoe</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($value->pelanggan)->username_pppoe }}" readonly />
                                </div>
                                <div class="col mb-3 form-password-toggle">
                                    <label class="form-label" for="password">Password Pppoe</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" id="password"
                                            value="{{ optional($value->pelanggan)->password_pppoe }}" readonly /><span
                                            class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Jenis Paket</label>
                                    <input type="text" value="{{ $value->paket->jenis_paket }}" class="form-control"
                                        readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Tanggal Pasang</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($value->pelanggan)->tgl_pasang }}" readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Tanggal Isolir</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($value->pelanggan)->tgl_isolir }}" readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Status Instalasi</label>
                                    <input type="text" class="form-control" value="{{ $value->status_instalasi }}"
                                        readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Status Aktivasi</label>
                                    <input type="text" class="form-control" value="{{ $value->status_aktivasi }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Biaya</label>
                                    <input type="text" class="form-control" value="{{ $value->transaksi->biaya }}"
                                        readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Bayar</label>
                                    <input type="text" class="form-control" value="{{ $value->transaksi->biaya }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Diskon</label>
                                    <input type="text" class="form-control" value="{{ $value->transaksi->diskon }}"
                                        readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Status</label>
                                    <input type="text" class="form-control" value="{{ $value->transaksi->status }}"
                                        readonly />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- status instalasi --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="update-instalasi{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Status Instalasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.instalasi', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="status_instalasi">Status Survey</label>
                                <select class="form-select" id="status_instalasi" name="status_instalasi">
                                    <option value="" selected>Pilih Status Instalasi</option>
                                    <option value="berhasil instalasi"
                                        {{ $value->status_instalasi === 'berhasil instalasi' ? 'selected' : '' }}>
                                        Berhasil Instalasi
                                    </option>
                                    <option value="gagal instalasi"
                                        {{ $value->status_instalasi === 'gagal instalasi' ? 'selected' : '' }}>Gagal
                                        Instalasi
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if (is_null($value->status_instalasi))
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

    {{-- status aktivasi --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="update-aktivasi{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Status Aktivasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.aktivasi', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="status_aktivasi">Status Survey</label>
                                <select class="form-select" id="status_aktivasi" name="status_aktivasi">
                                    <option value="" selected>Pilih Status Aktivasi</option>
                                    <option value="berhasil aktivasi"
                                        {{ $value->status_aktivasi === 'berhasil aktivasi' ? 'selected' : '' }}>
                                        Berhasil aktivasi
                                    </option>
                                    <option value="gagal aktivasi"
                                        {{ $value->status_aktivasi === 'gagal aktivasi' ? 'selected' : '' }}>Gagal
                                        Aktivasi
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            @if (is_null($value->status_instalasi && !is_null($value->status_aktivasi)))
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

    {{-- pembayaran --}}
    @foreach ($pemasangan as $value)
        <div class="modal fade" id="pembayaran{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('pemasangan.pembayaran', $value->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">No Pelanggan</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($value->pelanggan)->no_pelanggan }}" readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Jenis Paket</label>
                                    <input type="text" value="{{ $value->paket->jenis_paket }}" class="form-control"
                                        readonly />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Iuran</label>
                                    <input type="text" class="form-control" value="{{ $value->paket->iuran }}"
                                        readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Instalasi</label>
                                    <input type="text" value="{{ $value->paket->instalasi }}" class="form-control"
                                        readonly />
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="biaya" class="form-label">Biaya</label>
                                    <input type="text" id="biaya" name="biaya" class="form-control"
                                        value="{{ $value->paket->iuran + $value->paket->instalasi }}" readonly />
                                </div>
                                <div class="col mb-3">
                                    <label for="bayar" class="form-label">Bayar</label>
                                    <input type="text" id="bayar" name="bayar" class="form-control"
                                        value="{{ $value->transaksi->bayar }}" required />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="diskon" class="form-label">Diskon</label>
                                    <input type="text" id="diskon" name="diskon" class="form-control"
                                        value="{{ $value->transaksi->diskon }}" required />
                                </div>
                                <div class="col mb-3">
                                    <label for="keterangan" class="form-label">Keterangan Diskon</label>
                                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                                        value="{{ $value->transaksi->keterangan }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status Lunas</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" selected>Pilih Status Lunas</option>
                                        <option value="lunas" {{ $value->status === 'lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="belum lunas"
                                            {{ $value->status === 'belum lunas' ? 'selected' : '' }}>Belum
                                            Lunas
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
    </script>
    <script>
        @foreach ($pemasangan as $item)
            $('#btnCetakPdf{{ $item->id }}').click(function() {
                var id = {{ $item->id }};
                var pdfUrl = "{{ route('pemasangan.invoice', ':id') }}".replace(':id', id);
                window.open(pdfUrl, '_blank');
            });
        @endforeach
    </script>
@endpush
