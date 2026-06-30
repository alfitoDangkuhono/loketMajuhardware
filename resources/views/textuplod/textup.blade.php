@include('headadmin.headadmin')

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Input Text</title>
    <style>
        .text-card {
            max-width: 720px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .text-card .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: #fff;
            padding: 18px 20px;
            border-bottom: none;
        }

        .text-card .card-header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .text-card .card-body {
            padding: 28px;
            background-color: #ffffff;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #d2d6dc;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-action {
            min-width: 140px;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
        }

        .text-card .card-footer {
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
            padding: 18px 28px;
        }

        .note-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background-color: #fff8f0;
            border: 1px solid #ffd9a8;
            border-left: 4px solid #f6c23e;
            border-radius: 6px;
            padding: 12px 14px;
            margin-top: 14px;
            color: #8a6100;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 0;
        }

        .note-box strong {
            color: #6b4e00;
        }

        .error-text {
            color: #e74a3b;
            font-size: 0.85rem;
            margin-top: 6px;
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="card text-card">
            <div class="card-header text-center">
                <h1><i class="fas fa-keyboard mr-2"></i> Input Text</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('upload_txt') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group text-left">
                        <label for="text">Masukan Text</label>
                        <input type="text" id="text" name="text"
                            class="form-control @if ($errors->has('text')) is-invalid @endif"
                            value="{{ old('text') }}" placeholder="Tulis text yang ingin ditampilkan..."
                            autocomplete="off" />

                        @if ($errors->has('text'))
                            <p class="error-text">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('text') }}
                            </p>
                        @endif
                    </div>

                    <div class="text-right">
                        <button class="btn btn-primary btn-action" type="submit" name="click">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <form action="{{ route('delettext') }}" method="GET" class="text-right mb-0">
                    <button class="btn btn-danger btn-action" type="submit">
                        <i class="fas fa-trash mr-1"></i> Hapus Text
                    </button>
                </form>
                <p class="note-box">
                    <i class="fas fa-info-circle mt-1"></i>
                    <span><strong>Info:</strong> mengirim text baru akan otomatis mengganti text lama. Gunakan tombol
                        Hapus Text untuk mengosongkan tampilan.</span>
                </p>
            </div>
        </div>
    </div>
</body>

</html>

@extends('headadmin.footeradmin')
