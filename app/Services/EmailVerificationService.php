<?php

namespace App\Services;

class EmailVerificationService
{
    /**
     * Verify an email with format + DNS + optional SMTP probe.
     *
     * @param string $email
     * @return array
     */
    public function verify(string $email): array
    {
        $result = [
            'valid' => false,
            'reason' => null,
            'domain_ok' => false,
            'has_mx' => false,
            'smtp_ok' => null,
        ];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['reason'] = 'Invalid email format.';
            return $result;
        }

        [, $domain] = explode('@', $email, 2);

        $domain = strtolower(trim($domain));

        if (empty($domain)) {
            $result['reason'] = 'Unable to parse email domain.';
            return $result;
        }

        // DNS check
        $mxRecords = [];
        if (function_exists('dns_get_record')) {
            $mxRecords = @dns_get_record($domain, DNS_MX);
            if ($mxRecords === false) {
                $mxRecords = [];
            }
        }

        $hasMx = false;
        if (!empty($mxRecords)) {
            $hasMx = true;
        } elseif (function_exists('checkdnsrr') && (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A'))) {
            $hasMx = true;
        }

        $result['domain_ok'] = true;
        $result['has_mx'] = $hasMx;

        if (!$hasMx) {
            $result['reason'] = 'Domain has no MX/A records; email delivery not possible.';
            return $result;
        }

        // Attempt SMTP RCPT verification, if permitted.
        $smtp = $this->verifySmtpRecipient($email, $domain, $mxRecords);
        $result['smtp_ok'] = $smtp['smtp_ok'];

        if ($smtp['smtp_ok'] === true) {
            $result['valid'] = true;
            $result['reason'] = 'Email is syntactically valid and mailbox seems deliverable.';
            return $result;
        }

        if ($smtp['smtp_ok'] === false) {
            $result['reason'] = $smtp['reason'] ?: 'SMTP check failed: mailbox condemned or not reachable.';
            return $result;
        }

        // If smtp could not be determined we still accept since domain MX is present.
        $result['valid'] = true;
        $result['reason'] = 'Email format and domain validate; could not confirm SMTP recipient for privacy reasons.';

        return $result;
    }

    protected function verifySmtpRecipient(string $email, string $domain, array $mxRecords): array
    {
        $result = ['smtp_ok' => null, 'reason' => null];

        $hosts = [];
        foreach ($mxRecords as $mx) {
            if (isset($mx['target'])) {
                $hosts[] = rtrim($mx['target'], '.');
            }
        }

        if (empty($hosts)) {
            $hosts[] = $domain;
        }

        foreach ($hosts as $host) {
            $socket = @fsockopen($host, 25, $errno, $errstr, 5);
            if (!$socket) {
                continue;
            }

            stream_set_timeout($socket, 5);
            $serverLine = fgets($socket, 512);
            if (strpos($serverLine, '220') !== 0) {
                fclose($socket);
                continue;
            }

            $localHost = 'localhost';
            fputs($socket, "HELO {$localHost}\r\n");
            fgets($socket, 512);

            fputs($socket, "MAIL FROM:<noreply@{$domain}>\r\n");
            fgets($socket, 512);

            fputs($socket, "RCPT TO:<{$email}>\r\n");
            $rcptResponse = fgets($socket, 512);

            fputs($socket, "QUIT\r\n");
            fclose($socket);

            if ($rcptResponse && preg_match('/^2[0-9]{2}/', trim($rcptResponse))) {
                $result['smtp_ok'] = true;
                return $result;
            }

            if ($rcptResponse && preg_match('/^5[0-9]{2}/', trim($rcptResponse))) {
                $result['smtp_ok'] = false;
                $result['reason'] = 'SMTP server rejected recipient address.';
                return $result;
            }
        }

        // If none of the hosts confirm, return null (unknown) rather than absolute false.
        $result['smtp_ok'] = null;
        $result['reason'] = 'Unable to confirm SMTP delivery status. Please send verification email if required.';

        return $result;
    }
}
