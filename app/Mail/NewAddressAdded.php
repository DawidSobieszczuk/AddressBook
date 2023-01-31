<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\user;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAddressAdded extends Mailable
{
    use Queueable, SerializesModels;
    protected $address;
    protected $user;

    public function __construct(Address $address, User $user)
    {
        $this->address = $address;
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Nowy adress został dodany',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.newAddressAdded',
            with: [
                'street' => $this->address->street,
                'houseNumber' => $this->address->house_number,
                'zip' => $this->address->zip,
                'city' => $this->address->city,
                'county' => $this->address->county,
                'state' => $this->address->state,
                'user_name' => $this->user->name,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
