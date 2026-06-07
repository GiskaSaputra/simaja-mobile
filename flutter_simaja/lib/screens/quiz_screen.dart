import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'detail_quiz_screen.dart';
import 'pencarian_screen.dart';

class QuizScreen extends StatelessWidget {
  const QuizScreen({super.key});

  @override
  Widget build(BuildContext context) {
    // Dummy Data Kuis sesuai Figma
    final List<Map<String, dynamic>> quizList = [
      {
        "judul": "Pertemuan 1 UI/UX Design",
        "isDone": true,
        "nilai": 93,
      },
      {
        "judul": "Pertemuan 1 : WEB Basic",
        "isDone": false,
      },
      {
        "judul": "Pertemuan 1 : WEB Advance",
        "isDone": false,
      },
      {
        "judul": "Pertemuan 1 : Mobile Dev",
        "isDone": false,
      },
    ];

    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        children: [
          // --- HEADER HIJAU ---
          Container(
            padding: const EdgeInsets.fromLTRB(24, 60, 24, 30),
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(30)),
            ),
            child: Column(
              children: [
                Row(
                  children: [
                    GestureDetector(
                      onTap: () => Navigator.pop(context),
                      child: const Icon(Icons.arrow_back_ios, color: Colors.white, size: 20),
                    ),
                    const Expanded(
                      child: Center(
                        child: Text(
                          'Quiz Study Jam',
                          style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                        ),
                      ),
                    ),
                    const SizedBox(width: 20), // Penyeimbang
                  ],
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

          // --- LIST KUIS ---
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(24),
              itemCount: quizList.length,
              itemBuilder: (context, index) {
                final data = quizList[index];
                
                return GestureDetector(
                  onTap: () {
                    // Jika diklik, masuk ke halaman pengerjaan kuis
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => DetailQuizScreen(quizTitle: data['judul']),
                      ),
                    );
                  },
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 16),
                    padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.black87, width: 1.2), // Border outline
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        // Bagian Kiri (Teks Judul & Status)
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                data['judul'],
                                style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black),
                              ),
                              const SizedBox(height: 6),
                              // Kondisi Status (Sudah Selesai vs Belum)
                              if (data['isDone'])
                                Row(
                                  children: const [
                                    Icon(Icons.check_circle, color: AppTheme.primaryGreen, size: 16),
                                    SizedBox(width: 6),
                                    Text('Selesai dikerjakan', style: TextStyle(fontSize: 12, color: AppTheme.primaryGreen, fontWeight: FontWeight.w600)),
                                  ],
                                )
                              else
                                const Text('Klik untuk mulai', style: TextStyle(fontSize: 12, color: Colors.black54)),
                            ],
                          ),
                        ),
                        const SizedBox(width: 12),
                        
                        // Bagian Kanan (Pill Nilai atau Tombol Play)
                        if (data['isDone'])
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                            decoration: BoxDecoration(
                              color: AppTheme.primaryGreen,
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              'Nilai : ${data['nilai']}',
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12),
                            ),
                          )
                        else
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: Colors.grey.shade100,
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(Icons.play_arrow, color: Colors.black87),
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