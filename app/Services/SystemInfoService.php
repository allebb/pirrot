<?php


namespace Ballen\Pirrot\Services;


class SystemInfoService
{

    /**
     * Display text for in-detectable system information data.
     */
    const NOT_DETECTED = "**not detected**";

    /**
     * The file where the OS information can be retrieved from.
     */
    const OS_BUILD_INFO_FILE = "/etc/os-release";

    /**
     * The file where the Raspberry Pi Hardware version can be retrieved from.
     */
    const HARDWARE_VERSION_FILE = "/sys/firmware/devicetree/base/model";

    /**
     * The system hostname.
     * @var string
     */
    public $hostname = self::NOT_DETECTED;

    /**
     * The detected Raspberry Pi hardware version that Pirrot is running on.
     * @var string
     */
    public $hardwareModel = self::NOT_DETECTED;

    /**
     * The detected Raspberry Pi hardware serial number that Pirrot is running on.
     * @var string
     */
    public $hardwareSerial = self::NOT_DETECTED;

    /**
     * The CPU architecture.
     * @var string
     */
    public $hardwareCpuArch = self::NOT_DETECTED;

    /**
     * The CPU (core) count.
     * @var int
     */
    public $hardwareCpuCount = self::NOT_DETECTED;

    /**
     * The CPU speed (max frequency)
     * @var int
     */
    public $hardwareCpuFrequency = self::NOT_DETECTED;

    /**
     * The detected operating system version that Pirrot is running on.
     * @var string
     */
    public $raspbainVersion = self::NOT_DETECTED;

    /**
     * The detected operating system kernel version.
     * @var string
     */
    public $kernelVersion = self::NOT_DETECTED;

    /**
     * The Pirrot version (release) number.
     * @var string
     */
    public $pirrotVersion = self::NOT_DETECTED;

    /**
     * The PHP version that Pirrot is compiled (running) on.
     * @var string
     */
    public $phpVersion = self::NOT_DETECTED;

    /**
     * Detects if gpsd is installed (and we can therefore access GPS data)
     * @var bool
     */
    public $hasGpsConfigured = false;


    /**
     * Detect and cache all.
     * @return void
     */
    public function detect()
    {
        $this->detectHostname();
        $this->detectPhpVersion();
        $this->detectKernelVersion();
        $this->detectHardwareModel();
        $this->detectHardwareSerial();
        $this->detectHardwareCpuArch();
        $this->detectCpuCoreCount();
        $this->detectCpuFrequency();
        $this->detectGpsHardware();
        $this->detectRaspbianVersion();
        $this->detectPirrotVersion();
        $this->detectGpsHardware();
    }


    /**
     * Detects the system hostname.
     * @return void
     */
    protected function detectHostname()
    {
        if ($hostname = gethostname()) {
            $this->hostname = $hostname;
        }
    }

    /**
     * Detects the Raspberry Pi hardware version.
     * @return void
     */
    protected function detectHardwareModel()
    {
        if (!file_exists('/sys/firmware/devicetree/base/model')) {
            return;
        }
        $this->hardwareModel = trim(file_get_contents(self::HARDWARE_VERSION_FILE));
    }

    /**
     * Detects the operating system version.
     * @return void
     */
    protected function detectRaspbianVersion()
    {
        $osMatches = [];
        $versionMatches = [];
        if (!file_exists(self::OS_BUILD_INFO_FILE)) {
            return;
        }
        $osReleaseFile = file_get_contents(self::OS_BUILD_INFO_FILE);
        preg_match("/\bID=(.+)/i", $osReleaseFile, $osMatches);
        preg_match("/\bVERSION=(.+)/i", $osReleaseFile, $versionMatches);

        if (isset($versionMatches[1])) {
            $this->raspbainVersion = trim(ucwords($osMatches[1]), '"') . " " . trim($versionMatches[1], '"');
        }
    }

    /**
     * Detects the Pirrot release version.
     * @return void
     */
    protected function detectPirrotVersion()
    {
        $pirrotVersionInfo = '/opt/pirrot/VERSION';
        if (!file_exists($pirrotVersionInfo)) {
            return;
        }
        $this->pirrotVersion = trim(file_get_contents($pirrotVersionInfo));
    }

    /**
     * Detects the Raspberry Pi Hardware Serial number
     * @return void
     */
    protected function detectHardwareSerial()
    {
        if (!file_exists('/proc/cpuinfo')) {
            return;
        }

        ob_start();
        $serialNumber = system("cat /proc/cpuinfo |grep Serial|cut -d' ' -f2");
        ob_clean();
        $this->hardwareSerial = trim($serialNumber);
    }

    /**
     * Detects if GPSD has been setup on this system
     * @return void
     */
    protected function detectGpsHardware()
    {
        $this->hasGpsConfigured = false;
        if (file_exists('/etc/default/gpsd')) {
            $this->hasGpsConfigured = true;
        }
    }

    /**
     * The PHP version that Pirrot is compiled (running) on.
     * @return void
     */
    protected function detectPhpVersion()
    {
        $this->phpVersion = phpversion();
    }

    /**
     * The operating system kernel version.
     * @return void
     */
    protected function detectKernelVersion()
    {
        $this->kernelVersion = php_uname('r');
    }

    /**
     * The CPU architecture.
     * @return void
     */
    protected function detectHardwareCpuArch()
    {
        $this->hardwareCpuArch = php_uname('m');
    }

    /**
     * Detect the number of CPUs (cores) for this system.
     * @return void
     */
    protected function detectCpuCoreCount()
    {
        ob_start();
        $cpuCount = system("lscpu | grep \"CPU(s):\" | cut -d : -f2");
        ob_clean();

        $cpuCountClean = trim($cpuCount);
        if (is_numeric($cpuCountClean)) {
            $this->hardwareCpuCount = (int)$cpuCountClean;
        }

    }

    /**
     * Detect the CPU Speed (max frequency) for this system.
     * @return void
     */
    protected function detectCpuFrequency()
    {
        ob_start();
        $cpuFreq = system("lscpu | grep \"CPU max MHz:\" | cut -d : -f2");
        ob_clean();

        $cpuFreqClean = trim($cpuFreq);
        if (is_numeric($cpuFreqClean)) {
            $this->hardwareCpuFrequency = (int)$cpuFreqClean;
        }

    }

}