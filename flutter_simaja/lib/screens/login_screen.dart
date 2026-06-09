import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Import API Service yang baru dibuat
import 'register_screen.dart';
import 'main_screen.dart';
import 'forgot_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _usernameController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  
  bool _isLoading = false; // Variabel penanda status loading

  // Fungsi untuk memanggil API
  void _prosesLogin() async {
    setState(() {
      _isLoading = true; // Munculkan animasi berputar
    });

    String username = _usernameController.text;
    String password = _passwordController.text;

    // Validasi input kosong
    if (username.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Username dan Password tidak boleh kosong!'))
      );
      setState(() { _isLoading = false; });
      return;
    }

    // Tembak API
    var response = await ApiService.login(username, password);

    setState(() {
      _isLoading = false; // Matikan animasi berputar
    });

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.green)
        );
        Navigator.pushReplacement(
          context, 
          MaterialPageRoute(builder: (context) => const MainScreen())
        );
      }
    } else {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.red)
        );
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
          // 1. BACKGROUND HIJAU ATAS (Tetap diam di belakang)
          Container(
            height: height * 0.45, // Mengambil 45% dari tinggi layar
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(
                bottom: Radius.circular(40), // Melengkung di sudut bawah
              ),
            ),
          ),

          // 2. KONTEN UTAMA DAN SEGITIGA BAWAH (Sekarang semua ikut ke-scroll)
          SafeArea(
            child: LayoutBuilder(
              builder: (context, constraints) {
                return SingleChildScrollView(
                  child: ConstrainedBox(
                    constraints: BoxConstraints(
                      minHeight: constraints.maxHeight, // Memastikan area minimal setinggi layar HP
                    ),
                    child: IntrinsicHeight(
                      child: Column(
                        children: [
                          
                          // --- BAGIAN KONTEN ATAS (Form, Teks, Tombol) ---
                          // Menggunakan Expanded agar jika ada ruang kosong, konten ini akan mendorong segitiga mentok ke bawah
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

                                  // Card Form
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

                                          // Input Username
                                          TextField(
                                            controller: _usernameController,
                                            textAlign: TextAlign.center,
                                            decoration: InputDecoration(
                                              hintText: 'Username',
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
                                          ),
                                          const SizedBox(height: 16),

                                          // Input Password
                                          TextField(
                                            controller: _passwordController,
                                            obscureText: true,
                                            textAlign: TextAlign.center, 
                                            decoration: InputDecoration(
                                              hintText: 'Password',
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
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 25),

                                  // Tombol Login (Di Luar Card)
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
                                      // Logika tombol disable saat loading
                                      onPressed: _isLoading ? null : _prosesLogin, 
                                      child: _isLoading 
                                          ? const SizedBox(
                                              height: 24,
                                              width: 24,
                                              child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3),
                                            )
                                          : const Text('Login', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                    ),
                                  ),
                                  const SizedBox(height: 20),

                                  // Teks Link Bawah (Need an account & Forgot Password)
                                  Center(
                                    child: Column(
                                      children: [
                                        GestureDetector(
                                          onTap: () {
                                            Navigator.push(
                                              context,
                                              MaterialPageRoute(builder: (context) => const RegisterScreen()),
                                            );
                                          },
                                          child: const Text(
                                            'Need an account?',
                                            style: TextStyle(color: AppTheme.primaryGreen, fontWeight: FontWeight.w500),
                                          ),
                                        ),
                                        const SizedBox(height: 8),
                                        GestureDetector(
                                          onTap: () {
                                            Navigator.push(
                                              context,
                                              MaterialPageRoute(builder: (context) => const ForgotScreen()),
                                            );
                                          },
                                          child: const Text(
                                            'Forget your password?',
                                            style: TextStyle(color: AppTheme.primaryGreen, fontWeight: FontWeight.bold),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),

                          // --- BAGIAN SEGITIGA BAWAH ---
                          SizedBox(
                            width: double.infinity,
                            // Hapus batasan height agar tidak kepotong, biarkan proporsinya alami
                            child: Image.asset(
                              'lib/assets/segitiga.png',
                              fit: BoxFit.fitWidth, // Ganti jadi fitWidth agar proporsi segitiga utuh dari kiri ke kanan
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
}