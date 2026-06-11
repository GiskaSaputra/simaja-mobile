import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'pencarian_screen.dart';

class PeringkatScreen extends StatefulWidget {
  const PeringkatScreen({super.key});

  @override
  State<PeringkatScreen> createState() => _PeringkatScreenState();
}

class _PeringkatScreenState extends State<PeringkatScreen> {
  Map<String, dynamic>? _peringkatData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchPeringkat();
  }

  Future<void> _fetchPeringkat() async {
    setState(() => _isLoading = true);
    var data = await ApiService.getPeringkat();
    setState(() {
      _peringkatData = data;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
          : RefreshIndicator(
              onRefresh: _fetchPeringkat,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    _buildHeader(context),
                    const SizedBox(height: 16),
                    _buildTopThree(),
                    const SizedBox(height: 16),
                    _buildListPeringkat(),
                    const SizedBox(height: 24), 
                  ],
                ),
              ),
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
                      'Peringkat',
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
              readOnly: true,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const PencarianScreen()),
                );
              },
              decoration: const InputDecoration(
                hintText: 'Search',
                prefixIcon: Icon(Icons.search, color: Colors.black87),
                border: InputBorder.none,
                contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 15),
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildTopThree() {
    List<dynamic> top3 = _peringkatData?['top3'] ?? [];

    if (top3.isEmpty) {
      return const Center(child: Text("Belum ada data peringkat.", style: TextStyle(color: Colors.grey)));
    }

    // Ambil data untuk juara 1, 2, dan 3 (jika ada)
    var juara1 = top3.length > 0 ? top3[0] : null;
    var juara2 = top3.length > 1 ? top3[1] : null;
    var juara3 = top3.length > 2 ? top3[2] : null;

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.end,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Juara 2 (Kiri)
          if (juara2 != null)
            _buildTopRankCard(
              rank: 2, 
              name: juara2['nama_lengkap'] ?? 'User ${juara2['user_id']}', 
              jurusan: juara2['jurusan'] ?? '-', 
              points: int.parse(juara2['total_skor'].toString()), 
              isCenter: false
            ),
          
          if (juara2 != null) const SizedBox(width: 8),

          // Juara 1 (Tengah - Paling Tinggi)
          if (juara1 != null)
            _buildTopRankCard(
              rank: 1, 
              name: juara1['nama_lengkap'] ?? 'User ${juara1['user_id']}', 
              jurusan: juara1['jurusan'] ?? '-', 
              points: int.parse(juara1['total_skor'].toString()), 
              isCenter: true
            ),

          if (juara3 != null) const SizedBox(width: 8),

          // Juara 3 (Kanan)
          if (juara3 != null)
            _buildTopRankCard(
              rank: 3, 
              name: juara3['nama_lengkap'] ?? 'User ${juara3['user_id']}', 
              jurusan: juara3['jurusan'] ?? '-', 
              points: int.parse(juara3['total_skor'].toString()), 
              isCenter: false
            ),
        ],
      ),
    );
  }

  Widget _buildTopRankCard({
    required int rank,
    required String name,
    required String jurusan,
    required int points,
    required bool isCenter,
  }) {
    final bgColor = isCenter ? AppTheme.lightGreen : Colors.white;
    final height = isCenter ? 180.0 : 150.0;
    final width = isCenter ? 110.0 : 100.0;

    return Container(
      width: width,
      height: height,
      padding: const EdgeInsets.all(8),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: AppTheme.textGrey, width: 0.5),
        boxShadow: isCenter
            ? const [
                BoxShadow(color: Colors.black12, blurRadius: 10, offset: Offset(0, 5))
              ]
            : null,
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: const BoxDecoration(color: AppTheme.primaryGreen, shape: BoxShape.circle),
            child: Text('#$rank', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
          const SizedBox(height: 8),
          Text(
            name,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            overflow: TextOverflow.ellipsis,
            maxLines: 1,
          ),
          const SizedBox(height: 4),
          Text(
            jurusan,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 8, color: AppTheme.textGrey),
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
          const SizedBox(height: 8),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
            decoration: BoxDecoration(
              border: Border.all(color: AppTheme.textDark, width: 0.5),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Text('$points Poin', style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold)),
          )
        ],
      ),
    );
  }

  Widget _buildListPeringkat() {
    List<dynamic> restRank = _peringkatData?['rest'] ?? [];

    if (restRank.isEmpty) return const SizedBox.shrink();

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Column(
        children: restRank.asMap().entries.map((entry) {
          int index = entry.key;
          var item = entry.value;
          int rankActual = index + 4; // Karena index mulai dari 0, dan top 3 sudah diambil
          String name = item['nama_lengkap'] ?? 'User ${item['user_id']}';
          
          return Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: _buildListItem(
              rank: rankActual,
              name: name,
              initial: name.isNotEmpty ? name[0].toUpperCase() : 'U',
              jurusan: item['jurusan'] ?? '-',
              points: int.parse(item['total_skor'].toString()),
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildListItem({
    required int rank,
    required String name,
    required String initial,
    required String jurusan,
    required int points,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Row(
        children: [
          Text('#$rank', style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey)),
          const SizedBox(width: 16),
          CircleAvatar(
            backgroundColor: Colors.grey.shade200,
            radius: 16,
            child: Text(
              initial,
              style: const TextStyle(color: AppTheme.secondaryGreen, fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                Text(
                  jurusan,
                  style: const TextStyle(fontSize: 10, color: Colors.grey),
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
          Row(
            children: [
              Text(
                '$points',
                style: const TextStyle(color: AppTheme.secondaryGreen, fontWeight: FontWeight.bold),
              ),
              const Text(' Poin', style: TextStyle(color: Colors.grey, fontSize: 12)),
            ],
          ),
        ],
      ),
    );
  }
}