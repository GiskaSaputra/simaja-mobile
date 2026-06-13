import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'pencarian_screen.dart';

import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

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
  
  // Variabel untuk YouTube Player VERSI 10.0
  YoutubePlayerController? _ytController;
  bool _isYouTube = false;

  @override
  void initState() {
    super.initState();
    _checkMateriType();
  }

  // Fungsi cerdas untuk mengekstrak ID video dari berbagai URL Youtube
  String? _extractYoutubeId(String url) {
    RegExp regExp = RegExp(
      r'.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*',
      caseSensitive: false,
      multiLine: false,
    );
    final match = regExp.firstMatch(url);
    if (match != null && match.groupCount >= 1) {
      return match.group(1);
    }
    return null;
  }

  void _checkMateriType() {
    String url = widget.isiMateri;
    
    if (url.contains('youtube.com') || url.contains('youtu.be')) {
      _isYouTube = true;
      String? videoId = _extractYoutubeId(url);
      
      if (videoId != null) {
        // --- SINTAKS BARU YOUTUBE_PLAYER_FLUTTER VERSI 10.0.1 ---
        _ytController = YoutubePlayerController.fromVideoId(
          videoId: videoId,
          autoPlay: false,
          params: const YoutubePlayerParams(
            showControls: true,
            showFullscreenButton: true,
            mute: false,
          ),
        );
      }
    }
  }

  @override
  void dispose() {
    _ytController?.close(); // Versi 10.0 menggunakan close(), bukan dispose()
    super.dispose();
  }

  // Fungsi untuk membuka PDF / Link Website
  Future<void> _bukaLink(String urlString) async {
    final Uri url = Uri.parse(urlString);
    if (!await launchUrl(url, mode: LaunchMode.externalApplication)) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tidak dapat membuka link materi.'), backgroundColor: Colors.red),
        );
      }
    }
  }

  void _tandaiSelesai() async {
    setState(() => _isLoading = true);

    var response = await ApiService.markComplete(widget.pertemuanId);
    
    setState(() => _isLoading = false);

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Berhasil! Progres belajarmu telah bertambah."), backgroundColor: Colors.green),
        );
        Navigator.pop(context); 
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
                    Navigator.push(context, MaterialPageRoute(builder: (context) => const PencarianScreen()));
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
                    'Member Protic Belajar Materi Ini',
                    style: TextStyle(fontSize: 11, color: Colors.grey),
                  ),
                  const SizedBox(height: 20),

                  // ========================================================
                  // LOGIKA PENAMPILAN MATERI (YOUTUBE / PDF)
                  // ========================================================
                  if (_isYouTube && _ytController != null) ...[
                    // --- WIDGET YOUTUBE PLAYER VERSI 10.0 ---
                    ClipRRect(
                      borderRadius: BorderRadius.circular(16),
                      child: YoutubePlayer(
                        controller: _ytController!,
                        aspectRatio: 16 / 9,
                      ),
                    ),
                  ] else ...[
                    // TAMPILKAN KOTAK BUKA DOKUMEN / PDF
                    Container(
                      width: double.infinity,
                      height: 180,
                      decoration: BoxDecoration(
                        color: Colors.grey.shade100,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: Colors.grey.shade400),
                      ),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(Icons.picture_as_pdf_rounded, size: 50, color: Colors.redAccent),
                          const SizedBox(height: 16),
                          ElevatedButton.icon(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.primaryGreen,
                              foregroundColor: Colors.white,
                            ),
                            onPressed: () => _bukaLink(widget.isiMateri),
                            icon: const Icon(Icons.open_in_browser),
                            label: const Text('Buka Dokumen/PDF Materi'),
                          ),
                        ],
                      ),
                    ),
                  ],
                  // ========================================================

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
                    'URL Materi:\n${widget.isiMateri}',
                    style: const TextStyle(fontSize: 12, color: Colors.black54),
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