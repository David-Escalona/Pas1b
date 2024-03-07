<?php

class Logger {

    private $logFile; // nombre del fichero
    private $logLevel; // nivel para registrar los mensajes
    private $confile; // conexión del fichero
    const DEBUG = 100;
    const INFO = 75;
    const NOTICE = 50;
    const WARNING = 25;
    const ERROR = 10;
    const CRITICAL = 5;

    private function __construct() {
        $this->logLevel = self::DEBUG; // Se establece el nivel predeterminado a DEBUG
        $this->logFile = "logmessage.txt";
        $this->confile = fopen($this->logFile, 'a+');
        if (!is_resource($this->confile)) {
            trigger_error("No se pudo abrir el archivo de log.", E_USER_ERROR);
            return false;
        }
    }

    public static function getInstance() {
        static $objLog;
        if (!isset($objLog)) {
            $objLog = new Logger();
        }
        return $objLog;
    }

    public function __destruct() {
        if (is_resource($this->confile)) {
            fclose($this->confile);
        }
    }

    public function logMessage($msg, $loglevel = self::INFO) {
        if ($loglevel > $this->logLevel) {
            return false;
        }

        date_default_timezone_set('America/New_York');
        $formatterDate = DateTimeImmutable::createFromFormat('U', time());
        $time = $formatterDate->format('Y-m-d H:i:s');

        $strloglevel = $this->levelToString($loglevel);

        $message = "$time\t$strloglevel\t$msg\n";
        fwrite($this->confile, $message);
    }

    public static function levelToString($loglevel = null) {
        switch ($loglevel) {
            case self::DEBUG:
                return 'DEBUG';
            case self::INFO:
                return 'INFO';
            case self::NOTICE:
                return 'NOTICE';
            case self::WARNING:
                return 'WARNING';
            case self::ERROR:
                return 'ERROR';
            case self::CRITICAL:
                return 'CRITICAL';
            default:
                return '[NOLEVEL]';
        }
    }
}

$log = Logger::getInstance();
$log->logMessage('Working....', Logger::WARNING);
$log->logMessage('Working....', Logger::NOTICE);
$log->logMessage('Working....', Logger::INFO);
$log->logMessage('Working....', Logger::DEBUG);
$log->logMessage('Working....', 999); // Nivel de log desconocido

?>


