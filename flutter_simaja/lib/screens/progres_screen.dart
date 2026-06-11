import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'peringkat_screen.dart';
import 'pencarian_screen.dart';

class ProgresScreen extends StatefulWidget {
  const ProgresScreen({super.key});

  @override
  State<ProgresScreen> createState() => _ProgresScreenState();
}

class _ProgresScreenState extends State<ProgresScreen> {
  Map<String, dynamic>? _progresData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchProgres();
  }

  Future<void> _fetchProgres() async {
    setState(() => _isLoading = true);
    var data = await ApiService.getProgres();
    setState(() {
      _progresData = data;
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
              onRefresh: _fetchProgres,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    _buildHeader(context),
                    const SizedBox(height: 16),
                    _buildStatCards(),
                    const SizedBox(height: 16),
                    _buildPeringkatButton(context),
                    const SizedBox(height: 24),
                    _buildAktivitasMingguan(),
                    const SizedBox(height: 16),
                    _buildProgresMateri(),
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
          child: const Padding(
            padding: EdgeInsets.only(top: 60),
            child: Text(
              'Progres Belajar',
              textAlign: TextAlign.center,
              style: TextStyle(
                color: Colors.white,
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
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

  Widget _buildStatCards() {
    int totalJam = _progresData?['total_jam'] ?? 0;
    int streak = _progresData?['streak'] ?? 0;

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        children: [
          Expanded(
            child: _buildStatCard(
              title: 'Total Jam Belajar',
              value: '$totalJam Jam',
              icon: Icons.access_time,
              iconColor: Colors.amber,
              subtitle: 'Terus meningkat',
              subtitleColor: AppTheme.secondaryGreen,
              subtitleIcon: Icons.trending_up,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: _buildStatCard(
              title: 'Streak',
              value: '$streak Hari',
              icon: Icons.local_fire_department,
              iconColor: Colors.redAccent,
              subtitle: 'Pertahankan Konsistenmu',
              subtitleColor: AppTheme.secondaryGreen,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard({
    required String title,
    required String value,
    required IconData icon,
    required Color iconColor,
    required String subtitle,
    required Color subtitleColor,
    IconData? subtitleIcon,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(icon, color: iconColor, size: 16),
              const SizedBox(width: 4),
              Text(title, style: const TextStyle(color: Colors.grey, fontSize: 12)),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppTheme.textDark),
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              if (subtitleIcon != null) ...[
                Icon(subtitleIcon, color: subtitleColor, size: 12),
                const SizedBox(width: 4),
              ],
              Expanded(
                child: Text(
                  subtitle,
                  textAlign: TextAlign.center,
                  style: TextStyle(color: subtitleColor, fontSize: 10),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildPeringkatButton(BuildContext context) {
    return ElevatedButton(
      onPressed: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const PeringkatScreen()),
        );
      },
      style: ElevatedButton.styleFrom(
        backgroundColor: AppTheme.secondaryGreen,
        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 12),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
      child: const Text(
        'PERINGKAT',
        style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
      ),
    );
  }

  Widget _buildAktivitasMingguan() {
    List<dynamic> aktivitasMingguan = _progresData?['jam_mingguan'] ?? [];

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(15),
          border: Border.all(color: Colors.grey.shade300),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: const [
                Icon(Icons.bar_chart, color: AppTheme.textDark, size: 24),
                SizedBox(width: 8),
                Text(
                  'Aktivitas Mingguan',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textDark),
                ),
              ],
            ),
            const SizedBox(height: 20),
            if (aktivitasMingguan.isEmpty)
              const Text("Belum ada aktivitas.", style: TextStyle(color: Colors.grey))
            else
              ...aktivitasMingguan.map((item) {
                // Konversi persen (contoh 20) menjadi decimal 0.2 untuk LinearProgressIndicator
                double progressDecimal = (item['persen'] ?? 0) / 100.0;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: _buildProgressBarRow(item['minggu'], progressDecimal),
                );
              }).toList(),
          ],
        ),
      ),
    );
  }

  Widget _buildProgresMateri() {
    List<dynamic> progresMateri = _progresData?['target_bulanan'] ?? [];

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(15),
          border: Border.all(color: Colors.grey.shade300),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: const [
                Icon(Icons.bar_chart, color: AppTheme.textDark, size: 24),
                SizedBox(width: 8),
                Text(
                  'Progres Materi',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textDark),
                ),
              ],
            ),
            const SizedBox(height: 20),
            if (progresMateri.isEmpty)
              const Text("Belum ada materi dipelajari.", style: TextStyle(color: Colors.grey))
            else
              ...progresMateri.map((item) {
                double progressDecimal = (item['persen'] ?? 0) / 100.0;
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: _buildProgressBarRow(item['judul'], progressDecimal),
                );
              }).toList(),
          ],
        ),
      ),
    );
  }

  Widget _buildProgressBarRow(String label, double progress) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(label, style: const TextStyle(fontWeight: FontWeight.w500)),
            Text(
              '${(progress * 100).toInt()}%',
              style: const TextStyle(color: Colors.grey, fontSize: 12),
            ),
          ],
        ),
        const SizedBox(height: 8),
        LinearProgressIndicator(
          value: progress,
          backgroundColor: Colors.grey.shade300,
          valueColor: const AlwaysStoppedAnimation<Color>(AppTheme.primaryGreen),
          minHeight: 8,
          borderRadius: BorderRadius.circular(4),
        ),
      ],
    );
  }
}