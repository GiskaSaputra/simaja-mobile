import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'pencarian_screen.dart';

class DetailQuizScreen extends StatefulWidget {
  final String quizTitle;

  const DetailQuizScreen({super.key, required this.quizTitle});

  @override
  State<DetailQuizScreen> createState() => _DetailQuizScreenState();
}

class _DetailQuizScreenState extends State<DetailQuizScreen> {
  // Menyimpan jawaban yang dipilih user (Format: Kunci ID Soal -> Jawaban)
  Map<int, String> answers = {};

  // Dummy Soal
  final List<Map<String, dynamic>> questions = [
    {
      "id": 1,
      "soal": "Properti CSS untuk mengubah warna teks adalah?",
      "opsi": ["background-color", "text-color", "color", "font-color"]
    },
    {
      "id": 2,
      "soal": "Apa kepanjangan dari HTML?",
      "opsi": ["Hyper Text Markup Language", "Hyperlinks and Text Markup Language", "Home Tool Markup Language", "Hyper Text Multi Language"]
    }
  ];

  @override
  Widget build(BuildContext context) {
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
                          'Quiz',
                          style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                        ),
                      ),
                    ),
                    const SizedBox(width: 20),
                  ],
                ),
                const SizedBox(height: 20),
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

          // --- KONTEN SOAL ---
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Judul & Sub-judul
                  Text(
                    widget.quizTitle,
                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.primaryGreen),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    'Pastikan semua soal terjawab sebelum mengirim.',
                    style: TextStyle(fontSize: 12, color: Colors.black54),
                  ),
                  const SizedBox(height: 24),

                  // Mapping List Soal
                  ...questions.map((q) => _buildQuestion(q)).toList(),
                  
                  const SizedBox(height: 20),
                  
                  // Tombol Kirim Quiz
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryGreen,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      ),
                      onPressed: () {
                        // TODO: Logika submit jawaban
                        Navigator.pop(context);
                      },
                      child: const Text('Kirim Jawaban', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    ),
                  ),
                  const SizedBox(height: 40),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  // WIDGET KHUSUS UNTUK SOAL (Sesuai Figma)
  Widget _buildQuestion(Map<String, dynamic> q) {
    int qId = q['id'];
    List<String> options = q['opsi'];

    return Padding(
      padding: const EdgeInsets.only(bottom: 30),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Teks Soal dengan Badge "No. X"
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                margin: const EdgeInsets.only(top: 2), // Penyelarasan agar rata dengan teks
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.primaryGreen,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Text(
                  'No. $qId',
                  style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(width: 8),
              Expanded(
                child: Text(
                  q['soal'],
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black87),
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),

          // Pilihan Ganda (Berbentuk Card Outline)
          ...options.map((option) {
            bool isSelected = answers[qId] == option;
            
            return GestureDetector(
              onTap: () {
                setState(() {
                  answers[qId] = option; // Simpan jawaban
                });
              },
              child: Container(
                margin: const EdgeInsets.only(bottom: 12),
                padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(
                    color: AppTheme.primaryGreen, // Border hijau seperti Figma
                    width: 1.5,
                  ),
                ),
                child: Row(
                  children: [
                    // Icon Radio Kustom
                    Icon(
                      isSelected ? Icons.radio_button_checked : Icons.radio_button_unchecked,
                      color: AppTheme.primaryGreen,
                    ),
                    const SizedBox(width: 12),
                    // Teks Opsi
                    Expanded(
                      child: Text(
                        option,
                        style: const TextStyle(
                          color: AppTheme.primaryGreen,
                          fontWeight: FontWeight.bold,
                          fontSize: 15,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            );
          }).toList(),
        ],
      ),
    );
  }
}