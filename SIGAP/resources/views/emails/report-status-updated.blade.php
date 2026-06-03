<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Status Laporan</title>
</head>
<body style="font-family:sans-serif; background:#f3f4f6; margin:0; padding:2rem">
    <div style="max-width:520px; margin:0 auto; background:#fff;
                border-radius:12px; overflow:hidden; border:1px solid #e5e7eb">

        <div style="background:#14532d; padding:1.5rem 2rem">
            <h2 style="color:#fff; margin:0; font-size:1.2rem">
                {{ config('app.name') }}
            </h2>
        </div>

        <div style="padding:2rem">
            <p style="color:#374151; margin-top:0">Halo <strong>{{ $report->user->name }}</strong>,</p>

            <p style="color:#374151">
                Status laporan Anda telah diperbarui.
            </p>

            <div style="background:#f9fafb; border-radius:8px; padding:1rem 1.25rem;
                        margin:1.25rem 0; border-left:4px solid #14532d">
                <p style="margin:0 0 .4rem; font-size:.85rem; color:#6b7280">Judul Laporan</p>
                <p style="margin:0; font-weight:700; color:#111827">{{ $report->title }}</p>
            </div>

            <div style="background:#f9fafb; border-radius:8px; padding:1rem 1.25rem; margin:1.25rem 0">
                <p style="margin:0 0 .4rem; font-size:.85rem; color:#6b7280">Status Terbaru</p>
                <p style="margin:0; font-weight:700; color:#14532d; font-size:1.1rem">
                    {{ ucfirst($report->status) }}
                </p>
            </div>

            <a href="{{ route('laporan.show', $report->id) }}"
               style="display:inline-block; padding:.75rem 1.5rem; background:#14532d;
                      color:#fff; text-decoration:none; border-radius:8px;
                      font-weight:700; font-size:.9rem; margin-top:.5rem">
                Lihat Detail Laporan
            </a>
        </div>

        <div style="padding:1rem 2rem; border-top:1px solid #e5e7eb;
                    font-size:.8rem; color:#9ca3af; text-align:center">
            Email ini dikirim otomatis, mohon jangan dibalas.
        </div>
    </div>
</body>
</html>