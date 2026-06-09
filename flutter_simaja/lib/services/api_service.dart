import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/foundation.dart';

class ApiService {
  // Pengaturan IP Otomatis (Chrome vs Emulator)
  static const String baseUrl = kIsWeb 
      ? 'http://localhost:8080/api' 
      : 'http://10.0.2.2:8080/api';

  // ================= FUNGSI LOGIN =================
  static Future<Map<String, dynamic>> login(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'login': username,
          'password': password,
        }),
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

  // ================= FUNGSI REGISTER =================
  static Future<Map<String, dynamic>> register(String username, String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'username': username,
          'email': email,
          'password': password,
        }),
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

  // ================= FUNGSI FORGOT PASSWORD =================
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

  // ================= FUNGSI GET JADWAL =================
  static Future<List<dynamic>> getJadwal() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      String url = '$baseUrl/jadwal';
      if (userId != null) {
        url += '?user_id=$userId';
      }

      final response = await http.get(Uri.parse(url)).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] ?? [];
      } else {
        return [];
      }
    } catch (e) {
      print("=== ERROR GET JADWAL ===");
      print(e);
      return [];
    }
  }

  // ================= FUNGSI DAFTAR JADWAL =================
  static Future<Map<String, dynamic>> daftarJadwal(String jadwalId) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/jadwal/daftar'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'user_id': userId,
          'jadwal_id': jadwalId,
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4.'};
      }

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

  // ================= FUNGSI KIRIM ABSENSI =================
  static Future<Map<String, dynamic>> kirimAbsensi(String jadwalId, String status) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/jadwal/absensi'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'user_id': userId,
          'jadwal_id': jadwalId,
          'status': status,
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4.'};
      }

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

  // ================= FUNGSI GET MATERI (LMS) =================
  static Future<List<dynamic>> getMateri() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      String url = '$baseUrl/materi';
      if (userId != null) {
        url += '?user_id=$userId';
      }

      final response = await http.get(Uri.parse(url)).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return data['data'] ?? [];
      } else {
        return [];
      }
    } catch (e) {
      print("=== ERROR GET MATERI ===");
      print(e);
      return [];
    }
  }

  // ================= FUNGSI MARK COMPLETE (SELESAI BELAJAR) =================
  static Future<Map<String, dynamic>> markComplete(String pertemuanId) async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('user_id');

      if (userId == null) return {'success': false, 'message': 'Sesi login tidak ditemukan'};

      final response = await http.post(
        Uri.parse('$baseUrl/materi/complete'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'user_id': userId,
          'pertemuan_id': pertemuanId,
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.body.contains('<html') || response.body.contains('<!DOCTYPE')) {
        return {'success': false, 'message': 'Terjadi error di server CI4.'};
      }

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
}