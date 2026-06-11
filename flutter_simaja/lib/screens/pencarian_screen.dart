import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'detailmateri_screen.dart';
import 'jadwal_screen.dart';

class PencarianScreen extends StatefulWidget {
  final String? initialQuery;
  const PencarianScreen({super.key, this.initialQuery});

  @override
  State<PencarianScreen> createState() => _PencarianScreenState();
}

class _PencarianScreenState extends State<PencarianScreen> {
  late TextEditingController _searchController;
  String _currentQuery = '';
  bool _isLoading = true;

  // Tempat menyimpan data asli dari database
  List<dynamic> _allMateri = [];
  List<dynamic> _allJadwal = [];

  // Tempat menyimpan data yang sudah di-filter (disaring)
  List<dynamic> _filteredMateri = [];
  List<dynamic> _filteredJadwal = [];

  @override
  void initState() {
    super.initState();
    _currentQuery = widget.initialQuery ?? '';
    _searchController = TextEditingController(text: _currentQuery);
    _fetchDataFromApi();
  }

  // Tarik semua data dari API saat halaman pertama kali dibuka
  Future<void> _fetchDataFromApi() async {
    setState(() => _isLoading = true);
    
    var dataMateri = await ApiService.getMateri();
    var dataJadwal = await ApiService.getJadwal();

    setState(() {
      _allMateri = dataMateri;
      _allJadwal = dataJadwal;
      _isLoading = false;
      // Jika ada teks bawaan dari halaman sebelumnya, langsung filter
      if (_currentQuery.isNotEmpty) {
        _filterResults(_currentQuery);
      }
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  // Fungsi menyaring teks secara dinamis
  void _filterResults(String query) {
    setState(() {
      _currentQuery = query;
      if (query.isEmpty) {
        _filteredMateri = [];
        _filteredJadwal = [];
      } else {
        final lowerQuery = query.toLowerCase();
        
        // Filter untuk Materi
        _filteredMateri = _allMateri.where((m) {
          final judul = (m['judul'] ?? '').toLowerCase();
          final deskripsi = (m['deskripsi'] ?? '').toLowerCase();
          return judul.contains(lowerQuery) || deskripsi.contains(lowerQuery);
        }).toList();

        // Filter untuk Jadwal
        _filteredJadwal = _allJadwal.where((j) {
          final judul = (j['judul'] ?? '').toLowerCase();
          final deskripsi = (j['deskripsi'] ?? '').toLowerCase();
          return judul.contains(lowerQuery) || deskripsi.contains(lowerQuery);
        }).toList();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Column(
        children: [
          _buildHeader(context),
          // Jika sedang narik data dari server, tampilkan loading. Jika selesai, tampilkan hasil
          Expanded(
            child: _isLoading 
              ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
              : SingleChildScrollView(
                  child: Column(
                    children: [
                      const SizedBox(height: 24),
                      _buildSearchResults(),
                      const SizedBox(height: 24), 
                    ],
                  ),
                ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Stack(
      alignment: Alignment.bottomCenter,
      children: [
        Container(
          width: double.infinity,
          height: 180,
          margin: const EdgeInsets.only(bottom: 24),
          decoration: const BoxDecoration(
            color: AppTheme.primaryGreen,
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(30),
              bottomRight: Radius.circular(30),
            ),
          ),
          child: Padding(
            padding: const EdgeInsets.only(top: 40, left: 16, right: 16),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Positioned(
                  left: 0,
                  child: IconButton(
                    icon: const Icon(Icons.arrow_back, color: Colors.white),
                    onPressed: () => Navigator.pop(context),
                  ),
                ),
                const Expanded(
                  child: Padding(
                    padding: EdgeInsets.only(top: 10),
                    child: Text(
                      'Pencarian',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 48), 
              ],
            ),
          ),
        ),
        Positioned(
          bottom: 0,
          left: 24,
          right: 24,
          child: Container(
            height: 50,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(15),
              boxShadow: const [
                BoxShadow(
                  color: Colors.black12,
                  blurRadius: 10,
                  offset: Offset(0, 5),
                ),
              ],
            ),
            child: TextField(
              controller: _searchController,
              autofocus: true,
              onChanged: _filterResults,
              decoration: InputDecoration(
                hintText: 'Search',
                prefixIcon: const Icon(Icons.search, color: Colors.black87),
                suffixIcon: _currentQuery.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear, color: Colors.black54),
                        onPressed: () {
                          _searchController.clear();
                          _filterResults('');
                        },
                      )
                    : null,
                border: InputBorder.none,
                contentPadding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 15,
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSearchResults() {
    if (_currentQuery.isEmpty) {
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(24.0),
          child: Text(
            'Ketik kata kunci untuk mulai mencari\n(misal: "UI/UX" atau "Web")',
            textAlign: TextAlign.center,
            style: TextStyle(color: AppTheme.textGrey, height: 1.5),
          ),
        ),
      );
    }

    if (_filteredMateri.isEmpty && _filteredJadwal.isEmpty) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Text(
            'Tidak ada hasil untuk "$_currentQuery"',
            style: const TextStyle(color: AppTheme.textGrey),
          ),
        ),
      );
    }

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Center(
            child: RichText(
              text: TextSpan(
                style: const TextStyle(
                  fontSize: 16,
                  color: AppTheme.textDark,
                  fontWeight: FontWeight.bold,
                ),
                children: [
                  const TextSpan(text: 'Hasil Pencarian untuk: '),
                  TextSpan(
                    text: '"$_currentQuery"',
                    style: const TextStyle(color: AppTheme.secondaryGreen),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 8),
          const Center(
            child: Text(
              'Menampilkan hasil dari Materi dan Jadwal Study Jam',
              style: TextStyle(color: AppTheme.textGrey, fontSize: 12),
            ),
          ),
          const SizedBox(height: 24),

          // --- MATERI SECTION ---
          if (_filteredMateri.isNotEmpty) ...[
            Row(
              children: const [
                Icon(Icons.menu_book, color: AppTheme.secondaryGreen),
                SizedBox(width: 8),
                Text(
                  'Materi',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.secondaryGreen,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Container(
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey.shade300),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                children: _filteredMateri.asMap().entries.map((entry) {
                  final index = entry.key;
                  final item = entry.value;
                  return Column(
                    children: [
                      _buildResultItem(
                        title: item['judul'] ?? 'Tanpa Judul',
                        subtitle: item['deskripsi'] ?? '-',
                        buttonText: 'Lihat Detail',
                        onPressed: () {
                          // Pindah ke Detail Materi saat diklik
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => DetailMateriScreen(
                                materiId: item['id'].toString(),
                                materiTitle: item['judul'] ?? 'Materi',
                              ),
                            ),
                          );
                        },
                      ),
                      if (index < _filteredMateri.length - 1)
                        const Divider(height: 1, color: Colors.grey),
                    ],
                  );
                }).toList(),
              ),
            ),
            const SizedBox(height: 32),
          ],

          // --- JADWAL SECTION ---
          if (_filteredJadwal.isNotEmpty) ...[
            Row(
              children: const [
                Icon(Icons.calendar_month, color: AppTheme.secondaryGreen),
                SizedBox(width: 8),
                Text(
                  'Jadwal',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.secondaryGreen,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Container(
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey.shade300),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                children: _filteredJadwal.asMap().entries.map((entry) {
                  final index = entry.key;
                  final item = entry.value;
                  
                  // Gabungkan tanggal dan waktu untuk subtitle
                  String infoJadwal = "${item['tanggal'] ?? '-'} • ${item['waktu_mulai'] ?? '-'} WIB";

                  return Column(
                    children: [
                      _buildResultItem(
                        title: item['judul'] ?? 'Tanpa Judul',
                        subtitle: infoJadwal,
                        buttonText: 'Lihat Jadwal',
                        isSolidButton: true,
                        onPressed: () {
                          // Lempar ke halaman jadwal agar user bisa daftar/absen
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (context) => const JadwalScreen()),
                          );
                        },
                      ),
                      if (index < _filteredJadwal.length - 1)
                        const Divider(height: 1, color: Colors.grey),
                    ],
                  );
                }).toList(),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildResultItem({
    required String title,
    required String subtitle,
    required String buttonText,
    bool isSolidButton = false,
    required VoidCallback onPressed,
  }) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: const TextStyle(color: AppTheme.textGrey, fontSize: 12),
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          const SizedBox(width: 8),
          isSolidButton
              ? ElevatedButton(
                  onPressed: onPressed,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.primaryGreen,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                    ),
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    minimumSize: Size.zero,
                  ),
                  child: Text(
                    buttonText,
                    style: const TextStyle(color: Colors.white, fontSize: 12),
                  ),
                )
              : OutlinedButton(
                  onPressed: onPressed,
                  style: OutlinedButton.styleFrom(
                    side: const BorderSide(color: AppTheme.secondaryGreen),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(20),
                    ),
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    minimumSize: Size.zero,
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        buttonText,
                        style: const TextStyle(color: AppTheme.secondaryGreen, fontSize: 12),
                      ),
                      const SizedBox(width: 4),
                      const Icon(
                        Icons.arrow_forward,
                        size: 14,
                        color: AppTheme.secondaryGreen,
                      ),
                    ],
                  ),
                ),
        ],
      ),
    );
  }
}