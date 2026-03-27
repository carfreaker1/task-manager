<div style="font-family: Arial, Helvetica, sans-serif; background-color:#f4f6f9; padding:30px; margin:0;">

    <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.08);">
  
  ```
  <div style="background:#2f80ed; padding:20px; text-align:center;">
    <h2 style="color:#ffffff; margin:0; font-size:24px;">Task Meeting Notification</h2>
  </div>
  
  <div style="padding:30px; color:#333333; font-size:15px; line-height:1.6;">
    
    <p style="margin:0 0 15px 0;">Hello <strong>{{ $user->name }}</strong>,</p>
  
    <p style="margin:0 0 20px 0;">
      You have been assigned a <strong>task meeting</strong>. Please review the meeting details below.
    </p>
  
    <div style="background:#f8fafc; border:1px solid #e5e7eb; padding:20px; border-radius:6px;">
  
      <p style="margin:10px 0;">
        <strong>Message:</strong><br>
        {{ $messageText }}
      </p>
  
      <p style="margin:10px 0;">
        <strong>Start Time:</strong> {{ $start }}
      </p>
  
      <p style="margin:10px 0;">
        <strong>End Time:</strong> {{ $end }}
      </p>
  
    </div>
  
    <div style="text-align:center; margin-top:30px;">
      <p style="margin-bottom:15px; font-weight:bold;">Join the meeting:</p>
  
      <a href="{{ $link }}" 
         style="display:inline-block; background:#27ae60; color:#ffffff; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold; font-size:14px;">
         Join Google Meet
      </a>
  
      <p style="margin-top:15px; font-size:13px; color:#777;">
        Or copy this link:<br>
        <a href="{{ $link }}" style="color:#2f80ed; text-decoration:none;">{{ $link }}</a>
      </p>
    </div>
  
    <p style="margin-top:30px;">
      Please make sure to join the meeting on time.
    </p>
  
    <p style="margin-top:20px;">
      Regards,<br>
      <strong>Team</strong>
    </p>
  
  </div>
  
  <div style="background:#f1f3f5; text-align:center; padding:15px; font-size:12px; color:#888;">
    © {{ date('Y') }} Your Company. All rights reserved.
  </div>
  ```
  
    </div>
  
  </div>
  