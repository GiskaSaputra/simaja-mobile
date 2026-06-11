import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/foundation.dart';

class ApiService {
  // Pengaturan IP Otomatis (Chrome vs Emulator)
  static const String baseUrl = kIsWeb 
      ? 'http://localhost:8080/api' 
      : 'http://10.0.2.2:8080/api';

  // ==========================================================================
  // 1. FUNGSI AUTENTIKASI (LOGIN, REGISTER, FORGOT PASSWORD)
  // ==========================================================================
  
  static Future<Map<String, dynamic>> login(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'login': username, 'password': password}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4. Cek terminal CI4.'};
      }

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        SharedPreferences prefs = await SharedPreferences.getInstance();
        await prefs.setString('user_id', data['data']['id'].toString());
        await prefs.setString('username', data['data']['username']);
        return {'success': true, 'message': data['message']};
      } else {
        String errorMsg = data['messages']?['error'] ?? data['message'] ?? 'Login gagal';
        return {'success': false, 'message': errorMsg};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server! Pastikan CI4 menyala.'};
    }
  }

  static Future<Map<String, dynamic>> register(String username, String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'username': username, 'email': email, 'password': password}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4. Cek terminal CI4.'};
      }

      final data = jsonDecode(response.body);

      if (response.statusCode == 201 || response.statusCode == 200) {
        return {'success': true, 'message': data['message'] ?? 'Registrasi berhasil!'};
      } else {
        String errorMsg = 'Gagal melakukan registrasi';
        if (data['messages'] != null) {
          if (data['messages']['email'] != null) errorMsg = data['messages']['email'];
          else if (data['messages']['username'] != null) errorMsg = data['messages']['username'];
          else if (data['messages']['password'] != null) errorMsg = data['messages']['password'];
          else if (data['messages']['error'] != null) errorMsg = data['messages']['error'];
        }
        return {'success': false, 'message': errorMsg};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server! Pastikan CI4 menyala.'};
    }
  }

  static Future<Map<String, dynamic>> forgotPassword(String email) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/forgot'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4.'};
      }

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message']};
      } else {
        return {'success': false, 'message': data['messages']?['error'] ?? data['message'] ?? 'Gagal memproses permintaan'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

  // ==========================================================================
  // 2. FUNGSI JADWAL & ABSENSI
  // ==========================================================================

  static Future<List<dynamic>> getJadwal() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      String url = '$baseUrl/jadwal';
      if (userId != null) url += '?user_id=$userId';

      final response = await http.get(Uri.parse(url)).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'] ?? [];
      } else {
        return [];
      }
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> daftarJadwal(String jadwalId) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/jadwal/daftar'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'user_id': userId, 'jadwal_id': jadwalId}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html')) return {'success': false, 'message': 'Terjadi error di server CI4.'};

      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message']};
      } else {
        return {'success': false, 'message': data['messages']?['error'] ?? data['message'] ?? 'Gagal mendaftar'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

  static Future<Map<String, dynamic>> kirimAbsensi(String jadwalId, String status) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/jadwal/absensi'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'user_id': userId, 'jadwal_id': jadwalId, 'status': status}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html')) return {'success': false, 'message': 'Terjadi error di server CI4.'};

      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message']};
      } else {
        return {'success': false, 'message': data['messages']?['error'] ?? data['message'] ?? 'Gagal mengirim absensi'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

  // ==========================================================================
  // 3. FUNGSI MATERI (LMS)
  // ==========================================================================

  static Future<List<dynamic>> getMateri() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      String url = '$baseUrl/materi';
      if (userId != null) url += '?user_id=$userId';

      final response = await http.get(Uri.parse(url)).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'] ?? [];
      } else {
        return [];
      }
    } catch (e) {
      return [];
    }
  }

  static Future<Map<String, dynamic>> markComplete(String pertemuanId) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/materi/complete'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'user_id': userId, 'pertemuan_id': pertemuanId}),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html')) return {'success': false, 'message': 'Terjadi error di CI4.'};

      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message']};
      } else {
        return {'success': false, 'message': data['messages']?['error'] ?? data['message'] ?? 'Gagal mencatat progres'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

  // ==========================================================================
  // 4. FUNGSI PROFILE & UPDATE BIODATA
  // ==========================================================================

  static Future<Map<String, dynamic>?> getProfile() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');
      if (userId == null) return null;

      final response = await http.get(Uri.parse('$baseUrl/profile?user_id=$userId')).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'];
      }
    } catch (e) {
      print("Error Get Profile: $e");
    }
    return null;
  }

  static Future<Map<String, dynamic>> updateProfile(Map<String, dynamic> profileData) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');
      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      profileData['user_id'] = userId; // Sisipkan user ID

      final response = await http.post(
        Uri.parse('$baseUrl/profile/update'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(profileData),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html')) return {'success': false, 'message': 'Terjadi error di CI4.'};

      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message']};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Gagal update profile'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

  // ==========================================================================
  // 5. FUNGSI PROGRES & PERINGKAT (LEADERBOARD)
  // ==========================================================================

  static Future<Map<String, dynamic>?> getProgres() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');
      if (userId == null) return null;

      final response = await http.get(Uri.parse('$baseUrl/progres?user_id=$userId')).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'];
      }
    } catch (e) {
      print("Error Get Progres: $e");
    }
    return null;
  }

  static Future<Map<String, dynamic>?> getPeringkat() async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/peringkat')).timeout(const Duration(seconds: 10));
      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'];
      }
    } catch (e) {
      print("Error Get Peringkat: $e");
    }
    return null;
  }

  // ==========================================================================
  // 6. FUNGSI QUIZ
  // ==========================================================================

  // Mendapatkan daftar pertemuan untuk materi tertentu beserta skor quiznya
  static Future<List<dynamic>> getQuizList(String materiId) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');
      if (userId == null) return [];

      final response = await http.get(Uri.parse('$baseUrl/quiz/pertemuan?materi_id=$materiId&user_id=$userId'))
                                 .timeout(const Duration(seconds: 10));
      
      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'] ?? [];
      }
    } catch (e) {
      print("Error Get Quiz List: $e");
    }
    return [];
  }

  // Mendapatkan daftar soal dan opsi jawaban
  static Future<List<dynamic>> getSoalQuiz(String pertemuanId) async {
    try {
      final response = await http.get(Uri.parse('$baseUrl/quiz/mulai?pertemuan_id=$pertemuanId'))
                                 .timeout(const Duration(seconds: 10));
      
      if (response.statusCode == 200) {
        return jsonDecode(response.body)['data'] ?? [];
      }
    } catch (e) {
      print("Error Get Soal Quiz: $e");
    }
    return [];
  }

  // Mengirim jawaban dan menerima skor akhir
  static Future<Map<String, dynamic>> submitQuiz(String pertemuanId, Map<String, dynamic> jawabanUser) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');
      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/quiz/submit'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'user_id': userId,
          'pertemuan_id': pertemuanId,
          'jawaban': jawabanUser // Format: {"soal_id1": "jawaban_id1", "soal_id2": "jawaban_id2"}
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html')) return {'success': false, 'message': 'Terjadi error di CI4.'};

      final data = jsonDecode(response.body);
      if (response.statusCode == 200) {
        return {'success': true, 'message': data['message'], 'skor_akhir': data['skor_akhir']};
      } else {
        return {'success': false, 'message': data['message'] ?? 'Gagal mensubmit quiz'};
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal terhubung ke server!'};
    }
  }

}