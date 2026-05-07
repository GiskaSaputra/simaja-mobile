import 'package:flutter/material.dart';
import '../utils/theme.dart';

class AbsensiScreen extends StatefulWidget {
  const AbsensiScreen({super.key});

  @override
  State<AbsensiScreen> createState() => _AbsensiScreenState();
}

class _AbsensiScreenState extends State<AbsensiScreen> {
  // Variabel untuk menyimpan status pilihan absensi
  String _attendanceStatus = 'Hadir'; // Default pilihan

  @override
  Widget build(BuildContext context) {
    // Mengambil ukuran tinggi layar untuk membagi background
    final height = MediaQuery.of(context).size.height;

    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          // 1. BACKGROUND HIJAU ATAS
          Container(
            height: height * 0.45,
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(40)),
            ),
          ),

          // 2. SEGITIGA BAWAH (Mentok bawah)
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: SizedBox(
              height: height * 0.18,
              child: Image.asset(
                'lib/assets/segitiga.png',
                fit: BoxFit.cover,
                alignment: Alignment.topCenter,
              ),
            ),
          ),

          // 3. KONTEN UTAMA
          SafeArea(
            child: Column(
              children: [
                // Header dengan tombol Back (Kiri Atas)
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Row(
                    children: [
                      IconButton(
                        icon: const Icon(Icons.chevron_left, color: Colors.white, size: 30),
                        onPressed: () => Navigator.pop(context),
                      ),
                      const Text(
                        'Absensi Pertemuan',
                        style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                ),

                // Area Scrollable untuk Form
                Expanded(
                  child: SingleChildScrollView(
                    padding: const EdgeInsets.fromLTRB(24, 0, 24, 100), // Padding bawah agar tidak menabrak segitiga
                    child: Column(
                      children: [
                        // Card Info Pertemuan & Form (TANPA LOGO BESAR)
                        Card(
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          elevation: 6,
                          color: Colors.white,
                          child: Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 20.0, vertical: 30.0),
                            child: Column(
                              // crossAxisAlignment: CrossAxisAlignment.start, // Rata kiri untuk teks
                              children: [
                                // Judul & Deskripsi Pertemuan (Dummy Data)
                                const Text(
                                  'UI/UX Design',
                                  style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
                                  textAlign: TextAlign.center,
                                ),
                                const SizedBox(height: 8),
                                const Text(
                                  'Introduction UI/UX with Figma',
                                  style: TextStyle(fontSize: 14, color: Colors.black87),
                                  textAlign: TextAlign.center,
                                ),
                                const SizedBox(height: 12),
                                const Text(
                                  'Sistem Manajemen Study Jam by PROTIC',
                                  style: TextStyle(fontSize: 12, color: Colors.grey),
                                  textAlign: TextAlign.center,
                                ),
                                const Divider(height: 40, thickness: 1),

                                // Pilihan Absensi (Radio Buttons)
                                Column(
                                  children: [
                                    _buildRadioOption('Hadir'),
                                    const SizedBox(height: 12),
                                    _buildRadioOption('Izin'),
                                    const SizedBox(height: 12),
                                    _buildRadioOption('Sakit'),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                        
                        const SizedBox(height: 30),

                        // Tombol Submit (Di Luar Card)
                        SizedBox(
                          width: double.infinity,
                          height: 50,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.primaryGreen,
                              foregroundColor: Colors.white,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                              elevation: 4,
                            ),
                            onPressed: () {
                              // Panggil fungsi untuk menampilkan Pop-up Sukses
                              _showSuccessDialog(context);
                            },
                            child: const Text('Kirim', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // Widget Bantuan untuk membuat pilihan Radio Button yang rapi
  Widget _buildRadioOption(String title) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _attendanceStatus = title;
        });
      },
      child: Container(
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey.shade300),
          borderRadius: BorderRadius.circular(8),
          color: _attendanceStatus == title ? Colors.blue.withOpacity(0.05) : Colors.white,
        ),
        child: Row(
          children: [
            Radio<String>(
              value: title,
              groupValue: _attendanceStatus,
              activeColor: AppTheme.primaryGreen, // Warna hijau saat dipilih
              onChanged: (String? value) {
                setState(() {
                  _attendanceStatus = value!;
                });
              },
            ),
            Text(
              title, 
              style: TextStyle(
                fontWeight: _attendanceStatus == title ? FontWeight.bold : FontWeight.w500,
                color: _attendanceStatus == title ? AppTheme.primaryGreen : Colors.black87,
              )
            ),
          ],
        ),
      ),
    );
  }

  // FUNGSI UNTUK MENAMPILKAN POP-UP NOTIFIKASI SUKSES (Sesuai Figma)
  void _showSuccessDialog(BuildContext context) {
    showDialog(
      context: context,
      barrierDismissible: false, // User tidak bisa menutup dengan klik di luar
      builder: (BuildContext dialogContext) {
        return Dialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          child: Padding(
            padding: const EdgeInsets.all(24.0),
            child: Column(
              mainAxisSize: MainAxisSize.min, // Sesuai isinya
              children: [
                // Icon Centang Hijau Besar
                const Icon(
                  Icons.check_circle_outline,
                  color: Colors.green,
                  size: 100,
                ),
                const SizedBox(height: 20),
                
                // Teks Judul
                const Text(
                  'Absensi Berhasil!',
                  style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.green),
                ),
                const SizedBox(height: 10),
                
                // Teks Detail (Dummy Data sesuai Figma)
                const Text(
                  'Detail Absensi :',
                  style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black87),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Minggu, 01 May 2026',
                  style: TextStyle(fontSize: 14, color: Colors.black87),
                ),
                const Text(
                  'Pukul 16.00 WIB',
                  style: TextStyle(fontSize: 14, color: Colors.black87),
                ),
                const SizedBox(height: 12),
                
                // Status Absensi yang dipilih
                Text(
                  'Status Absensi : $_attendanceStatus',
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black),
                ),
                const SizedBox(height: 30),
                
                // Tombol Oke
                SizedBox(
                  width: double.infinity,
                  height: 45,
                  child: ElevatedButton(
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryGreen,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    onPressed: () {
                      Navigator.pop(dialogContext); // Tutup Pop-up
                      Navigator.pop(context); // Kembali ke halaman Jadwal
                    },
                    child: const Text('Oke', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}