@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">Data
                    Master /</span> User</h4>
            <div class="card">
                <h5 class="card-header">
                    @if (auth()->user()->hasRole('route'))
                        <button class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add-user">Tambah</button>
                    @else
                        <button class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#add">Tambah</button>
                    @endif

                </h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="myTable" class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    @role('route')
                                        <th>Mitra</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($data as $item)
                                    @role('route')
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
                                                        <button data-bs-toggle="modal"
                                                            data-bs-target="#update-user{{ $item->id }}"
                                                            class="dropdown-item"><i class="bx bx-edit-alt me-1"></i>
                                                            Edit</button>
                                                        <button data-bs-toggle="modal"
                                                            data-bs-target="#modalHapus{{ $item->id }}"
                                                            class="dropdown-item"><i class="bx bx-trash me-1"></i>
                                                            Hapus</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $item->name }}</strong>
                                            </td>
                                            <td>
                                                @if ($item->roles->isNotEmpty())
                                                    <span
                                                        class="badge bg-label-primary me-1">{{ $item->roles->pluck('name')->implode(', ') }}</span>
                                                @else
                                                    No Role
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->email }}
                                            </td>
                                            <td>{{ optional($item->mitra)->nama_mitra }}</td>
                                        </tr>
                                    @endrole
                                    @role('admin ' . auth()->user()->mitra->nama_mitra)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#update-user-admin{{ $item->id }}"
                                                    class="btn btn-sm btn-icon btn-warning">
                                                    <span class="tf-icons bx bx-edit"></span>
                                                </button>
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                @if ($item->roles->isNotEmpty())
                                                    <span
                                                        class="badge bg-label-primary me-1">{{ $item->roles->pluck('name')->implode(', ') }}</span>
                                                @else
                                                    No Role
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->email }}
                                            </td>
                                        </tr>
                                    @endrole
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add --}}
    <div class="modal fade" id="add-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.add') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" id="nama" name="name" class="form-control"
                                    placeholder="Masukan Name" required />
                            </div>
                            <div class="col mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" id="role" name="name_role" class="form-control"
                                    placeholder="Enter Name" required />
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col mb-0">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control"
                                    placeholder="xxxx@gmail.com" required />
                            </div>
                            <div class="col mb-0">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" id="password" name="password" class="form-control"
                                    placeholder="********" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col mb-0">
                                <label for="mitra_id" class="form-label">Mitra</label>
                                <div class="input-group">
                                    <select class="form-select" id="mitra_id" name="mitra_id">
                                        <option value="" selected>Pilih Mitra</option>
                                        @foreach ($mitra as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_mitra }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#add-mitra" type="button">Tambah Mitra</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col mb-0">
                                <label class="form-label">Permissions</label>
                                <div class="form-check d-flex flex-wrap">
                                    <div class="form-check me-3 mb-2" style="flex-basis: 20%;">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                        <label class="form-check-label" for="select-all">check all</label>
                                    </div>
                                    @foreach ($permission as $item)
                                        <div class="form-check me-3 mb-2" style="flex-basis: 20%;">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                id="permissions_{{ $item->id }}" value="{{ $item->name }}">
                                            <label class="form-check-label"
                                                for="permissions_{{ $item->id }}">{{ $item->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- add mitra --}}
    <div class="modal fade" id="add-mitra" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Tambah Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('mitra.post') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nama_mitra" class="form-label">Nama Mitra</label>
                                <input type="text" id="nama_mitra" name="nama_mitra" class="form-control"
                                    placeholder="Masukan Nama Mitra" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#add-user">
                            Kembali
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal delete --}}
    @foreach ($data as $value)
        <div class="modal fade" id="modalHapus{{ $value->id }}" tabindex="-1" aria-hidden="true">
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

    {{-- modal update --}}
    @foreach ($data as $value)
        <div class="modal fade" id="update-user{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Data User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.update', $value->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" id="nama" name="name" class="form-control"
                                        placeholder="Masukan Name" value="{{ $value->name }}" required />
                                </div>
                                <div class="col mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    @php
                                        $firstRoleName = explode(' ', $value->roles->first()->name)[0];
                                    @endphp
                                    <input type="text" id="role" name="name_role" class="form-control"
                                        placeholder="Enter Name" value="{{ $firstRoleName }}" required />
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control"
                                        placeholder="xxxx@gmail.com" value="{{ $value->email }}" required />
                                </div>
                                <div class="col mb-0">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="text" id="password" name="password" class="form-control"
                                        placeholder="********" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col mb-0">
                                    <label for="mitra_id" class="form-label">Mitra</label>
                                    <select class="form-select" id="mitra_id" name="mitra_id">
                                        @foreach ($mitra as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->nama_mitra ? 'selected' : '' }}>
                                                {{ $item->nama_mitra }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col mb-0">
                                    <label class="form-label">Permissions</label>
                                    <div class="form-check d-flex flex-wrap">
                                        <div class="form-check me-3 mb-2" style="flex-basis: 20%;">
                                            <input class="form-check-input" type="checkbox"
                                                id="select-all-{{ $value->id }}">
                                            <label class="form-check-label" for="select-all-{{ $value->id }}">check
                                                all</label>
                                        </div>
                                        @foreach ($permission as $item)
                                            <div class="form-check me-3 mb-2" style="flex-basis: 20%;">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    id="p_{{ $item->id }}" value="{{ $item->name }}"
                                                    {{ $value->roles->flatMap->permissions->contains('name', $item->name) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="p_{{ $item->id }}">{{ $item->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- modal update admin --}}
    @foreach ($data as $value)
        <div class="modal fade" id="update-user-admin{{ $value->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Data User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.update.admin', $value->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" id="nama" name="name" class="form-control"
                                        placeholder="Masukan Name" value="{{ $value->name }}" required />
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control"
                                        placeholder="xxxx@gmail.com" value="{{ $value->email }}" required />
                                </div>
                                <div class="col mb-0">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="text" id="password" name="password" class="form-control"
                                        placeholder="********" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Tutup
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
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');

            selectAllCheckbox.addEventListener('change', function() {
                for (const checkbox of permissionCheckboxes) {
                    checkbox.checked = this.checked;
                }
            });

            for (const checkbox of permissionCheckboxes) {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateModals = document.querySelectorAll('[id^=update-user]');

            for (const modal of updateModals) {
                const selectAllCheckbox = modal.querySelector('[id^=select-all]');
                const permissionCheckboxes = modal.querySelectorAll('input[name="permissions[]"]');

                selectAllCheckbox.addEventListener('change', function() {
                    for (const checkbox of permissionCheckboxes) {
                        checkbox.checked = this.checked;
                    }
                });

                for (const checkbox of permissionCheckboxes) {
                    checkbox.addEventListener('change', function() {
                        if (!this.checked) {
                            selectAllCheckbox.checked = false;
                        } else {
                            const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                            selectAllCheckbox.checked = allChecked;
                        }
                    });
                }
            }
        });
    </script>
@endpush
