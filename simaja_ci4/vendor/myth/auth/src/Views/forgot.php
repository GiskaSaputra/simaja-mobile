
<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<style>
    body {
        background: #0a3d35;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .forgot-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .forgot-box {
        display: flex;
        background: linear-gradient(to bottom right, #005f4a, #008060);
        border-radius: 12px;
        overflow: hidden;
        width: 850px;
        max-width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        transition: all 0.3s ease;
    }

    /* Bagian kiri */
    .forgot-left {
        background: #e5e5e5;
        width: 50%;
        text-align: center;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
    }

    .forgot-left::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(to bottom, #007b66, #006250);
        border-bottom-left-radius: 60px;
        border-bottom-right-radius: 60px;
    }

    .forgot-left::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 180px;
        background: linear-gradient(to bottom right, #007b66, #005f4a);
        clip-path: polygon(50% 50%, 0% 100%, 100% 100%);
    }

    .forgot-left h2 {
        position: relative;
        margin-top: 60px;
        color: white;
        font-weight: 700;
    }

    .forgot-left p {
        color: white;
        font-size: 14px;
        margin-top: -5px;
    }

    /* 🔹 LOGO AREA 🔹 */
    .forgot-left .logo {
        position: relative;
        transition: transform 0.3s ease;
    }

    .forgot-left img {
        width: 120px;
        margin-top: 15px;
        z-index: 2;
        position: relative;
    }

    /* Bagian kanan */
    .forgot-right {
        background: linear-gradient(to bottom right, #007b66, #009e7f);
        width: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding: 40px 30px;
    }

    .forgot-header {
        color: white;
        font-weight: 700;
        font-size: 26px;
        letter-spacing: 1px;
        margin-top: 20px;
        margin-bottom: 15px;
        text-align: center;
    }

    .forgot-form {
        background: #f0f0f0;
        border-radius: 10px;
        padding: 30px 40px;
        width: 100%;
        max-width: 350px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .forgot-form img {
        width: 60px;
        margin-bottom: 10px;
    }

    .form-control {
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .btn-forgot {
        width: 100%;
        background: linear-gradient(to right, #007b66, #009e7f);
        border: none;
        padding: 10px;
        color: white;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-forgot:hover {
        background: #005f52;
    }

    .forgot-form a {
        font-size: 13px;
        color: #005f52;
        text-decoration: none;
    }

    .forgot-form a:hover {
        text-decoration: underline;
    }

    .small-links {
        margin-top: 10px;
    }

    /* ==========================
       🔹 RESPONSIVE AREA 🔹
       ========================== */

    /* Default (desktop/web) */
    .forgot-left .logo {
        transform: translateY(-10px); /* logo agak ke bawah di layar besar */
    }

    /* Tablet */
    @media (max-width: 1024px) {
        
        .forgot-left .logo {
            transform: translateY(30px); /* naik sedikit di tablet */
        }

        .forgot-left h2 {
            margin-top: 15px;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .forgot-box {
            flex-direction: column;
            width: 100%;
            max-width: 500px;
        }

        .forgot-left,
        .forgot-right {
            width: 100%;
        }

        .forgot-left {
            padding: 30px 20px 60px;
        }

        .forgot-left::after {
            display: none; /* hilangkan segitiga di mobile */
        }

        .forgot-left .logo {
            transform: translateY(30px); /* naik lebih tinggi di mobile */
        }

        .forgot-left h2 {
            font-size: 20px;
        }

        .forgot-left img {
            width: 100px;
            margin-top: 25px;
        }

        .forgot-form {
            margin-top: 10px;
            padding: 25px 30px;
        }
    }

    @media (max-width: 480px) {
        .forgot-left p {
            font-size: 12px;
        }

        .btn-forgot {
            font-size: 14px;
        }

        .forgot-form {
            padding: 20px;
        }
    }
</style>

<div class="forgot-container">
    <div class="forgot-box">

        <!-- Kiri -->
        <div class="forgot-left">
            <div class="logo">
                <h2>SIMAJA</h2>
                <p>Sistem Manajemen Study Jam</p>
                <h4 style="color: black; font-weight: 700;">PROTIC</h4>
                <img src="<?= base_url('img/protic.png') ?>" alt="Logo">
            </div>
        </div>

        <!-- Kanan -->
        <div class="forgot-right">
            <!-- Tulisan di luar card -->
            <div class="forgot-header">FORGOT PASSWORD</div>

            <div class="forgot-form">
                <img src="<?= base_url('img/protic.png') ?>" alt="Logo kecil">

                <?= view('Myth\Auth\Views\_message_block') ?>

                <p><?= lang('Auth.enterEmailForInstructions') ?></p>

                <form action="<?= url_to('forgot') ?>" method="post">
                    <?= csrf_field() ?>

                    <input type="email" name="email"
                        class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                        placeholder="Email">
                    <div class="invalid-feedback"><?= session('errors.email') ?></div>

                    <button type="submit" class="btn-forgot"><?= lang('Auth.sendInstructions') ?></button>
                </form>

                <div class="small-links">
                    <p><a href="<?= url_to('login') ?>">Back to login</a></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>