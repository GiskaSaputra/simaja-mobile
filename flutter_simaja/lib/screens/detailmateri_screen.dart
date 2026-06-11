import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Wajib import ApiService
import 'show_belajar_screen.dart'; 
import 'pencarian_screen.dart';

class DetailMateriScreen extends StatefulWidget {
  final String materiId;
  final String materiTitle;

  const DetailMateriScreen({
    super.key, 
    required this.materiId, 
    required this.materiTitle
  });

  @override
  State<DetailMateriScreen> createState() => _DetailMateriScreenState();
}

class _DetailMateriScreenState extends State<DetailMateriScreen> {
  List<dynamic> _pertemuanList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchPertemuan();
  }

  Future<void> _fetchPertemuan() async {
    setState(() => _isLoading = true);
    // Kita manfaatkan getQuizList karena endpoint CI4-nya mengembalikan tabel pertemuan
    var data = await ApiService.getQuizList(widget.materiId);
    setState(() {
      _pertemuanList = data;
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
                    const Expanded(
                      child: Center(
                        child: Text(
                          'Materi Study Jam',
                          style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
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

          // --- LIST KARTU PERTEMUAN ---
          Expanded(
            child: _isLoading 
              ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
              : _pertemuanList.isEmpty
                  ? const Center(child: Text("Belum ada pertemuan untuk materi ini.", style: TextStyle(color: Colors.grey)))
                  : ListView.builder(
                      padding: const EdgeInsets.all(24),
                      itemCount: _pertemuanList.length,
                      itemBuilder: (context, index) {
                        final pertemuan = _pertemuanList[index];

                        return GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => ShowBelajarScreen(
                                  pertemuanId: pertemuan['id'].toString(), // Kirim ID
                                  judulPertemuan: pertemuan['judul_pertemuan'] ?? 'Pertemuan', 
                                  materiTitle: widget.materiTitle,
                                  isiMateri: pertemuan['isi_materi'] ?? '', // Kirim link video/PDF
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
                              pertemuan['judul_pertemuan'] ?? 'Pertemuan ${index + 1}',
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