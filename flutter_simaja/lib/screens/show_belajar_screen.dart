import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Wajib import ApiService
import 'pencarian_screen.dart';

class ShowBelajarScreen extends StatefulWidget {
  final String pertemuanId;
  final String judulPertemuan;
  final String materiTitle;
  final String isiMateri;

  const ShowBelajarScreen({
    super.key, 
    required this.pertemuanId,
    required this.judulPertemuan, 
    required this.materiTitle,
    required this.isiMateri,
  });

  @override
  State<ShowBelajarScreen> createState() => _ShowBelajarScreenState();
}

class _ShowBelajarScreenState extends State<ShowBelajarScreen> {
  bool _isLoading = false;

  // Fungsi untuk menandai materi sudah dibaca (dikirim ke API)
  void _tandaiSelesai() async {
    setState(() => _isLoading = true);

    var response = await ApiService.markComplete(widget.pertemuanId);
    
    setState(() => _isLoading = false);

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Berhasil! Progres belajarmu telah bertambah."), backgroundColor: Colors.green),
        );
        Navigator.pop(context); // Kembali ke halaman list pertemuan
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.red),
        );
      }
    }
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

          // --- KONTEN BELAJAR ---
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Judul Pertemuan 
                  Text(
                    '${widget.judulPertemuan}: ${widget.materiTitle}',
                    style: const TextStyle(
                      color: AppTheme.primaryGreen,
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 4),
                  const Text(
                    '2 Member Protic Belajar Materi Ini',
                    style: TextStyle(fontSize: 11, color: Colors.grey),
                  ),
                  const SizedBox(height: 20),

                  // VIDEO PLAYER AREA (Sesuai Desain Figma)
                  Container(
                    width: double.infinity,
                    height: 200,
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.grey.shade400),
                    ),
                    child: Stack(
                      alignment: Alignment.center,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(15),
                          child: Image.asset(
                            'lib/assets/banner.png', // Pastikan gambar banner.png kamu ada
                            width: double.infinity,
                            height: double.infinity,
                            fit: BoxFit.cover,
                          ),
                        ),
                        // Play Button
                        Container(
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.9),
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(Icons.play_arrow, color: AppTheme.primaryGreen, size: 50),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Deskripsi Materi
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: Colors.grey.shade400),
                    ),
                    child: const Row(
                      children: [
                        Icon(Icons.description_outlined, size: 20, color: Colors.black87),
                        SizedBox(width: 10),
                        Text('Deskripsi Materi', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),
                  
                  // Menampilkan Link / URL dari Database
                  Text(
                    'Materi Pembelajaran:\n${widget.isiMateri}',
                    style: const TextStyle(fontSize: 13, color: Colors.blueAccent),
                  ),
                  const SizedBox(height: 24),

                  // TIPS BELAJAR
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8F5E9),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: AppTheme.primaryGreen, width: 1.5),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Image.asset('lib/assets/logo_protik.png', height: 24), 
                            const SizedBox(width: 10),
                            const Text(
                              'Tips Belajar:',
                              style: TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                                color: AppTheme.primaryGreen,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        const Text(
                          'Catat poin penting, jika kurang jelas. Ulangi materi di atas.',
                          style: TextStyle(fontSize: 13, color: Colors.black87, height: 1.5),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 40),

                  // TOMBOL TANDAI SELESAI
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.primaryGreen,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      ),
                      onPressed: _isLoading ? null : _tandaiSelesai,
                      child: _isLoading 
                          ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                          : const Text('Tandai Selesai Belajar', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}