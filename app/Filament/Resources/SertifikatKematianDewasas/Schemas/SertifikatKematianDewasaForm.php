<?php

namespace App\Filament\Resources\SertifikatKematianDewasas\Schemas;

use Filament\Schemas\Schema;

class SertifikatKematianDewasaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- SECTION 1: IDENTITAS JENAZAH ---
                \Filament\Schemas\Components\Section::make('Identitas Jenazah')
                    ->description('Data diri jenazah sesuai KTP/KK')
                    ->components([
                        \Filament\Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->numeric()
                            ->maxLength(16)
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->maxLength(255),
                    ])->columns(3),

                // --- SECTION 2: WAKTU KEMATIAN ---
                \Filament\Schemas\Components\Section::make('Waktu Kematian')
                    ->components([
                        \Filament\Forms\Components\DateTimePicker::make('waktu_meninggal')
                            ->label('Tanggal & Jam Meninggal')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('usia')
                            ->label('Usia Saat Meninggal')
                            ->numeric()
                            ->suffix('Tahun')
                            ->required(),
                    ])->columns(2),

                // --- SECTION 3: PENYEBAB KEMATIAN (WHO) ---
                \Filament\Schemas\Components\Section::make('Penyebab Kematian (Format WHO)')
                    ->description('Berdasarkan urutan terjadinya kematian')
                    ->components([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->components([
                                \Filament\Forms\Components\TextInput::make('penyebab_langsung')
                                    ->label('Penyebab Langsung (a)')
                                    ->placeholder('Misal: Gagal Jantung'),
                                \Filament\Forms\Components\TextInput::make('interval_langsung')
                                    ->label('Interval (a) - Waktu mulai sakit s/d meninggal')
                                    ->placeholder('Misal: 2 Jam'),
                                    
                                \Filament\Forms\Components\TextInput::make('penyebab_antara')
                                    ->label('Penyebab Antara (b) - Akibat dari (c)')
                                    ->placeholder('Misal: Pendarahan Otak'),
                                \Filament\Forms\Components\TextInput::make('interval_antara')
                                    ->label('Interval (b)'),
                                    
                                \Filament\Forms\Components\TextInput::make('penyebab_dasar')
                                    ->label('Penyebab Dasar (c)')
                                    ->placeholder('Misal: Hipertensi Kronis'),
                                \Filament\Forms\Components\TextInput::make('interval_dasar')
                                    ->label('Interval (c)'),
                                    
                                \Filament\Forms\Components\TextInput::make('penyebab_utama')
                                    ->label('Kondisi Lain / Penyebab Utama (d)')
                                    ->placeholder('Kondisi lain yang berkontribusi'),
                                \Filament\Forms\Components\TextInput::make('interval_utama')
                                    ->label('Interval (d)'),
                            ]),
                    ]),

                // --- SECTION 4: PENGESAHAN ---
                \Filament\Schemas\Components\Section::make('Pengesahan')
                    ->components([
                        \Filament\Forms\Components\Select::make('nama_dokter')
                            ->label('Nama Dokter')
                            ->options([
                                'Dr. A' => 'Dr. A',
                                'Dr. B' => 'Dr. B',
                            ])
                            ->searchable()
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('nomor_sip')
                            ->label('Nomor SIP')
                            ->required()
                            ->maxLength(255),
                            
                        \Filament\Forms\Components\FileUpload::make('tanda_tangan')
                            ->label('Tanda Tangan Dokter (Upload)')
                            ->image()
                            ->directory('tanda_tangan_dokter')
                            ->required(),
                    ])->columns(3),
            ]);
    }
}
