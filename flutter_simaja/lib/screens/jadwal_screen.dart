import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'absensi_screen.dart'; // Tambahkan baris ini

class JadwalScreen extends StatefulWidget {
  const JadwalScreen({super.key});

  @override
  State<JadwalScreen> createState() => _JadwalScreenState();
}

class _JadwalScreenState extends State<JadwalScreen> {
  // Dummy data jadwal untuk sementara
  final List<Map<String, dynamic>> _jadwalList = [
    {
      "materi": "UI/UX Design",
      "deskripsi": "Introduction UI/UX with Figma",
      "tanggal": "01 May 2026",
      "waktu": "16.00 WIB",
      "lokasi": "Lab Komputer 1",
      "peserta": "30 Peserta",
    },
    {
      "materi": "WEB Basic",
      "deskripsi": "Dasar HTML, CSS, dan Bootstrap",
      "tanggal": "03 May 2026",
      "waktu": "16.00 WIB",
      "lokasi": "Lab Komputer 2",
      "peserta": "25 Peserta",
    },
    {
      "materi": "WEB Advance",
      "deskripsi": "Framework CodeIgniter 4",
      "tanggal": "05 May 2026",
      "waktu": "16.00 WIB",
      "lokasi": "Ruang Kelas A",
      "peserta": "20 Peserta",
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: Column(
        children: [
          // HEADER HIJAU MELENGKUNG DENGAN SEARCH BAR
          Container(
            padding: const EdgeInsets.fromLTRB(20, 60, 20, 30), // Padding atas disesuaikan untuk SafeArea
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(bottom: Radius.circular(30)),
            ),
            child: Column(
              children: [
                const Text(
                  'Jadwal Study Jam',
                  style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 20),
                // Search Bar
                TextField(
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
          
          // LIST JADWAL
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _jadwalList.length,
              itemBuilder: (context, index) {
                final data = _jadwalList[index];
                return _buildJadwalCard(data);
              },
            ),
          ),
        ],
      ),
    );
  }

  // WIDGET CARD JADWAL
  Widget _buildJadwalCard(Map<String, dynamic> data) {
    return Card(
      elevation: 2,
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              data['materi'],
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(
              data['deskripsi'],
              style: const TextStyle(fontSize: 12, color: Colors.black54),
            ),
            const SizedBox(height: 16),
            
            // Info Grid (Tanggal, Waktu, Lokasi, Peserta)
            Row(
              children: [
                Expanded(child: _buildInfoItem(Icons.calendar_today, data['tanggal'])),
                Expanded(child: _buildInfoItem(Icons.access_time, data['waktu'])),
              ],
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                Expanded(child: _buildInfoItem(Icons.location_on_outlined, data['lokasi'])),
                Expanded(child: _buildInfoItem(Icons.people_outline, data['peserta'])),
              ],
            ),
            
            const SizedBox(height: 16),
            
            // Tombol Action
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(
                    onPressed: null, // Disabled
                    style: OutlinedButton.styleFrom(
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      side: const BorderSide(color: Colors.grey),
                    ),
                    child: const Text('✓ Terdaftar', style: TextStyle(color: Colors.grey)),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () {
                      // Logika pindah halaman ke AbsensiScreen
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const AbsensiScreen()),
                      );
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryGreen,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                    ),
                    child: const Text('Absen', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                  ),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }

  // Widget bantuan kecil untuk icon dan teks info
  Widget _buildInfoItem(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 16, color: Colors.black54),
        const SizedBox(width: 6),
        Text(text, style: const TextStyle(fontSize: 12, color: Colors.black87)),
      ],
    );
  }
}