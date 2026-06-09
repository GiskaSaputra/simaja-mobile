import 'package:flutter/material.dart';
import '../utils/theme.dart';
import '../services/api_service.dart'; // Import API Service

class ForgotScreen extends StatefulWidget {
  const ForgotScreen({super.key});

  @override
  State<ForgotScreen> createState() => _ForgotScreenState();
}

class _ForgotScreenState extends State<ForgotScreen> {
  final TextEditingController _emailController = TextEditingController();
  
  bool _isLoading = false; // Penanda loading

  // Fungsi Proses Lupa Password
  void _prosesForgot() async {
    String email = _emailController.text.trim();

    if (email.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Email wajib diisi!'), backgroundColor: Colors.red)
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    // Panggil API
    var response = await ApiService.forgotPassword(email);

    setState(() {
      _isLoading = false;
    });

    if (response['success']) {
      if (context.mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(response['message']), backgroundColor: Colors.green)
        );
        // Bisa langsung dikembalikan ke halaman login
        Navigator.pop(context);
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
    final height = MediaQuery.of(context).size.height;

    return Scaffold(
      backgroundColor: Colors.white, 
      body: Stack(
        children: [
          // 1. BACKGROUND HIJAU ATAS
          Container(
            height: height * 0.45,
            decoration: const BoxDecoration(
              color: AppTheme.primaryGreen,
              borderRadius: BorderRadius.vertical(
                bottom: Radius.circular(40),
              ),
            ),
          ),

          // 2. KONTEN UTAMA
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
                          
                          Expanded(
                            child: Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 30.0),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start, 
                                children: [
                                  const Text(
                                    'SIMAJA',
                                    style: TextStyle(color: Colors.white, fontSize: 32, fontWeight: FontWeight.bold),
                                  ),
                                  const Text(
                                    'Sistem Manajemen Study Jam',
                                    style: TextStyle(color: Colors.white, fontSize: 14),
                                  ),
                                  const SizedBox(height: 20),
                                  
                                  Center(
                                    child: Padding(
                                      padding: const EdgeInsets.only(right: 20.0),
                                      child: Image.asset('lib/assets/by_protik.png', height: 65),
                                    ),
                                  ),
                                  const SizedBox(height: 30),

                                  Card(
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                    elevation: 8,
                                    color: Colors.white,
                                    child: Padding(
                                      padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 30.0),
                                      child: Column(
                                        children: [
                                          Image.asset('lib/assets/logo_protik.png', height: 95),
                                          const SizedBox(height: 20),

                                          const Text(
                                            'No problem! Enter your email below and we will send instructions to reset your password',
                                            textAlign: TextAlign.center,
                                            style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: Colors.black87),
                                          ),
                                          const SizedBox(height: 24),

                                          TextField(
                                            controller: _emailController,
                                            textAlign: TextAlign.center, 
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

                                  // Tombol Send Instructions
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
                                      // Hubungkan onPressed ke fungsi _prosesForgot
                                      onPressed: _isLoading ? null : _prosesForgot, 
                                      child: _isLoading 
                                          ? const SizedBox(height: 24, width: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                                          : const Text('Send Instructions', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                    ),
                                  ),
                                  const SizedBox(height: 20),

                                  Center(
                                    child: GestureDetector(
                                      onTap: () {
                                        Navigator.pop(context); 
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