<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quote Received</title>
  <style>
    body { margin: 0; padding: 0; background: #f5f0e8; font-family: Georgia, serif; }
    .wrapper { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
    .header { background: #0e1117; padding: 36px 40px; text-align: center; }
    .header h1 { margin: 0; color: #f5f0e8; font-size: 24px; letter-spacing: 0.02em; }
    .header span { color: #c9a84c; }
    .accent { height: 3px; background: linear-gradient(90deg, #c9a84c, #e8c96d); }
    .body { padding: 40px; color: #2c2c2c; line-height: 1.7; }
    .body h2 { font-size: 20px; margin-top: 0; color: #0e1117; }
    .body p { margin: 0 0 16px; font-size: 15px; }
    .details { background: #f9f6f0; border-radius: 8px; padding: 20px 24px; margin: 24px 0; }
    .details table { width: 100%; border-collapse: collapse; }
    .details td { padding: 6px 0; font-size: 14px; vertical-align: top; }
    .details td:first-child { color: #888; width: 140px; font-style: italic; }
    .cta { display: inline-block; margin-top: 8px; padding: 14px 32px; background: #c9a84c;
           color: #0e1117; font-weight: bold; text-decoration: none; border-radius: 50px;
           font-size: 13px; letter-spacing: 0.1em; text-transform: uppercase; }
    .footer { background: #f5f0e8; padding: 24px 40px; text-align: center;
              font-size: 12px; color: #999; }
    .footer a { color: #c9a84c; text-decoration: none; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <h1>Native<span>Kilimanjaro</span></h1>
    </div>
    <div class="accent"></div>
    <div class="body">
      <h2>We've received your quote request, {{ $quote->first_name }}!</h2>
      <p>
        Thank you for reaching out to Native Kilimanjaro. One of our safari specialists will
        review your request and get back to you within <strong>24 hours</strong> — usually sooner.
      </p>

      <div class="details">
        <table>
          <tr>
            <td>Destinations</td>
            <td>{{ implode(', ', $quote->destinations ?? []) ?: '—' }}</td>
          </tr>
          <tr>
            <td>Group</td>
            <td>{{ $quote->adults }} adult{{ $quote->adults !== 1 ? 's' : '' }}
              @if($quote->children > 0), {{ $quote->children }} child{{ $quote->children !== 1 ? 'ren' : '' }}@endif
            </td>
          </tr>
          @if($quote->arrival_date)
          <tr>
            <td>Arrival</td>
            <td>{{ $quote->arrival_date->format('d F Y') }}</td>
          </tr>
          @endif
          @if($quote->accommodation)
          <tr>
            <td>Accommodation</td>
            <td>{{ $quote->accommodation }}</td>
          </tr>
          @endif
        </table>
      </div>

      <p>In the meantime, feel free to browse our tours and destinations:</p>
      <a href="{{ env('FRONTEND_URL') }}/tours" class="cta">Explore Our Tours</a>

      <p style="margin-top: 32px; font-size: 13px; color: #888;">
        If you have any urgent questions, WhatsApp us at
        <a href="https://wa.me/255685808332" style="color: #c9a84c;">+255 685 808332</a>
        or email <a href="mailto:info@nativekilimanjaro.com" style="color: #c9a84c;">info@nativekilimanjaro.com</a>.
      </p>
    </div>
    <div class="footer">
      © {{ date('Y') }} Native Kilimanjaro · Arusha, Tanzania<br/>
      <a href="{{ env('FRONTEND_URL') }}">nativekilimanjaro.com</a>
    </div>
  </div>
</body>
</html>