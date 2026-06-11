import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Wajib import untuk narik data
import 'peringkat_screen.dart'; 

class BerandaScreen extends StatefulWidget {
  final Function(int)? onChangeTab;

  const BerandaScreen({super.key, this.onChangeTab});

  @override
  State<BerandaScreen> createState() => _BerandaScreenState();
}

// Ubah menjadi StatefulWidget agar bisa menyimpan data dari API
class _BerandaScreenState extends State<BerandaScreen> {
  List<dynamic> _jadwalList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchBerandaData();
  }

  // Fungsi untuk menarik data jadwal terdekat
  Future<void> _fetchBerandaData() async {
    setState(() => _isLoading = true);
    var jadwal = await ApiService.getJadwal();
    
    if (mounted) {
      setState(() {
        _jadwalList = jadwal;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white, 
      body: RefreshIndicator(
        onRefresh: _fetchBerandaData,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // --- BAGIAN HEADER HIJAU & MENU CARD ---
              Stack(
                clipBehavior: Clip.none, 
                children: [
                  // 1. Background Hijau Melengkung
                  Container(
                    padding: const EdgeInsets.only(left: 24, right: 24, top: 60, bottom: 160), 
                    decoration: const BoxDecoration(
                      color: AppTheme.primaryGreen,
                      borderRadius: BorderRadius.vertical(
                        bottom: Radius.circular(40), 
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
                              height: 45, 
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

                        // Teks Intro
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
                    bottom: -90, 
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
                                if (widget.onChangeTab != null) widget.onChangeTab!(1);
                              }),
                              _buildMenuIcon(Icons.menu_book_outlined, 'Materi', () {
                                if (widget.onChangeTab != null) widget.onChangeTab!(2);
                              }),
                            ],
                          ),
                          const SizedBox(height: 24),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceAround,
                            children: [
                              _buildMenuIcon(Icons.bar_chart_outlined, 'Progres', () {
                                if (widget.onChangeTab != null) widget.onChangeTab!(3);
                              }),
                              _buildMenuIcon(Icons.emoji_events_outlined, 'Peringkat', () {
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

              const SizedBox(height: 120), 

              // --- BAGIAN BAWAH: JADWAL STUDY JAM ---
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Judul Jadwal & Lihat Semua
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Jadwal Terdekat', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                        GestureDetector(
                          onTap: () {
                            if (widget.onChangeTab != null) widget.onChangeTab!(1); // Pindah ke Tab Jadwal
                          },
                          child: const Text('Lihat Semua', style: TextStyle(fontSize: 12, color: Colors.black87, fontWeight: FontWeight.bold)),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),

                    // Tampilkan Loading, Teks Kosong, atau 1 Card Jadwal
                    if (_isLoading)
                      const Center(child: Padding(padding: EdgeInsets.all(20), child: CircularProgressIndicator(color: AppTheme.primaryGreen)))
                    else if (_jadwalList.isEmpty)
                      const Center(child: Padding(padding: EdgeInsets.all(20), child: Text("Belum ada jadwal tersedia.", style: TextStyle(color: Colors.grey))))
                    else
                      _buildJadwalPreviewCard(_jadwalList.first), // Hanya ambil 1 data teratas

                    const SizedBox(height: 40), 
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // Widget Pembantu untuk Icon Menu
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

  // Widget Pembantu untuk info text di Card Jadwal
  Widget _buildInfoItem(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 16, color: Colors.black54),
        const SizedBox(width: 6),
        Flexible(
          child: Text(text, style: const TextStyle(fontSize: 12, color: Colors.black87), overflow: TextOverflow.ellipsis),
        ),
      ],
    );
  }

  // Widget Card Preview Jadwal (Dinamis dari API)
  Widget _buildJadwalPreviewCard(dynamic data) {
    String waktu = "${data['waktu_mulai'] ?? '-'} s/d ${data['waktu_selesai'] ?? '-'} WIB";
    String kuotaTeks = "${data['terisi'] ?? 0} / ${data['kuota'] ?? 0} Peserta";

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.shade300),
        boxShadow: const [
          BoxShadow(color: Colors.black12, blurRadius: 5, offset: Offset(0, 2)),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(data['judul'] ?? 'Tanpa Judul', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
          const SizedBox(height: 4),
          Text(data['deskripsi'] ?? '-', style: const TextStyle(fontSize: 12, color: Colors.black87), maxLines: 2, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(child: _buildInfoItem(Icons.calendar_today, data['tanggal'] ?? '-')),
              Expanded(child: _buildInfoItem(Icons.access_time, waktu)),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            children: [
              Expanded(child: _buildInfoItem(Icons.location_on_outlined, 'Lab / Ruang Kelas')),
              Expanded(child: _buildInfoItem(Icons.people_outline, kuotaTeks)),
            ],
          ),
          const SizedBox(height: 16),
          
          // Tombol Buka Jadwal (Arahkan ke tab jadwal)
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.primaryGreen,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
              ),
              onPressed: () {
                if (widget.onChangeTab != null) widget.onChangeTab!(1); // Pindah ke Tab Jadwal
              },
              child: const Text('Buka Menu Jadwal', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
            ),
          ),
        ],
      ),
    );
  }
}