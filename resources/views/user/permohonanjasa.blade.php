@extends('layout.default')

@section('mainframe')

<!-- Main Content -->
<div class="main-content">
<section class="section">
    <div class="section-header">
    <h3>Permohonan Jasa</h3>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{url('administrator')}}">Dashboard</a></div>
        <div class="breadcrumb-item">Permohonan Jasa</div>
    </div>
    </div>

    <div class="section-body">
    <h2 class="section-title">This is Example Page</h2>
    <p class="section-lead">This page is just an example for you to create your own page.</p>
    <div class="card">
        <!-- <div class="card-header">
        <h4>Example Card</h4>
        </div> -->
        <div class="card-body">
            <table id="member" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Kode Permohonan</th>
                        <th>Penyedia Barang</th>
                        <th>Alamat</th>
                        <th>Paket Pengadaan</th>
                        <th>Pengguna Barang</th>
                        <th>No Dokumen</th>
                        <th>Verifikator</th>
                        <th>Barang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonans as $permohonans)
                    <tr>
                        <td>
                            {{$permohonans->no_permohonan}}
                        </td>
                        <td>
                            {{$permohonans->perusahaan}}
                        </td>
                        <td>
                            {{$permohonans->alamat}}
                        </td>
                        <td>
                            {{$permohonans->pengadaan}}
                        </td>
                        <td>
                            {{$permohonans->keperluan}}
                        </td>
                        <td>
                            {{$permohonans->no_dokumen}}
                        </td>
                        <td>
                            {{$permohonans->pic}}
                        </td>
                        <td>
                            {{$permohonans->verifikator}}
                        </td>
                        <td>
                            <div class="buttons">
                                <a href="#" class="btn btn-icon btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-secondary"><i class="far fa-user"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-warning"><i class="fas fa-exclamation-triangle"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-success"><i class="fas fa-check"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-light"><i class="fas fa-star"></i></a>
                                <a href="#" class="btn btn-icon btn-sm btn-dark"><i class="far fa-file"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <!-- <div class="card-footer bg-whitesmoke">
            This is card footer
        </div> -->
        </div>
    </div>
</section>
</div>
@endsection


@section('css')


@endsection
@section('js')
	
@endsection