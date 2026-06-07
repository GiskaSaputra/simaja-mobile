import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'pencarian_screen.dart';

class PeringkatScreen extends StatelessWidget {
  const PeringkatScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHeader(context),
            const SizedBox(height: 16),
            _buildTopThree(),
            const SizedBox(height: 16),
            _buildListPeringkat(),
            const SizedBox(height: 24), // Bottom padding
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
                const SizedBox(width: 48), // Balance for the back button
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

  Widget _buildTopThree() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.end,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          _buildTopRankCard(rank: 2, name: 'Ale', jurusan: 'Teknik Informatika', points: 500, isCenter: false),
          const SizedBox(width: 8),
          _buildTopRankCard(rank: 1, name: 'Nazril', jurusan: 'Teknik Informatika', points: 500, isCenter: true),
          const SizedBox(width: 8),
          _buildTopRankCard(rank: 2, name: 'Giska', jurusan: 'Teknik Informatika', points: 500, isCenter: false),
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
                BoxShadow(
                  color: Colors.black12,
                  blurRadius: 10,
                  offset: Offset(0, 5),
                )
              ]
            : null,
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              shape: BoxShape.circle,
            ),
            child: Text(
              '#$rank',
              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            name,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            overflow: TextOverflow.ellipsis,
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
            child: Text(
              '$points Poin',
              style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildListPeringkat() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Column(
        children: [
          _buildListItem(rank: 4, name: 'Daffa', initial: 'D', jurusan: 'Teknik Informatika. Komputer & Bisnis', points: 370),
          const SizedBox(height: 12),
          _buildListItem(rank: 5, name: 'Bima', initial: 'B', jurusan: 'Teknik Informatika. Komputer & Bisnis', points: 370),
          const SizedBox(height: 12),
          _buildListItem(rank: 6, name: 'Chandra', initial: 'C', jurusan: 'Teknik Informatika. Komputer & Bisnis', points: 370),
          const SizedBox(height: 12),
          _buildListItem(rank: 7, name: 'Ifant', initial: 'I', jurusan: 'Teknik Informatika. Komputer & Bisnis', points: 370),
        ],
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
          Text(
            '#$rank',
            style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey),
          ),
          const SizedBox(width: 16),
          CircleAvatar(
            backgroundColor: Colors.grey.shade200,
            radius: 16,
            child: Text(
              initial,
              style: const TextStyle(
                color: AppTheme.secondaryGreen,
                fontWeight: FontWeight.bold,
              ),
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  name,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                ),
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
                style: const TextStyle(
                  color: AppTheme.secondaryGreen,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Text(
                ' Poin',
                style: TextStyle(color: Colors.grey, fontSize: 12),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
