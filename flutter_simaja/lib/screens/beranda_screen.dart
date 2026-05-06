import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../widgets/menu_card.dart';

class BerandaScreen extends StatelessWidget {
  const BerandaScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      appBar: AppBar(
        backgroundColor: AppTheme.primaryGreen,
        elevation: 0,
        title: const Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('SIMAJA', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 20, color: Colors.white)),
            Text('Sistem Manajemen Study Jam', style: TextStyle(fontSize: 12, color: Colors.white70)),
          ],
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              height: 180,
              width: double.infinity,
              decoration: BoxDecoration(
                color: Colors.grey[300],
                borderRadius: BorderRadius.circular(16),
              ),
              child: const Center(child: Icon(Icons.image, size: 50, color: Colors.grey)),
            ),
            const SizedBox(height: 16),
            const Text(
              'Temukan pengalaman belajar baru bersama SIMAJA, sistem manajemen yang memfasilitasi kegiatan Study Jam Protic secara terorganisir dan menyenangkan.',
              style: TextStyle(fontSize: 14, color: Colors.black87),
              textAlign: TextAlign.justify,
            ),
            const SizedBox(height: 24),
            GridView.count(
              crossAxisCount: 2,
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              mainAxisSpacing: 16,
              crossAxisSpacing: 16,
              childAspectRatio: 1.2,
              children: [
                MenuCard(
                  icon: Icons.calendar_today,
                  title: 'Jadwal',
                  onTap: () {
                    // Logika pindah halaman atau pindah tab bawah nanti disini
                  },
                ),
                MenuCard(
                  icon: Icons.menu_book,
                  title: 'Materi',
                  onTap: () {},
                ),
                MenuCard(
                  icon: Icons.bar_chart,
                  title: 'Progres',
                  onTap: () {},
                ),
                MenuCard(
                  icon: Icons.emoji_events,
                  title: 'Peringkat',
                  onTap: () {},
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}