import 'package:flutter/material.dart';
import '../utils/theme.dart';

class ForgotScreen extends StatefulWidget {
  const ForgotScreen({super.key});

  @override
  State<ForgotScreen> createState() => _ForgotScreenState();
}

class _ForgotScreenState extends State<ForgotScreen> {
  final TextEditingController _emailController = TextEditingController();

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

                                  // Card Form Lupa Password
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
                                          const SizedBox(height: 20),

                                          // Teks Instruksi
                                          const Text(
                                            'No problem! Enter your email below and we will send instructions to reset your password',
                                            textAlign: TextAlign.center,
                                            style: TextStyle(
                                              fontSize: 14,
                                              fontWeight: FontWeight.w500,
                                              color: Colors.black87,
                                            ),
                                          ),
                                          const SizedBox(height: 24),

                                          // Input Email
                                          TextField(
                                            controller: _emailController,
                                            textAlign: TextAlign.center, // Teks rata tengah sesuai desain
                                            decoration: InputDecoration(
                                              hintText: 'Email',
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

                                  // Tombol Send Instructions (Di Luar Card)
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
                                      onPressed: () {
                                        // TODO: Logika kirim email reset password
                                      },
                                      child: const Text('Send Instructions', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                    ),
                                  ),
                                  const SizedBox(height: 20),

                                  // Teks Link Bawah (Back to login)
                                  Center(
                                    child: GestureDetector(
                                      onTap: () {
                                        Navigator.pop(context); // Kembali ke halaman Login
                                      },
                                      child: const Text(
                                        'Back to login',
                                        style: TextStyle(color: AppTheme.primaryGreen, fontWeight: FontWeight.w500),
                                      ),
                                    ),
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
}