<?php
/**
 * Created by miljenko.rebernisak@prelovac.com
 * Date: 2/18/14
 */

/**
 * Class MWP_Configuration_Service
 * This class is service provider for configuration object.
 * It include get/save operations, with singleton to lower database hits.
 *
 * @package src\MWP\Configuration
 */
class MWP_Configuration_Service
{
    /**
     * @var MWP_Configuration_Conf
     */
    public static $configuration;

    /**
     * Returns configuration instance. This is singleton
     *
     * @return MWP_Configuration_Conf
     */
    public function getConfiguration()
    {
        if (!self::$configuration) {
            $configuration = get_option("mwp_worker_configuration");
            $path          = realpath(dirname(__FILE__)."/../../../worker.json");
            if (empty($configuration) && file_exists($path)) {
                $json          = file_get_contents($path);
                $configuration = json_decode($json, true);
                update_option("mwp_worker_configuration", $configuration);
            }

            self::$configuration = new MWP_Configuration_Conf($configuration);
        }

        return self::$configuration;
    }

    /**
     * Reloads configuration from database, update internal singleton and returns reload object
     *
     * @return MWP_Configuration_Conf
     */
    public function reloadConfiguration()
    {
        self::$configuration = new MWP_Configuration_Conf(get_option("mwp_worker_configuration"));

        return self::$configuration;
    }

    /**
     * Save to database configuration instance
     *
     * @param MWP_Configuration_Conf $configuration
     */
    public function saveConfiguration(MWP_Configuration_Conf $configuration)
    {
        self::$configuration = $configuration;
        $data                = $configuration->toArray();
        if (array_key_exists("master_cron_url", $data) && !empty($data['master_cron_url'])) {
            update_option("mwp_worker_configuration", $data);
        }
    }
}
