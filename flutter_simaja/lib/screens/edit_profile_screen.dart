import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart';

class EditProfileScreen extends StatefulWidget {
  final Map<String, dynamic>? currentData;

  const EditProfileScreen({super.key, this.currentData});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  // Controller untuk menangkap inputan dari TextField
  final TextEditingController _namaController = TextEditingController();
  final TextEditingController _nimController = TextEditingController();
  final TextEditingController _kelasController = TextEditingController();
  final TextEditingController _prodiController = TextEditingController();
  final TextEditingController _jurusanController = TextEditingController();
  final TextEditingController _semesterController = TextEditingController();
  final TextEditingController _jkController = TextEditingController();
  final TextEditingController _alamatController = TextEditingController();

  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    // Mengisi form secara otomatis dengan data yang sudah ada sebelumnya
    if (widget.currentData != null) {
      _namaController.text = widget.currentData!['nama_lengkap'] ?? '';
      _nimController.text = widget.currentData!['nim'] ?? '';
      _kelasController.text = widget.currentData!['kelas'] ?? '';
      _prodiController.text = widget.currentData!['prodi'] ?? '';
      _jurusanController.text = widget.currentData!['jurusan'] ?? '';
      _semesterController.text = widget.currentData!['semester'] ?? '';
      _jkController.text = widget.currentData!['jenis_kelamin'] ?? '';
      _alamatController.text = widget.currentData!['alamat'] ?? '';
    }
  }

  @override
  void dispose() {
    _namaController.dispose();
    _nimController.dispose();
    _kelasController.dispose();
    _prodiController.dispose();
    _jurusanController.dispose();
    _semesterController.dispose();
    _jkController.dispose();
    _alamatController.dispose();
    super.dispose();
  }

  void _simpanProfil() async {
    setState(() => _isSubmitting = true);

    // Kumpulkan data ke dalam Map
    Map<String, dynamic> updateData = {
      'nama_lengkap': _namaController.text.trim(),
      'nim': _nimController.text.trim(),
      'kelas': _kelasController.text.trim(),
      'prodi': _prodiController.text.trim(),
      'jurusan': _jurusanController.text.trim(),
      'semester': _semesterController.text.trim(),
      'jenis_kelamin': _jkController.text.trim(),
      'alamat': _alamatController.text.trim(),
    };

    var response = await ApiService.updateProfile(updateData);

    setState(() => _isSubmitting = false);

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.green),
        );
        Navigator.pop(context, true); // Kembali ke ProfileScreen dan beri sinyal refresh
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.red),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgGrey,
      body: SingleChildScrollView(
        child: Column(
          children: [
            _buildHeader(context),
            const SizedBox(height: 16),
            _buildEditForm(context),
            const SizedBox(height: 32), 
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
          margin: const EdgeInsets.only(bottom: 40),
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
                      'Profil',
                      textAlign: TextAlign.center,
                      style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
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
            height: 80,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(15),
              boxShadow: const [
                BoxShadow(color: Colors.black12, blurRadius: 10, offset: Offset(0, 5)),
              ],
            ),
            child: Row(
              children: [
                const SizedBox(width: 16),
                const CircleAvatar(
                  radius: 24,
                  backgroundColor: Colors.grey,
                  child: Icon(Icons.person, color: Colors.white, size: 32),
                ),
                const SizedBox(width: 16),
                Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      widget.currentData?['nama_lengkap']?.toString().toUpperCase() ?? 'USER BARU',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                    Text(
                      'NIM. ${widget.currentData?['nim'] ?? '-'}',
                      style: const TextStyle(color: AppTheme.textGrey, fontSize: 12),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildEditForm(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(15),
          border: Border.all(color: Colors.grey.shade400),
        ),
        child: Column(
          children: [
            const Text(
              'Edit Informasi Profil',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textDark),
            ),
            const SizedBox(height: 24),
            const CircleAvatar(
              radius: 40,
              backgroundColor: Colors.white,
              child: Icon(Icons.account_circle, size: 80, color: Colors.black),
            ),
            const SizedBox(height: 24),
            _buildTextField('Masukkan Nama', _namaController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan NIM', _nimController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Kelas', _kelasController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Prodi', _prodiController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Jurusan', _jurusanController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Semester', _semesterController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Jenis Kelamin', _jkController),
            const SizedBox(height: 16),
            _buildTextField('Masukkan Alamat', _alamatController),
            const SizedBox(height: 24),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                OutlinedButton(
                  onPressed: _isSubmitting ? null : () => Navigator.pop(context),
                  style: OutlinedButton.styleFrom(
                    side: const BorderSide(color: Colors.grey),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                  child: const Text('Batal', style: TextStyle(color: Colors.grey)),
                ),
                const SizedBox(width: 12),
                ElevatedButton(
                  onPressed: _isSubmitting ? null : _simpanProfil,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.primaryGreen,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                  child: _isSubmitting 
                      ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                      : const Text('Simpan', style: TextStyle(color: Colors.white)),
                ),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _buildTextField(String hint, TextEditingController controller) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          hint,
          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
        ),
        const SizedBox(height: 4),
        Container(
          height: 40,
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey.shade400),
            borderRadius: BorderRadius.circular(8),
          ),
          child: TextField(
            controller: controller, // Menghubungkan controller
            decoration: const InputDecoration(
              border: InputBorder.none,
              contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 12), // Sesuaikan padding vertikal
            ),
          ),
        ),
      ],
    );
  }
}