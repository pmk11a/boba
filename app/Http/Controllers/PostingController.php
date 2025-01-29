<?php

namespace App\Http\Controllers;

use App\Http\Repository\Task\GlobalInterface;
use App\Http\Services\CustomDataTable;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    private $globalRepository;
    private $access;

    public function __construct(GlobalInterface $globalRepository)
    {
        $this->globalRepository = $globalRepository;
        $this->middleware(function ($request, $next) {
            $this->access = auth()->user()->getPermissionsName('01001008');
            return $next($request);
        });
    }

    public function posting()
    {
        $postings = [
            ["cardId" => "KAS", "cardName" => "Table Kas", "cardIcon" => "fa fa-money-bill-wave", "cardComponent" => "kas", "modalWidth" => "md"],
            ["cardId" => "BANK", "cardName" => "Table Bank", "cardIcon" => "fa fa-university", "cardComponent" => "kas", "modalWidth" => "md"],
            ["cardId" => "AKUMULASI", "cardName" => "Table Akumulasi", "cardIcon" => "fa fa-calculator", "cardComponent" => "kas", "modalWidth" => "md"],
            ["cardId" => "AKTIVA", "cardName" => "Table Aktiva", "cardIcon" => "fa fa-book", "cardComponent" => "aktiva", "modalWidth" => "xl"],
            ["cardId" => "HARGAPOKOK", "cardName" => "Harga Pokok", "cardIcon" => "fa fa-times"],
            ["cardId" => "PIUTANG", "cardName" => "Piutang", "cardIcon" => "fa fa-times"],
            ["cardId" => "HUTANG", "cardName" => "Hutang", "cardIcon" => "fa fa-times"],
            ["cardId" => "DEPOSITO", "cardName" => "Deposito", "cardIcon" => "fa fa-times"],
            ["cardId" => "UMPIUTANG", "cardName" => "UM Piutang", "cardIcon" => "fa fa-times"],
            ["cardId" => "UMHUTANG", "cardName" => "UM Hutang", "cardIcon" => "fa fa-times"],
            ["cardId" => "PIUTANGSEMENTARA", "cardName" => "Piutang Sementara", "cardIcon" => "fa fa-times"],
            ["cardId" => "HUTANGSEMENTARA", "cardName" => "Hutang Sementara", "cardIcon" => "fa fa-times"],
            ["cardId" => "RLTAHUNLALU", "cardName" => "RL Tahun Lalu", "cardIcon" => "fa fa-times"],
            ["cardId" => "RLTAHUNINI", "cardName" => "RL Tahun Ini", "cardIcon" => "fa fa-times"],
            ["cardId" => "RLBULANINI", "cardName" => "RL Bulan Ini", "cardIcon" => "fa fa-times"],
            ["cardId" => "SELISIH", "cardName" => "Selisih", "cardIcon" => "fa fa-times"],
            ["cardId" => "BIAYADEBET", "cardName" => "Biaya Debet Note", "cardIcon" => "fa fa-times"],
            ["cardId" => "BIAYAKREDIT", "cardName" => "Biaya Kredit Note", "cardIcon" => "fa fa-times"],
            ["cardId" => "BIAYAOPNAME", "cardName" => "Biaya Opname", "cardIcon" => "fa fa-times"],
            ["cardId" => "WIP", "cardName" => "W I P", "cardIcon" => "fa fa-times"],
            ["cardId" => "PENDAPATAN", "cardName" => "Pendapatan", "cardIcon" => "fa fa-times"],
            ["cardId" => "PPNMASUKAN", "cardName" => "PPN Masukan", "cardIcon" => "fa fa-times"],
            ["cardId" => "PPNKELUARAN", "cardName" => "PPN Keluaran", "cardIcon" => "fa fa-times"],
            ["cardId" => "PPHMASUKAN", "cardName" => "PPH Masukan", "cardIcon" => "fa fa-times"],
            ["cardId" => "PPHKELUARAN", "cardName" => "PPH Keluaran", "cardIcon" => "fa fa-times"],

        ];
        return view('master_data.master_accounting.posting', [
            'postings' => $postings
        ]);
    }

    public function getAllKelompokKas($kode)
    {
        $this->requestAjax($this->access, 'HASACCESS');
        return CustomDataTable::init()
            ->of($this->globalRepository->getKelompokKasOrBank($kode))
            ->mapData(function ($data) {
                $data->Perkiraan = trim($data->Perkiraan);
                $data->Keterangan = trim($data->Keterangan);
                return $data;
            })
            ->apply()
            ->addColumn('action', function ($data) use ($kode) {
                $button = '<div style="max-width: 100%; position: relative; width: 1px; height: 1px; margin: auto;">
                <div class="notification-container close-button-container">';
                if (in_array('ISKOREKSI', $this->access)) {
                    $button .= "<button class='btn btn-warning btn-sm mr-1 btnEditPostingKas' data-perkiraan='{$data->Perkiraan}'><i class='fa fa-pen mr-1'></i>Edit</button>";
                }
                if (in_array('ISHAPUS', $this->access)) {
                    $url = route('master-data.master-accounting.posting.deletePosting', ['posting' => $kode, 'id' => $data->Perkiraan]);
                    $button .= "<button class='btn btn-danger btn-sm mr-1 btnGlobalDelete' data-url='{$url}' data-id='{$data->Perkiraan}' data-key='{$data->Perkiraan}'><i class='fa fa-trash mr-1'></i>Hapus</button>";
                }
                $button .= '</div></div><button type="button" class="btn btn-primary btn-sm showButton" id=""><i class="fa fa-arrow-alt-circle-left"></i></button>';
                return $button;
            })->done();
    }

    public function getAllKelompokAktiva()
    {
        $this->requestAjax($this->access, 'HASACCESS');
        return CustomDataTable::init()
            ->of($this->globalRepository->getKelompokAktiva())
            ->apply()
            ->addColumn('action', function ($data) {
                $button = '<div style="max-width: 100%; position: relative; width: 1px; height: 1px; margin: auto;">
                <div class="notification-container close-button-container">';
                if (in_array('ISKOREKSI', $this->access)) {
                    $button .= "<button class='btn btn-warning btn-sm mr-1 btnEditPosting' data-perkiraan='{$data->Perkiraan}'><i class='fa fa-pen mr-1'></i>Edit</button>";
                }
                if (in_array('ISHAPUS', $this->access)) {
                    $url = route('master-data.master-accounting.posting.deletePosting', ['posting' => 'AKTIVA', 'id' => $data->Perkiraan]);
                    $button .= "<button class='btn btn-danger btn-sm mr-1 btnGlobalDelete' data-url='{$url}' data-id='{$data->Perkiraan}' data-key='{$data->Perkiraan}'><i class='fa fa-trash mr-1'></i>Hapus</button>";
                }
                $button .= '</div></div><button type="button" class="btn btn-primary btn-sm showButton" id=""><i class="fa fa-arrow-alt-circle-left"></i></button>';
                return $button;
            })->done();
    }

    public function getTable($kode)
    {
        switch ($kode) {
            case 'KAS':
                return $this->getAllKelompokKas($kode);
                break;
            case 'BANK':
                return $this->getAllKelompokKas($kode);
                break;
            case 'AKUMULASI':
                return $this->getAllKelompokKas('AKM');
                break;
            case 'AKTIVA':
                return $this->getAllKelompokAktiva();
                break;
            default:
                return $this->setResponseError('Halaman tidak ditemukan', 500);
                break;
        }
    }

    public function getModalPosting($kode, $component)
    {
        switch ($kode) {
            case 'KAS':
                $url = route('master-data.master-accounting.posting.getTable', ['posting' => $kode]);
                return [
                    'formAction' => $url,
                    'modalTitle' => 'Kelompok Kas',
                    'component' => $component,
                    'datatableUrl' => $url,
                    'callback' => "postingKAS('formPostingKAS')"
                ];
                break;
            case 'BANK':
                $url = route('master-data.master-accounting.posting.getTable', ['posting' => $kode]);
                return [
                    'formAction' => $url,
                    'modalTitle' => 'Kelompok Bank',
                    'component' => $component,
                    'datatableUrl' => $url,
                    'callback' => "postingKAS('formPostingBANK')"
                ];
                break;
            case 'AKUMULASI':
                $url = route('master-data.master-accounting.posting.getTable', ['posting' => $kode]);
                return [
                    'formAction' => $url,
                    'modalTitle' => 'Kelompok Akumulasi',
                    'component' => $component,
                    'datatableUrl' => $url,
                    'callback' => "postingKAS('formPostingAKUMULASI')"
                ];
                break;
            case 'AKTIVA':
                $url = route('master-data.master-accounting.posting.getTable', ['posting' => $kode]);
                return [
                    'formAction' => $url,
                    'modalTitle' => 'Kelompok Aktiva',
                    'component' => $component,
                    'datatableUrl' => $url,
                    'callback' => "postingAktiva()"
                ];
                break;
            default:
                return abort(404, 'Halaman tidak ditemukan');
                break;
        }
    }

    public function storePosting(Request $request, $posting)
    {
        $this->requestAjax($this->access, 'ISKOREKSI');
        switch ($posting) {
            case 'KAS':
                if ($this->globalRepository->storeKelompokKasOrBank($request->Perkiraan, $request->oldPerkiraan, 'KAS')) {
                    return $this->setResponseSuccess();
                }
                break;
            case 'BANK':
                if ($this->globalRepository->storeKelompokKasOrBank($request->Perkiraan, $request->oldPerkiraan, 'BANK')) {
                    return $this->setResponseSuccess();
                }
                break;
            case 'AKUMULASI':
                if ($this->globalRepository->storeKelompokKasOrBank($request->Perkiraan, $request->oldPerkiraan, 'AKM')) {
                    return $this->setResponseSuccess();
                }
            case 'AKTIVA':
                if ($this->globalRepository->storeKelompokAktiva($request, $request->oldPerkiraan)) {
                    return $this->setResponseSuccess();
                }
                break;
        }
        return $this->setResponseError('Halaman tidak ditemukan', 500);
    }

    public function deletePosting($posting, $id)
    {
        $this->requestAjax($this->access, 'ISHAPUS');
        switch ($posting) {
            case 'KAS':
                if ($this->globalRepository->deleteKelompokKasOrBank($id, 'KAS')) {
                    return $this->setResponseData(['datatable' => 'datatableMain']);
                }
                break;
            case 'BANK':
                if ($this->globalRepository->deleteKelompokKasOrBank($id, 'BANK')) {
                    return $this->setResponseData(['datatable' => 'datatableMain']);
                }
                break;
            case 'AKM':
                if ($this->globalRepository->deleteKelompokKasOrBank($id, 'AKM')) {
                    return $this->setResponseData(['datatable' => 'datatableMain']);
                }
            case 'AKTIVA':
                if ($this->globalRepository->deleteKelompokAktiva($id)) {
                    return $this->setResponseData(['datatable' => 'datatableMain']);
                }
                break;
        }
        return $this->setResponseError('Halaman tidak ditemukan', 500);
    }
}
