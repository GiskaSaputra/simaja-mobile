import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart'; // Library yang baru diinstall
import '../utils/theme.dart';
import '../services/api_service.dart';

class EditProfileScreen extends StatefulWidget {
  final Map<String, dynamic>? currentData;
  const EditProfileScreen({super.key, this.currentData});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final TextEditingController _namaController = TextEditingController();
  final TextEditingController _nimController = TextEditingController();
  final TextEditingController _kelasController = TextEditingController();
  final TextEditingController _prodiController = TextEditingController();
  final TextEditingController _jurusanController = TextEditingController();
  final TextEditingController _semesterController = TextEditingController();
  final TextEditingController _jkController = TextEditingController();
  final TextEditingController _alamatController = TextEditingController();

  File? _imageFile; // Penampung foto dari galeri
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    if (widget.currentData != null) {
      _namaController.text = widget.currentData!['nama_lengkap']?.toString() ?? '';
      _nimController.text = widget.currentData!['nim']?.toString() ?? '';
      _kelasController.text = widget.currentData!['kelas']?.toString() ?? '';
      _prodiController.text = widget.currentData!['prodi']?.toString() ?? '';
      _jurusanController.text = widget.currentData!['jurusan']?.toString() ?? '';
      _semesterController.text = widget.currentData!['semester']?.toString() ?? '';
      _jkController.text = widget.currentData!['jenis_kelamin']?.toString() ?? '';
      _alamatController.text = widget.currentData!['alamat']?.toString() ?? '';
    }
  }

  // Fungsi membuka galeri HP
  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery, imageQuality: 50);

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  void _simpanProfil() async {
    setState(() => _isSubmitting = true);

    // Kumpulkan data teks (Harus berupa String agar sesuai format MultipartRequest)
    Map<String, String> updateData = {
      'nama_lengkap': _namaController.text.trim(),
      'nim': _nimController.text.trim(),
      'kelas': _kelasController.text.trim(),
      'prodi': _prodiController.text.trim(),
      'jurusan': _jurusanController.text.trim(),
      'semester': _semesterController.text.trim(),
      'jenis_kelamin': _jkController.text.trim(),
      'alamat': _alamatController.text.trim(),
    };

    // Panggil API dengan melampirkan file foto
    var response = await ApiService.updateProfile(updateData, imageFile: _imageFile);

    setState(() => _isSubmitting = false);

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response['message']), backgroundColor: Colors.green));
        Navigator.pop(context, true); 
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response['message']), backgroundColor: Colors.red));
      }
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
            borderRadius: BorderRadius.only(bottomLeft: Radius.circular(30), bottomRight: Radius.circular(30)),
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
                    child: Text('Edit Profil', textAlign: TextAlign.center, style: TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                  ),
                ),
                const SizedBox(width: 48), 
              ],
            ),
          ),
        ),
        Positioned(
          bottom: 0, left: 24, right: 24,
          child: Container(
            height: 80,
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(15),
              boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 10, offset: Offset(0, 5))],
            ),
            child: Row(
              children: [
                const SizedBox(width: 16),
                // Avatar Mini di Header
                CircleAvatar(
                  radius: 24,
                  backgroundColor: Colors.grey.shade300,
                  backgroundImage: _imageFile != null
                      ? FileImage(_imageFile!) // Jika baru pilih foto
                      : (widget.currentData != null && widget.currentData!['foto'] != null && widget.currentData!['foto'] != 'default.png')
                          ? NetworkImage('${ApiService.baseImageUrl}${widget.currentData!['foto']}') as ImageProvider
                          : null,
                  child: (_imageFile == null && (widget.currentData == null || widget.currentData!['foto'] == null || widget.currentData!['foto'] == 'default.png'))
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
                        widget.currentData?['nama_lengkap']?.toString().toUpperCase() ?? 'USER BARU',
                        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                        maxLines: 1, overflow: TextOverflow.ellipsis,
                      ),
                      Text('NIM. ${widget.currentData?['nim']?.toString() ?? '-'}', style: const TextStyle(color: AppTheme.textGrey, fontSize: 12)),
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
            const Text('Informasi Biodata', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.textDark)),
            const SizedBox(height: 24),
            
            // FOTO PROFIL UTAMA (Bisa Diklik)
            GestureDetector(
              onTap: _pickImage,
              child: Stack(
                alignment: Alignment.bottomRight,
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Colors.grey.shade200,
                    backgroundImage: _imageFile != null
                        ? FileImage(_imageFile!)
                        : (widget.currentData != null && widget.currentData!['foto'] != null && widget.currentData!['foto'] != 'default.png')
                            ? NetworkImage('${ApiService.baseImageUrl}${widget.currentData!['foto']}') as ImageProvider
                            : null,
                    child: (_imageFile == null && (widget.currentData == null || widget.currentData!['foto'] == null || widget.currentData!['foto'] == 'default.png'))
                        ? const Icon(Icons.person, size: 60, color: Colors.grey)
                        : null,
                  ),
                  Container(
                    padding: const EdgeInsets.all(4),
                    decoration: const BoxDecoration(color: AppTheme.primaryGreen, shape: BoxShape.circle),
                    child: const Icon(Icons.camera_alt, color: Colors.white, size: 20),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 8),
            const Text('Ketuk foto untuk mengubah', style: TextStyle(fontSize: 10, color: Colors.grey)),
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
                  style: OutlinedButton.styleFrom(side: const BorderSide(color: Colors.grey), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10))),
                  child: const Text('Batal', style: TextStyle(color: Colors.grey)),
                ),
                const SizedBox(width: 12),
                ElevatedButton(
                  onPressed: _isSubmitting ? null : _simpanProfil,
                  style: ElevatedButton.styleFrom(backgroundColor: AppTheme.primaryGreen, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10))),
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
        Text(hint, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
        const SizedBox(height: 4),
        Container(
          height: 40,
          decoration: BoxDecoration(border: Border.all(color: Colors.grey.shade400), borderRadius: BorderRadius.circular(8)),
          child: TextField(
            controller: controller, 
            decoration: const InputDecoration(border: InputBorder.none, contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 12)),
          ),
        ),
      ],
    );
  }
}