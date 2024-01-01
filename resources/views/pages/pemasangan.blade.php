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
                <div class="table-responsive text-nowrap">
                    <table class="table mb-3">
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
                                                <button data-bs-toggle="modal" data-bs-target="#show{{ $item->id }}"
                                                    class="dropdown-item"><i class="bx bx-id-card me-1"></i>
                                                    Show</button>
                                                <button data-bs-toggle="modal"
                                                    data-bs-target="#instalasi{{ $item->id }}" class="dropdown-item"><i
                                                        class="bx bx-slider-alt me-1"></i>
                                                    Instalasi</button>
                                                <button data-bs-toggle="modal"
                                                    data-bs-target="#aktivasi{{ $item->id }}" class="dropdown-item"><i
                                                        class="bx bx-slider-alt me-1"></i>
                                                    Aktivasi</button>
                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#delete"><i class="bx bx-trash me-1"></i>
                                                    Delete</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                    <td>{{ $item->nik }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>{{ $item->telepon }}</td>
                                    <td> <button type="button" class="btn btn-primary">
                                            <span class="tf-icons bx bxs-credit-card" data-bs-toggle="modal"
                                                data-bs-target="#pembayaran{{ $item->id }}"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning" id="btnCetakPdf{{ $item->id }}">
                                            <span class="tf-icons bx bxs-printer" data-bs-toggle="modal"></span>
                                        </button>
                                    </td>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
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
