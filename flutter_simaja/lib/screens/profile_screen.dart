import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';
import 'edit_profile_screen.dart';
import 'login_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  Map<String, dynamic>? _profileData;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchProfile();
  }

  Future<void> _fetchProfile() async {
    setState(() => _isLoading = true);
    var data = await ApiService.getProfile();
    setState(() {
      _profileData = data;
      _isLoading = false;
    });
  }

  void _logout() async {
    // Hapus sesi lokal
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    if (context.mounted) {
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (context) => const LoginScreen()),
        (route) => false,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryGreen))
          : RefreshIndicator(
              onRefresh: _fetchProfile,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  children: [
                    _buildHeader(context),
                    const SizedBox(height: 16),
                    _buildInfoProfil(context),
                    const SizedBox(height: 24),
                    _buildStatsGrid(),
                    const SizedBox(height: 24),
                    _buildLogoutButton(context),
                    const SizedBox(height: 32), 
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    // Memastikan tipe datanya Map<String, dynamic>
    Map<String, dynamic> biodata = {};
    if (_profileData != null && _profileData!['biodata'] != null) {
      biodata = Map<String, dynamic>.from(_profileData!['biodata']);
    }

    String namaLengkap = biodata['nama_lengkap']?.toString() ?? 'User Baru';
    String nim = biodata['nim']?.toString() ?? 'Belum ada NIM';

    return Stack(
      alignment: Alignment.bottomCenter,
      children: [
        Container(
          width: double.infinity,
          height: 180,
          margin: const EdgeInsets.only(bottom: 40),
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
              'Profil',
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
            height: 80,
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
            child: Row(
              children: [
                const SizedBox(width: 16),
                
                // MENAMPILKAN FOTO PROFIL DARI DATABASE CI4
                CircleAvatar(
                  radius: 24,
                  backgroundColor: Colors.grey.shade300,
                  backgroundImage: (biodata['foto'] != null && biodata['foto'] != 'default.png')
                      ? NetworkImage('${ApiService.baseImageUrl}${biodata['foto']}')
                      : null,
                  child: (biodata['foto'] == null || biodata['foto'] == 'default.png')
                      ? const Icon(Icons.person, color: Colors.white, size: 32)
                      : null,
                ),

                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        namaLengkap.toUpperCase(),
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      Text(
                        'NIM. $nim',
                        style: const TextStyle(color: AppTheme.textGrey, fontSize: 12),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildInfoProfil(BuildContext context) {
    Map<String, dynamic> biodata = {};
    if (_profileData != null && _profileData!['biodata'] != null) {
      biodata = Map<String, dynamic>.from(_profileData!['biodata']);
    }

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
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Informasi Profil',
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textDark),
                ),
                IconButton(
                  icon: const Icon(Icons.edit_square, color: AppTheme.textDark),
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => EditProfileScreen(currentData: biodata),
                      ),
                    ).then((_) => _fetchProfile()); 
                  },
                ),
              ],
            ),
            const SizedBox(height: 16),
            _buildInfoRow('Kelas', biodata['kelas']?.toString() ?? '-'),
            const SizedBox(height: 12),
            _buildInfoRow('Prodi', biodata['prodi']?.toString() ?? '-'),
            const SizedBox(height: 12),
            _buildInfoRow('Jurusan', biodata['jurusan']?.toString() ?? '-'),
            const SizedBox(height: 12),
            _buildInfoRow('Semester', biodata['semester']?.toString() ?? '-'),
            const SizedBox(height: 12),
            _buildInfoRow('Jenis Kelamin', biodata['jenis_kelamin']?.toString() ?? '-'),
            const SizedBox(height: 12),
            _buildInfoRow('Alamat', biodata['alamat']?.toString() ?? '-'),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        border: Border.all(color: Colors.grey.shade300),
        borderRadius: BorderRadius.circular(10),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Expanded(
            flex: 2,
            child: Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
            ),
          ),
          Expanded(
            flex: 3,
            child: Text(
              value,
              textAlign: TextAlign.right,
              style: const TextStyle(color: AppTheme.textGrey, fontSize: 12, fontWeight: FontWeight.bold),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildStatsGrid() {
    Map<String, dynamic> stats = {};
    if (_profileData != null && _profileData!['stats'] != null) {
      stats = Map<String, dynamic>.from(_profileData!['stats']);
    }
    
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: GridView.count(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        crossAxisCount: 2,
        mainAxisSpacing: 16,
        crossAxisSpacing: 16,
        childAspectRatio: 2.5,
        children: [
          _buildStatCard(Icons.emoji_events_outlined, '${stats['poin'] ?? 0}', 'Total Poin', AppTheme.secondaryGreen),
          _buildStatCard(Icons.access_time, '${stats['jam'] ?? 0}', 'Jam Belajar', AppTheme.primaryGreen),
          _buildStatCard(Icons.menu_book_outlined, '${stats['selesai'] ?? 0}', 'Materi\nSelesai', AppTheme.primaryGreen),
          _buildStatCard(Icons.star_outline, '#${stats['ranking'] ?? '-'}', 'Rangking', AppTheme.secondaryGreen),
        ],
      ),
    );
  }

  Widget _buildStatCard(IconData icon, String value, String label, Color iconColor) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: Colors.black, width: 1.5),
      ),
      child: Row(
        children: [
          Icon(icon, color: iconColor, size: 28),
          const SizedBox(width: 8),
          Expanded(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  value,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                ),
                Text(
                  label,
                  style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold),
                  maxLines: 2,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLogoutButton(BuildContext context) {
    return ElevatedButton(
      onPressed: _logout,
      style: ElevatedButton.styleFrom(
        backgroundColor: AppTheme.redLogout,
        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 12),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
      ),
      child: const Text(
        'Logout',
        style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
      ),
    );
  }
}