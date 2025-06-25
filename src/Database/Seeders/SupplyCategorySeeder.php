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
                ['name' => 'Alat Tulis Kantor', 'label' => 'Alat Tulis Kantor'],
                ['name' => 'Elektronik', 'label' => 'Elektronik'],
                ['name' => 'Komputer & Aksesoris', 'label' => 'Komputer & Aksesoris'],
                ['name' => 'Furniture', 'label' => 'Furniture'],
                ['name' => 'ATK Cetak', 'label' => 'ATK Cetak'],
                ['name' => 'Perlengkapan Kebersihan', 'label' => 'Perlengkapan Kebersihan'],
                ['name' => 'Konsumsi / Pantry', 'label' => 'Konsumsi / Pantry'],
                ['name' => 'Linen / Tekstil', 'label' => 'Linen / Tekstil'],
                ['name' => 'Suku Cadang Umum', 'label' => 'Suku Cadang Umum'],
                ['name' => 'Alat Keselamatan Kerja', 'label' => 'Alat Keselamatan Kerja'],
                ['name' => 'Alat Pengukur & Kalibrasi', 'label' => 'Alat Pengukur & Kalibrasi'],
            ],
        ],
        [
            'name' => 'Bidang Kesehatan',
            'label' => 'Bidang Kesehatan',
            'childs' => [
                ['name' => 'Alat Kesehatan', 'label' => 'Alat Kesehatan'],
                ['name' => 'Peralatan Laboratorium', 'label' => 'Peralatan Laboratorium'],
                ['name' => 'Peralatan Radiologi', 'label' => 'Peralatan Radiologi'],
                ['name' => 'Bahan Kimia', 'label' => 'Bahan Kimia'],
                ['name' => 'Bahan Baku Obat', 'label' => 'Bahan Baku Obat'],
                ['name' => 'Bahan Pembersih', 'label' => 'Bahan Pembersih'],
                ['name' => 'Alat Pelindung Diri', 'label' => 'Alat Pelindung Diri'],
                ['name' => 'Reagen', 'label' => 'Reagen'],
                ['name' => 'Consumable Medis', 'label' => 'Consumable Medis'],
                ['name' => 'Perlengkapan Bedah', 'label' => 'Perlengkapan Bedah'],
                ['name' => 'Peralatan Sterilisasi', 'label' => 'Peralatan Sterilisasi'],
                ['name' => 'Peralatan Gawat Darurat', 'label' => 'Peralatan Gawat Darurat'],
            ],
        ],
        [
            'name' => 'Bidang Otomotif',
            'label' => 'Bidang Otomotif',
            'childs' => [
                ['name' => 'Suku Cadang Kendaraan', 'label' => 'Suku Cadang Kendaraan'],
                ['name' => 'Pelumas & Oli', 'label' => 'Pelumas & Oli'],
                ['name' => 'Bahan Bakar', 'label' => 'Bahan Bakar'],
                ['name' => 'Alat Bengkel', 'label' => 'Alat Bengkel'],
                ['name' => 'Peralatan Keselamatan', 'label' => 'Peralatan Keselamatan'],
                ['name' => 'Ban & Aksesoris', 'label' => 'Ban & Aksesoris'],
                ['name' => 'Accu & Kelistrikan', 'label' => 'Accu & Kelistrikan'],
            ],
        ],
        [
            'name' => 'Bidang Teknik & Konstruksi',
            'label' => 'Bidang Teknik & Konstruksi',
            'childs' => [
                ['name' => 'Material Bangunan', 'label' => 'Material Bangunan'],
                ['name' => 'Alat Ukur', 'label' => 'Alat Ukur'],
                ['name' => 'Alat Tukang', 'label' => 'Alat Tukang'],
                ['name' => 'Perlengkapan Listrik', 'label' => 'Perlengkapan Listrik'],
                ['name' => 'Pipa & Aksesoris', 'label' => 'Pipa & Aksesoris'],
                ['name' => 'Alat Berat', 'label' => 'Alat Berat'],
                ['name' => 'Bahan Perekat & Sealant', 'label' => 'Bahan Perekat & Sealant'],
            ],
        ],
        [
            'name' => 'Bidang Pertanian & Peternakan',
            'label' => 'Bidang Pertanian & Peternakan',
            'childs' => [
                ['name' => 'Pupuk', 'label' => 'Pupuk'],
                ['name' => 'Benih', 'label' => 'Benih'],
                ['name' => 'Obat Hama', 'label' => 'Obat Hama'],
                ['name' => 'Alat Pertanian', 'label' => 'Alat Pertanian'],
                ['name' => 'Pakan Ternak', 'label' => 'Pakan Ternak'],
                ['name' => 'Peralatan Peternakan', 'label' => 'Peralatan Peternakan'],
            ],
        ],
        [
            'name' => 'Bidang IT & Telekomunikasi',
            'label' => 'Bidang IT & Telekomunikasi',
            'childs' => [
                ['name' => 'Server & Networking', 'label' => 'Server & Networking'],
                ['name' => 'Perangkat Jaringan', 'label' => 'Perangkat Jaringan'],
                ['name' => 'Perangkat Lunak', 'label' => 'Perangkat Lunak'],
                ['name' => 'Perangkat Komputer', 'label' => 'Perangkat Komputer'],
                ['name' => 'Aksesoris Komputer', 'label' => 'Aksesoris Komputer'],
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
