import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'absensi_screen.dart';
import 'pencarian_screen.dart';

class JadwalScreen extends StatefulWidget {
  const JadwalScreen({super.key});

  @override
  State<JadwalScreen> createState() => _JadwalScreenState();
}

class _JadwalScreenState extends State<JadwalScreen> {
  List<dynamic> _jadwalList = [];
  bool _isLoading = true; // Penanda loading saat mengambil data

  @override
  void initState() {
    super.initState();
    _fetchJadwal();
  }

  // Fungsi untuk menarik data dari database CI4
  Future<void> _fetchJadwal() async {
    setState(() => _isLoading = true);
    
    var data = await ApiService.getJadwal();
    
    setState(() {
      _jadwalList = data;
      _isLoading = false;
    });
  }

  // Fungsi untuk mengeksekusi pendaftaran jadwal
  void _prosesDaftar(String jadwalId) async {
    // Tampilkan notifikasi loading sementara
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Sedang memproses pendaftaran..."), duration: Duration(seconds: 1)),
    );

    var response = await ApiService.daftarJadwal(jadwalId);

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.green)
        );
        // Refresh daftar jadwal agar tombol berubah menjadi "✓ Terdaftar"
        _fetchJadwal(); 
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.red)
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: Column(
        children: [
          // HEADER HIJAU MELENGKUNG DENGAN SEARCH BAR
          Container(
            padding: const EdgeInsets.fromLTRB(20, 60, 20, 30), 
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
          
          // LIST JADWAL ATAU LOADING ANIMATION
          Expanded(
            child: _isLoading 
              ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
              : _jadwalList.isEmpty 
                  ? const Center(child: Text("Belum ada jadwal tersedia.", style: TextStyle(color: Colors.grey)))
                  : RefreshIndicator(
                      onRefresh: _fetchJadwal, // Tarik ke bawah untuk refresh manual
                      child: ListView.builder(
                        padding: const EdgeInsets.all(16),
                        itemCount: _jadwalList.length,
                        itemBuilder: (context, index) {
                          final data = _jadwalList[index];
                          return _buildJadwalCard(data);
                        },
                      ),
                    ),
          ),
        ],
      ),
    );
  }

  // WIDGET CARD JADWAL DINAMIS
  Widget _buildJadwalCard(dynamic data) {
    bool isTerdaftar = data['is_terdaftar'] == true;
    String idJadwal = data['id'].toString();
    String waktu = "${data['waktu_mulai'] ?? '-'} s/d ${data['waktu_selesai'] ?? '-'} WIB";
    String kuotaTeks = "${data['terisi'] ?? 0} / ${data['kuota'] ?? 0} Peserta";

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
              data['judul'] ?? 'Tanpa Judul',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(
              data['deskripsi'] ?? '-',
              style: const TextStyle(fontSize: 12, color: Colors.black54),
            ),
            const SizedBox(height: 16),
            
            // Info Grid (Tanggal, Waktu, Lokasi, Peserta)
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
            
            // Tombol Action Dinamis
            Row(
              children: [
                // TOMBOL DAFTAR
                Expanded(
                  child: OutlinedButton(
                    onPressed: isTerdaftar ? null : () => _prosesDaftar(idJadwal),
                    style: OutlinedButton.styleFrom(
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      side: BorderSide(color: isTerdaftar ? Colors.grey : AppTheme.primaryGreen),
                    ),
                    child: Text(
                      isTerdaftar ? '✓ Terdaftar' : 'Daftar', 
                      style: TextStyle(color: isTerdaftar ? Colors.grey : AppTheme.primaryGreen, fontWeight: FontWeight.bold)
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                
                // TOMBOL ABSEN
                Expanded(
                  child: ElevatedButton(
                    onPressed: isTerdaftar ? () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => AbsensiScreen(
                            // Parameter ini akan ditangkap oleh AbsensiScreen
                            jadwalId: idJadwal, 
                            judul: data['judul'] ?? 'Tanpa Judul',
                            tanggal: data['tanggal'] ?? '-',
                            waktu: waktu,
                          )
                        ),
                      );
                    } : null, // Matikan tombol Absen jika belum terdaftar
                    style: ElevatedButton.styleFrom(
                      backgroundColor: isTerdaftar ? AppTheme.primaryGreen : Colors.grey.shade400,
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
        Flexible(
          child: Text(
            text, 
            style: const TextStyle(fontSize: 12, color: Colors.black87),
            overflow: TextOverflow.ellipsis,
          )
        ),
      ],
    );
  }
}