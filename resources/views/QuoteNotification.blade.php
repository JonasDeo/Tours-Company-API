<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>New Quote</title>
  <style>
    body { margin: 0; padding: 0; background: #f0f0f0; font-family: Georgia, serif; }
    .wrapper { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; }
    .header { background: #0e1117; padding: 28px 40px; }
    .header h1 { margin: 0; color: #c9a84c; font-size: 13px; letter-spacing: 0.2em; text-transform: uppercase; }
    .header p { margin: 6px 0 0; color: #f5f0e8; font-size: 20px; }
    .accent { height: 3px; background: linear-gradient(90deg, #c9a84c, #e8c96d); }
    .body { padding: 36px 40px; color: #2c2c2c; }
    .row { display: flex; gap: 16px; margin-bottom: 12px; }
    .label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.12em; color: #999; margin-bottom: 3px; }
    .value { font-size: 15px; color: #0e1117; }
    .divider { border: none; border-top: 1px solid #eee; margin: 24px 0; }
    .message-box { background: #f9f6f0; border-left: 3px solid #c9a84c; padding: 16px 20px;
                   border-radius: 0 8px 8px 0; font-size: 14px; line-height: 1.7; color: #444; }
    .cta { display: inline-block; margin-top: 24px; padding: 13px 28px; background: #c9a84c;
           color: #0e1117; font-weight: bold; text-decoration: none; border-radius: 50px;
           font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; }
    .footer { background: #f5f0e8; padding: 20px 40px; font-size: 12px; color: #aaa; text-align: center; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <h1>New Quote Request</h1>
      <p>{{ $quote->full_name }}</p>
    </div>
    <div class="accent"></div>
    <div class="body">

      {{-- Contact --}}
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        <div>
          <div class="label">Email</div>
          <div class="value">{{ $quote->email }}</div>
        </div>
        <div>
          <div class="label">Phone</div>
          <div class="value">{{ $quote->phone ?: '—' }}</div>
        </div>
        <div>
          <div class="label">Country</div>
          <div class="value">{{ $quote->country ?: '—' }}</div>
        </div>
        <div>
          <div class="label">Received</div>
          <div class="value">{{ $quote->created_at->format('d M Y, H:i') }}</div>
        </div>
      </div>

      <hr class="divider"/>

      {{-- Trip --}}
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        <div>
          <div class="label">Destinations</div>
          <div class="value">{{ implode(', ', $quote->destinations ?? []) ?: '—' }}</div>
        </div>
        <div>
          <div class="label">Trip Types</div>
          <div class="value">{{ implode(', ', $quote->trip_types ?? []) ?: '—' }}</div>
        </div>
        <div>
          <div class="label">Group</div>
          <div class="value">
            {{ $quote->adults }} adult{{ $quote->adults !== 1 ? 's' : '' }}
            @if($quote->children > 0), {{ $quote->children }} child{{ $quote->children !== 1 ? 'ren' : '' }}@endif
          </div>
        </div>
        <div>
          <div class="label">Arrival Date</div>
          <div class="value">{{ $quote->arrival_date ? $quote->arrival_date->format('d M Y') : '—' }}</div>
        </div>
        <div>
          <div class="label">Accommodation</div>
          <div class="value">{{ $quote->accommodation ?: '—' }}</div>
        </div>
        <div>
          <div class="label">Occasions</div>
          <div class="value">{{ implode(', ', $quote->occasions ?? []) ?: '—' }}</div>
        </div>
      </div>

      @if($quote->message)
        <hr class="divider"/>
        <div class="label">Message</div>
        <div class="message-box">{{ $quote->message }}</div>
      @endif

      <a href="{{ env('APP_URL') }}/admin/quotes/{{ $quote->id }}" class="cta">
        View in Dashboard →
      </a>
    </div>
    <div class="footer">Balbina Safaris Admin · Quote #{{ $quote->id }}</div>
  </div>
</body>
</html>