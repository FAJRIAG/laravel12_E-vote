<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $loginUrl;
    public ?int $expiresInMinutes;
    public ?string $brandName;
    public ?string $brandColor;
    public ?string $brandLogoUrl;

    /**
     * @param string      $code             Kode login (format bebas)
     * @param string      $loginUrl         URL halaman login
     * @param int|null    $expiresInMinutes (opsional) masa berlaku dalam menit (mis. 15)
     * @param string|null $brandName        (opsional) fallback nama brand
     * @param string|null $brandColor       (opsional) warna hex utk header (#2563eb)
     * @param string|null $brandLogoUrl     (opsional) URL logo (absolute URL)
     */
    public function __construct(
        string $code,
        string $loginUrl,
        ?int $expiresInMinutes = null,
        ?string $brandName = null,
        ?string $brandColor = null,
        ?string $brandLogoUrl = null
    ) {
        $this->code = $code;
        $this->loginUrl = $loginUrl;
        $this->expiresInMinutes = $expiresInMinutes;
        $this->brandName = $brandName ?? config('app.name');
        $this->brandColor = $brandColor ?? '#2563eb';
        $this->brandLogoUrl = $brandLogoUrl; // boleh null
    }

    public function build()
    {
        $preheader = 'Kode login Anda untuk ' . ($this->brandName ?? config('app.name'));

        return $this->subject('Kode Login Anda')
            ->view('emails.login_code')
            ->text('emails.login_code_plain')
            ->with([
                'code'             => $this->code,
                'loginUrl'         => $this->loginUrl,
                'expiresInMinutes' => $this->expiresInMinutes,
                'brandName'        => $this->brandName,
                'brandColor'       => $this->brandColor,
                'brandLogoUrl'     => $this->brandLogoUrl,
                'preheader'        => $preheader,
            ]);
    }
}
