import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'detail_quiz_screen.dart';
import 'pencarian_screen.dart';

class QuizScreen extends StatefulWidget {
  final String materiId;
  final String materiTitle;

  const QuizScreen({
    super.key, 
    required this.materiId, 
    required this.materiTitle
  });

  @override
  State<QuizScreen> createState() => _QuizScreenState();
}

class _QuizScreenState extends State<QuizScreen> {
  List<dynamic> _quizList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchQuizList();
  }

  Future<void> _fetchQuizList() async {
    setState(() => _isLoading = true);
    var data = await ApiService.getQuizList(widget.materiId);
    setState(() {
      _quizList = data;
      _isLoading = false;
    });
  }

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
                    Expanded(
                      child: Center(
                        child: Text(
                          'Quiz ${widget.materiTitle}',
                          style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ),
                    const SizedBox(width: 20), 
                  ],
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

          // --- LIST KUIS ---
          Expanded(
            child: _isLoading 
              ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
              : _quizList.isEmpty
                  ? const Center(child: Text("Belum ada quiz tersedia.", style: TextStyle(color: Colors.grey)))
                  : RefreshIndicator(
                      onRefresh: _fetchQuizList,
                      child: ListView.builder(
                        padding: const EdgeInsets.all(24),
                        itemCount: _quizList.length,
                        itemBuilder: (context, index) {
                          final data = _quizList[index];
                          
                          // Cek apakah user sudah mengerjakan quiz ini (skor_user tidak null)
                          bool isDone = data['skor_user'] != null;
                          String nilai = isDone ? data['skor_user'].toString() : '0';
                          String judul = data['judul_pertemuan'] ?? 'Pertemuan ${index + 1}';

                          return GestureDetector(
                            onTap: () {
                              if (!isDone) {
                                // Hanya bisa diklik jika belum dikerjakan
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => DetailQuizScreen(
                                      pertemuanId: data['id'].toString(),
                                      quizTitle: judul,
                                    ),
                                  ),
                                ).then((_) => _fetchQuizList()); // Refresh setelah mengerjakan
                              } else {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  const SnackBar(content: Text('Anda sudah mengerjakan Quiz ini!'))
                                );
                              }
                            },
                            child: Container(
                              margin: const EdgeInsets.only(bottom: 16),
                              padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 20),
                              decoration: BoxDecoration(
                                color: isDone ? Colors.grey.shade50 : Colors.white,
                                borderRadius: BorderRadius.circular(16),
                                border: Border.all(
                                  color: isDone ? Colors.grey.shade400 : Colors.black87, 
                                  width: 1.2
                                ),
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
                                          judul,
                                          style: TextStyle(
                                            fontSize: 16, 
                                            fontWeight: FontWeight.bold, 
                                            color: isDone ? Colors.grey.shade700 : Colors.black
                                          ),
                                        ),
                                        const SizedBox(height: 6),
                                        if (isDone)
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
                                  if (isDone)
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 6),
                                      decoration: BoxDecoration(
                                        color: AppTheme.primaryGreen,
                                        borderRadius: BorderRadius.circular(20),
                                      ),
                                      child: Text(
                                        'Nilai : $nilai',
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
          ),
        ],
      ),
    );
  }
}