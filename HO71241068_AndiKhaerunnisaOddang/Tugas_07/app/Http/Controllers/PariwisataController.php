<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 

class PariwisataController extends Controller
{
    public function home()
    {
        $data = [
            'title' => 'Home',
            'kota' => 'Jakarta',
            'tagline' => 'Ibukota yang Tak Pernah Tidur'
        ];
        return view('home', $data);
    }

    public function destinasi()
    {
        $data = [
            'title' => 'Destinasi Wisata',
            'destinasi' => [
                [
                    'nama' => 'Monumen Nasional (Monas)',
                    'deskripsi' => 'Ikon Jakarta yang megah setinggi 132 meter, menjadi simbol perjuangan kemerdekaan Indonesia. Di puncaknya terdapat api kemerdekaan berlapis emas yang dapat dilihat dari berbagai sudut kota.',
                    'gambar' => 'images/monas.jpg'
                ],
                [
                    'nama' => 'Kota Tua Jakarta',
                    'deskripsi' => 'Kawasan bersejarah dengan arsitektur kolonial Belanda yang masih terjaga. Tempat ini menawarkan museum, kafe klasik, dan spot foto Instagramable yang penuh dengan nilai sejarah.',
                    'gambar' => 'images/kota-tua.jpg'
                ],
                [
                    'nama' => 'Taman Mini Indonesia Indah',
                    'deskripsi' => 'Miniatur keragaman budaya Indonesia dalam satu tempat. Terdapat 34 rumah adat dari seluruh provinsi, berbagai museum, dan wahana rekreasi yang edukatif untuk semua usia.',
                    'gambar' => 'images/tmii.jpg'
                ],
                [
                    'nama' => 'Ancol Dreamland',
                    'deskripsi' => 'Kawasan rekreasi terpadu terbesar di Jakarta dengan pantai, taman bermain, Sea World, dan Ocean Dream Samudra. Destinasi favorit keluarga untuk berlibur dan menikmati sunset di tepi pantai.',
                    'gambar' => 'images/ancol.jpg'
                ]
            ]
        ];
        return view('destinasi', $data);
    }

    public function kuliner()
    {
        $data = [
            'title' => 'Kuliner Khas',
            'kuliner' => [
                [
                    'nama' => 'Kerak Telor',
                    'deskripsi' => 'Makanan tradisional Betawi berbahan dasar beras ketan putih, telur ayam atau bebek, ebi kering, dan bawang goreng. Dimasak di atas wajan kecil dengan arang hingga kecokelatan dan renyah.',
                    'gambar' => 'images/kerak-telor.jpg'
                ],
                [
                    'nama' => 'Soto Betawi',
                    'deskripsi' => 'Soto khas Jakarta dengan kuah santan gurih yang kaya rempah. Berisi daging sapi, jeroan, dan tomat dengan tambahan emping dan sambal sebagai pelengkap yang menggugah selera.',
                    'gambar' => 'images/soto-betawi.jpg'
                ],
                [
                    'nama' => 'Nasi Uduk',
                    'deskripsi' => 'Nasi gurih yang dimasak dengan santan dan rempah khas Betawi. Biasanya disajikan dengan lauk seperti ayam goreng, telur balado, tempe orek, dan kerupuk sebagai menu sarapan favorit warga Jakarta.',
                    'gambar' => 'images/nasi-uduk.jpg'
                ],
                [
                    'nama' => 'Gado-gado',
                    'deskripsi' => 'Salad sayuran tradisional Indonesia dengan bumbu kacang yang gurih dan sedikit manis. Berisi campuran sayuran rebus seperti kangkung, tauge, kentang, dan dilengkapi dengan lontong atau ketupat.',
                    'gambar' => 'images/gado-gado.jpg'
                ]
            ]
        ];
        return view('kuliner', $data);
    }

    public function galeri()
    {
        $data = [
            'title' => 'Galeri',
            'galeri' => [
                ['gambar' => 'images/galeri1.jpg', 'caption' => 'Jakarta di Malam Hari'],
                ['gambar' => 'images/galeri2.jpg', 'caption' => 'Gedung Pencakar Langit'],
                ['gambar' => 'images/galeri3.jpg', 'caption' => 'Jembatan Semanggi'],
                ['gambar' => 'images/galeri4.jpg', 'caption' => 'Museum Nasional'],
                ['gambar' => 'images/galeri5.jpg', 'caption' => 'Masjid Istiqlal'],
                ['gambar' => 'images/galeri6.jpg', 'caption' => 'Bundaran HI'],
            ]
        ];
        return view('galeri', $data);
    }

    public function kontak()
    {
        $data = [
            'title' => 'Kontak Kami'
        ];
        return view('kontak', $data);
    }
}