import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'utils/theme.dart';
import 'screens/login_screen.dart';
import 'screens/main_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  bool _isLoggedIn = false;
  bool _isLoading = true; // Penanda saat aplikasi sedang mengecek memori HP

  @override
  void initState() {
    super.initState();
    _checkLoginStatus();
  }

  // Fungsi untuk mengecek apakah user_id sudah tersimpan di HP
  void _checkLoginStatus() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? userId = prefs.getString('user_id');
    
    setState(() {
      _isLoggedIn = userId != null; // Jika userId ada isinya, berarti true (sudah login)
      _isLoading = false; // Selesai loading
    });
  }

  @override
  Widget build(BuildContext context) {
    // Tampilkan layar kosong berputar sebentar saat mengecek memori
    if (_isLoading) {
      return const MaterialApp(
        debugShowCheckedModeBanner: false,
        home: Scaffold(
          backgroundColor: AppTheme.primaryGreen,
          body: Center(child: CircularProgressIndicator(color: Colors.white)),
        ),
      );
    }

    return MaterialApp(
      title: 'SIMAJA Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: AppTheme.primaryGreen),
        useMaterial3: true,
      ),
      // LOGIKA CERDAS: Jika sudah login, ke MainScreen. Jika belum, ke LoginScreen.
      home: _isLoggedIn ? const MainScreen() : const LoginScreen(),
    );
  }
}