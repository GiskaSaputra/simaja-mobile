import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'peringkat_screen.dart';
import 'pencarian_screen.dart';

class ProgresScreen extends StatelessWidget {
  const ProgresScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: SingleChildScrollView(
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
            const SizedBox(height: 24), // Bottom padding for navbar
          ],
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
                  MaterialPageRoute(
                    builder: (context) => const PencarianScreen(),
                  ),
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
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        children: [
          Expanded(
            child: _buildStatCard(
              title: 'Total Jam Belajar',
              value: '124 Jam',
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
              value: '12 Hari',
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
              Text(
                title,
                style: const TextStyle(color: Colors.grey, fontSize: 12),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: const TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.bold,
              color: AppTheme.textDark,
            ),
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
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
      ),
      child: const Text(
        'PERINGKAT',
        style: TextStyle(
          color: Colors.white,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildAktivitasMingguan() {
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
              children: [
                Icon(Icons.bar_chart, color: AppTheme.textDark, size: 24),
                const SizedBox(width: 8),
                const Text(
                  'Aktivitas Mingguan',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textDark,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            _buildProgressBarRow('Minggu 1', 0.2),
            const SizedBox(height: 12),
            _buildProgressBarRow('Minggu 2', 0.2),
            const SizedBox(height: 12),
            _buildProgressBarRow('Minggu 3', 0.2),
            const SizedBox(height: 12),
            _buildProgressBarRow('Minggu 4', 0.2),
          ],
        ),
      ),
    );
  }

  Widget _buildProgresMateri() {
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
              children: [
                Icon(Icons.bar_chart, color: AppTheme.textDark, size: 24),
                const SizedBox(width: 8),
                const Text(
                  'Progres Materi',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textDark,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            _buildProgressBarRow('UI/UX', 0.2),
            const SizedBox(height: 12),
            _buildProgressBarRow('Web Basic', 0.2),
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
            Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.w500),
            ),
            Text(
              '${(progress * 100).toInt()}% Aktivitas',
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
