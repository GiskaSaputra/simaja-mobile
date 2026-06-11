import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Wajib import ApiService
import 'detailmateri_screen.dart';
import 'quiz_screen.dart'; 
import 'pencarian_screen.dart';

class MateriScreen extends StatefulWidget {
  const MateriScreen({super.key});

  @override
  State<MateriScreen> createState() => _MateriScreenState();
}

class _MateriScreenState extends State<MateriScreen> {
  List<dynamic> _materiList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchMateri();
  }

  // Fungsi untuk menarik data dari API CI4
  Future<void> _fetchMateri() async {
    setState(() => _isLoading = true);
    var data = await ApiService.getMateri();
    setState(() {
      _materiList = data;
      _isLoading = false;
    });
  }

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
                      MaterialPageRoute(builder: (context) => const PencarianScreen()),
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
            child: _isLoading
              ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
              : _materiList.isEmpty
                  ? const Center(child: Text("Belum ada materi tersedia.", style: TextStyle(color: Colors.grey)))
                  : RefreshIndicator(
                      onRefresh: _fetchMateri,
                      child: ListView.builder(
                        padding: const EdgeInsets.all(24),
                        itemCount: _materiList.length,
                        itemBuilder: (context, index) {
                          final data = _materiList[index];
                          
                          // Kalkulasi progress bar
                          int selesai = data['completed_count'] ?? 0;
                          int total = data['total_pertemuan'] ?? 0;
                          double progresValue = total > 0 ? (selesai / total) : 0.0;

                          return Card(
                            elevation: 0, 
                            margin: const EdgeInsets.only(bottom: 20),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(16),
                              side: BorderSide(color: Colors.grey.shade400), 
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(20.0),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.center, 
                                children: [
                                  // Judul & Deskripsi
                                  Text(
                                    data['judul'] ?? 'Tanpa Judul',
                                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    data['deskripsi'] ?? '-',
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
                                  Container(
                                    transform: Matrix4.translationValues(0, -8, 0),
                                    height: 8,
                                    decoration: BoxDecoration(
                                      border: Border.all(color: Colors.grey.shade400),
                                      borderRadius: BorderRadius.circular(10),
                                    ),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    '$selesai/$total Selesai',
                                    style: const TextStyle(fontSize: 12, color: Colors.black54),
                                  ),
                                  const SizedBox(height: 16),

                                  // Tombol Lihat Materi 
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
                                          MaterialPageRoute(
                                            builder: (context) => DetailMateriScreen(
                                              materiId: data['id'].toString(), // Kirim ID Materi
                                              materiTitle: data['judul'] ?? 'Tanpa Judul',
                                            ),
                                          ),
                                        ).then((_) => _fetchMateri()); // Refresh list kalau balik
                                      },
                                      child: const Text('Lihat Materi'),
                                    ),
                                  ),
                                  const SizedBox(height: 12),

                                  // Tombol Quiz
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
                                        Navigator.push(
                                          context,
                                          MaterialPageRoute(
                                            builder: (context) => QuizScreen(
                                              materiId: data['id'].toString(), // Mengirimkan ID materi
                                              materiTitle: data['judul'] ?? 'Tanpa Judul', // Mengirimkan judul materi
                                            ),
                                          ),
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
          ),
        ],
      ),
    );
  }
}