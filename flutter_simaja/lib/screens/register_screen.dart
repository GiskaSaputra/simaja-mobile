import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Wajib import ApiService

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _repeatPasswordController = TextEditingController();

  bool _isLoading = false; // Penanda loading

  // Fungsi Proses Register
  void _prosesRegister() async {
    String email = _emailController.text.trim();
    String username = _usernameController.text.trim();
    String password = _passwordController.text;
    String repeatPassword = _repeatPasswordController.text;

    // Validasi Kosong
    if (email.isEmpty || username.isEmpty || password.isEmpty || repeatPassword.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Semua kolom wajib diisi!'), backgroundColor: Colors.red));
      return;
    }

    // Validasi Password Cocok
    if (password != repeatPassword) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Password dan Repeat Password tidak sama!'), backgroundColor: Colors.red));
      return;
    }

    setState(() {
      _isLoading = true;
    });

    // Panggil API Register
    var response = await ApiService.register(username, email, password);

    setState(() {
      _isLoading = false;
    });

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response['message']), backgroundColor: Colors.green));
        // Jika sukses, kembali ke halaman login
        Navigator.pop(context);
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response['message']), backgroundColor: Colors.red));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    // Mengambil ukuran tinggi layar untuk membagi background
    final height = MediaQuery.of(context).size.height;

    return Scaffold(
      backgroundColor: Colors.white, // Background dasar bawah (Putih)
      body: Stack(
        children: [
          // 1. BACKGROUND HIJAU ATAS (Melengkung di sudut bawah)
          Container(
            height: height * 0.45,
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(
                bottom: Radius.circular(40), 
              ),
            ),
          ),

          // 2. KONTEN UTAMA DAN SEGITIGA BAWAH (Ikut ke-scroll)
          SafeArea(
            child: LayoutBuilder(
              builder: (context, constraints) {
                return SingleChildScrollView(
                  child: ConstrainedBox(
                    constraints: BoxConstraints(
                      minHeight: constraints.maxHeight, 
                    ),
                    child: IntrinsicHeight(
                      child: Column(
                        children: [
                          
                          // --- BAGIAN KONTEN ATAS (Form, Teks, Tombol) ---
                          Expanded(
                            child: Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 30.0),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start, // Rata kiri untuk Header
                                children: [
                                  // Header
                                  const Text(
                                    'SIMAJA',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 32,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                                  const Text(
                                    'Sistem Manajemen Study Jam',
                                    style: TextStyle(color: Colors.white, fontSize: 14),
                                  ),
                                  const SizedBox(height: 20),
                                  
                                  // Logo "by PROTIC" 
                                  Center(
                                    child: Padding(
                                      padding: const EdgeInsets.only(right: 20.0), 
                                      child: Image.asset(
                                        'lib/assets/by_protik.png',
                                        height: 65, 
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 30),

                                  // Card Form Register
                                  Card(
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                    elevation: 8,
                                    color: Colors.white,
                                    child: Padding(
                                      padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 30.0),
                                      child: Column(
                                        children: [
                                          // Logo di dalam Card
                                          Image.asset(
                                            'lib/assets/logo_protik.png',
                                            height: 95, 
                                          ),
                                          const SizedBox(height: 30),

                                          // Input Email
                                          _buildTextField(
                                            controller: _emailController,
                                            hintText: 'Email',
                                            isObscure: false,
                                          ),
                                          const SizedBox(height: 16),

                                          // Input Username
                                          _buildTextField(
                                            controller: _usernameController,
                                            hintText: 'Username',
                                            isObscure: false,
                                          ),
                                          const SizedBox(height: 16),

                                          // Input Password
                                          _buildTextField(
                                            controller: _passwordController,
                                            hintText: 'Password',
                                            isObscure: true,
                                          ),
                                          const SizedBox(height: 16),

                                          // Input Repeat Password
                                          _buildTextField(
                                            controller: _repeatPasswordController,
                                            hintText: 'Repeat Password',
                                            isObscure: true,
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 25),

                                  // Tombol Daftar (Di Luar Card)
                                  SizedBox(
                                    width: double.infinity,
                                    height: 50,
                                    child: ElevatedButton(
                                      style: ElevatedButton.styleFrom(
                                        backgroundColor: AppTheme.primaryGreen,
                                        foregroundColor: Colors.white,
                                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                                        elevation: 4,
                                      ),
                                      onPressed: _isLoading ? null : _prosesRegister, // Hubungkan fungsi disini
                                      child: _isLoading 
                                          ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                                          : const Text('Daftar', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                    ),
                                  ),
                                  const SizedBox(height: 20),

                                  // Teks Link Bawah (Already registered? Sign in)
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      const Text(
                                        'Already registered? ',
                                        style: TextStyle(fontWeight: FontWeight.w600),
                                      ),
                                      GestureDetector(
                                        onTap: () {
                                          Navigator.pop(context); // Kembali ke halaman Login
                                        },
                                        child: const Text(
                                          'Sign in',
                                          style: TextStyle(color: AppTheme.primaryGreen, fontWeight: FontWeight.bold),
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ),

                          // --- BAGIAN SEGITIGA BAWAH ---
                          SizedBox(
                            width: double.infinity,
                            child: Image.asset(
                              'lib/assets/segitiga.png',
                              fit: BoxFit.fitWidth, 
                              alignment: Alignment.bottomCenter,
                            ),
                          ),

                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  // Helper Widget untuk membuat TextField agar kodingan tidak terlalu panjang dan berulang
  Widget _buildTextField({required TextEditingController controller, required String hintText, required bool isObscure}) {
    return TextField(
      controller: controller,
      obscureText: isObscure,
      textAlign: TextAlign.center, // Teks rata tengah sesuai desain
      decoration: InputDecoration(
        hintText: hintText,
        hintStyle: const TextStyle(color: Colors.grey),
        contentPadding: const EdgeInsets.symmetric(vertical: 16),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: Colors.grey),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: const BorderSide(color: AppTheme.primaryGreen, width: 2),
        ),
      ),
    );
  } 
}