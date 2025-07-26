<?php

namespace Hanafalah\ModuleItem\Database\Seeders;

use Hanafalah\LaravelSupport\Concerns\Support\HasRequestData;
use Illuminate\Database\Seeder;

class SupplyCategorySeeder extends Seeder{
    use HasRequestData;

    protected $datas = [
        [
            'name' => 'Umum',
            'label' => 'Umum',
            'childs' => [
                ['name' => 'Alat Tulis Kantor', 'label' => 'Umum'],
                ['name' => 'Elektronik', 'label' => 'Umum'],
                ['name' => 'Komputer & Aksesoris', 'label' => 'Umum'],
                ['name' => 'Furniture', 'label' => 'Umum'],
                ['name' => 'ATK Cetak', 'label' => 'Umum'],
                ['name' => 'Perlengkapan Kebersihan', 'label' => 'Umum'],
                ['name' => 'Konsumsi / Pantry', 'label' => 'Umum'],
                ['name' => 'Linen / Tekstil', 'label' => 'Umum'],
                ['name' => 'Suku Cadang Umum', 'label' => 'Umum'],
                ['name' => 'Alat Keselamatan Kerja', 'label' => 'Umum'],
                ['name' => 'Alat Pengukur & Kalibrasi', 'label' => 'Umum'],
            ],
        ],
        [
            'name' => 'Bidang Kesehatan',
            'label' => 'Bidang Kesehatan',
            'childs' => [
                ['name' => 'Alat Kesehatan', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Peralatan Laboratorium', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Peralatan Radiologi', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Bahan Kimia', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Bahan Baku Obat', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Bahan Pembersih', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Alat Pelindung Diri', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Reagen', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Consumable Medis', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Perlengkapan Bedah', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Peralatan Sterilisasi', 'label' => 'Bidang Kesehatan'],
                ['name' => 'Peralatan Gawat Darurat', 'label' => 'Bidang Kesehatan'],
            ],
        ],
        [
            'name' => 'Bidang Otomotif',
            'label' => 'Bidang Otomotif',
            'childs' => [
                ['name' => 'Suku Cadang Kendaraan', 'label' => 'Bidang Otomotif'],
                ['name' => 'Pelumas & Oli', 'label' => 'Bidang Otomotif'],
                ['name' => 'Bahan Bakar', 'label' => 'Bidang Otomotif'],
                ['name' => 'Alat Bengkel', 'label' => 'Bidang Otomotif'],
                ['name' => 'Peralatan Keselamatan', 'label' => 'Bidang Otomotif'],
                ['name' => 'Ban & Aksesoris', 'label' => 'Bidang Otomotif'],
                ['name' => 'Accu & Kelistrikan', 'label' => 'Bidang Otomotif'],
            ],
        ],
        [
            'name' => 'Bidang Teknik & Konstruksi',
            'label' => 'Bidang Teknik & Konstruksi',
            'childs' => [
                ['name' => 'Material Bangunan', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Alat Ukur', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Alat Tukang', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Perlengkapan Listrik', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Pipa & Aksesoris', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Alat Berat', 'label' => 'Bidang Teknik & Kontruksi'],
                ['name' => 'Bahan Perekat & Sealant', 'label' => 'Bidang Teknik & Kontruksi'],
            ],
        ],
        [
            'name' => 'Bidang Pertanian & Peternakan',
            'label' => 'Bidang Pertanian & Peternakan',
            'childs' => [
                ['name' => 'Pupuk', 'label' => 'Bidang Pertanian & Peternakan'],
                ['name' => 'Benih', 'label' => 'Bidang Pertanian & Peternakan'],
                ['name' => 'Obat Hama', 'label' => 'Bidang Pertanian & Peternakan'],
                ['name' => 'Alat Pertanian', 'label' => 'Bidang Pertanian & Peternakan'],
                ['name' => 'Pakan Ternak', 'label' => 'Bidang Pertanian & Peternakan'],
                ['name' => 'Peralatan Peternakan', 'label' => 'Bidang Pertanian & Peternakan'],
            ],
        ],
        [
            'name' => 'Bidang IT & Telekomunikasi',
            'label' => 'Bidang IT & Telekomunikasi',
            'childs' => [
                ['name' => 'Server & Networking', 'label' => 'Bidang IT & Telekomunikasi'],
                ['name' => 'Perangkat Jaringan', 'label' => 'Bidang IT & Telekomunikasi'],
                ['name' => 'Perangkat Lunak', 'label' => 'Bidang IT & Telekomunikasi'],
                ['name' => 'Perangkat Komputer', 'label' => 'Bidang IT & Telekomunikasi'],
                ['name' => 'Aksesoris Komputer', 'label' => 'Bidang IT & Telekomunikasi'],
            ],
        ],
    ];

    public function run()
    {
        foreach ($this->datas as $data) {
            app(config('app.contracts.SupplyCategory'))->prepareStoreSupplyCategory(
                $this->requestDTO(config('app.contracts.SupplyCategoryData'), $data)
            );
        }

    }
}
