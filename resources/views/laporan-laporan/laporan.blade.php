@extends('layouts.app', ['title' => 'Laporan'])
@section('body')
    <div class="container-fluid pt-4">
        <div class="border p-3 bg-white shadow">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Nama Laporan</label>
                    <select name="access" id="nama-laporan" class="form-control w-100">
                        <option value="" selected disabled>-- Pilih Laporan --</option>
                        @foreach ($laporan as $item)
                            {{-- <option value="{{ $item->ACCESS }}" {{ $item->ACCESS == '20501' ? 'selected' : '' }}> --}}
                            {{-- <option value="{{ $item->ACCESS }}" {{ $item->ACCESS == '202021' ? 'selected' : '' }}> --}}
                            <option value="{{ $item->ACCESS }}" {{ $item->ACCESS == '20502' ? 'selected' : '' }}>
                                {{ $item->Keterangan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <form action="{{ route('laporan-laporan.view-laporan') }}" id="form-filter-laporan" {{-- target="viewGeneratedLaporan" --}}
                class="mt-2" method="POST">
                @method('POST')
                @csrf
                <div class="row">
                    <div class="col-md-12" aria-label="filter-neraca-lajur" data-access="20501|20502">
                        <div class="row">
                            <div class="col-sm-12 my-2">
                                <div class="form-group">
                                    <label for="">Devisi</label>
                                    <input type="text" name="devisi" class="form-control" value="{{ $devisi->Devisi }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-sm-12 border my-2">
                                <div class="input-group">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-2">Periode</label>
                                    </div>
                                    <input type="number" name="bulan" id="bulan" min="1" max="12"
                                        value="{{ $periode->BULAN }}" class="form-control">
                                    <span class="mx-2" style="font-size: 24px;">/</span>
                                    <input type="number" name="tahun" id="tahun" min="2011"
                                        value="{{ $periode->TAHUN }}" max="{{ date('Y') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" aria-label="filter-buku-tambahan" data-access="202021">
                        <div class="row">
                            <div class="col-sm-12 my-2">
                                <div class="form-group">
                                    <label for="">Devisi</label>
                                    <input type="text" name="devisi" class="form-control" value="{{ $devisi->Devisi }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-sm-12 border p-2">
                                <div>
                                    <label class="mb-0 mr-2">Perkiraan</label>
                                </div>
                                <div class="input-group">
                                    <select name="awal" id="awal-perkiraan" class="form-control">
                                        <option value="1111.1">
                                            KAS
                                        </option>
                                    </select>
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mx-2">s/d</label>
                                    </div>
                                    <select name="akhir" id="akhir-perkiraan" class="form-control">
                                        <option value="7201.11">
                                            BEBAN PAJAK TANGGUHAN-OCI
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 border p-2">
                                <div>
                                    <label class="mb-0 mr-2">Periode</label>
                                </div>
                                <div class="input-group">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-2">Awal</label>
                                    </div>
                                    <input type="date" name="tglawal" id="tglawal" value="{{ date('2022-01-01') }}"
                                        class="form-control">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mx-2">Akhir</label>
                                    </div>
                                    <input type="date" name="tglakhir" id="tglakhir" value="{{ date('2022-01-31') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12 my-2">
                                <div class="input-group">
                                    <div class="d-flex align-items-center">
                                        <label class="mb-0 mr-2">1 Rp / 2 Valas</label>
                                    </div>
                                    <input type="number" name="valas" id="valas" class="form-control" value="1">
                                </div>
                            </div>
                            <div class="col-sm-12 my-2">
                                <div class="form-group">
                                    <label class="mb-0 mr-2">Jurnal Penutup</label>
                                    <div>
                                        <label class="mb-0 mr-2">
                                            <input type="radio" name="jurnal" value="Y" checked>
                                            Ya
                                        </label>
                                        <label class="mb-0 mr-2">
                                            <input type="radio" name="jurnal" value="T">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Lihat Laporan</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="sr-only" id="button-container">
                    <a class="btn btn-secondary" href="" id="preview-new-tab"
                        data-url="{{ route('laporan-laporan.generate-laporan-pdf', ['export_pdf' => true]) }}"
                        target="_blank">Export PDF</a>
                    {{-- <button class="btn btn-dark" id="reset-canvas">Reset</button>
                    <button class="btn btn-dark" id="zoom-in">Zoom In</button>
                    <button class="btn btn-dark" id="zoom-out">Zoom Out</button>
                    <button class="btn btn-dark" id="prev">Previous</button>
                    <button class="btn btn-dark" id="next">Next</button> --}}
                    {{-- &nbsp; &nbsp;
                    <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span> --}}
                </div>
                {{-- <canvas id="pdfCanvas" style="width: 100%; height: 600px;" class="border"></canvas> --}}
                <div id="laporanViewer" style="max-height: 500px" class="pt-3 overflow-auto"></div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/pdf-js/build/pdf.mjs') }}" type="module"></script>
    <script src="{{ asset('assets/js/laporan-laporan/laporan.js') }}" type="module"></script>
@endpush
