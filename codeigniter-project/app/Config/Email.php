<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * @var string
     */
    public $fromEmail = 'noreply@afrigig.com';

    /**
     * @var string
     */
    public $fromName = 'AfriGig';

    /**
     * @var string
     */
    public $protocol = 'smtp';

    /**
     * @var string
     */
    public $SMTPHost = 'smtp.mailtrap.io';

    /**
     * @var int
     */
    public $SMTPPort = 2525;

    /**
     * @var string
     */
    public $SMTPUser = '';

    /**
     * @var string
     */
    public $SMTPPass = '';

    /**
     * @var string
     */
    public $SMTPCrypto = 'tls';

    /**
     * @var string
     */
    public $mailType = 'html';

    /**
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * @var string
     */
    public $wordWrap = true;

    /**
     * @var bool
     */
    public $validate = true;

    /**
     * @var int
     */
    public $priority = 3;

    /**
     * @var int
     */
    public $CRLF = "\r\n";

    /**
     * @var int
     */
    public $newline = "\r\n";

    /**
     * @var bool
     */
    public $BCCBatchMode = false;

    /**
     * @var int
     */
    public $BCCBatchSize = 200;

    /**
     * @var bool
     */
    public $DSN = false;
}
