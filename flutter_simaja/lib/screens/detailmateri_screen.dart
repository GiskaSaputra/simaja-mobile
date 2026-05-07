import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'show_belajar_screen.dart'; // 1. Tambahkan import ini

class DetailMateriScreen extends StatelessWidget {
  final String materiTitle;

  const DetailMateriScreen({super.key, required this.materiTitle});

  @override
  Widget build(BuildContext context) {
    // Membuat list dummy Pertemuan 1 - 5
    final List<String> pertemuanList = [
      "Pertemuan 1",
      "Pertemuan 2",
      "Pertemuan 3",
      "Pertemuan 4",
      "Pertemuan 5",
    ];

    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        children: [
          // --- HEADER HIJAU (Persis Figma) ---
          Container(
            padding: const EdgeInsets.fromLTRB(24, 60, 24, 30),
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(30)),
            ),
            child: Column(
              children: [
                // Ada tombol back di kiri atas khusus untuk detail materi agar bisa kembali
                Row(
                  children: [
                    GestureDetector(
                      onTap: () => Navigator.pop(context),
                      child: const Icon(Icons.arrow_back_ios, color: Colors.white, size: 20),
                    ),
                    const Expanded(
                      child: Center(
                        child: Text(
                          'Materi Study Jam',
                          style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                        ),
                      ),
                    ),
                    const SizedBox(width: 20), // Penyeimbang agar judul tetap di tengah
                  ],
                ),
                const SizedBox(height: 20),
                // Search Bar
                TextField(
                  decoration: InputDecoration(
                    hintText: 'Search',
                    prefixIcon: const Icon(Icons.search, color: Colors.black54),
                    filled: true,
                    fillColor: Colors.white,
                    contentPadding: const EdgeInsets.symmetric(vertical: 0),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
              ],
            ),
          ),

          // --- LIST KARTU PERTEMUAN ---
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(24),
              itemCount: pertemuanList.length,
              itemBuilder: (context, index) {
                // 2. Bungkus Container dengan GestureDetector disini
                return GestureDetector(
                  onTap: () {
                    // Logika pindah halaman ke ShowBelajarScreen
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => ShowBelajarScreen(
                          judulPertemuan: pertemuanList[index], 
                          materiTitle: materiTitle,
                        ),
                      ),
                    );
                  },
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 16),
                    width: double.infinity,
                    padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.grey.shade400),
                    ),
                    child: Text(
                      pertemuanList[index],
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: Colors.black,
                      ),
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}