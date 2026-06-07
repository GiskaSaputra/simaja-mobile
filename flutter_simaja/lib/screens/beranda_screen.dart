import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'peringkat_screen.dart'; // Only Peringkat uses push now

class BerandaScreen extends StatelessWidget {
  final Function(int)? onChangeTab;

  const BerandaScreen({super.key, this.onChangeTab});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white, // Background dasar aplikasi
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- BAGIAN HEADER HIJAU & MENU CARD (Tumpang Tindih / Overlap) ---
            Stack(
              clipBehavior: Clip.none, // Mengizinkan elemen keluar dari batas kotak Stack
              children: [
                // 1. Background Hijau Melengkung
                Container(
                  // PADDING BOTTOM DIPERBESAR MENJADI 160 AGAR TEKS TIDAK TERTUTUP CARD
                  padding: const EdgeInsets.only(left: 24, right: 24, top: 60, bottom: 160), 
                  decoration: const BoxDecoration(
                    color: AppTheme.primaryGreen,
                    borderRadius: BorderRadius.vertical(
                      bottom: Radius.circular(40), // Corner radius sesuai Figma
                    ),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Header (Logo & Text SIMAJA)
                      Row(
                        children: [
                          Image.asset(
                            'lib/assets/logo_protik.png',
                            height: 45, // Menggunakan logo protik kecil di header
                          ),
                          const SizedBox(width: 12),
                          const Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'SIMAJA',
                                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 22, color: Colors.white),
                              ),
                              Text(
                                'Sistem Manajemen Study Jam',
                                style: TextStyle(fontSize: 12, color: Colors.white70),
                              ),
                            ],
                          ),
                        ],
                      ),
                      const SizedBox(height: 24),

                      // Banner Image
                      ClipRRect(
                        borderRadius: BorderRadius.circular(16),
                        child: Image.asset(
                          'lib/assets/banner.png',
                          width: double.infinity,
                          height: 180,
                          fit: BoxFit.cover,
                        ),
                      ),
                      const SizedBox(height: 20),

                      // Teks Intro (Sekarang aman, tidak tertutup)
                      const Text(
                        'Temukan pengalaman belajar baru bersama SIMAJA, sistem manajemen yang memfasilitasi kegiatan Study Jam Protic secara terorganisir dan menyenangkan.',
                        style: TextStyle(fontSize: 13, color: Colors.white, height: 1.4),
                        textAlign: TextAlign.justify,
                      ),
                    ],
                  ),
                ),

                // 2. Menu Card (Warna Putih Tumpang Tindih)
                Positioned(
                  bottom: -90, // Menarik card ke bawah agar keluar/overlap dengan batas background hijau
                  left: 24,
                  right: 24,
                  child: Container(
                    padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.grey.shade200),
                      boxShadow: const [
                        BoxShadow(color: Colors.black12, blurRadius: 10, offset: Offset(0, 5)),
                      ],
                    ),
                    child: Column(
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            _buildMenuIcon(Icons.calendar_today_outlined, 'Jadwal', () {
                              if (onChangeTab != null) onChangeTab!(1);
                            }),
                            _buildMenuIcon(Icons.menu_book_outlined, 'Materi', () {
                              if (onChangeTab != null) onChangeTab!(2);
                            }),
                          ],
                        ),
                        const SizedBox(height: 24),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            _buildMenuIcon(Icons.bar_chart_outlined, 'Progres', () {
                              if (onChangeTab != null) onChangeTab!(3);
                            }),
                            _buildMenuIcon(Icons.emoji_events_outlined, 'Peringkat', () {
                              // Peringkat is not a bottom tab, so we push it within the Home tab's navigator
                              Navigator.push(context, MaterialPageRoute(builder: (context) => const PeringkatScreen()));
                            }),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),

            // Memberikan jarak yang lebih besar agar konten Jadwal di bawahnya tidak menabrak Card Menu
            const SizedBox(height: 120), 

            // --- BAGIAN BAWAH: JADWAL STUDY JAM ---
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Judul Jadwal & Lihat Semua
                  const Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text('Jadwal Study Jam', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                      Text('Lihat Semua', style: TextStyle(fontSize: 12, color: Colors.black87, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  const SizedBox(height: 16),

                  // Placeholder Dummy Card Jadwal agar sesuai Figma
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: Colors.grey.shade300),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('UI/UX Design', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
                        const SizedBox(height: 4),
                        const Text('Introduction UI/UX with Figma', style: TextStyle(fontSize: 12, color: Colors.black87)),
                        const SizedBox(height: 16),
                        Row(
                          children: [
                            Expanded(child: _buildInfoItem(Icons.calendar_today, 'Hari/tanggal')),
                            Expanded(child: _buildInfoItem(Icons.access_time, 'Waktu')),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(child: _buildInfoItem(Icons.location_on_outlined, 'Lokasi')),
                            Expanded(child: _buildInfoItem(Icons.people_outline, 'Peserta')),
                          ],
                        ),
                        const SizedBox(height: 16),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.primaryGreen,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                            ),
                            onPressed: () {},
                            child: const Text('Daftar Sekarang', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 40), // Jarak bawah ekstra agar bisa di scroll nyaman
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Widget Pembantu untuk Icon Menu (Jadwal, Materi, dll)
  Widget _buildMenuIcon(IconData icon, String label, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 45, color: AppTheme.primaryGreen),
          const SizedBox(height: 8),
          Text(
            label,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
          ),
        ],
      ),
    );
  }

  // Widget Pembantu untuk info text (Kalender, Waktu, Lokasi) di Card Jadwal
  Widget _buildInfoItem(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 16, color: Colors.black87),
        const SizedBox(width: 6),
        Text(text, style: const TextStyle(fontSize: 12, color: Colors.black87)),
      ],
    );
  }
}