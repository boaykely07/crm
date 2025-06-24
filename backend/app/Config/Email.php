<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'tsioryvahyarabearivony@gmail.com';
    public string $fromName   = 'Support CRM';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'tsioryvahyarabearivony@gmail.com';

    /**
     * SMTP Password
     */
    public string $SMTPPass = 'munq tgih cvic fuls';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    protected function sendResolutionMailToClient($ticket)
    {
        $clientModel = new \App\Models\ClientsModel();
        $client = $clientModel->find($ticket['id_client']);
        if (!$client || empty($client['email'])) {
            return;
        }
        $email = \Config\Services::email();
        $email->setTo($client['email']);
        $email->setSubject('Votre ticket #' . $ticket['id'] . ' a été résolu');
        $email->setMessage(
            'Bonjour ' . htmlspecialchars($client['nom']) . ",<br><br>" .
            "Votre ticket <b>#" . $ticket['id'] . "</b> a été marqué comme <b>résolu</b>.<br>" .
            "Titre : <b>" . htmlspecialchars($ticket['titre']) . "</b><br><br>" .
            "Si vous avez encore besoin d'aide, n'hésitez pas à répondre à ce message.<br><br>" .
            "Cordialement,<br>L'équipe support"
        );
        $email->setMailType('html');
        if (!$email->send()) {
            // Affiche le débogueur pour voir l'erreur
            echo $email->printDebugger(['headers', 'subject', 'body']);
            exit;
        }
    }

    public function testMail()
    {
        $email = \Config\Services::email();
        $email->setTo('ton.email@gmail.com'); // Mets ton adresse ici pour tester
        $email->setSubject('Test envoi');
        $email->setMessage('Ceci est un test.');
        $email->setMailType('html');
        if ($email->send()) {
            echo 'Mail envoyé !';
        } else {
            echo $email->printDebugger(['headers', 'subject', 'body']);
        }
    }
}
