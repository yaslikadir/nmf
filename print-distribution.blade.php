<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $document->document_code }} - Doküman Dağıtım Formu</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 11px;
      background: #fff;
      color: #000;
      padding: 20px;
    }

    /* ── HEADER ─────────────────────────────────────────── */
    .header {
      display: flex;
      border: 1px solid #000;
      margin-bottom: 12px;
    }

    .header-logo {
      width: 140px;
      min-width: 140px;
      border-right: 1px solid #000;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 8px 10px;
      gap: 4px;
    }

    .header-title {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: bold;
      letter-spacing: 3px;
      text-transform: uppercase;
      border-right: 1px solid #000;
      padding: 10px;
      text-align: center;
    }

    .header-info {
      width: 220px;
      min-width: 220px;
      display: flex;
      flex-direction: column;
      font-size: 10px;
    }

    .header-info-row {
      display: flex;
      border-bottom: 1px solid #000;
      flex: 1;
    }

    .header-info-row:last-child { border-bottom: none; }

    .header-info-label {
      width: 115px;
      min-width: 115px;
      padding: 3px 6px;
      border-right: 1px solid #000;
      font-weight: bold;
    }

    .header-info-value {
      flex: 1;
      padding: 3px 6px;
    }

    /* ── SECTION TITLE ──────────────────────────────────── */
    .section-title {
      font-size: 11px;
      font-weight: bold;
      margin-bottom: 6px;
    }

    /* ── MAIN TABLE ─────────────────────────────────────── */
    .main-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    .main-table th,
    .main-table td {
      border: 1px solid #000;
      padding: 3px 5px;
      text-align: center;
      vertical-align: middle;
    }

    .main-table th {
      background-color: #d6e4c4;
      font-weight: bold;
    }

    .main-table td {
      height: 26px; /* Satır aralıklarını biraz açtık ki ferah dursun */
    }

    .main-table .col-sn    { width: 32px; }
    .main-table .col-kod   { width: 22%; }
    .main-table .col-rev   { width: 10%; }
    .main-table .col-ta-ad { width: 13%; }
    .main-table .col-ta-im { width: 10%; }
    .main-table .col-iade-rev { width: 11%; }
    .main-table .col-ie-ad { width: 13%; }
    .main-table .col-ie-im { width: 10%; }

    .main-table .row-num {
      text-align: left;
      padding-left: 5px;
      font-weight: bold;
    }

    /* ── SIGNATURE AREA ─────────────────────────────────── */
    .sig-area {
      margin-top: 8px;
      margin-bottom: 40px;
      line-height: 2.2;
      font-weight: bold;
      font-size: 11px;
    }

    /* ── FOOTER TABLE ───────────────────────────────────── */
    .footer-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .footer-table td {
      border: 1px solid #000;
      padding: 4px 8px;
      width: 33.33%;
      vertical-align: top;
      font-size: 10px;
    }

    .footer-table .footer-label {
      font-weight: bold;
      font-size: 10px;
      border-bottom: 1px solid #000;
      padding-bottom: 2px;
      margin-bottom: 2px;
    }

    @media print {
      body { padding: 0; }
      @page { size: A4 portrait; margin: 10mm; }
    }
  </style>
</head>
<body onload="window.print()">

  <div class="header">
    <div class="header-logo">
      {{-- LOGO ALANI: Settings Tablosundan Dinamik Çekiliyor --}}
      @php $logo = \App\Models\Setting::where('key', 'site_logo')->value('value'); @endphp
      @if($logo)
          <img src="{{ asset($logo) }}" style="max-width: 120px; max-height: 60px;" alt="Akademi Çevre">
      @else
          <div style="font-size: 14px; font-weight:bold;">AKADEMİ ÇEVRE</div>
      @endif
    </div>

    <div class="header-title">DOKÜMAN DAĞITIM FORMU</div>

    <div class="header-info">
      <div class="header-info-row">
        <div class="header-info-label">Doküman No</div>
        <div class="header-info-value">FR-02-03</div>
      </div>
      <div class="header-info-row">
        <div class="header-info-label">Yayın Tarihi</div>
        {{-- DÜZELTME: \Carbon\Carbon::parse() ile metni tarihe çevirdik --}}
        <div class="header-info-value">{{ $latestVersion && $latestVersion->valid_from ? $latestVersion->valid_from->format('d.m.Y') : ($latestVersion && $latestVersion->published_at ? \Carbon\Carbon::parse($latestVersion->published_at)->format('d.m.Y') : \Carbon\Carbon::parse($document->created_at)->format('d.m.Y')) }}</div>
      </div>
      <div class="header-info-row">
        <div class="header-info-label">Rev. No/Tarihi</div>
        <div class="header-info-value">
            {{ $latestVersion ? str_pad($latestVersion->version_no, 2, '0', STR_PAD_LEFT) : '00' }} / 
            {{-- DÜZELTME: Aynı şekilde created_at için de parse ekledik --}}
            {{ $latestVersion ? ($latestVersion->valid_from ? $latestVersion->valid_from->format('d.m.Y') : \Carbon\Carbon::parse($latestVersion->created_at)->format('d.m.Y')) : '-' }}
        </div>
      </div>
      <div class="header-info-row">
        <div class="header-info-label">Sayfa No</div>
        <div class="header-info-value">1/1</div>
      </div>
    </div>
  </div>

  <div class="section-title">DAĞITIMI YAPAN / İADE ALAN</div>

  <table class="main-table">
    <thead>
      <tr>
        <th class="col-sn" rowspan="2">SN</th>
        <th class="col-kod" rowspan="2">Doküman Kodu - Adı</th>
        <th class="col-rev" rowspan="2">Rev. No. /<br>Nüsha Sayısı</th>
        <th colspan="2">Teslim Alan</th>
        <th class="col-iade-rev" rowspan="2">İade Edilen Rev.<br>No/Nüsha Sayısı</th>
        <th colspan="2">İade Eden</th>
      </tr>
      <tr>
        <th class="col-ta-ad">Adı - Soyadı</th>
        <th class="col-ta-im">İmza</th>
        <th class="col-ie-ad">Adı - Soyadı</th>
        <th class="col-ie-im">İmza</th>
      </tr>
    </thead>
    <tbody>
      {{-- VERİTABANINDAN DAĞITIM LİSTESİNİ ÇEKİYORUZ --}}
      @php 
        $distributions = $document->distributions ?? collect([]);
        $rowCount = max(13, $distributions->count()); // En az 13 satır göster, daha fazlaysa esne
      @endphp

      @for($i = 0; $i < $rowCount; $i++)
          @php $dist = $distributions->get($i); @endphp
          <tr>
            <td class="row-num">{{ $i + 1 }}.</td>
            
            {{-- Doküman Bilgisi (Dağıtım Varsa Dolu, Yoksa Boş) --}}
            @if($dist)
                <td style="font-size: 9px; font-weight:bold;">{{ $document->document_code }}<br><span style="font-weight:normal;">{{ Str::limit($document->name, 35) }}</span></td>
                <td>{{ $latestVersion ? str_pad($latestVersion->version_no, 2, '0', STR_PAD_LEFT) : '00' }} / 1</td>
                <td style="font-size: 9px;">{{ $dist->user->name ?? 'Departman' }}</td>
                <td style="font-size: 8px;">{{ $dist->is_read ? 'Okudu (Dijital)' : 'Bekliyor' }}</td>
            @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            @endif

            {{-- İade Kısımları Boş Kalacak --}}
            <td></td>
            <td></td>
            <td></td>
          </tr>
      @endfor
    </tbody>
  </table>

  <div class="sig-area">
    <div>ADI SOYADI: {{ auth()->user()->name }}</div>
    <div>TARİH: {{ date('d.m.Y') }}</div>
    <div>İMZA:</div>
  </div>

  <table class="footer-table">
    <tr>
      <td>
        <div class="footer-label">Hazırlayan</div>
        Kalite Yönetim Temsilcisi<br>
        Gözde Kaplan
      </td>
      <td>
        <div class="footer-label">Gözden Geçiren</div>
        Kalite Yönetim Temsilcisi<br>
        Gözde Kaplan
      </td>
      <td>
        <div class="footer-label">Onaylayan</div>
        Laboratuvar Müdürü<br>
        Engin Katırağ
      </td>
    </tr>
  </table>

</body>
</html>
