import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'detailmateri_screen.dart';
import 'quiz_screen.dart'; // Pastikan import ini ada
import 'pencarian_screen.dart';

class MateriScreen extends StatefulWidget {
  const MateriScreen({super.key});

  @override
  State<MateriScreen> createState() => _MateriScreenState();
}

class _MateriScreenState extends State<MateriScreen> {
  // Dummy Data sesuai Figma
  final List<Map<String, dynamic>> _materiList = [
    {
      "judul": "UI/UX Design",
      "deskripsi": "Belajar desain antarmuka",
      "selesai": 2,
      "total": 6,
    },
    {
      "judul": "WEB Basic",
      "deskripsi": "Belajar desain antarmuka",
      "selesai": 2,
      "total": 6,
    },
    {
      "judul": "WEB Advance",
      "deskripsi": "Belajar desain antarmuka",
      "selesai": 2,
      "total": 6,
    },
  ];

  @override
  Widget build(BuildContext context) {
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
                const Text(
                  'Materi Study Jam',
                  style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 20),
                // Search Bar
                TextField(
                  readOnly: true,
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const PencarianScreen(),
                      ),
                    );
                  },
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

          // --- LIST KARTU MATERI ---
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(24),
              itemCount: _materiList.length,
              itemBuilder: (context, index) {
                final data = _materiList[index];
                final double progresValue = data['selesai'] / data['total'];

                return Card(
                  elevation: 0, // Dibuat flat sesuai figma
                  margin: const EdgeInsets.only(bottom: 20),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                    side: BorderSide(color: Colors.grey.shade400), // Border tipis abu-abu
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center, // Semua isi di-center
                      children: [
                        // Judul & Deskripsi
                        Text(
                          data['judul'],
                          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          data['deskripsi'],
                          style: const TextStyle(fontSize: 12, color: Colors.grey),
                        ),
                        const SizedBox(height: 16),

                        // Progress Bar & Teks Selesai
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10),
                          child: LinearProgressIndicator(
                            value: progresValue,
                            backgroundColor: Colors.white,
                            valueColor: const AlwaysStoppedAnimation<Color>(AppTheme.primaryGreen),
                            minHeight: 8,
                          ),
                        ),
                        // Border manual untuk menutupi background putih linear progress (Trik UI Figma)
                        Container(
                          transform: Matrix4.translationValues(0, -8, 0), // Menimpa progress bar
                          height: 8,
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey.shade400),
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          '${data['selesai']}/${data['total']} Selesai',
                          style: const TextStyle(fontSize: 12, color: Colors.black54),
                        ),
                        const SizedBox(height: 16),

                        // Tombol Lihat Materi (Putih / Outlined)
                        SizedBox(
                          width: double.infinity,
                          height: 40,
                          child: OutlinedButton(
                            style: OutlinedButton.styleFrom(
                              foregroundColor: Colors.black87,
                              side: BorderSide(color: Colors.grey.shade400),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                            ),
                            onPressed: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(builder: (context) => DetailMateriScreen(materiTitle: data['judul'])),
                              );
                            },
                            child: const Text('Lihat Materi'),
                          ),
                        ),
                        const SizedBox(height: 12),

                        // Tombol Quiz (Hijau / Filled) - BAGIAN YANG DI-UPDATE!
                        SizedBox(
                          width: double.infinity,
                          height: 40,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.primaryGreen,
                              foregroundColor: Colors.white,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                            ),
                            onPressed: () {
                              // Navigasi ke halaman QuizScreen
                              Navigator.push(
                                context,
                                MaterialPageRoute(builder: (context) => const QuizScreen()),
                              );
                            },
                            child: const Text('Quiz'),
                          ),
                        ),
                      ],
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