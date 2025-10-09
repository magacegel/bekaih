@extends('layout.default')

@section('mainframe')
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="body table-responsive">
				<table id="pegawai" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>NUP</th>
							<th>Nama Pegawai</th>
							<th>Kota Lahir</th>
							<th>Gol. Darah</th>
							<th>Usia</th>
							<th>Jml Anak</th>
							<th>Jenis Kelamin</th>
							<th>Nama Direktorat</th>
							<th>Nama Departemen</th>
							<th>Nama Jabatan</th>
							<th>Ref. No.</th>
							<th>Kel. Peg.</th>
							<th>Grade</th>
							<th>Tgl Masuk</th>
							<th>Tgl Percobaan</th>
							<th>Tgl Kontrak</th>
							<th>Lama di BKI</th>
							<th>Tgl Organik</th>
							<th>Masa Organik</th>
							<th>Tipe Peg.</th>
							<th>Status</th>
							<th>Pendidikan</th>
							<th>Jurusan</th>
							<th>Email</th>							
							<th>No. HP</th>
							<th>NIK</th>
							<th>NPWP</th>
						</tr>
					</thead>
					<tbody>
						@foreach($querys as $query)
						<tr>
							<td>
								{{$query->nup}}
							</td>
							<td>
								{{$query->fullname}}
							</td>
							<td>
								{{$query->kotalahir}}
							</td>
							<td>
								{{$query->goldarah}}
							</td>
							<td>
								{{$query->usia}}
							</td>
							<td>
								{{$query->jmlanak}}
							</td>
							<td>
								@if($query->gender=='L')
									Laki-laki								
								@elseif($query->gender=='P')
									Perempuan
								@endif
							</td>
							<td>
								{{$query->namadirektorat}}
							</td>
							<td>
								{{$query->namadivision}}
							</td>
							<td>
								{{$query->namajabatangrade}}
							</td>
							<td>
								{{$query->refno}}
							</td>
							<td>
								{{$query->klpegchar}}
							</td>
							<td>
								@if($query->grade == '')
								-
								@else
								{{$query->grade}}
								@endif
								
							</td>
							<td>
								{{$query->tgmasuk}}
							</td>
							<td>
								{{$query->tgpercobaan}}
							</td>
							<td>
								{{$query->tgkontrakawal}}
							</td>
							<td>
								{{$query->lamadibki}}
							</td>
							<td>
								{{$query->tgorganik}}
							</td>
							<td>
								{{$query->masaorganik}}
							</td>
							<td>
								{{$query->jenisgrade}}
							</td>
							<td>
								@if($query->stpeg == 'A')
									Organik
								@elseif($query->stpeg == 'K')
									Kontrak
								@endif								
							</td>
							<td>
								{{$query->jenispendidikan}}
							</td>
							<td>
								{{$query->jurusan}}
							</td>
							<td>
								{{$query->officemail}}
							</td>
							<td>
								{{$query->mobilephone}}
							</td>
							<td>
								{{$query->nik}}
							</td>
							<td>
								{{$query->npwp}}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
            </div>
        </div>
    </section>
</div>
@endsection