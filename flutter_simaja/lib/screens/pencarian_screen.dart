import 'package:flutter/material.dart';
import '../utils/theme.dart';

class PencarianScreen extends StatefulWidget {
  final String? initialQuery;
  const PencarianScreen({super.key, this.initialQuery});

  @override
  State<PencarianScreen> createState() => _PencarianScreenState();
}

class _PencarianScreenState extends State<PencarianScreen> {
  late TextEditingController _searchController;
  String _currentQuery = '';

  // Dummy data
  final List<Map<String, String>> _allMateri = [
    {'title': 'WEB Basic', 'subtitle': 'Dasar HTML & CSS'},
    {'title': 'WEB Advance', 'subtitle': 'Framework PHP & JS'},
    {'title': 'Mobile Basic', 'subtitle': 'Pengenalan Flutter'},
    {'title': 'Mobile Advance', 'subtitle': 'State Management'},
  ];

  final List<Map<String, String>> _allJadwal = [
    {'title': 'WEB Basic', 'subtitle': 'Introduction HTML'},
    {'title': 'WEB Advance', 'subtitle': 'Introduction PHP'},
    {'title': 'Mobile Basic', 'subtitle': 'Introduction Dart'},
    {'title': 'Mobile Advance', 'subtitle': 'API Integration'},
  ];

  List<Map<String, String>> _filteredMateri = [];
  List<Map<String, String>> _filteredJadwal = [];

  @override
  void initState() {
    super.initState();
    _currentQuery = widget.initialQuery ?? '';
    _searchController = TextEditingController(text: _currentQuery);
    _filterResults(_currentQuery);
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _filterResults(String query) {
    setState(() {
      _currentQuery = query;
      if (query.isEmpty) {
        _filteredMateri = [];
        _filteredJadwal = [];
      } else {
        final lowerQuery = query.toLowerCase();
        _filteredMateri = _allMateri.where((m) {
          return m['title']!.toLowerCase().contains(lowerQuery) ||
              m['subtitle']!.toLowerCase().contains(lowerQuery);
        }).toList();

        _filteredJadwal = _allJadwal.where((j) {
          return j['title']!.toLowerCase().contains(lowerQuery) ||
              j['subtitle']!.toLowerCase().contains(lowerQuery);
        }).toList();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHeader(context),
            const SizedBox(height: 24),
            _buildSearchResults(),
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
            'Ketik kata kunci untuk mulai mencari (misal: "web" atau "mobile")',
            textAlign: TextAlign.center,
            style: TextStyle(color: AppTheme.textGrey),
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
                  const TextSpan(text: 'Hasil Pencari untuk: '),
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

          if (_filteredMateri.isNotEmpty) ...[
            // Materi Section
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
              ),
              child: Column(
                children: _filteredMateri.asMap().entries.map((entry) {
                  final index = entry.key;
                  final item = entry.value;
                  return Column(
                    children: [
                      _buildResultItem(
                        title: item['title']!,
                        subtitle: item['subtitle']!,
                        buttonText: 'Lihat Detail',
                        onPressed: () {},
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

          if (_filteredJadwal.isNotEmpty) ...[
            // Jadwal Section
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
              ),
              child: Column(
                children: _filteredJadwal.asMap().entries.map((entry) {
                  final index = entry.key;
                  final item = entry.value;
                  return Column(
                    children: [
                      _buildResultItem(
                        title: item['title']!,
                        subtitle: item['subtitle']!,
                        buttonText: 'Lihat Jadwal',
                        isSolidButton: true,
                        onPressed: () {},
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
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                  ),
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: const TextStyle(
                    color: AppTheme.textGrey,
                    fontSize: 12,
                  ),
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
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 8,
                    ),
                    minimumSize:
                        Size.zero, // Overrides default size constraints
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
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 8,
                    ),
                    minimumSize: Size.zero,
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        buttonText,
                        style: const TextStyle(
                          color: AppTheme.secondaryGreen,
                          fontSize: 12,
                        ),
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
